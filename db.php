<?php
$servername = "localhost";
$username = "root";   // sesuaikan dengan username MySQL Anda
$password = "";       // sesuaikan dengan password MySQL Anda
$dbname = "joki_courses";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
