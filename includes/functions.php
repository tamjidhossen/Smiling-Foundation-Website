<?php
// Sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getCategoryName($category_id, $categories) {
    foreach ($categories as $category) {
        if ($category['id'] === $category_id) {
            return $category['name'];
        }
    }
    return 'Uncategorized';
}

// Validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate page title
function getPageTitle($title) {
    return $title . ' - ' . SITE_NAME;
}

// Check if a page is active
function isActivePage($page) {
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // For home page
    if ($page === 'index.php' && $current_page === 'index.php') {
        return 'active';
    }
    
    // For other pages
    if ($page === $current_page) {
        return 'active';
    }
    
    return '';
}

// Format text excerpt
function createExcerpt($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// Format social media links
function formatSocialLink($platform, $username) {
    $platforms = [
        'facebook' => 'https://facebook.com/',
        'twitter' => 'https://twitter.com/',
        'instagram' => 'https://instagram.com/',
        'linkedin' => 'https://linkedin.com/in/'
    ];
    
    return isset($platforms[$platform]) ? $platforms[$platform] . $username : '#';
}