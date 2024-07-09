<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pemilihan_ekskul"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data dari database
$query = "SELECT s.kd_siswa, s.nm_siswa, e.kd_ekskul, e.nm_ekskul, k.nama_kriteria, nk.nilai
          FROM detail_siswa ds
          JOIN siswa s ON ds.kd_siswa = s.kd_siswa
          JOIN ekskul e ON ds.kd_ekskul = e.kd_ekskul
          JOIN nilai_kriteria nk ON ds.kd_detail = nk.kd_detail
          JOIN kriteria k ON nk.id_kriteria = k.id_kriteria";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['nm_siswa']][$row['nm_ekskul']][$row['nama_kriteria']] = $row['nilai'];
}

// Mengambil data kriteria dari database
$query_criteria = "SELECT * FROM kriteria";
$result_criteria = $conn->query($query_criteria);

$criteria_weights = [];
$criteria_count = $result_criteria->num_rows; // Jumlah kriteria dari database
$criteria_names = [];

while ($row = $result_criteria->fetch_assoc()) {
    // echo "<pre>";
    // print_r($row);
    // echo "</pre>";
    $criteria_weights[] = $row['bobot'];
    $criteria_names[] = $row['nama_kriteria'];
}

// Fungsi menghitung nilai utilitas
function calculate_utility($value, $min, $max)
{
    return ($value - $min) / ($max - $min);
}

// Menghitung nilai utilitas dan nilai akhir untuk setiap siswa
$utility_data = [];
$final_scores = [];

foreach ($data as $student_name => $records) {
    // Mendapatkan nilai minimum dan maksimum untuk setiap kriteria
    $min_max = [];
    foreach ($criteria_names as $criteria) {
        $min_max[$criteria] = ['min' => PHP_INT_MAX, 'max' => PHP_INT_MIN];
    }

    foreach ($records as $ekskul_name => $scores) {
        foreach ($scores as $criteria => $value) {
            if ($value < $min_max[$criteria]['min']) {
                $min_max[$criteria]['min'] = $value;
            }
            if ($value > $min_max[$criteria]['max']) {
                $min_max[$criteria]['max'] = $value;
            }
        }
    }

    // Menghitung nilai utilitas untuk setiap kriteria
    foreach ($records as $ekskul_name => $scores) {
        $utility_row = [];
        foreach ($scores as $criteria => $value) {
            $utility_row[$criteria] = calculate_utility($value, $min_max[$criteria]['min'], $min_max[$criteria]['max']);
        }
        $utility_data[$student_name][$ekskul_name] = $utility_row;

        // Menghitung nilai akhir
        $final_score = 0;
        foreach ($criteria_names as $index => $criteria) {
            $final_score += $utility_row[$criteria] * $criteria_weights[$index];
        }
        $final_scores[$student_name][$ekskul_name] = $final_score;
    }
}

// Menentukan ekstrakurikuler terbaik untuk setiap siswa
$best_extracurricular = [];
foreach ($final_scores as $student => $scores) {
    arsort($scores);
    $best_extracurricular[$student] = key($scores);
}

// Menampilkan hasil akhir
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Akhir Metode SMART</title>
</head>

<body>
    <h1>Data Siswa dan Ekstrakurikuler</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Ekstrakurikuler</th>
                <?php foreach ($criteria_names as $criteria) : ?>
                <th><?php echo $criteria; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $displayed_students = [];
            foreach ($data as $student_name => $records) :
                $rowspan = count($records); // Jumlah baris untuk nama siswa ini
                foreach ($records as $ekskul_name => $scores) :
            ?>
            <tr>
                <?php if (!in_array($student_name, $displayed_students)) : ?>
                <td rowspan="<?php echo $rowspan; ?>"><?php echo $student_name; ?></td>
                <?php $displayed_students[] = $student_name; ?>
                <?php endif; ?>
                <td><?php echo $ekskul_name; ?></td>
                <?php foreach ($criteria_names as $criteria) : ?>
                <td><?php echo $scores[$criteria]; ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Nilai Utilitas</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Ekstrakurikuler</th>
                <?php foreach ($criteria_names as $criteria) : ?>
                <th>Utility <?php echo $criteria; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $displayed_students = [];
            foreach ($utility_data as $student_name => $records) :
                $rowspan = count($records);
            ?>
            <?php foreach ($records as $ekskul_name => $scores) : ?>
            <tr>
                <?php if (!in_array($student_name, $displayed_students)) : ?>
                <td rowspan="<?php echo $rowspan; ?>"><?php echo $student_name; ?></td>
                <?php $displayed_students[] = $student_name; ?>
                <?php endif; ?>
                <td><?php echo $ekskul_name; ?></td>
                <?php foreach ($criteria_names as $criteria) : ?>
                <td><?php echo $scores[$criteria]; ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Nilai Akhir</h2>
    <table border="1">
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
            <?php foreach ($scores as $ekskul_name => $score) : ?>
            <tr>
                <?php if (!in_array($student, $displayed_students)) : ?>
                <td rowspan="<?php echo $rowspan; ?>"><?php echo $student; ?></td>
                <?php $displayed_students[] = $student; ?>
                <?php endif; ?>
                <td><?php echo $ekskul_name; ?></td>
                <td><?php echo $score; ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Hasil Akhir: Ekstrakurikuler yang Paling Cocok</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Ekstrakurikuler yang Paling Cocok</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($best_extracurricular as $student => $ekskul) : ?>
            <tr>
                <td><?php echo $student; ?></td>
                <td><?php echo $ekskul; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>

<?php
$conn->close();
?>