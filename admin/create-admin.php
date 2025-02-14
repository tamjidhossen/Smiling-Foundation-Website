<?php
require_once '../config/database.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$email = "admin@example.com";

$query = "INSERT INTO admins (username, password, email) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sss", $username, $password, $email);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "Admin account created successfully";
} else {
    echo "Error creating admin account";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);