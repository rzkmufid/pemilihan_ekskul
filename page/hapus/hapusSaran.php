<?php
session_start();
include '../../koneksi.php';
// Informasi koneksi database


// Memeriksa apakah ID saran telah dikirimkan melalui GET
if (isset($_GET['id_saran'])) {
    $id_saran = $conn->real_escape_string($_GET['id_saran']);

    // Query SQL untuk menghapus data dari tabel saran berdasarkan ID
    $sql = "DELETE FROM saran WHERE id_saran='$id_saran'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../index.php?i=saran&notif=delete");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "ID saran tidak ditemukan.";
}

// Menutup koneksi
$conn->close();
?>
