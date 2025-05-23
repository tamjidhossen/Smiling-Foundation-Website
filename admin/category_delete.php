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

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: blogs.php?error=invalid_request');
    exit;
}

$category_id = $_GET['id'];

// Check if the category has associated posts
$check_query = "SELECT COUNT(*) as post_count FROM blogs WHERE category_id = ?";
$stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($stmt, 'i', $category_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row['post_count'] > 0) {
    // Category has posts, cannot delete
    header('Location: blogs.php?error=category_has_posts');
    exit;
}

// Delete the category
$delete_query = "DELETE FROM categories WHERE id = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, 'i', $category_id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: blogs.php?success=category_deleted');
} else {
    header('Location: blogs.php?error=delete_failed');
}
exit;
?>