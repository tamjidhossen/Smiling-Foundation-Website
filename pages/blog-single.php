<?php 
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$conn = get_database_connection();

// Define the three fixed categories
$fixed_categories = [
    1 => 'Projects',
    2 => 'Stories',
    3 => 'Updates'
];

$blog_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch blog post
$query = "SELECT * FROM blogs WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $blog_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$blog = mysqli_fetch_assoc($result)) {
    // Blog post not found
    header('Location: 404.php');
    exit;
}

// Find related posts (by category)
$related_query = "SELECT * FROM blogs WHERE id != ? AND category_id = ? ORDER BY created_at DESC LIMIT 3";
$stmt = mysqli_prepare($conn, $related_query);
mysqli_stmt_bind_param($stmt, 'ii', $blog_id, $blog['category_id']);
mysqli_stmt_execute($stmt);
$related_result = mysqli_stmt_get_result($stmt);
$related_posts = mysqli_fetch_all($related_result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/animations.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <article class="blog-single">
            <div class="blog-hero" style="background-image: url('../assets/img/blog/<?php echo htmlspecialchars($blog['image']); ?>')">
                <div class="container">
                    <h1 class="fade-in"><?php echo htmlspecialchars($blog['title']); ?></h1>
                    <div class="blog-meta">
                        <span><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                        <span><i class="far fa-user"></i> <?php echo htmlspecialchars($blog['author']); ?></span>
                        <span><i class="far fa-folder"></i> <?php echo htmlspecialchars($fixed_categories[$blog['category_id']] ?? 'Unknown'); ?></span>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="blog-content-wrapper">
                    <div class="blog-main-content fade-in">
                        <div class="blog-content">
                            <?php
                            // Check if content contains HTML tags (from previous TinyMCE editor)
                            if (strip_tags($blog['content']) !== $blog['content']) {
                                // Content has HTML tags, display it as is
                                echo $blog['content'];
                            } else {
                                // Plain text content, add line breaks
                                echo nl2br(htmlspecialchars($blog['content']));
                            }
                            ?>
                        </div>

                        <!-- Social Share Buttons -->
                        <div class="share-buttons">
                            <h4>Share This Post</h4>
                            <button class="share-button" data-platform="facebook">
                                <i class="fab fa-facebook"></i> Share
                            </button>
                            <button class="share-button" data-platform="twitter">
                                <i class="fab fa-twitter"></i> Tweet
                            </button>
                            <button class="share-button" data-platform="linkedin">
                                <i class="fab fa-linkedin"></i> Share
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </main>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/blog.js"></script>
</body>
</html>