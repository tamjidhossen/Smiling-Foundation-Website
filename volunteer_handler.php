<?php
require_once 'config/config.php';
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$facebook = trim($_POST['facebook'] ?? '');
$nid = trim($_POST['nid'] ?? '');
$occupation = trim($_POST['occupation'] ?? '');
$volunteer_type = trim($_POST['volunteer_type'] ?? '');
$special_skills = trim($_POST['special_skills'] ?? '');
$present_division = trim($_POST['present_division'] ?? '');
$present_address = trim($_POST['present_address'] ?? '');

// Validate required fields
$errors = [];

if (empty($name)) {
    $errors[] = 'Full name is required';
}

if (empty($phone)) {
    $errors[] = 'Phone number is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($nid)) {
    $errors[] = 'NID number is required';
}

if (empty($occupation)) {
    $errors[] = 'Occupation is required';
}

if (empty($volunteer_type)) {
    $errors[] = 'Volunteer type is required';
}

if (empty($present_division)) {
    $errors[] = 'Division is required';
}

if (empty($present_address)) {
    $errors[] = 'Present address is required';
}

// Return validation errors if any
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Connect to database
$conn = get_database_connection();

// Check if email or NID already exists
$check_sql = "SELECT id FROM volunteers WHERE email = ? OR nid = ?";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "ss", $email, $nid);
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['success' => false, 'message' => 'Email or NID number already registered']);
    exit;
}

// Insert volunteer data
$sql = "INSERT INTO volunteers (name, phone, email, facebook, nid, occupation, volunteer_type, special_skills, present_division, present_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    exit;
}

mysqli_stmt_bind_param($stmt, "ssssssssss", $name, $phone, $email, $facebook, $nid, $occupation, $volunteer_type, $special_skills, $present_division, $present_address);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Volunteer application submitted successfully! We will review your application and contact you soon.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit application. Please try again.']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
