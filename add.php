<?php
session_start();
include 'db.php';

// Redirect ke halaman login jika belum login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Tambah kursus jika form disubmit oleh admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && $role == 'admin') {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = isset($_POST['description']) ? $_POST['description'] : null;
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 1;  
    // Memeriksa apakah image di-upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $image_name = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $image_name); // Sanitasi nama file
        $target_file = $target_dir . $image_name;

        // Memeriksa apakah file adalah image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File yang diunggah bukan image.";
            exit;
        }

        // Memeriksa jenis file image
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Hanya file JPG, JPEG, PNG, dan GIF yang diizinkan.";
            exit;
        }

        // Memindahkan file yang di-upload ke direktori target
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Simpan data kursus ke database
            $stmt = $conn->prepare("INSERT INTO courses (title, price, description, image, rating) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sissi", $title, $price, $description, $image_name, $rating);
            $stmt->execute();

            echo "Kursus berhasil ditambahkan!";
        } else {
            echo "Terjadi kesalahan saat mengunggah image. Path: " . $target_file;
        }
    } else {
        echo "image tidak ditemukan atau tidak diunggah dengan benar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Joki</title>
    <link rel="stylesheet" href="style4.css">
</head>
<body>
   
    <form action="add.php" method="post" enctype="multipart/form-data">
    <h1>Tambah Joki</h1>
        <label for="title">Judul Joki:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="price">Harga Joki:</label><br>
        <input type="text" id="price" name="price" required><br><br>

        <label for="description">Deskripsi:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="image">Upload image:</label><br>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>

        <label for="rating">Rating:</label><br>
        <select id="rating" name="rating" required>
            <option value="1">1 Bintang</option>
            <option value="2">2 Bintang</option>
            <option value="3">3 Bintang</option>
            <option value="4">4 Bintang</option>
            <option value="5">5 Bintang</option>
        </select><br><br>

        <input type="submit" value="Tambah Joki">
        <div class="a1">
        <a href="index.php">Lihat data Joki</a>
        </div>
    </form>
</body>
</html>
