<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$conn = get_database_connection();

// Function to format LinkedIn URL
function formatLinkedInUrl($url) {
    if (empty($url)) return '';
    
    // If URL doesn't start with http:// or https://, add https://
    if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
        // Check if it starts with www.
        if (strpos($url, 'www.') === 0) {
            return 'https://' . $url;
        } else {
            return 'https://www.' . $url;
        }
    }
    
    return $url;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = mysqli_real_escape_string($conn, $_POST['name']);
                $position = mysqli_real_escape_string($conn, $_POST['position']);
                $bio = mysqli_real_escape_string($conn, $_POST['bio']);
                $linkedin = mysqli_real_escape_string($conn, $_POST['linkedin']);
                $type = mysqli_real_escape_string($conn, $_POST['type']);
                
                // Handle image upload
                $image_name = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $upload_dir = '../assets/img/team/';
                    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $image_name = uniqid() . '.' . $file_extension;
                    $upload_path = $upload_dir . $image_name;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        // Image uploaded successfully
                    } else {
                        $error_message = "Failed to upload image.";
                    }
                }
                
                if (!isset($error_message)) {
                    $sql = "INSERT INTO team_members (name, position, image, bio, linkedin, type) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ssssss", $name, $position, $image_name, $bio, $linkedin, $type);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        // Redirect to prevent form resubmission
                        header("Location: team.php?success=added");
                        exit();
                    } else {
                        $error_message = "Failed to add team member.";
                    }
                    mysqli_stmt_close($stmt);
                }
                break;
                
            case 'edit':
                $id = intval($_POST['id']);
                $name = mysqli_real_escape_string($conn, $_POST['name']);
                $position = mysqli_real_escape_string($conn, $_POST['position']);
                $bio = mysqli_real_escape_string($conn, $_POST['bio']);
                $linkedin = mysqli_real_escape_string($conn, $_POST['linkedin']);
                $type = mysqli_real_escape_string($conn, $_POST['type']);
                
                // Handle image upload
                $image_update = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $upload_dir = '../assets/img/team/';
                    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $image_name = uniqid() . '.' . $file_extension;
                    $upload_path = $upload_dir . $image_name;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        $image_update = ", image = '$image_name'";
                    }
                }
                
                $sql = "UPDATE team_members SET name = ?, position = ?, bio = ?, linkedin = ?, type = ? $image_update WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssssi", $name, $position, $bio, $linkedin, $type, $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to prevent form resubmission
                    header("Location: team.php?success=updated");
                    exit();
                } else {
                    $error_message = "Failed to update team member.";
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'delete':
                $id = intval($_POST['id']);
                $sql = "DELETE FROM team_members WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to prevent form resubmission
                    header("Location: team.php?success=deleted");
                    exit();
                } else {
                    $error_message = "Failed to delete team member.";
                }
                mysqli_stmt_close($stmt);
                break;
        }
    }
}

// Get all team members
$sql = "SELECT * FROM team_members ORDER BY type, created_at DESC";
$result = mysqli_query($conn, $sql);
$team_members = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get team member for editing
$edit_member = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $sql = "SELECT * FROM team_members WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $edit_member = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Set success message based on query parameter
$success_message = null;
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'added':
            $success_message = 'Team member added successfully!';
            break;
        case 'updated':
            $success_message = 'Team member updated successfully!';
            break;
        case 'deleted':
            $success_message = 'Team member deleted successfully!';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Management - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
            position: relative;
        }
        
        .admin-content {
            flex: 1;
            padding: 30px;
            margin-left: 280px; /* Match the exact sidebar width */
            max-width: calc(100% - 280px);
            min-height: 100vh;
            position: relative;
        }
        
        /* Override any conflicting styles from main CSS */
        .admin-content h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 600;
        }
        
        .team-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }
        
        .team-form h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 1.4rem;
            font-weight: 600;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
            font-size: 0.9rem;
        }
        
        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: #fff;
        }
        
        .form-group input:focus, 
        .form-group textarea:focus, 
        .form-group select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }
        
        .form-group input[type="file"] {
            padding: 8px 12px;
            background-color: #f8f9fa;
        }
        
        .current-image-info {
            margin-top: 8px;
            padding: 8px 12px;
            background-color: #e3f2fd;
            border-radius: 6px;
            color: #1976d2;
            font-size: 0.85rem;
            border-left: 3px solid #2196f3;
        }
        
        .btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(52, 152, 219, 0.2);
        }
        
        .btn:hover {
            background: linear-gradient(135deg, #2980b9, #1f639a);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            box-shadow: 0 2px 4px rgba(231, 76, 60, 0.2);
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            box-shadow: 0 2px 4px rgba(243, 156, 18, 0.2);
        }
        
        .btn-warning:hover {
            background: linear-gradient(135deg, #e67e22, #d35400);
            box-shadow: 0 4px 8px rgba(243, 156, 18, 0.3);
        }
        
        .btn-actions {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 25px;
        }
        
        .team-list {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        
        .team-list-header {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            padding: 20px 30px;
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .team-section {
            padding: 20px 30px;
        }
        
        .team-section h3 {
            color: #2c3e50;
            margin: 30px 0 20px 0;
            font-size: 1.2rem;
            font-weight: 600;
            padding: 10px 0;
            border-bottom: 2px solid #ecf0f1;
            position: relative;
        }
        
        .team-section h3:first-child {
            margin-top: 0;
        }
        
        .team-section h3::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: linear-gradient(135deg, #3498db, #2980b9);
        }
        
        .team-item {
            display: flex;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #ecf0f1;
            max-width: 100%;
        }
        
        .team-item:last-child {
            border-bottom: none;
        }
        
        .team-item:hover {
            background-color: #f8f9fa;
        }
        
        .member-image {
            width: 80px !important;
            height: 80px !important;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
            flex-shrink: 0 !important;
            flex-grow: 0 !important;
            border: 3px solid #ecf0f1;
            position: relative;
            background: #f8f9fa;
            display: block !important;
        }
        
        .member-image img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
            object-position: center;
            display: block;
        }
        
        .member-placeholder {
            background: linear-gradient(135deg, #bdc3c7, #95a5a6);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }
        
        .member-info {
            flex: 1;
            padding-right: 20px;
        }
        
        .member-name {
            font-weight: 600;
            margin-bottom: 6px;
            color: #2c3e50;
            font-size: 1.1rem;
        }
        
        .member-position {
            color: #7f8c8d;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .member-bio {
            color: #6c757d;
            font-size: 0.85rem;
            line-height: 1.4;
            margin-bottom: 8px;
            max-width: 400px;
        }
        
        .member-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .member-type.board {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }
        
        .member-type.core {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }
        
        .member-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }
        
        .member-actions .btn {
            padding: 8px 16px;
            font-size: 0.8rem;
            min-width: auto;
        }
        
        .member-actions .btn i {
            margin-right: 5px;
        }
        
        .alert {
            padding: 16px 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            border-left: 4px solid;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }
        
        .no-members {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
            font-style: italic;
        }
        
        .linkedin-link {
            color: #0077b5;
            margin-top: 5px;
            font-size: 0.85rem;
        }
        
        .linkedin-link i {
            margin-right: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            background-color: #0077b5;
            color: white;
            border-radius: 50%;
            font-size: 12px;
        }
        
        /* Ensure sidebar doesn't interfere */
        .admin-nav {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 280px !important;
            height: 100vh;
            z-index: 1000;
            background: white;
            border-right: 1px solid #e2e8f0;
            overflow-y: auto;
        }
        
        /* Responsive Design */
        @media (max-width: 1200px) {
            .admin-content {
                margin-left: 280px;
                padding: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .admin-nav {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-nav.open {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
                max-width: 100%;
                padding: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .team-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .member-image {
                align-self: center;
            }
            
            .member-actions {
                align-self: stretch;
                justify-content: center;
            }
            
            .btn-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <h1>Team Management</h1>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <div class="team-form">
                <h2><?php echo $edit_member ? 'Edit Team Member' : 'Add New Team Member'; ?></h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="<?php echo $edit_member ? 'edit' : 'add'; ?>">
                    <?php if ($edit_member): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_member['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" required 
                                   value="<?php echo $edit_member ? htmlspecialchars($edit_member['name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" id="position" name="position" required 
                                   value="<?php echo $edit_member ? htmlspecialchars($edit_member['position']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="type">Member Type</label>
                        <select id="type" name="type" required>
                            <option value="board" <?php echo ($edit_member && $edit_member['type'] === 'board') ? 'selected' : ''; ?>>Board Member</option>
                            <option value="core" <?php echo ($edit_member && $edit_member['type'] === 'core') ? 'selected' : ''; ?>>Core Team</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="bio">Bio (Optional)</label>
                        <textarea id="bio" name="bio"><?php echo $edit_member ? htmlspecialchars($edit_member['bio']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="linkedin">LinkedIn URL (Optional)</label>
                        <input type="text" id="linkedin" name="linkedin" 
                               placeholder="e.g., https://linkedin.com/in/yourprofile or linkedin.com/in/yourprofile"
                               value="<?php echo $edit_member ? htmlspecialchars($edit_member['linkedin']) : ''; ?>">
                        <small style="color: #6c757d; font-size: 0.8rem; margin-top: 5px; display: block;">
                            You can enter the full URL (https://linkedin.com/in/yourprofile) or just the profile part (linkedin.com/in/yourprofile)
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Profile Image</label>
                        <input type="file" id="image" name="image" accept="image/*" <?php echo !$edit_member ? 'required' : ''; ?>>
                        <?php if ($edit_member && $edit_member['image']): ?>
                            <p class="current-image-info">Current image: <?php echo $edit_member['image']; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn">
                        <?php echo $edit_member ? 'Update Member' : 'Add Member'; ?>
                    </button>
                    
                    <?php if ($edit_member): ?>
                        <a href="team.php" class="btn btn-warning" style="margin-left: 10px;">Cancel Edit</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="team-list">
                <h2 class="team-list-header">Current Team Members</h2>
                
                <?php if (empty($team_members)): ?>
                    <p class="no-members">No team members found. Add the first team member above.</p>
                <?php else: ?>
                    <div class="team-section">
                        <h3>Board Members</h3>
                        <?php foreach ($team_members as $member): ?>
                            <?php if ($member['type'] === 'board'): ?>
                                <div class="team-item">
                                    <div class="member-image">
                                        <?php if ($member['image']): ?>
                                            <img src="../assets/img/team/<?php echo htmlspecialchars($member['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($member['name']); ?>">
                                        <?php else: ?>
                                            <div class="member-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="member-info">
                                        <div class="member-name"><?php echo htmlspecialchars($member['name']); ?></div>
                                        <div class="member-position"><?php echo htmlspecialchars($member['position']); ?></div>
                                        <?php if (!empty($member['bio'])): ?>
                                            <div class="member-bio"><?php echo htmlspecialchars($member['bio']); ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($member['linkedin'])): ?>
                                            <div class="linkedin-link">
                                                <i class="fab fa-linkedin"></i>
                                                <a href="<?php echo htmlspecialchars(formatLinkedInUrl($member['linkedin'])); ?>" target="_blank">LinkedIn Profile</a>
                                            </div>
                                        <?php endif; ?>
                                        <span class="member-type board">Board Member</span>
                                    </div>
                                    <div class="member-actions">
                                        <a href="team.php?edit=<?php echo $member['id']; ?>" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this member?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        
                        <h3 style="margin-top: 30px;">Core Team</h3>
                        <?php foreach ($team_members as $member): ?>
                            <?php if ($member['type'] === 'core'): ?>
                                <div class="team-item">
                                    <div class="member-image">
                                        <?php if ($member['image']): ?>
                                            <img src="../assets/img/team/<?php echo htmlspecialchars($member['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($member['name']); ?>">
                                        <?php else: ?>
                                            <div class="member-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="member-info">
                                        <div class="member-name"><?php echo htmlspecialchars($member['name']); ?></div>
                                        <div class="member-position"><?php echo htmlspecialchars($member['position']); ?></div>
                                        <?php if (!empty($member['bio'])): ?>
                                            <div class="member-bio"><?php echo htmlspecialchars($member['bio']); ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($member['linkedin'])): ?>
                                            <div class="linkedin-link">
                                                <i class="fab fa-linkedin"></i>
                                                <a href="<?php echo htmlspecialchars(formatLinkedInUrl($member['linkedin'])); ?>" target="_blank">LinkedIn Profile</a>
                                            </div>
                                        <?php endif; ?>
                                        <span class="member-type core">Core Team</span>
                                    </div>
                                    <div class="member-actions">
                                        <a href="team.php?edit=<?php echo $member['id']; ?>" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this member?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>