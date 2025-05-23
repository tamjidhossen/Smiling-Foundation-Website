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

// Initialize variables
$category = [
    'id' => '',
    'name' => '',
];
$editing = false;

// Check if editing existing category
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = $_GET['id'];
    $editing = true;
    
    // Fetch category details
    $query = "SELECT * FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $category = $row;
    } else {
        // Category not found
        header('Location: blogs.php');
        exit;
    }
}

$pageTitle = $editing ? "Edit Category" : "Add New Category";
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
            <p><?php echo $editing ? 'Update the category details' : 'Create a new blog category'; ?></p>
        </div>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">
                <?php 
                    switch($_GET['error']) {
                        case 'name':
                            echo "Please enter a category name.";
                            break;
                        case 'duplicate':
                            echo "A category with this name already exists.";
                            break;
                        case 'create':
                            echo "Error creating category.";
                            break;
                        case 'update':
                            echo "Error updating category.";
                            break;
                        default:
                            echo "An error occurred.";
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="content-section">
            <form action="category_handler.php" method="POST" class="admin-form">
                <?php if ($editing): ?>
                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Category Name<span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="submit" class="cta-button">
                        <?php echo $editing ? 'Update Category' : 'Add Category'; ?>
                    </button>
                    <a href="blogs.php" class="cta-button secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>