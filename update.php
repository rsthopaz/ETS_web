<?php
session_start();
include 'db.php';

// Redirect jika pengguna belum login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Cek apakah ID dikirim untuk update
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    // Query untuk mengambil data kursus berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    if (!$course) {
        echo "Kursus tidak ditemukan!";
        exit();
    }
}

// Update data kursus jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_name = $course['image']; // gambar default jika tidak ada yang baru

    // Cek jika ada file gambar baru diunggah
    if (isset($_FILES["image"]) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi tipe file
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false && in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            // Upload file baru
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "Gambar berhasil diunggah.";
            } else {
                echo "Gagal mengunggah gambar.";
                exit();
            }
        } else {
            echo "Format file tidak valid.";
            exit();
        }
    }

    // Update database dengan data baru
    $stmt = $conn->prepare("UPDATE courses SET title = ?, price = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sdssi", $title, $price, $description, $image_name, $id);

    if ($stmt->execute()) {
        echo "Kursus berhasil diperbarui!";

    } else {
        echo "Gagal memperbarui kursus!";
    }
}

// Mengambil semua ID kursus untuk ditampilkan sebagai link
$result = $conn->query("SELECT id FROM courses");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Kursus</title>
    <link rel="stylesheet" href="update.css">

</head>
<body style="background-image: url(std\ 1.png);">
    <h1>Pilih ID Kursus untuk Diedit</h1>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li><a href="update.php?id=<?= $row['id']; ?>">Edit Kursus ID: <?= $row['id']; ?></a></li>
        <?php endwhile; ?>
    </ul>

    <?php if (isset($course)): ?>
        
        <form method="POST"  action="" enctype="multipart/form-data">
        <h2 style="text-align:center;">Edit Kursus ID <?= htmlspecialchars($course['id']); ?></h2>
            <label style="color: #685f78; margin-bottom:20px;" for="title">Judul Joki:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($course['title']); ?>" required><br>

            <label style="color: #685f78; margin-bottom:20px;" for="price">Harga Joki:</label>
            <input type="text" name="price" value="<?= htmlspecialchars($course['price']); ?>" required><br>

            <label style="color: #685f78; margin-bottom:20px;" for="description">Deskripsi:</label>
            <textarea name="description" required><?= htmlspecialchars($course['description']); ?></textarea><br>

            <label style="color: #685f78; margin-bottom:20px;" for="image">Upload image (opsional):</label>
            <input type="file" name="image" accept="image/*"><br>
            <p>Gambar saat ini: <br> <img src="uploads/<?= htmlspecialchars($course['image']); ?>" alt="Gambar Kursus" width="100"></p>


            <button type="submit">Update Kursus</button>

            <div class="a1">
        <a href="index.php">Lihat data Joki</a>
        </div>
        </form>
    <?php endif; ?>
</body>
</html>
