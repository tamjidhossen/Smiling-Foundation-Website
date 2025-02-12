<?php 
require_once '../config/config.php';
require_once '../includes/functions.php';

$blog_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$blogs = loadJsonData('blog.json');
$blog = null;
$related_posts = [];

foreach ($blogs['blogs'] as $b) {
    if ($b['id'] === $blog_id) {
        $blog = $b;
        // Find related posts by category and tags
        foreach ($blogs['blogs'] as $related) {
            if ($related['id'] !== $blog_id && 
                ($related['category_id'] === $blog['category_id'] || 
                 array_intersect($related['tags'], $blog['tags']))) {
                $related_posts[] = $related;
            }
        }
        break;
    }
}

if (!$blog) {
    header('Location: 404.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $blog['title']; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/animations.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <article class="blog-single">
            <div class="blog-hero" style="background-image: url('../assets/img/blog/<?php echo $blog['image']; ?>')">
                <div class="container">
                    <h1 class="fade-in"><?php echo $blog['title']; ?></h1>
                    <div class="blog-meta">
                        <span><i class="far fa-calendar"></i> <?php echo formatDate($blog['date']); ?></span>
                        <span><i class="far fa-user"></i> <?php echo $blog['author']; ?></span>
                        <span><i class="far fa-folder"></i> <?php echo getCategoryName($blog['category_id'], $blogs['categories']); ?></span>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="blog-content-wrapper">
                    <div class="blog-main-content fade-in">
                        <div class="blog-tags">
                            <?php foreach ($blog['tags'] as $tag): ?>
                                <a href="blog.php?tag=<?php echo urlencode($tag); ?>" class="tag">#<?php echo $tag; ?></a>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="blog-content">
                            <?php echo $blog['content']; ?>
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