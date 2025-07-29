<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'config/email_config.php';
require_once 'includes/SMTPEmailHandler.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
$errors = [];

if (empty($name)) {
    $errors[] = 'Full name is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($subject)) {
    $errors[] = 'Subject is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

// Return validation errors if any
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Connect to database
$conn = get_database_connection();

// Insert contact message
$sql = "INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    exit;
}

mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $subject, $message);

if (mysqli_stmt_execute($stmt)) {
    // Prepare contact data for email
    $contact_data = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'subject' => $subject,
        'message' => $message
    ];
    
    // Try to send email notification to admin
    try {
        $emailHandler = new EmailHandler();
        $email_sent = $emailHandler->sendContactNotification($contact_data);
        
        if ($email_sent) {
            echo json_encode([
                'success' => true, 
                'message' => 'Thank you for your message! We will get back to you soon.'
            ]);
        } else {
            echo json_encode([
                'success' => true, 
                'message' => 'Your message has been saved successfully. We will get back to you soon.'
            ]);
        }
    } catch (Exception $e) {
        // Even if email fails, the message was saved to database
        error_log("Contact email failed: " . $e->getMessage());
        echo json_encode([
            'success' => true, 
            'message' => 'Your message has been saved successfully. We will get back to you soon.'
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again.']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
