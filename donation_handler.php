<?php
require_once 'config/config.php';
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Exchange rate (USD to BDT) - In a real application, this should be fetched from an API
define('USD_TO_BDT_RATE', 119.50);

// Get form data
$currency = trim($_POST['currency'] ?? 'usd');
$amount = floatval($_POST['amount'] ?? 0);
$purpose = trim($_POST['purpose'] ?? '');
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');
$is_anonymous = isset($_POST['anonymous']) ? 1 : 0;

// Validate required fields
$errors = [];

if ($amount <= 0) {
    $errors[] = 'Please enter a valid donation amount';
}

if (empty($purpose)) {
    $errors[] = 'Please select a donation purpose';
}

if (empty($name)) {
    $errors[] = 'Full name is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

// Validate phone number if provided (optional field)
if (!empty($phone) && !preg_match('/^01[3-9]\d{8}$/', $phone)) {
    $errors[] = 'Please enter a valid Bangladeshi mobile number (11 digits starting with 01)';
}

// Return validation errors if any
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Calculate amounts in both currencies
if ($currency === 'usd') {
    $amount_usd = $amount;
    $amount_bdt = $amount * USD_TO_BDT_RATE;
} else {
    $amount_usd = $amount / USD_TO_BDT_RATE;
    $amount_bdt = $amount;
}

// Generate transaction ID
$transaction_id = 'TXN_' . time() . '_' . rand(1000, 9999);

// Connect to database
$conn = get_database_connection();

// Insert donation data
$sql = "INSERT INTO donations (donor_name, email, phone, amount_usd, amount_bdt, purpose, is_anonymous, message, transaction_id, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'completed')";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    exit;
}

mysqli_stmt_bind_param($stmt, "sssddsiss", $name, $email, $phone, $amount_usd, $amount_bdt, $purpose, $is_anonymous, $message, $transaction_id);

if (mysqli_stmt_execute($stmt)) {
    $donation_id = mysqli_insert_id($conn);
    
    // Update receipt generation flag
    $update_sql = "UPDATE donations SET receipt_generated = 1 WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "i", $donation_id);
    mysqli_stmt_execute($update_stmt);
    mysqli_stmt_close($update_stmt);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you for your generous donation!',
        'donation_id' => $donation_id,
        'transaction_id' => $transaction_id,
        'amount_usd' => number_format($amount_usd, 2),
        'amount_bdt' => number_format($amount_bdt, 2),
        'currency' => $currency
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to process donation. Please try again.']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
