<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if (isset($_POST['remember'])) {
            // Atur cookie selama 30 hari
            setcookie("username", $user['username'], time() + (86400 * 30), "/");
            setcookie("role", $user['role'], time() + (86400 * 30), "/");
        }
        header("Location: index.php");
    } else {
        echo "Login gagal, username atau password salah!";
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
        <h1>Login Akun</h1>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="username">Nama Pengguna:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
            <div class="form-group">
    <label>
        <input type="checkbox" name="remember"> Ingatkan Saya
    </label>
</div>
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </form>
    </div>

    
</body>
</html>
