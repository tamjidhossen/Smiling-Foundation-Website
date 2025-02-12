<?php
require_once '../config/config.php';
error_log("Looking for file: " . DATA_PATH . '/blog.json');

// Load blog data from JSON file
$blogs = loadJsonData('blog.json');
if (!$blogs) {
    // Log the error
    error_log("Failed to load blog data");
    // Set default values
    $all_blogs = [];
    $categories = [];
} else {
    $all_blogs = $blogs['blogs'] ?? [];
    $categories = $blogs['categories'] ?? [];
}

// Check if blogs data is loaded correctly
if (!$blogs || !isset($blogs['categories']) || !isset($blogs['blogs'])) {
    die("Error: Unable to load blog data");
}

$all_blogs = $blogs['blogs'];
$categories = $blogs['categories'];

// Simple category filter if category is set in URL
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$tag = isset($_GET['tag']) ? $_GET['tag'] : null;

// Filter blogs based on category or tag
$filtered_blogs = [];
if ($category_id) {
    $filtered_blogs = array_filter($all_blogs, function($blog) use ($category_id) {
        return $blog['category_id'] === $category_id;
    });
} elseif ($tag) {
    $filtered_blogs = array_filter($all_blogs, function($blog) use ($tag) {
        return in_array($tag, $blog['tags']);
    });
} else {
    $filtered_blogs = $all_blogs;
}

// Convert filtered_blogs from array with keys to indexed array
$filtered_blogs = array_values($filtered_blogs);
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
                    <?php foreach ($categories as $cat): ?>
                        <a href="?category=<?php echo $cat['id']; ?>" 
                           class="category-link <?php echo $category_id === $cat['id'] ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Blog Grid -->
                <div class="blog-grid">
                    <?php if (empty($filtered_blogs)): ?>
                        <div class="no-results">
                            <h3>No blog posts found</h3>
                            <p>Try selecting a different category</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($filtered_blogs as $blog): ?>
                            <article class="blog-card fade-in">
                                <div class="blog-image">
                                    <img src="../assets/img/blog/<?php echo htmlspecialchars($blog['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($blog['title']); ?>">
                                </div>
                                <div class="blog-content">
                                    <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                                    <div class="blog-meta">
                                        <span><i class="far fa-calendar"></i> <?php echo formatDate($blog['date']); ?></span>
                                        <span><i class="far fa-user"></i> <?php echo htmlspecialchars($blog['author']); ?></span>
                                    </div>
                                    <p class="blog-excerpt"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                                    <div class="blog-footer">
                                        <a href="blog-single.php?id=<?php echo $blog['id']; ?>" class="read-more">
                                            Read More
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