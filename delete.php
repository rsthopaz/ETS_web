<?php
session_start();
include 'db.php';

// Check if user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


$role = $_SESSION['role'];
if (isset($_GET['delete']) && $role == 'admin') {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Successfully deleted
    header("Location: delete.php?message=success");
} else {
    // Error deleting course
    header("Location: delete.php?message=error");
}
}

// Mengambil semua data kursus
$result = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kursus</title>
    
    <link rel="stylesheet" href="style5.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
    <section class="bgcourse">

        <div class="btn4">
       
        <a href="index.php"><i class='bx bx-arrow-back'></i> Kembali</a>
        </div>
        
        <div class="center-text">
            <h5>Joki</h5>
            <h2>Explore Semua Joki</h2>
        </div>
        <div class="courses" id="courses">
           
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="coursescontent">
                <div class="row visible">
                    <img src="uploads/<?= $row['image']; ?>" alt="">
                    <div class="courses-text">
                        <h5><?= $row['price'] ?></h5>
                        <h3><?= htmlspecialchars($row['title']); ?></h3>
                        <h6><?= htmlspecialchars($row['description']); ?></h6>
                        <div class="rating">
                            <div class="star">
                            <?php
        $rating = $row['rating'];
        // echo "$rating";
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                echo "<a href='#'><i class='bx bxs-star'></i></a>"; // Bintang aktif
            } else {
                echo "<a href='#'><i class='bx bx-star'></i></a>"; // Bintang kosong
            }
        }
        ?>
                            </div>
                            <div class="review">
                            <?php if ($role === 'admin'): ?>
                                <a href="delete.php?delete=<?= $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus kursus ini?');">
    <button class="btn2">Delete</button>
</a>

            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
    
    <script src="script.js"></script>
    <script src="script2.js"></script>
    <script src="script3.js"></script>
</body>
</html>
