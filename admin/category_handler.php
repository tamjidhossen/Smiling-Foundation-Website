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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $name = trim($_POST['name']);
    
    // Validate name
    if (empty($name)) {
        header('Location: category_form.php?error=name');
        exit;
    }
    
    // Check for duplicate name
    $check_query = "SELECT id FROM categories WHERE name = ? AND id != ?";
    $stmt = mysqli_prepare($conn, $check_query);
    $id_for_check = $id ?? 0; // Use 0 if creating new category
    mysqli_stmt_bind_param($stmt, 'si', $name, $id_for_check);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        header('Location: category_form.php' . ($id ? "?id=$id&" : '?') . 'error=duplicate');
        exit;
    }
    
    // If editing existing category
    if ($id) {
        $query = "UPDATE categories SET name = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $name, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header('Location: blogs.php?success=category_updated');
            exit;
        } else {
            header('Location: category_form.php?id=' . $id . '&error=update');
            exit;
        }
    } else {
        // Creating new category
        $query = "INSERT INTO categories (name, created_at) VALUES (?, NOW())";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $name);
        
        if (mysqli_stmt_execute($stmt)) {
            header('Location: blogs.php?success=category_created');
            exit;
        } else {
            header('Location: category_form.php?error=create');
            exit;
        }
    }
} else {
    // Not a POST request
    header('Location: blogs.php');
    exit;
}
?>