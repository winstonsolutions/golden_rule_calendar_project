<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "golden_rules_calendar";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination setup
$images_per_page = 2; // Adjust number of images per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $images_per_page;

// Fetch images for the current page
$total_images = $conn->query("SELECT COUNT(*) AS total FROM gallery")->fetch_assoc()['total'];
$total_pages = ceil($total_images / $images_per_page);

$result = $conn->query("SELECT * FROM gallery LIMIT $images_per_page OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
        }
        nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #333;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
        }
        .gallery-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            position: relative;
        }
        .gallery-item {
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            width: 400px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .gallery-item .content {
            padding: 15px;
        }
        .gallery-item .content h3 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }
        .gallery-item .content p {
            margin: 10px 0;
            color: #666;
        }
        .nav-btn {
            background-color: transparent;
            color: #333;
            border: none;
            font-size: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            transition: background-color 0.3s ease, color 0.3s ease;
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .nav-btn:focus,
        .nav-btn:active {
            background-color: #4caf50; /* Green color */
            color: white;
            outline: none;
        }
        .prev-btn {
            left: 10px;
        }
        .next-btn {
            right: 10px;
        }
        footer {
            text-align: center;
            background-color: #f4f4f4;
            padding: 15px 0;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .nav-btn:hover {
            background-color: rgba(0, 128, 0, 0.2); /* Light green hover effect */
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="../views/user_registration.php">Register</a>
        <a href="about.php">About</a>
    </nav>
    <h1>Gallery</h1>

    <div class="gallery-container">
        <?php if ($current_page > 1): ?>
            <button class="nav-btn prev-btn" onclick="location.href='?page=<?= $current_page - 1 ?>'">&lt;</button>
        <?php endif; ?>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="gallery-item">
                <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                <div class="content">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p><small>Uploaded: <?= htmlspecialchars($row['upload_time']) ?></small></p>
                </div>
            </div>
        <?php endwhile; ?>

        <?php if ($current_page < $total_pages): ?>
            <button class="nav-btn next-btn" onclick="location.href='?page=<?= $current_page + 1 ?>'">&gt;</button>
        <?php endif; ?>
    </div>

    <footer>
        Contact Information: 204 306 114 | goldenrule@swsrc | 625 Osborne Street
    </footer>

    <script>
        // Enable swipe navigation for gallery items
        let startX;
        const container = document.querySelector('.gallery-container');

        container.addEventListener('mousedown', (e) => {
            startX = e.pageX;
        });

        container.addEventListener('mouseup', (e) => {
            const endX = e.pageX;
            if (startX > endX + 50) {
                // Swipe left (next)
                const nextBtn = document.querySelector('.next-btn');
                if (nextBtn) nextBtn.click();
            } else if (startX < endX - 50) {
                // Swipe right (previous)
                const prevBtn = document.querySelector('.prev-btn');
                if (prevBtn) prevBtn.click();
            }
        });
    </script>
</body>
</html>
<?php $conn->close();?>