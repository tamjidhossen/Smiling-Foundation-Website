<?php
require_once '../config/config.php';
require_once '../config/database.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$conn = get_database_connection();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    
    // Handle news vs blog content
    if ($category_id == 3) { // News category
        $external_url = $_POST['external_url'];
        $content = '';
        $author = '';
    } else { // Regular blog
        $content = $_POST['content'];
        $author = $_POST['author'];
        $external_url = '';
    }
    
    // Handle image upload
    $image_name = null;
    if (!empty($_FILES['image']['name'])) {
        $image_name = handle_image_upload();
    } elseif ($id) {
        // If editing and no new image uploaded, keep existing image
        $query = $category_id == 3 ? "SELECT thumbnail as image FROM news WHERE id = ?" : "SELECT image FROM blogs WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $image_name = $row['image'];
    }
    
    // If editing existing post
    if ($id) {
        if ($category_id == 3) { // Update news
            $query = "UPDATE news SET title = ?, thumbnail = ?, external_url = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sssi', $title, $image_name, $external_url, $id);
        } else { // Update blog
            $query = "UPDATE blogs SET title = ?, content = ?, author = ?, category_id = ?, image = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sssssi', $title, $content, $author, $category_id, $image_name, $id);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to blog management
            header('Location: blogs.php?success=updated');
            exit;
        } else {
            header('Location: blog_form.php?id=' . $id . '&error=update');
            exit;
        }
    } else {
        // Creating new post
        if ($category_id == 3) { // Create news
            $query = "INSERT INTO news (title, thumbnail, external_url) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sss', $title, $image_name, $external_url);
        } else { // Create blog
            $query = "INSERT INTO blogs (title, content, author, category_id, image, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sssss', $title, $content, $author, $category_id, $image_name);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to blog management
            header('Location: blogs.php?success=created');
            exit;
        } else {
            header('Location: blog_form.php?error=create');
            exit;
        }
    }
} else {
    // Not a POST request
    header('Location: blogs.php');
    exit;
}

// Function to handle image upload
function handle_image_upload() {
    // Set upload directory
    $upload_dir = '../assets/img/blog/';
    
    // Generate unique filename
    $filename = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $target_file = $upload_dir . $filename;
    
    // Check file size (10MB max)
    if ($_FILES['image']['size'] > 10 * 1024 * 1024) {
        header('Location: blog_form.php?error=filesize');
        exit;
    }
    
    // Check if image file is an actual image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        header('Location: blog_form.php?error=filetype');
        exit;
    }
    
    // Upload file
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        header('Location: blog_form.php?error=upload');
        exit;
    }
    
    return $filename;
}
?>