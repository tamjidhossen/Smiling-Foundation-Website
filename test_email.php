<?php
// Test script for email functionality
// This script is for testing purposes only - remove in production

require_once 'config/email_config.php';
require_once 'includes/SMTPEmailHandler.php';

// Test data
$test_donation_data = [
    'id' => 999,
    'transaction_id' => 'TEST_' . time(),
    'donor_name' => 'John Doe',
    'email' => 'test@example.com', // Change this to your test email
    'phone' => '01712345678',
    'amount_usd' => 100.00,
    'amount_bdt' => 11950.00,
    'purpose' => 'General Donation',
    'created_at' => date('Y-m-d H:i:s')
];

$test_volunteer_data = [
    'name' => 'Jane Smith',
    'email' => 'volunteer@example.com', // Change this to your test email
    'volunteer_type' => 'Event Coordinator'
];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Email System Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; }
        .test-section { background: #f9f9f9; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #212529; }
        .result { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .warning { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email System Test</h1>
        <p>Use this page to test the email functionality. Make sure to configure your Gmail SMTP settings first.</p>
        
        <?php if (isset($_GET['action'])): ?>
            <div class="result">
                <?php
                try {
                    $emailHandler = new EmailHandler();
                    
                    if ($_GET['action'] === 'donation') {
                        $result = $emailHandler->sendDonationInvoice($test_donation_data);
                        if ($result) {
                            echo '<div class="success">✅ Donation invoice email sent successfully!</div>';
                        } else {
                            echo '<div class="error">❌ Failed to send donation invoice email.</div>';
                        }
                    } elseif ($_GET['action'] === 'volunteer') {
                        $result = $emailHandler->sendVolunteerApproval($test_volunteer_data);
                        if ($result) {
                            echo '<div class="success">✅ Volunteer approval email sent successfully!</div>';
                        } else {
                            echo '<div class="error">❌ Failed to send volunteer approval email.</div>';
                        }
                    }
                } catch (Exception $e) {
                    echo '<div class="error">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="test-section">
            <h2>⚠️ Before Testing</h2>
            <ol>
                <li>Configure your Gmail SMTP settings in <code>config/email_config.php</code></li>
                <li>Set up Gmail App Password (not regular password)</li>
                <li>Update the test email addresses above to your actual email</li>
                <li>Make sure your server can make outbound SMTP connections</li>
            </ol>
        </div>
        
        <div class="test-section">
            <h2>🧪 Test Functions</h2>
            
            <h3>Test Donation Invoice Email</h3>
            <p>This will send a donation invoice email with PDF attachment.</p>
            <p><strong>Test Email:</strong> <?php echo htmlspecialchars($test_donation_data['email']); ?></p>
            <a href="?action=donation" class="btn">Send Test Donation Email</a>
            
            <h3>Test Volunteer Approval Email</h3>
            <p>This will send a volunteer approval email.</p>
            <p><strong>Test Email:</strong> <?php echo htmlspecialchars($test_volunteer_data['email']); ?></p>
            <a href="?action=volunteer" class="btn btn-success">Send Test Volunteer Email</a>
        </div>
        
        <div class="test-section">
            <h2>📋 Configuration Check</h2>
            <div class="result warning">
                <h4>Current Configuration:</h4>
                <p><strong>SMTP Host:</strong> <?php echo defined('SMTP_HOST') ? SMTP_HOST : 'Not configured'; ?></p>
                <p><strong>SMTP Port:</strong> <?php echo defined('SMTP_PORT') ? SMTP_PORT : 'Not configured'; ?></p>
                <p><strong>From Email:</strong> <?php echo defined('FROM_EMAIL') ? FROM_EMAIL : 'Not configured'; ?></p>
                <p><strong>From Name:</strong> <?php echo defined('FROM_NAME') ? FROM_NAME : 'Not configured'; ?></p>
                <?php if (!defined('SMTP_USERNAME') || empty(SMTP_USERNAME)): ?>
                    <p style="color: red;"><strong>⚠️ SMTP credentials not configured!</strong></p>
                <?php else: ?>
                    <p style="color: green;"><strong>✅ SMTP credentials configured</strong></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="test-section">
            <h2>🔧 Troubleshooting</h2>
            <p><strong>If emails are not sending:</strong></p>
            <ul>
                <li>Check your Gmail App Password is correct</li>
                <li>Ensure 2-Step Verification is enabled on your Google Account</li>
                <li>Verify your server allows SMTP connections on port 587/465</li>
                <li>Check server error logs for detailed error messages</li>
                <li>Try using a different email service if Gmail doesn't work</li>
            </ul>
        </div>
    </div>
</body>
</html>
