<?php
session_start();
include 'db.php';

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil semua data kursus
$result = $conn->query("SELECT id, title FROM courses");

if ($result->num_rows > 0): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursus untuk Diperbarui</title>
</head>
<body>
    <h2>Pilih Kursus untuk Diperbarui</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Opsi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td>
                    <a href="update.php?id=<?= $row['id'] ?>">Update</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="index.php">Kembali ke Halaman Utama</a>
</body>
</html>

<?php else: ?>
    <p>Tidak ada kursus yang tersedia.</p>
<?php endif; ?>
