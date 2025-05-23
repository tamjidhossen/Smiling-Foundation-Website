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
    3 => 'Updates'
];

// Handle delete blog post
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $blog_id = $_GET['delete'];
    
    // Delete the blog post
    $delete_query = "DELETE FROM blogs WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, 'i', $blog_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Blog post deleted successfully!";
    } else {
        $error_message = "Error deleting blog post: " . mysqli_error($conn);
    }
}

// Success message for update
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'updated') {
        $success_message = "Blog post updated successfully!";
    } elseif ($_GET['success'] == 'created') {
        $success_message = "Blog post created successfully!";
    } elseif ($_GET['success'] == 'category_updated') {
        $success_message = "Category updated successfully!";
    } elseif ($_GET['success'] == 'category_created') {
        $success_message = "Category created successfully!";
    } elseif ($_GET['success'] == 'category_deleted') {
        $success_message = "Category deleted successfully!";
    }
}

// Fetch all blog posts (with no caching)
$query = "SELECT * FROM blogs ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$blogs = mysqli_fetch_all($result, MYSQLI_ASSOC);

$pageTitle = "Blog Management";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Management - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="admin-dashboard">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="admin-main">
        <div class="dashboard-header">
            <h1>Blog Management</h1>
            <p>Create, edit, and manage blog posts</p>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="alert success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <div class="content-section">
            <div class="page-header">
                <h2>All Blog Posts</h2>
                <a href="blog_form.php" class="cta-button">Add New Post</a>
            </div>
            
            <div class="filter-controls" style="margin-bottom: 20px;">
                <select id="categoryFilter" onchange="filterByCategory(this.value)">
                    <option value="">All Categories</option>
                    <?php foreach ($fixed_categories as $id => $name): ?>
                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="projects-table">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($blogs)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No blog posts found. <a href="blog_form.php">Create your first blog post</a></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($blogs as $blog): ?>
                                <tr class="blog-item" data-category="<?php echo $blog['category_id']; ?>">
                                    <td>
                                        <?php if (!empty($blog['image'])): ?>
                                            <img src="../assets/img/blog/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="table-image">
                                        <?php else: ?>
                                            <div class="no-image">No Image</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($blog['title']); ?></td>
                                    <td><?php echo htmlspecialchars($fixed_categories[$blog['category_id']] ?? 'Unknown'); ?></td>
                                    <td><?php echo htmlspecialchars($blog['author']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></td>
                                    <td>
                                        <a href="blog_form.php?id=<?php echo $blog['id']; ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
                                        <a href="blog_preview.php?id=<?php echo $blog['id']; ?>" class="action-btn" title="Preview"><i class="fas fa-eye"></i></a>
                                        <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $blog['id']; ?>)" class="action-btn delete"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this blog post? This action cannot be undone.')) {
                window.location.href = 'blogs.php?delete=' + id;
            }
        }
        
        function filterByCategory(categoryId) {
            const rows = document.querySelectorAll('.blog-item');
            rows.forEach(row => {
                if (!categoryId || row.dataset.category === categoryId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>