<?php
// Define database connection parameters (adjust as needed)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pemilihan_ekskul"; // Ganti dengan nama basis data Anda

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil kd_siswa dari data POST
$kd_siswa = $_GET['kd_siswa'];

// Query untuk mengambil detail siswa dan nilai kriteria untuk setiap kegiatan ekstrakurikuler
$query_siswa = "SELECT s.kd_siswa, s.nm_siswa, ds.kd_ekskul, nk.id_kriteria, nk.nilai, k.nama_kriteria
                FROM siswa s
                LEFT JOIN detail_siswa ds ON s.kd_siswa = ds.kd_siswa
                LEFT JOIN nilai_kriteria nk ON ds.kd_detail = nk.kd_detail
                LEFT JOIN kriteria k ON nk.id_kriteria = k.id_kriteria
                WHERE s.kd_siswa = '$kd_siswa'
                ORDER BY ds.kd_ekskul ASC";

$result_siswa = $conn->query($query_siswa);

if ($result_siswa->num_rows > 0) {
    $data = [
        "data" => [
            "kd_siswa" => "",
            "nm_siswa" => "",
            "nilai_kriteria" => []
        ]
    ];

    while ($row = $result_siswa->fetch_assoc()) {
        $kd_siswa = $row['kd_siswa'];
        $nm_siswa = $row['nm_siswa'];
        $kd_ekskul = $row['kd_ekskul'];
        $id_kriteria = $row['id_kriteria'];
        $nilai = $row['nilai'];
        $nama_kriteria = $row['nama_kriteria'];

        // Set detail siswa hanya sekali
        if (empty($data['data']['kd_siswa'])) {
            $data['data']['kd_siswa'] = $kd_siswa;
            $data['data']['nm_siswa'] = $nm_siswa;
        }

        // Inisialisasi array nilai_kriteria untuk setiap kegiatan ekstrakurikuler jika belum ada
        if (!isset($data['data']['nilai_kriteria'][$kd_ekskul])) {
            $data['data']['nilai_kriteria'][$kd_ekskul] = [];
        }

        // Memasukkan nilai kriteria untuk kegiatan ekstrakurikuler yang bersangkutan
        $data['data']['nilai_kriteria'][$kd_ekskul][$id_kriteria] = [
            "nama_kriteria" => $nama_kriteria,
            "nilai" => $nilai
        ];
    }

    // Mengembalikan data dalam format JSON
    echo json_encode($data);
} else {
    // Jika tidak ada data yang ditemukan, kembalikan pesan kosong
    echo json_encode(["message" => "No records found"]);
}

// Menutup koneksi database
$conn->close();
