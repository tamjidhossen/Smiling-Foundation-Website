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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $category = $_POST['category'];
            $type = $_POST['type'];
            $image_name = null;
            $video_url = null;

            if ($type === 'image') {
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $upload_dir = '../assets/img/gallery/';
                    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $image_name = uniqid() . '.' . $file_extension;
                    $upload_path = $upload_dir . $image_name;
                    
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        $_SESSION['message'] = 'Error uploading image.';
                        $_SESSION['message_type'] = 'error';
                    }
                } else {
                    $_SESSION['message'] = 'Please select an image file for type "image".';
                    $_SESSION['message_type'] = 'error';
                }
            } elseif ($type === 'video') {
                if (!empty($_POST['video_url'])) {
                    $video_url = $_POST['video_url'];
                    // Basic validation for youtube url
                    if (preg_match('/(youtube\.com|youtu\.be)\/(watch\?v=|embed\/|v\/|)([\w\-]{11})/', $video_url)) {
                         // The URL is valid
                    } else {
                        $_SESSION['message'] = 'Invalid YouTube URL provided.';
                        $_SESSION['message_type'] = 'error';
                        $video_url = null; // unset url if invalid
                    }
                } else {
                    $_SESSION['message'] = 'Please provide a video URL for type "video".';
                    $_SESSION['message_type'] = 'error';
                }
            }

            // Proceed only if there are no errors so far
            if (!isset($_SESSION['message'])) {
                $query = "INSERT INTO gallery (title, description, category, type, image, video_url) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ssssss", $title, $description, $category, $type, $image_name, $video_url);
                
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['message'] = 'Gallery item added successfully!';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Error adding gallery item: ' . mysqli_error($conn);
                    $_SESSION['message_type'] = 'error';
                }
                mysqli_stmt_close($stmt);
            }
            
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            
            // First, get the image filename to delete it from the server
            $query_select = "SELECT image FROM gallery WHERE id = ?";
            $stmt_select = mysqli_prepare($conn, $query_select);
            mysqli_stmt_bind_param($stmt_select, "i", $id);
            mysqli_stmt_execute($stmt_select);
            $result = mysqli_stmt_get_result($stmt_select);
            if ($item = mysqli_fetch_assoc($result)) {
                if (!empty($item['image'])) {
                    $image_path = '../assets/img/gallery/' . $item['image'];
                    if (file_exists($image_path)) {
                        unlink($image_path); // Delete the file
                    }
                }
            }
            mysqli_stmt_close($stmt_select);

            // Now, mark the item as deleted in the database
            $query = "UPDATE gallery SET is_deleted = 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = 'Gallery item deleted successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error deleting gallery item: ' . mysqli_error($conn);
                $_SESSION['message_type'] = 'error';
            }
            mysqli_stmt_close($stmt);
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
                            <label for="type"><i class="fas fa-photo-video"></i> Media Type:</label>
                            <select id="type" name="type" required>
                                <option value="image">Image</option>
                                <option value="video">YouTube Video</option>
                            </select>
                        </div>

                        <div id="image-input-group" class="form-group">
                            <label for="image"><i class="fas fa-image"></i> Image:</label>
                            <input type="file" id="image" name="image" accept="image/*">
                            <small style="color: #6b7280; font-size: 0.75rem; margin-top: 0.25rem;">Supported formats: JPG, PNG, GIF (Max size: 5MB)</small>
                        </div>

                        <div id="video-input-group" class="form-group" style="display: none;">
                            <label for="video_url"><i class="fab fa-youtube"></i> YouTube Video URL:</label>
                            <input type="text" id="video_url" name="video_url" placeholder="e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ">
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
                                    <th>Preview</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = mysqli_fetch_assoc($gallery_result)): ?>
                                    <tr>
                                        <td>
                                            <?php if ($item['type'] === 'image' && !empty($item['image'])): ?>
                                                <img src="../assets/img/gallery/<?php echo htmlspecialchars($item['image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                                     class="gallery-image"
                                                     onerror="this.src='../assets/img/gallery/default.jpg'">
                                            <?php elseif ($item['type'] === 'video'): ?>
                                                <div class="video-placeholder"><i class="fab fa-youtube fa-2x" style="color: red;"></i></div>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                                        <td><span class="status-badge status-<?php echo $item['type'] === 'image' ? 'active' : 'paused'; ?>"><?php echo htmlspecialchars(ucfirst($item['type'])); ?></span></td>
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
            </div>
        </main>
    </div>
    
    <script>
        function toggleForm() {
            const form = document.getElementById('addForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }

        document.getElementById('type').addEventListener('change', function() {
            const imageInput = document.getElementById('image-input-group');
            const videoInput = document.getElementById('video-input-group');
            const imageField = document.getElementById('image');
            const videoField = document.getElementById('video_url');

            if (this.value === 'video') {
                imageInput.style.display = 'none';
                videoInput.style.display = 'block';
                imageField.required = false;
                videoField.required = true;
            } else {
                imageInput.style.display = 'block';
                videoInput.style.display = 'none';
                imageField.required = true;
                videoField.required = false;
            }
        });

        // Trigger change event on page load to set initial state
        document.getElementById('type').dispatchEvent(new Event('change'));
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
