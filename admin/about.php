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
    $content = $_POST['content'] ?? '';

    // For sections with repeater fields (values, goals)
    if ($section === 'values' || $section === 'goals') {
        $items = [];
        $item_titles = $_POST[$section . '_titles'];
        $item_descriptions = $_POST[$section . '_descriptions'];
        for ($i = 0; $i < count($item_titles); $i++) {
            if (!empty($item_titles[$i]) && !empty($item_descriptions[$i])) {
                $items[] = [
                    'title' => $item_titles[$i],
                    'description' => $item_descriptions[$i]
                ];
            }
        }
        $content = json_encode($items);
    }

    $query = "INSERT INTO about_content (section, title, content) VALUES (?, ?, ?) 
              ON DUPLICATE KEY UPDATE title = VALUES(title), content = VALUES(content)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $section, $title, $content);
    mysqli_stmt_execute($stmt);

    header('Location: about.php?success=1&section=' . $section);
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
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .value-item, .goal-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .delete-value, .delete-goal {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
            float: right;
        }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <h1>Manage About Page Content</h1>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert success">Content updated successfully!</div>
            <?php endif; ?>

            <!-- Who We Are Section Form -->
            <div class="content-section">
                <h2>Who We Are</h2>
                <form method="POST" class="admin-form">
                    <input type="hidden" name="section" value="who_we_are">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo isset($about_content['who_we_are']) ? htmlspecialchars($about_content['who_we_are']['title']) : 'Who We Are'; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" required><?php echo isset($about_content['who_we_are']) ? htmlspecialchars($about_content['who_we_are']['content']) : ''; ?></textarea>
                    </div>
                    <button type="submit" class="cta-button">Save</button>
                </form>
            </div>

            <!-- Mission Section Form -->
            <div class="content-section">
                <h2>Mission</h2>
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
                <h2>Vision</h2>
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
                <h2>Values</h2>
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
                                    <input type="text" name="values_titles[]" value="<?php echo htmlspecialchars($value['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Value Description</label>
                                    <textarea name="values_descriptions[]" required><?php echo htmlspecialchars($value['description']); ?></textarea>
                                </div>
                                <button type="button" class="delete-value" onclick="this.parentElement.remove()">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" onclick="addRepeaterItem('values')" class="cta-button secondary">Add Another Value</button>
                    <button type="submit" class="cta-button">Save Values</button>
                </form>
            </div>

            <!-- Goals & Objectives Section Form -->
            <div class="content-section">
                <h2>Goals & Objectives</h2>
                <form method="POST" class="admin-form" id="goalsForm">
                    <input type="hidden" name="section" value="goals">
                    <div class="form-group">
                        <label>Section Title</label>
                        <input type="text" name="title" value="<?php echo isset($about_content['goals']) ? htmlspecialchars($about_content['goals']['title']) : 'Our Goals & Objectives'; ?>" required>
                    </div>
                    
                    <div id="goals-container">
                        <?php
                        $goals = isset($about_content['goals']) ? json_decode($about_content['goals']['content'], true) : [];
                        if (empty($goals)) {
                            $goals = [['title' => '', 'description' => '']];
                        }
                        foreach ($goals as $goal):
                        ?>
                            <div class="goal-item">
                                <div class="form-group">
                                    <label>Goal Title</label>
                                    <input type="text" name="goals_titles[]" value="<?php echo htmlspecialchars($goal['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Goal Description</label>
                                    <textarea name="goals_descriptions[]" required><?php echo htmlspecialchars($goal['description']); ?></textarea>
                                </div>
                                <button type="button" class="delete-goal" onclick="this.parentElement.remove()">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" onclick="addRepeaterItem('goals')" class="cta-button secondary">Add Another Goal</button>
                    <button type="submit" class="cta-button">Save Goals</button>
                </form>
            </div>

            <!-- Activities Section Form -->
            <div class="content-section">
                <h2>Activities</h2>
                <form method="POST" class="admin-form">
                    <input type="hidden" name="section" value="activities">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo isset($about_content['activities']) ? htmlspecialchars($about_content['activities']['title']) : 'Our Activities'; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" required><?php echo isset($about_content['activities']) ? htmlspecialchars($about_content['activities']['content']) : ''; ?></textarea>
                    </div>
                    <button type="submit" class="cta-button">Save Activities</button>
                </form>
            </div>

        </main>
    </div>

    <script>
    function addRepeaterItem(type) {
        const container = document.getElementById(type + '-container');
        const newItem = document.createElement('div');
        newItem.className = type.slice(0, -1) + '-item'; // 'value-item' or 'goal-item'
        
        newItem.innerHTML = `
            <div class="form-group">
                <label>${type.charAt(0).toUpperCase() + type.slice(1, -1)} Title</label>
                <input type="text" name="${type}_titles[]" required>
            </div>
            <div class="form-group">
                <label>${type.charAt(0).toUpperCase() + type.slice(1, -1)} Description</label>
                <textarea name="${type}_descriptions[]" required></textarea>
            </div>
            <button type="button" class="delete-${type.slice(0, -1)}" onclick="this.parentElement.remove()">
                <i class="fas fa-trash"></i> Delete
            </button>
        `;
        container.appendChild(newItem);
    }
    </script>
</body>
</html>