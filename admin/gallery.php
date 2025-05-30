<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . ADMIN_URL . '/login.php');
    exit();
}

$conn = get_database_connection();
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add') {
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            
            // Handle image upload
            $image_name = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $upload_dir = '../assets/img/gallery/';
                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image_name = uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $image_name;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $query = "INSERT INTO gallery (title, description, image, category) VALUES ('$title', '$description', '$image_name', '$category')";
                    if (mysqli_query($conn, $query)) {
                        $_SESSION['message'] = 'Gallery item added successfully!';
                        $_SESSION['message_type'] = 'success';
                    } else {
                        $_SESSION['message'] = 'Error adding gallery item: ' . mysqli_error($conn);
                        $_SESSION['message_type'] = 'error';
                    }
                } else {
                    $_SESSION['message'] = 'Error uploading image.';
                    $_SESSION['message_type'] = 'error';
                }
            } else {
                $_SESSION['message'] = 'Please select an image file.';
                $_SESSION['message_type'] = 'error';
            }
            
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            $query = "UPDATE gallery SET is_deleted = 1 WHERE id = $id";
            if (mysqli_query($conn, $query)) {
                $_SESSION['message'] = 'Gallery item deleted successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error deleting gallery item: ' . mysqli_error($conn);
                $_SESSION['message_type'] = 'error';
            }
        }
        
        // Redirect to prevent resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Get messages from session
$message = '';
$error = '';
if (isset($_SESSION['message'])) {
    if ($_SESSION['message_type'] === 'success') {
        $message = $_SESSION['message'];
    } else {
        $error = $_SESSION['message'];
    }
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Fetch gallery items
$gallery_query = "SELECT * FROM gallery WHERE is_deleted = 0 ORDER BY created_at DESC";
$gallery_result = mysqli_query($conn, $gallery_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Management - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-dashboard">
        <?php include 'includes/sidebar.php'; ?>
          <main class="admin-main">
            <div class="page-header">
                <h1>Gallery Management</h1>
                <button class="cta-button" onclick="toggleForm()">
                    <i class="fas fa-plus"></i> Add New Gallery Item
                </button>
            </div>
              <?php if ($message): ?>
                <div class="alert success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>            
            <div class="gallery-management">
                <!-- Add New Gallery Item Form -->
                <div id="addForm" class="form-section" style="display: none;">
                    <h2><i class="fas fa-plus-circle"></i> Add New Gallery Item</h2>
                    <form method="POST" enctype="multipart/form-data" class="admin-form">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="title"><i class="fas fa-heading"></i> Title:</label>
                                <input type="text" id="title" name="title" required placeholder="Enter gallery item title">
                            </div>
                            
                            <div class="form-group">
                                <label for="category"><i class="fas fa-tags"></i> Category:</label>
                                <select id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="general">General</option>
                                    <option value="events">Events</option>
                                    <option value="projects">Projects</option>
                                    <option value="activities">Activities</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description"><i class="fas fa-align-left"></i> Description:</label>
                            <textarea id="description" name="description" rows="3" placeholder="Enter a brief description"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="image"><i class="fas fa-image"></i> Image:</label>
                            <input type="file" id="image" name="image" accept="image/*" required>
                            <small style="color: #6b7280; font-size: 0.75rem; margin-top: 0.25rem;">Supported formats: JPG, PNG, GIF (Max size: 5MB)</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Gallery Item
                        </button>
                    </form>
                </div>
                  <!-- Gallery Items List -->
                <div class="table-section">
                    <h2><i class="fas fa-images"></i> Gallery Items</h2>
                    <?php if (mysqli_num_rows($gallery_result) > 0): ?>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = mysqli_fetch_assoc($gallery_result)): ?>
                                    <tr>
                                        <td>
                                            <img src="../assets/img/gallery/<?php echo htmlspecialchars($item['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                                 class="gallery-image"
                                                 onerror="this.src='../assets/img/gallery/default.jpg'">
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                                        <td><?php echo htmlspecialchars(substr($item['description'], 0, 50)) . (strlen($item['description']) > 50 ? '...' : ''); ?></td>
                                        <td>
                                            <span class="status-badge status-active">
                                                <?php echo htmlspecialchars(ucfirst($item['category'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($item['created_at'])); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-images"></i>
                            <h3>No Gallery Items Found</h3>
                            <p>Start by adding your first gallery item above.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>        </main>
    </div>
    
    <script>
        function toggleForm() {
            const form = document.getElementById('addForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
