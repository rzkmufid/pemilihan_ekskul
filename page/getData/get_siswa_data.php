<?php
include "../../koneksi.php";

if (isset($_GET['nm_siswa'])) {
    $nm_siswa = $_GET['nm_siswa'];

    $query = "SELECT s.kd_siswa, s.nm_siswa, e.kd_ekskul, e.nm_ekskul, ds.c1, ds.c2, ds.c3, ds.c4, ds.c5, ds.c6
              FROM detail_siswa ds
              JOIN siswa s ON ds.kd_siswa = s.kd_siswa
              JOIN ekskul e ON ds.kd_ekskul = e.kd_ekskul
              WHERE s.nm_siswa = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nm_siswa);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    $stmt->close();
    $conn->close();
}
