<?php
session_start();
include 'db.php';

// Proses registrasi jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);  // Enkripsi password
    $role = $_POST['role'];  // Role ditentukan dari form

    // Cek apakah username sudah ada
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "Username sudah terdaftar!";
    } else {
        // Menyimpan pengguna baru ke dalam database
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);
        $stmt->execute();

        echo "Registrasi berhasil! Silakan <a href='login.php'>login</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style3.css">
</head>
<body>
<div class="containerr">
        <h1>Register Akun</h1>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="username">Nama Pengguna:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi:</label>
                <input type="password" id="password" name="password" required>
                <label for="role">Daftar sebagai:</label>
        <select name="role" id="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
            </div>
           
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>

    <!-- <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p> -->
</body>
</html>

