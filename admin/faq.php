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
        
        if ($action === 'add_faq') {
            $category_id = (int)$_POST['category_id'];
            $question = mysqli_real_escape_string($conn, $_POST['question']);
            $answer = mysqli_real_escape_string($conn, $_POST['answer']);
            $display_order = (int)$_POST['display_order'];
            
            $query = "INSERT INTO faqs (category_id, question, answer, display_order) VALUES ($category_id, '$question', '$answer', $display_order)";
            if (mysqli_query($conn, $query)) {
                $_SESSION['message'] = 'FAQ added successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error adding FAQ: ' . mysqli_error($conn);
                $_SESSION['message_type'] = 'error';
            }
            
        } elseif ($action === 'delete_faq') {
            $id = (int)$_POST['id'];
            $query = "UPDATE faqs SET is_deleted = 1 WHERE id = $id";
            if (mysqli_query($conn, $query)) {
                $_SESSION['message'] = 'FAQ deleted successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error deleting FAQ: ' . mysqli_error($conn);
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

// Fetch FAQ categories
$categories_query = "SELECT * FROM faq_categories WHERE is_deleted = 0 ORDER BY display_order, name";
$categories_result = mysqli_query($conn, $categories_query);

// Fetch FAQs with category names
$faqs_query = "SELECT f.*, c.name as category_name FROM faqs f 
               JOIN faq_categories c ON f.category_id = c.id 
               WHERE f.is_deleted = 0 AND c.is_deleted = 0 
               ORDER BY c.display_order, f.display_order";
$faqs_result = mysqli_query($conn, $faqs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Management - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-dashboard">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="page-header">
                <h1>FAQ Management</h1>
                <button class="cta-button" onclick="toggleForm()">
                    <i class="fas fa-plus"></i> Add New FAQ
                </button>
            </div>
            
            <?php if ($message): ?>
                <div class="alert success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="faq-management">
                <!-- Add New FAQ -->
                <div id="addForm" class="form-section" style="display: none;">
                    <h2><i class="fas fa-question-circle"></i> Add New FAQ</h2>
                    <form method="POST" class="admin-form">
                        <input type="hidden" name="action" value="add_faq">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="faq_category"><i class="fas fa-folder"></i> Category:</label>
                                <select id="faq_category" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php 
                                    mysqli_data_seek($categories_result, 0);
                                    while ($category = mysqli_fetch_assoc($categories_result)): 
                                    ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="faq_order"><i class="fas fa-sort-numeric-up"></i> Display Order:</label>
                                <input type="number" id="faq_order" name="display_order" value="0" min="0" placeholder="0">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="question"><i class="fas fa-question"></i> Question:</label>
                            <textarea id="question" name="question" rows="2" required placeholder="Enter the FAQ question"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="answer"><i class="fas fa-reply"></i> Answer:</label>
                            <textarea id="answer" name="answer" rows="4" required placeholder="Enter the detailed answer"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add FAQ
                        </button>
                    </form>
                </div>
                
                <!-- FAQs List -->
                <div class="table-section">
                    <h2><i class="fas fa-list"></i> All FAQs</h2>
                    <?php if (mysqli_num_rows($faqs_result) > 0): ?>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($faq = mysqli_fetch_assoc($faqs_result)): ?>
                                    <tr>
                                        <td>
                                            <span class="status-badge status-active">
                                                <?php echo htmlspecialchars($faq['category_name']); ?>
                                            </span>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars(substr($faq['question'], 0, 50)) . (strlen($faq['question']) > 50 ? '...' : ''); ?></strong></td>
                                        <td><?php echo htmlspecialchars(substr($faq['answer'], 0, 80)) . (strlen($faq['answer']) > 80 ? '...' : ''); ?></td>
                                        <td><?php echo $faq['display_order']; ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                                                    <input type="hidden" name="action" value="delete_faq">
                                                    <input type="hidden" name="id" value="<?php echo $faq['id']; ?>">
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
                            <i class="fas fa-question-circle"></i>
                            <h3>No FAQs Found</h3>
                            <p>Add your first FAQ above.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
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
