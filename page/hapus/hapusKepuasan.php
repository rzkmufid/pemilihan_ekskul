<?php
session_start();
include '../../koneksi.php';
// Informasi koneksi database


// Memeriksa apakah ID kepuasan telah dikirimkan melalui GET
if (isset($_GET['id_kepuasan'])) {
    $id_kepuasan = $conn->real_escape_string($_GET['id_kepuasan']);

    // Query SQL untuk menghapus data dari tabel kepuasan berdasarkan ID
    $sql = "DELETE FROM kepuasan WHERE id_kepuasan='$id_kepuasan'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../index.php?i=kepuasan&notif=delete");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "ID kepuasan tidak ditemukan.";
}

// Menutup koneksi
$conn->close();
?>
