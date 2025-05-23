<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}


$conn = get_database_connection();

// Define the three fixed categories
$fixed_categories = [
    1 => 'Projects',
    2 => 'Stories',
    3 => 'Updates'
];

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: blogs.php?error=invalid_request');
    exit;
}

$blog_id = $_GET['id'];

// Fetch blog post details
$query = "SELECT * FROM blogs WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $blog_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$blog = mysqli_fetch_assoc($result)) {
    // Blog post not found
    header('Location: blogs.php?error=post_not_found');
    exit;
}

// Debug output
error_log("Previewing blog ID: {$blog['id']}, Title: {$blog['title']}");

$pageTitle = "Preview: " . $blog['title'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="admin-dashboard">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="admin-main">
        <div class="dashboard-header">
            <h1>Blog Post Preview</h1>
            <p>Previewing: <?php echo htmlspecialchars($blog['title']); ?></p>
            
            <div style="margin-top: 1rem;">
                <a href="blog_form.php?id=<?php echo $blog['id']; ?>" class="cta-button secondary">
                    <i class="fas fa-edit"></i> Edit Post
                </a>
                <a href="blogs.php" class="cta-button secondary">
                    <i class="fas fa-arrow-left"></i> Back to All Posts
                </a>
            </div>
        </div>
        
        <div class="content-section">
            <div class="preview-header" style="margin-bottom: 2rem;">
                <div class="blog-meta" style="display: flex; gap: 1.5rem; margin-bottom: 1rem;">
                    <span><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                    <span><i class="far fa-user"></i> <?php echo htmlspecialchars($blog['author']); ?></span>
                    <span><i class="far fa-folder"></i> <?php echo htmlspecialchars($fixed_categories[$blog['category_id']] ?? 'Unknown'); ?></span>
                </div>
            </div>
            
            <?php if (!empty($blog['image'])): ?>
                <div style="margin-bottom: 2rem;">
                    <img src="../assets/img/blog/<?php echo htmlspecialchars($blog['image']); ?>" 
                         alt="<?php echo htmlspecialchars($blog['title']); ?>"
                         style="max-width: 100%; border-radius: 8px;">
                </div>
            <?php endif; ?>
            
            <div class="blog-content">
                <div class="blog-full-content">
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
            </div>
        </div>
    </div>
</body>
</html>