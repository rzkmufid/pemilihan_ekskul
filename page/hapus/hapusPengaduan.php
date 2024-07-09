<?php
session_start();
include '../../koneksi.php';
// Informasi koneksi database


// Memeriksa apakah ID pengaduan telah dikirimkan melalui GET
if (isset($_GET['id_pengaduan'])) {
    $id_pengaduan = $conn->real_escape_string($_GET['id_pengaduan']);

    // Query SQL untuk menghapus data dari tabel Pengaduan berdasarkan ID
    $sql = "DELETE FROM Pengaduan WHERE id_pengaduan='$id_pengaduan'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../index.php?i=pengaduan&notif=delete");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "ID pengaduan tidak ditemukan.";
}

// Menutup koneksi
$conn->close();
?>
