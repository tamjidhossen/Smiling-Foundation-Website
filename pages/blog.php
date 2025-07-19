<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$conn = get_database_connection();

// Define the three fixed categories
$fixed_categories = [
    1 => 'Projects',
    2 => 'Stories',
    3 => 'News'
];

// Simple category filter if category is set in URL
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Fetch blog posts or news based on category filter
if ($category_id === 3) {
    // For News category, fetch from news table only
    $query = "SELECT *, 'news' as type FROM news ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $is_news = true;
} elseif ($category_id && $category_id !== 3) {
    // For specific blog categories (Projects, Stories)
    $query = "SELECT *, 'blog' as type FROM blogs WHERE category_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $is_news = false;
} else {
    // For "All Posts" - combine blogs and news
    $query = "SELECT id, title, NULL as content, NULL as author, 3 as category_id, thumbnail as image, external_url, created_at, 'news' as type FROM news
              UNION ALL
              SELECT id, title, content, author, category_id, image, NULL as external_url, created_at, 'blog' as type FROM blogs 
              ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $is_news = false; // Mixed content, handle individually
}
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
                    <?php if (empty($items)): ?>
                        <div class="no-results">
                            <h3>No <?php echo $is_news ? 'news' : 'blog posts'; ?> found</h3>
                            <p>Try selecting a different category</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <?php if ($is_news || $item['type'] === 'news'): ?>
                                <!-- News Card -->
                                <article class="blog-card fade-in">
                                    <div class="blog-image">
                                        <img src="../assets/img/blog/<?php echo htmlspecialchars($item['thumbnail'] ?? $item['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['title']); ?>">
                                        <div class="news-badge">
                                            <i class="fas fa-external-link-alt"></i>
                                        </div>
                                    </div>
                                    <div class="blog-card-content">
                                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                        <div class="blog-meta">
                                            <span><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($item['created_at'])); ?></span>
                                            <span><i class="far fa-newspaper"></i> External News</span>
                                        </div>
                                        <div class="blog-footer">
                                            <a href="<?php echo htmlspecialchars($item['external_url']); ?>" 
                                               target="_blank" 
                                               rel="noopener noreferrer" 
                                               class="read-more">
                                                Read Full Story <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            <?php else: ?>
                                <!-- Blog Card -->
                                <article class="blog-card fade-in">
                                    <div class="blog-image">
                                        <img src="../assets/img/blog/<?php echo htmlspecialchars($item['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['title']); ?>">
                                    </div>
                                    <div class="blog-card-content">
                                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                        <div class="blog-meta">
                                            <span><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($item['created_at'])); ?></span>
                                            <span><i class="far fa-user"></i> <?php echo htmlspecialchars($item['author']); ?></span>
                                            <span><i class="far fa-folder"></i> <?php echo htmlspecialchars($fixed_categories[$item['category_id']] ?? 'Unknown'); ?></span>
                                        </div>
                                        <div class="blog-footer">
                                            <a href="blog-single.php?id=<?php echo $item['id']; ?>" class="read-more">
                                                Read More <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            <?php endif; ?>
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