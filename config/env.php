<?php
/**
 * Simple .env file parser
 * Loads environment variables from .env file in the project root
 */
function load_env($path) {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Check if line contains a key-value pair
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Remove quotes if present
            if ((strpos($value, '"') === 0 && substr($value, -1) === '"') || 
                (strpos($value, "'") === 0 && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            // Set as environment variable
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
    
    return true;
}

// Load environment variables from .env file
$env_path = dirname(__DIR__) . '/.env';
load_env($env_path);

/**
 * Helper function to get environment variables with default fallback
 */
function get_env($key, $default = null) {
    $value = getenv($key);
    if ($value === false) {
        return $default;
    }
    return $value;
}