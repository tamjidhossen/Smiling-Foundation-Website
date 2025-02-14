<?php
session_start();
require_once '../config/config.php';

// Destroy session
session_destroy();

// Redirect to login page
header('Location: ' . ADMIN_URL . '/login.php');
exit;