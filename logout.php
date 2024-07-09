<?php
session_start();

// Menghapus semua variabel session
session_unset();

// Menghapus session data dari penyimpanan
session_destroy();

// Mengarahkan kembali ke halaman login
header("Location: login.php");
exit();
?>
