<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pemilihan_ekskul"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil nama-nama kriteria
$query_criteria = "SELECT nama_kriteria FROM kriteria ORDER BY nama_kriteria ASC";
$result_criteria = $conn->query($query_criteria);

$criteria_names = [];
while ($row = $result_criteria->fetch_assoc()) {
    $criteria_names[] = $row['nama_kriteria'];
}

// Membangun bagian select query untuk nilai kriteria dinamis
$criteria_select = '';
foreach ($criteria_names as $criteria_name) {
    // COALESCE untuk menginisialisasi nilai dengan 0 jika tidak ada nilai yang terkait
    $criteria_select .= "COALESCE(MAX(CASE WHEN k.nama_kriteria = '$criteria_name' THEN nk.nilai END), 0) AS $criteria_name, ";
}
$criteria_select = rtrim($criteria_select, ', ');

// Query utama untuk menampilkan data siswa, ekstrakurikuler, dan nilai kriteria dinamis
$query = "
    SELECT s.nm_siswa AS `Nama Siswa`, e.nm_ekskul AS `Ekstrakurikuler`,
           $criteria_select
    FROM ekskul e
    CROSS JOIN siswa s
    LEFT JOIN detail_siswa ds ON e.kd_ekskul = ds.kd_ekskul AND ds.kd_siswa = s.kd_siswa
    LEFT JOIN nilai_kriteria nk ON ds.kd_detail = nk.kd_detail
    LEFT JOIN kriteria k ON nk.id_kriteria = k.id_kriteria
    GROUP BY s.nm_siswa, e.nm_ekskul
    ORDER BY s.nm_siswa, e.nm_ekskul;
";

$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['Nama Siswa']][] = $row;
}

// Query untuk mengambil bobot kriteria
$query_criteria = "SELECT bobot FROM kriteria ORDER BY nama_kriteria ASC";
$result_criteria = $conn->query($query_criteria);

$criteria_weights = [];
while ($row = $result_criteria->fetch_assoc()) {
    $criteria_weights[] = $row['bobot'];
}

// Fungsi menghitung nilai utilitas
function calculate_utility($value, $min, $max)
{
    // Memastikan tidak ada pembagian dengan nol
    if ($min == $max) {
        return 0; // Atau nilai default sesuai kebutuhan
    }

    return ($value - $min) / ($max - $min);
}

// Menghitung nilai utilitas dan nilai akhir untuk setiap siswa
$utility_data = [];
$final_scores = [];

foreach ($data as $student_name => $records) {
    // Mendapatkan nilai minimum dan maksimum untuk setiap kriteria
    $min_max = [];
    foreach ($criteria_names as $criteria_name) {
        $min_max[$criteria_name] = ['min' => PHP_INT_MAX, 'max' => PHP_INT_MIN];
    }

    foreach ($records as $row) {
        foreach ($criteria_names as $criteria_name) {
            $value = $row[$criteria_name];
            if ($value < $min_max[$criteria_name]['min']) {
                $min_max[$criteria_name]['min'] = $value;
            }
            if ($value > $min_max[$criteria_name]['max']) {
                $min_max[$criteria_name]['max'] = $value;
            }
        }
    }

    // Menghitung nilai utilitas untuk setiap kriteria dan nilai akhir
    foreach ($records as $row) {
        $utility_row = $row;
        foreach ($criteria_names as $criteria_name) {
            $utility_row[$criteria_name] = calculate_utility($row[$criteria_name], $min_max[$criteria_name]['min'], $min_max[$criteria_name]['max']);
        }
        $utility_data[$student_name][] = $utility_row;

        // Menghitung nilai akhir
        $final_score = 0;
        foreach ($criteria_names as $index => $criteria_name) {
            $final_score += $utility_row[$criteria_name] * $criteria_weights[$index];
        }
        $final_scores[$student_name][$row['Ekstrakurikuler']] = $final_score;
    }
}

// Menutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pemilihan Ekstrakurikuler dengan Metode SMART</title>
    <style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f2f2f2;
    }
    </style>
</head>

<body>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h3>Data Siswa dan Ekstrakurikuler</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Ekstrakurikuler</th>
                            <?php foreach ($criteria_names as $criteria_name) : ?>
                            <th><?php echo $criteria_name; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $displayed_students = [];
                        foreach ($data as $student_name => $records) :
                            $rowspan = count($records); // Jumlah baris untuk nama siswa ini
                            foreach ($records as $index => $row) :
                        ?>
                        <tr>
                            <?php if (!in_array($row['Nama Siswa'], $displayed_students)) : ?>
                            <td rowspan="<?php echo $rowspan; ?>"><?php echo $row['Nama Siswa']; ?></td>
                            <?php $displayed_students[] = $row['Nama Siswa']; ?>
                            <?php endif; ?>
                            <td><?php echo $row['Ekstrakurikuler']; ?></td>
                            <?php foreach ($criteria_names as $criteria_name) : ?>
                            <td><?php echo $row[$criteria_name]; ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h2>Nilai Utilitas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Ekstrakurikuler</th>
                            <?php foreach ($criteria_names as $criteria_name) : ?>
                            <th>Utilitas <?php echo $criteria_name; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utility_data as $student_name => $records) : ?>
                        <?php foreach ($records as $index => $row) : ?>
                        <tr>
                            <?php if ($index === 0) : ?>
                            <td rowspan="<?php echo count($records); ?>"><?php echo $student_name; ?></td>
                            <?php endif; ?>
                            <td><?php echo $row['Ekstrakurikuler']; ?></td>
                            <?php foreach ($criteria_names as $criteria_name) : ?>
                            <td><?php echo number_format($row[$criteria_name], 4); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h2>Nilai Akhir</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Ekstrakurikuler</th>
                            <th>Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $displayed_students = [];
                        foreach ($final_scores as $student => $scores) :
                            $rowspan = count($scores);
                        ?>
                        <?php foreach ($scores as $ekskul => $score) : ?>
                        <tr>
                            <?php if (!in_array($student, $displayed_students)) : ?>
                            <td rowspan="<?php echo $rowspan; ?>"><?php echo $student; ?></td>
                            <?php $displayed_students[] = $student; ?>
                            <?php endif; ?>
                            <td><?php echo $ekskul; ?></td>
                            <td><?php echo number_format($score, 4); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php
                // Hasil Akhir: Ekstrakurikuler yang Paling Cocok
                // Array untuk menyimpan nilai akhir
                $final_scores_data = [];

                foreach ($final_scores as $student => $scores) {
                    $total_score = 0;
                    foreach ($scores as $ekskul => $score) {
                        $total_score += $score;
                    }
                    $final_scores_data[] = ['Nama Siswa' => $student, 'Nilai Akhir' => $total_score];
                }

                // Mengurutkan siswa berdasarkan nilai akhir (descending)
                usort($final_scores_data, function ($a, $b) {
                    return $b['Nilai Akhir'] <=> $a['Nilai Akhir'];
                });

                // Menampilkan hasil ranking
                echo "<h2>Hasil Ranking Siswa Berdasarkan Nilai Akhir</h2>";
                echo "<ol>";
                foreach ($final_scores_data as $index => $data) {
                    echo "<li>{$data['Nama Siswa']} - Nilai Akhir: " . number_format($data['Nilai Akhir'], 4) . "</li>";
                }
                echo "</ol>";

                ?>

            </div>
        </div>
    </div>
</body>

</html>