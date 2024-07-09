<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_pemilihan_ekskul"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data dari database
$query = "SELECT s.kd_siswa, s.nm_siswa, e.kd_ekskul, e.nm_ekskul, ds.c1, ds.c2, ds.c3, ds.c4, ds.c5, ds.c6
          FROM detail_siswa ds
          JOIN siswa s ON ds.kd_siswa = s.kd_siswa
          JOIN ekskul e ON ds.kd_ekskul = e.kd_ekskul";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['nm_siswa']][] = $row;
}

// Mengambil data kriteria dari database
$query_criteria = "SELECT * FROM kriteria";
$result_criteria = $conn->query($query_criteria);

$criteria_weights = [];
$criteria_count = $result_criteria->num_rows; // Jumlah kriteria dari database

while ($row = $result_criteria->fetch_assoc()) {
    $criteria_weights[] = $row['bobot'];
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
    for ($i = 1; $i <= $criteria_count; $i++) {
        $min_max['c' . $i] = ['min' => PHP_INT_MAX, 'max' => PHP_INT_MIN];
    }

    foreach ($records as $row) {
        for ($i = 1; $i <= $criteria_count; $i++) {
            $value = $row['c' . $i];
            if ($value < $min_max['c' . $i]['min']) {
                $min_max['c' . $i]['min'] = $value;
            }
            if ($value > $min_max['c' . $i]['max']) {
                $min_max['c' . $i]['max'] = $value;
            }
        }
    }

    // Menghitung nilai utilitas untuk setiap kriteria
    foreach ($records as $row) {
        $utility_row = $row;
        for ($i = 1; $i <= $criteria_count; $i++) {
            $utility_row['c' . $i] = calculate_utility($row['c' . $i], $min_max['c' . $i]['min'], $min_max['c' . $i]['max']);
        }
        $utility_data[$student_name][] = $utility_row;

        // Menghitung nilai akhir
        $final_score = 0;
        for ($i = 1; $i <= $criteria_count; $i++) {
            $final_score += $utility_row['c' . $i] * $criteria_weights[$i - 1];
        }
        $final_scores[$row['nm_siswa']][$row['nm_ekskul']] = $final_score;
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
                <?php for ($i = 1; $i <= $criteria_count; $i++) : ?>
                <th>C<?php echo $i; ?></th>
                <?php endfor; ?>
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
                <?php if (!in_array($row['nm_siswa'], $displayed_students)) : ?>
                <td rowspan="<?php echo $rowspan; ?>"><?php echo $row['nm_siswa']; ?></td>
                <?php $displayed_students[] = $row['nm_siswa']; ?>
                <?php endif; ?>
                <td><?php echo $row['nm_ekskul']; ?></td>
                <?php for ($i = 1; $i <= $criteria_count; $i++) : ?>
                <td><?php echo $row['c' . $i]; ?></td>
                <?php endfor; ?>
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
                <?php for ($i = 1; $i <= $criteria_count; $i++) : ?>
                <th>Utility C<?php echo $i; ?></th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $displayed_students = [];
            foreach ($utility_data as $student_name => $records) :
                $rowspan = count($records);
            ?>
            <?php foreach ($records as $row) : ?>
            <tr>
                <?php if (!in_array($row['nm_siswa'], $displayed_students)) : ?>
                <td rowspan="<?php echo $rowspan; ?>"><?php echo $row['nm_siswa']; ?></td>
                <?php $displayed_students[] = $row['nm_siswa']; ?>
                <?php endif; ?>
                <td><?php echo $row['nm_ekskul']; ?></td>
                <?php for ($i = 1; $i <= $criteria_count; $i++) : ?>
                <td><?php echo $row['c' . $i]; ?></td>
                <?php endfor; ?>
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
            <?php foreach ($scores as $ekskul => $score) : ?>
            <tr>
                <?php if (!in_array($student, $displayed_students)) : ?>
                <td rowspan="<?php echo $rowspan; ?>"><?php echo $student; ?></td>
                <?php $displayed_students[] = $student; ?>
                <?php endif; ?>
                <td><?php echo $ekskul; ?></td>
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