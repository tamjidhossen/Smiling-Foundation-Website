<?php
// Site configuration
define('SITE_NAME', 'Smiling Foundation');
define('SITE_URL', 'http://localhost/smilingfoundation');
define('CONTACT_EMAIL', 'info@smilingfoundation.org');
define('CONTACT_PHONE', '+880 1712345678');
define('CONTACT_ADDRESS', '12/A, Trishal, Mymensingh, Bangladesh');
define('CURRENCY', 'BDT');
define('TIME_ZONE', 'Asia/Dhaka');
define('ADMIN_URL', SITE_URL . '/admin');

// Set default timezone
date_default_timezone_set(TIME_ZONE);

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Directory paths
define('ROOT_PATH', dirname(__DIR__));
define('UPLOADS_PATH', ROOT_PATH . '/assets/img/uploads');
define('DATA_PATH', ROOT_PATH . '/data');

// File upload settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Social Media Links
define('SOCIAL_FACEBOOK', 'https://facebook.com/charity');
define('SOCIAL_TWITTER', 'https://twitter.com/charity');
define('SOCIAL_INSTAGRAM', 'https://instagram.com/charity');

// Helper function to load JSON data
function loadJsonData($filename) {
    $jsonFile = DATA_PATH . '/' . $filename;
    if (!file_exists($jsonFile)) {
        error_log("JSON file not found: " . $jsonFile);
        return null;
    }
    
    $jsonContent = file_get_contents($jsonFile);
    if ($jsonContent === false) {
        error_log("Could not read JSON file: " . $jsonFile);
        return null;
    }
    
    $data = json_decode($jsonContent, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error: " . json_last_error_msg());
        return null;
    }
    
    return $data;
}

// Helper function to format currency
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

// Helper function to format date
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

function getHeroImage($page) {
    $config = loadJsonData('config.json');
    if (!$config || !isset($config['hero_images'][$page])) {
        return '../assets/img/hero/default-hero.jpg'; // Fallback image
    }
    return $config['hero_images'][$page];
}