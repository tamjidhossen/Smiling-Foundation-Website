<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$conn = get_database_connection();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $delete_sql = "DELETE FROM contact_messages WHERE id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "i", $id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        $_SESSION['success_message'] = "Message deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting message.";
    }
    
    mysqli_stmt_close($delete_stmt);
    header('Location: contacts.php');
    exit;
}

// Get all contact messages, latest first
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$contacts = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1><i class="fas fa-envelope"></i> Contact Messages</h1>
                <p>Manage contact form submissions</p>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <div class="admin-content">
                <?php if (empty($contacts)): ?>
                    <div class="empty-state">
                        <i class="fas fa-envelope-open"></i>
                        <h3>No Messages Yet</h3>
                        <p>Contact form submissions will appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="contacts-grid">
                        <?php foreach ($contacts as $contact): ?>
                            <div class="contact-card">
                                <div class="contact-header">
                                    <h3><?php echo htmlspecialchars($contact['name']); ?></h3>
                                    <div class="contact-actions">
                                        <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>?subject=Re: <?php echo urlencode($contact['subject']); ?>&body=<?php echo urlencode("Dear " . $contact['name'] . ",\n\nThank you for contacting us. We have received your message:\n\n\"" . $contact['message'] . "\"\n\nBest regards,\nSmiling Foundation Team"); ?>" 
                                           class="btn btn-primary reply-btn" title="Reply via Email">
                                            <i class="fas fa-reply"></i> Reply
                                        </a>
                                        <a href="?action=delete&id=<?php echo $contact['id']; ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this message?')" 
                                           title="Delete Message">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="contact-details">
                                    <div class="detail-row">
                                        <strong>Email:</strong> 
                                        <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>" class="email-link">
                                            <?php echo htmlspecialchars($contact['email']); ?>
                                        </a>
                                    </div>
                                    
                                    <?php if (!empty($contact['phone'])): ?>
                                        <div class="detail-row">
                                            <strong>Phone:</strong> 
                                            <a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>" class="phone-link">
                                                <?php echo htmlspecialchars($contact['phone']); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="detail-row">
                                        <strong>Subject:</strong> <?php echo htmlspecialchars($contact['subject']); ?>
                                    </div>
                                    
                                    <div class="detail-row">
                                        <strong>Date:</strong> <?php echo date('M j, Y g:i A', strtotime($contact['created_at'])); ?>
                                    </div>
                                </div>
                                
                                <div class="contact-message">
                                    <strong>Message:</strong>
                                    <p><?php echo nl2br(htmlspecialchars($contact['message'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <style>
        .contacts-grid {
            display: grid;
            gap: 20px;
            margin-top: 20px;
        }

        .contact-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #007bff;
        }

        .contact-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .contact-header h3 {
            margin: 0;
            color: #333;
            font-size: 1.2em;
        }

        .contact-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 0.9em;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .reply-btn {
            background-color: #28a745 !important;
        }

        .reply-btn:hover {
            background-color: #218838 !important;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .contact-details {
            margin-bottom: 15px;
        }

        .detail-row {
            margin-bottom: 8px;
            font-size: 0.95em;
        }

        .detail-row strong {
            color: #555;
            min-width: 70px;
            display: inline-block;
        }

        .email-link, .phone-link {
            color: #007bff;
            text-decoration: none;
        }

        .email-link:hover, .phone-link:hover {
            text-decoration: underline;
        }

        .contact-message {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #007bff;
        }

        .contact-message strong {
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        .contact-message p {
            margin: 0;
            line-height: 1.5;
            color: #666;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 4em;
            margin-bottom: 20px;
            color: #ddd;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: #555;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>

    <script>
        // Handle mailto links with fallback
        document.addEventListener('DOMContentLoaded', function() {
            const mailtoLinks = document.querySelectorAll('a[href^="mailto:"]');
            
            mailtoLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const email = this.href.split('mailto:')[1].split('?')[0];
                    
                    // Try to open the mailto link
                    setTimeout(() => {
                        // If user is still on the page after 500ms, show alternative
                        if (document.hasFocus()) {
                            const showCopyOption = confirm(
                                'If your email client didn\'t open, would you like to copy the email address to clipboard?\n\n' +
                                'Email: ' + email
                            );
                            
                            if (showCopyOption) {
                                // Copy email to clipboard
                                navigator.clipboard.writeText(email).then(() => {
                                    alert('Email address copied to clipboard: ' + email);
                                }).catch(() => {
                                    // Fallback for older browsers
                                    prompt('Copy this email address:', email);
                                });
                            }
                        }
                    }, 500);
                });
            });
        });
    </script>
</body>
</html>
