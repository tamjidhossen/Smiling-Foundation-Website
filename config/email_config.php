<?php

// Function to load .env file
function loadEnvFile($path) {
    if (!file_exists($path)) {
        return [];
    }
    
    $env = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, '"\'');
            $env[$key] = $value;
        }
    }
    
    return $env;
}

// Load environment variables
$env = loadEnvFile(dirname(__DIR__) . '/.env');

// Gmail SMTP Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587); // Use 587 for STARTTLS or 465 for SMTPS
define('SMTP_SECURE', 'tls'); // 'tls' for STARTTLS, 'ssl' for SMTPS
define('SMTP_AUTH', true);

// Gmail credentials - Load from .env or use configured values
$smtp_username = $env['GMAIL_USERNAME'] ?? 'your-gmail@gmail.com';
$smtp_password = $env['GMAIL_APP_PASSWORD'] ?? 'your-app-password';
$from_email = $env['FROM_EMAIL'] ?? $smtp_username;
$from_name = $env['FROM_NAME'] ?? 'Smiling Foundation';

define('SMTP_USERNAME', $smtp_username);
define('SMTP_PASSWORD', $smtp_password);
define('FROM_EMAIL', $from_email);
define('FROM_NAME', $from_name);
define('REPLY_TO_EMAIL', 'noreply@smilingfoundation.org');

// Organization details
define('ORG_NAME', 'Smiling Foundation');
define('ORG_ADDRESS', '12/A, Trishal, Mymensingh, Bangladesh');
define('ORG_PHONE', '+880 1712345678');
define('ORG_WEBSITE', 'http://localhost/smilingfoundation');

// Email templates path
define('EMAIL_TEMPLATES_PATH', dirname(__FILE__) . '/templates/');

// PDF settings
define('PDF_TEMP_PATH', dirname(__DIR__) . '/temp/pdf/');

// Create temp directory if it doesn't exist
if (!file_exists(PDF_TEMP_PATH)) {
    mkdir(PDF_TEMP_PATH, 0755, true);
}
?>
