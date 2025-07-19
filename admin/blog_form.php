<?php
require_once '../config/config.php';
require_once '../config/database.php';

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
    3 => 'News'
];

// Initialize variables
$blog = [
    'id' => '',
    'title' => '',
    'content' => '',
    'author' => '',
    'category_id' => '',
    'image' => '',
    'external_url' => ''
];
$editing = false;

// Check if editing existing blog post or news
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $item_id = $_GET['id'];
    $item_type = $_GET['type'] ?? 'blog';
    $editing = true;
    
    if ($item_type === 'news') {
        // Fetch news details
        $query = "SELECT id, title, thumbnail as image, external_url FROM news WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $item_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $blog = [
                'id' => $row['id'],
                'title' => $row['title'],
                'content' => '',
                'author' => '',
                'category_id' => 3, // News category
                'image' => $row['image'],
                'external_url' => $row['external_url']
            ];
        } else {
            header('Location: blogs.php');
            exit;
        }
    } else {
        // Fetch blog post details
        $query = "SELECT * FROM blogs WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $item_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $blog = $row;
            $blog['external_url'] = '';
        } else {
            // Blog post not found
            header('Location: blogs.php');
            exit;
        }
    }
}

$pageTitle = $editing ? "Edit Blog Post" : "Create New Blog Post";
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
            <h1><?php echo $pageTitle; ?></h1>
            <p><?php echo $editing ? 'Update the blog post details' : 'Create a new blog post'; ?></p>
        </div>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">
                <?php
                $error = $_GET['error'];
                if ($error === 'empty_content') {
                    echo "Blog content cannot be empty. Please add some content.";
                } elseif ($error === 'update') {
                    echo "There was an error updating the blog post. Please try again.";
                } elseif ($error === 'create') {
                    echo "There was an error creating the blog post. Please try again.";
                } elseif ($error === 'filetype') {
                    echo "Invalid file type. Please upload an image file.";
                } elseif ($error === 'filesize') {
                    echo "File size is too large. Maximum allowed size is 10MB.";
                } elseif ($error === 'upload') {
                    echo "Error uploading the image file. Please try again.";
                } else {
                    echo "An error occurred. Please try again.";
                }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="content-section">
            <form action="blog_handler.php" method="POST" enctype="multipart/form-data" class="admin-form" onsubmit="return validateForm()">
                <?php if ($editing): ?>
                    <input type="hidden" name="id" value="<?php echo $blog['id']; ?>">
                <?php endif; ?>
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="title">Blog Title<span class="required">*</span></label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category<span class="required">*</span></label>
                        <select id="category" name="category_id" required onchange="toggleNewsFields()">
                            <option value="">Select Category</option>
                            <?php foreach ($fixed_categories as $id => $name): ?>
                                <option value="<?php echo $id; ?>" <?php echo $blog['category_id'] == $id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- News-specific field -->
                    <div class="form-group" id="external_url_group" style="display: none;">
                        <label for="external_url">External News URL<span class="required">*</span></label>
                        <input type="url" id="external_url" name="external_url" value="<?php echo htmlspecialchars($blog['external_url']); ?>" placeholder="https://example.com/news-article">
                        <small>Full URL to the external news article</small>
                    </div>
                    
                    <div class="form-group" id="author_group">
                        <label for="author">Author<span class="required">*</span></label>
                        <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($blog['author']); ?>" required>
                    </div>
                    
                    <div class="form-group full-width" id="content_group">
                        <label for="content">Blog Content<span class="required">*</span></label>
                        <textarea id="content" name="content" rows="15" required class="simple-editor"><?php echo htmlspecialchars($blog['content']); ?></textarea>
                        <small>Enter Blog Content Text.</small>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="image">Blog Image</label>
                        <?php if (!empty($blog['image'])): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="../assets/img/blog/<?php echo htmlspecialchars($blog['image']); ?>" 
                                     alt="Current image" style="max-width: 200px; max-height: 200px;">
                                <p>Current image: <?php echo htmlspecialchars($blog['image']); ?></p>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="image" name="image" accept="image/*">
                        <small>Recommended size: 1200x800 pixels. Max file size: 10MB.</small>
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="submit" name="submit" class="cta-button">
                        <?php echo $editing ? 'Update Blog Post' : 'Create Blog Post'; ?>
                    </button>
                    <a href="blogs.php" class="cta-button secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Simple form validation function
        function validateForm() {
            const content = document.getElementById('content').value.trim();
            const categoryId = document.getElementById('category').value;
            const externalUrl = document.getElementById('external_url').value.trim();
            
            // For news category, check external URL instead of content
            if (categoryId === '3') {
                if (!externalUrl) {
                    alert('External News URL is required for news posts.');
                    return false;
                }
            } else {
                // Check if the content is empty for regular blog posts
                if (!content) {
                    alert('Blog content cannot be empty. Please add some content.');
                    return false;
                }
            }
            
            return true;
        }
        
        function toggleNewsFields() {
            const categoryId = document.getElementById('category').value;
            const isNews = categoryId === '3';
            
            // Toggle visibility of fields
            document.getElementById('external_url_group').style.display = isNews ? 'block' : 'none';
            document.getElementById('author_group').style.display = isNews ? 'none' : 'block';
            document.getElementById('content_group').style.display = isNews ? 'none' : 'block';
            
            // Toggle required attributes
            document.getElementById('external_url').required = isNews;
            document.getElementById('author').required = !isNews;
            document.getElementById('content').required = !isNews;
        }
        
        // Initialize form state on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleNewsFields();
        });
    </script>
<style>
.simple-editor {
    width: 100%;
    padding: 12px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    line-height: 1.5;
    border: 1px solid #ddd;
    border-radius: 4px;
    resize: vertical;
}
</style>
</body>
</html>