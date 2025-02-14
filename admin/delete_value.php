<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['admin_id']) || !isset($_POST['section']) || !isset($_POST['value_title'])) {
    exit('Unauthorized');
}

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$section = $_POST['section'];
$value_title = $_POST['value_title'];

// Get current values
$query = "SELECT content FROM about_content WHERE section = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $section);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $values = json_decode($row['content'], true);
    
    // Remove the value with matching title
    $values = array_filter($values, function($value) use ($value_title) {
        return $value['title'] !== $value_title;
    });
    
    // Update the database with new values
    $new_content = json_encode(array_values($values));
    $update_query = "UPDATE about_content SET content = ? WHERE section = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ss", $new_content, $section);
    $success = mysqli_stmt_execute($stmt);
    
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false]);
}

mysqli_close($conn);