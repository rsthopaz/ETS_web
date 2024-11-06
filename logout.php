<?php
session_start();
session_unset();
session_destroy();

// Hapus cookie dengan waktu kadaluwarsa negatif
setcookie("username", "", time() - 3600, "/");
setcookie("role", "", time() - 3600, "/");

header("Location: login.php");
exit();
?>