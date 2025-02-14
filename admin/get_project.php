<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$id = $_GET['id'] ?? 0;
$query = "SELECT * FROM projects WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$project = mysqli_fetch_assoc($result);

mysqli_close($conn);

echo json_encode($project);