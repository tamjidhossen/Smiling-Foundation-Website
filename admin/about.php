<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . ADMIN_URL . '/login.php'); 
    exit;
}

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section = $_POST['section'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // For values section, format the content as JSON
    if ($section === 'values') {
        $values = [];
        $titles = $_POST['value_titles'];
        $descriptions = $_POST['value_descriptions'];
        for ($i = 0; $i < count($titles); $i++) {
            if (!empty($titles[$i]) && !empty($descriptions[$i])) {
                $values[] = [
                    'title' => $titles[$i],
                    'description' => $descriptions[$i]
                ];
            }
        }
        $content = json_encode($values);
    }

    $query = "INSERT INTO about_content (section, title, content) VALUES (?, ?, ?) 
              ON DUPLICATE KEY UPDATE title = VALUES(title), content = VALUES(content)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $section, $title, $content);
    mysqli_stmt_execute($stmt);
    
    header('Location: about.php?success=1');
    exit;
}

// Get existing content
$query = "SELECT * FROM about_content WHERE is_deleted = 0";
$result = mysqli_query($conn, $query);
$about_content = array();
while ($row = mysqli_fetch_assoc($result)) {
    $about_content[$row['section']] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage About Content</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-dashboard">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <h1>Manage About Content</h1>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert success">Content updated successfully!</div>
            <?php endif; ?>

            <!-- Mission Section Form -->
            <div class="content-section">
                <h2>Mission Section</h2>
                <form method="POST" class="admin-form">
                    <input type="hidden" name="section" value="mission">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo isset($about_content['mission']) ? htmlspecialchars($about_content['mission']['title']) : 'Our Mission'; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" required><?php echo isset($about_content['mission']) ? htmlspecialchars($about_content['mission']['content']) : ''; ?></textarea>
                    </div>
                    <button type="submit" class="cta-button">Save Mission</button>
                </form>
            </div>

            <!-- Vision Section Form -->
            <div class="content-section">
                <h2>Vision Section</h2>
                <form method="POST" class="admin-form">
                    <input type="hidden" name="section" value="vision">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo isset($about_content['vision']) ? htmlspecialchars($about_content['vision']['title']) : 'Our Vision'; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" required><?php echo isset($about_content['vision']) ? htmlspecialchars($about_content['vision']['content']) : ''; ?></textarea>
                    </div>
                    <button type="submit" class="cta-button">Save Vision</button>
                </form>
            </div>

            <!-- Values Section Form -->
            <div class="content-section">
                <h2>Values Section</h2>
                <form method="POST" class="admin-form" id="valuesForm">
                    <input type="hidden" name="section" value="values">
                    <div class="form-group">
                        <label>Section Title</label>
                        <input type="text" name="title" value="<?php echo isset($about_content['values']) ? htmlspecialchars($about_content['values']['title']) : 'Our Values'; ?>" required>
                    </div>
                    
                    <div id="values-container">
                        <?php
                        $values = isset($about_content['values']) ? json_decode($about_content['values']['content'], true) : [];
                        if (empty($values)) {
                            $values = [['title' => '', 'description' => '']];
                        }
                        foreach ($values as $value):
                        ?>
                            <div class="value-item">
                                <div class="form-group">
                                    <label>Value Title</label>
                                    <input type="text" name="value_titles[]" value="<?php echo htmlspecialchars($value['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Value Description</label>
                                    <textarea name="value_descriptions[]" required><?php echo htmlspecialchars($value['description']); ?></textarea>
                                </div>
                                <button type="button" class="delete-value" onclick="deleteValue(this)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" onclick="addValue()" class="cta-button secondary">Add Another Value</button>
                    <button type="submit" class="cta-button">Save Values</button>
                </form>
            </div>
        </main>
    </div>

    <script>
    function addValue() {
        const container = document.getElementById('values-container');
        const newValue = document.createElement('div');
        newValue.className = 'value-item';
        newValue.innerHTML = `
            <div class="form-group">
                <label>Value Title</label>
                <input type="text" name="value_titles[]" required>
            </div>
            <div class="form-group">
                <label>Value Description</label>
                <textarea name="value_descriptions[]" required></textarea>
            </div>
            <button type="button" class="delete-value" onclick="deleteValue(this)">
                <i class="fas fa-trash"></i> Delete
            </button>
        `;
        container.appendChild(newValue);
    }
    
    async function deleteValue(button) {
        const valueItem = button.parentElement;
        const valueTitle = valueItem.querySelector('input[name="value_titles[]"]').value;
        
        if (document.querySelectorAll('.value-item').length <= 1) {
            alert('You must have at least one value.');
            return;
        }
    
        if (confirm('Are you sure you want to delete this value?')) {
            try {
                const response = await fetch('delete_value.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `section=values&value_title=${encodeURIComponent(valueTitle)}`
                });
    
                const data = await response.json();
                
                if (data.success) {
                    valueItem.remove();
                } else {
                    alert('Failed to delete value. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while deleting the value.');
            }
        }
    }
    
    // Add this to prevent accidental form submission
    document.getElementById('valuesForm').addEventListener('submit', function(e) {
        const values = document.querySelectorAll('.value-item');
        if (values.length === 0) {
            e.preventDefault();
            alert('You must have at least one value.');
        }
    });
    </script>
</body>
</html>