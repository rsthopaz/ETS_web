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
    $description = $_POST['description'];

    if (isset($_FILES["image"]) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Cek apakah file image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File yang diupload bukan image.";
            exit;
        }

        // Cek format file
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
            $uploadOk = 0;
            exit;
        }

        // Cek jika file sudah ada
        if (file_exists($target_file)) {
            echo "Maaf, file sudah ada.";
            $uploadOk = 0;
            exit;
        }

        // Jika semuanya baik, upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Simpan informasi kursus termasuk nama file image
                $stmt = $conn->prepare("INSERT INTO courses (title, price, description, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sdss", $title, $price, $description, $image_name);
                $stmt->execute();
            } else {
                echo "Maaf, terjadi kesalahan saat meng-upload file.";
            }
        }
    } else {
        echo "Tidak ada file yang di-upload.";
    }
}

// Hapus kursus jika admin melakukan penghapusan
if (isset($_GET['delete']) && $role == 'admin') {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet"

  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<header>
        <a href="#" class="logo">
            <img src="joki2.png" alt="">
        </a>
        <ul class="navbar">
            <li><a href="#home">Home</a></li>
            <li><a href="#categories">Kategori</a></li>
            <li><a href="#courses">Joki</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <div class="header-icons">
            <a href="#"><i class='bx bx-user'></i></a>
            <a href="#"><i class='bx bx-heart' ></i></a>
            <a href="#"><i class='bx bx-cart' ></i></a>
        </div class="bx bx-menu" id="menu-icon">
    </header>
    <section class="home" id="home">
        <div class="home-text">
            <h6>Joki tugas aman dan terpercaya</h6>
            <h1>Semua Mata Kuliah Tersedia</h1>
            <p>Jadilah malas, supaya kami dapat uang</p>
            <div class="latter">
                <div class="regis">
                    <a href="logout.php" class="a1">Logout</a>
                    <!-- <input type="submit" value="Let's Goo!" required> -->
                </div>
            </div>
            
        </div>
        <div class="home-img">
            <img src="home2.png" alt="">
        </div>

    </section>
    <section class="container">
        <div class="container-box">
            <div class="container-img">
                <img src="toga.svg" alt="">
            </div>
            <div class="container-text">
                <h4>100+</h4>
                <p>Latihan</p>
            </div>
        </div>

        
        <div class="container-box">
            <div class="container-img">
                <img src="time.svg" alt="">
            </div>
            <div class="container-text">
                <h4>3 Years</h4>
                <p>Pengalaman</p>
            </div>
        </div>
        
        <div class="container-box">
            <div class="container-img">
                <img src="grade.svg" alt="">
            </div>
            <div class="container-text">
                <h4>100%</h4>
                <p>Jawaban</p>
            </div>
        </div>

        </div>

    </section>
    <section class="categories" id="categories">
        <div class="center-text">
            <h5>Kategori</h5>
            <h2>Kategori Populer</h2>
        </div>
        <div class="categories-content">
        <div class="box vis">
            <img src="kalkulus.png" alt="">
            <h3>Kalkulus<br>I & II</h3>
            <p>7 Materi</p>
        </div>
        <div class="box vis">
            <img src="code.png" alt="">
            <h3>Pemrogramman Web</h3>
            <p>5 Materi</p>
        </div>
        <div class="box vis">
            <img src="physic.png" alt="">
            <h3>Fisika <br> Mekanika</h3>
            <p>12 Materi</p>
        </div>
        <div class="box vis">
            <img src="design.png" alt="">
            <h3>UI/UX <br> Design</h3>
            <p>6 Materi</p>
        </div>
        <div class="box hid">
            <img src="edit.png" alt="">
            <h3>Video <br> Editing</h3>
            <p>11 Materi</p>
        </div>
        <div class="box hid">
            <img src="foll.png" alt="">
            <h3>Bot <br> Follower </h3>
            <p>4 Materi</p>
        </div>
        <div class="box hid">
            <img src="game.png" alt="">
            <h3>Mobile <br> Gaming</h3>
            <p>3 Materi</p>
        </div>
        <div class="box hid">
            <img src="ill.png" alt="">
            <h3>Illustration <br> Design</h3>
            <p>14 Materi</p>
        </div>
    </div>
        <div class="main-btn">
            <a id="btn3"class="btn3">Semua Kategori</a>
        </div>
    </section>

    <section class="bgcourse">
    <div class="center-text">
        <h5>Joki</h5>
        <h2>Explore Semua Joki</h2>
        </div>
    <div class="courses" id="courses">
       
        <?php 
        while($row = $result->fetch_assoc()): 
            // $randomImage = $imageUrls[array_rand($imageUrls)];
            $imageUrl = 'uploads/' . $row['image'];
        ?>
        <div class="coursescontent">
        
            <div class="row visible">
                
                <img src="<?= $imageUrl?>" alt="">
                
                <div class="courses-text">
                <a href="<?= $imageUrl ?>" download="<?= basename($imageUrl) ?>"style="
    display: inline-block;
    padding: 8px 12px;
    background-color: #2a9df4;
    color: white;
    text-decoration: none;
    border-radius: 12px; margin-bottom:15px;"> <i class='bx bxs-download'></i> Download Gambar</a>
                    <h5><?= $row['price'] ?></h5>
                    <h3><?= $row['title'] ?></h5>
                    <h6><?= $row['description'] ?></h6>
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
        
                        
                    </div>
                    </div>
                </div>
            </div>
           
            </div>
            <?php endwhile; ?>
           
        </div>
        <?php if ($role == 'admin'): ?>
                <div class="main-btn">
            <a id="btn2"class="btn3" href="add.php">Tambah Joki?</a>
            <a id="btn2"class="btn3" href="delete.php">Hapus Joki?</a>
            <a id="btn2"class="btn3" href="update.php">Update Joki?</a>
                <?php endif; ?>
            </div>
    </section>

   
        <!-- <h3>Tambah Kursus Baru</h3>
        <form method="POST" action="" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Judul Kursus" required>
    <input type="text" name="price" placeholder="Harga Kursus" required>
    <textarea name="description" placeholder="Deskripsi Kursus" required></textarea>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">Tambah Kursus</button> -->
</form>
    
    <script src="script.js"></script>
    <script src="script2.js"></script>
    <script src="script3.js"></script>
</body>
</html>
