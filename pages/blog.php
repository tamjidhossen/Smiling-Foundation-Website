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

// Simple category filter if category is set in URL
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Fetch blog posts based on category filter
if ($category_id) {
    $query = "SELECT * FROM blogs WHERE category_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $category_id);
} else {
    $query = "SELECT * FROM blogs ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $query);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$blogs = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/animations.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php $heroImage = getHeroImage('blog'); ?>
    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
    <main>
        <section class="page-hero">
            <div class="hero-content fade-in">
                <h1>Our Blog</h1>
                <p>Stories and updates from our community</p>
            </div>
        </section>

        <section class="blog-section">
            <div class="container">
                <!-- Categories Menu -->
                <div class="blog-categories fade-in">
                    <a href="blog.php" class="category-link <?php echo !$category_id ? 'active' : ''; ?>">
                        All Posts
                    </a>
                    <?php foreach ($fixed_categories as $id => $name): ?>
                        <a href="?category=<?php echo $id; ?>" 
                           class="category-link <?php echo $category_id === $id ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Blog Grid -->
                <div class="blog-grid">
                    <?php if (empty($blogs)): ?>
                        <div class="no-results">
                            <h3>No blog posts found</h3>
                            <p>Try selecting a different category</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($blogs as $blog): ?>
                            <article class="blog-card fade-in">
                                <div class="blog-image">
                                    <img src="../assets/img/blog/<?php echo htmlspecialchars($blog['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($blog['title']); ?>">
                                </div>
                                <div class="blog-card-content">
                                    <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                                    <div class="blog-meta">
                                        <span><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                                        <span><i class="far fa-user"></i> <?php echo htmlspecialchars($blog['author']); ?></span>
                                        <span><i class="far fa-folder"></i> <?php echo htmlspecialchars($fixed_categories[$blog['category_id']] ?? 'Unknown'); ?></span>
                                    </div>
                                    <div class="blog-footer">
                                        <a href="blog-single.php?id=<?php echo $blog['id']; ?>" class="read-more">
                                            Read More <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/main.js"></script>
</body>
</html>