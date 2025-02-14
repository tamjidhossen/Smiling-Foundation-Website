<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
    case 'edit':
        $title = $_POST['title'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        $project_id = $_POST['project_id'] ?? null;
        
        // Handle image upload
        $image_name = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $image_name = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], "../assets/img/projects/$image_name");
            }
        }
        
        if ($action === 'add') {
            $query = "INSERT INTO projects (title, description, image, status) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $title, $description, $image_name, $status);
        } else {
            if ($image_name) {
                $query = "UPDATE projects SET title = ?, description = ?, image = ?, status = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ssssi", $title, $description, $image_name, $status, $project_id);
            } else {
                $query = "UPDATE projects SET title = ?, description = ?, status = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $status, $project_id);
            }
        }
        
        mysqli_stmt_execute($stmt);
        header('Location: projects.php?success=' . ($action === 'add' ? 'add' : 'edit'));
        break;
        
    case 'delete':
        $project_id = $_POST['project_id'];
    
        // First get the image name to delete the file
        $query = "SELECT image FROM projects WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $project_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $project = mysqli_fetch_assoc($result);
        
        // Delete the image file if it exists
        if ($project && $project['image']) {
            $image_path = "../assets/img/projects/" . $project['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        // Delete from database
        $query = "DELETE FROM projects WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $project_id);
        $success = mysqli_stmt_execute($stmt);
        
        echo json_encode(['success' => $success]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

mysqli_close($conn);