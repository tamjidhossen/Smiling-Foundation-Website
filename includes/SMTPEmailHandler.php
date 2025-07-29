<?php

/**
 * Simple SMTP Email Class
 * A lightweight implementation for sending emails via Gmail SMTP
 */
class SMTPMailer {
    private $smtp_host;
    private $smtp_port;
    private $smtp_username;
    private $smtp_password;
    private $smtp_secure;
    private $from_email;
    private $from_name;
    private $connection;
    private $debug = false;
    
    public function __construct() {
        $this->smtp_host = SMTP_HOST;
        $this->smtp_port = SMTP_PORT;
        $this->smtp_username = SMTP_USERNAME;
        $this->smtp_password = SMTP_PASSWORD;
        $this->smtp_secure = SMTP_SECURE;
        $this->from_email = FROM_EMAIL;
        $this->from_name = FROM_NAME;
    }
    
    /**
     * Enable debug mode
     */
    public function setDebug($debug = true) {
        $this->debug = $debug;
    }
    
    /**
     * Send email via SMTP
     */
    public function send($to_email, $to_name, $subject, $html_body) {
        try {
            // Connect to SMTP server
            if (!$this->connect()) {
                return false;
            }
            
            // Authenticate
            if (!$this->authenticate()) {
                return false;
            }
            
            // Send MAIL FROM command
            if (!$this->mailFrom($this->from_email)) {
                return false;
            }
            
            // Send RCPT TO command
            if (!$this->rcptTo($to_email)) {
                return false;
            }
            
            // Send DATA command and email content
            if (!$this->sendData($to_email, $to_name, $subject, $html_body)) {
                return false;
            }
            
            // Close connection
            $this->close();
            
            return true;
            
        } catch (Exception $e) {
            $this->log("SMTP Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Connect to SMTP server
     */
    private function connect() {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        
        if ($this->smtp_secure === 'ssl' || $this->smtp_port == 465) {
            $host = 'ssl://' . $this->smtp_host;
        } else {
            $host = $this->smtp_host;
        }
        
        $this->connection = stream_socket_client(
            $host . ':' . $this->smtp_port, 
            $errno, 
            $errstr, 
            30, 
            STREAM_CLIENT_CONNECT, 
            $context
        );
        
        if (!$this->connection) {
            $this->log("Failed to connect to SMTP server: $errstr ($errno)");
            return false;
        }
        
        // Read server greeting
        $response = $this->getResponse();
        if (substr($response, 0, 3) !== '220') {
            $this->log("Invalid server greeting: $response");
            return false;
        }
        
        // Send EHLO command
        $server_name = $_SERVER['SERVER_NAME'] ?? 'localhost';
        $this->sendCommand("EHLO " . $server_name);
        $response = $this->getResponse();
        
        // Start TLS if using STARTTLS
        if ($this->smtp_secure === 'tls' || $this->smtp_port == 587) {
            $this->sendCommand("STARTTLS");
            $response = $this->getResponse();
            
            if (substr($response, 0, 3) === '220') {
                if (!stream_socket_enable_crypto($this->connection, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    $this->log("Failed to enable TLS encryption");
                    return false;
                }
                
                // Send EHLO again after STARTTLS
                $this->sendCommand("EHLO " . $server_name);
                $response = $this->getResponse();
            }
        }
        
        return true;
    }
    
    /**
     * Authenticate with SMTP server
     */
    private function authenticate() {
        // Send AUTH LOGIN command
        $this->sendCommand("AUTH LOGIN");
        $response = $this->getResponse();
        
        if (substr($response, 0, 3) !== '334') {
            $this->log("AUTH LOGIN failed: $response");
            return false;
        }
        
        // Send username
        $this->sendCommand(base64_encode($this->smtp_username));
        $response = $this->getResponse();
        
        if (substr($response, 0, 3) !== '334') {
            $this->log("Username authentication failed: $response");
            return false;
        }
        
        // Send password
        $this->sendCommand(base64_encode($this->smtp_password));
        $response = $this->getResponse();
        
        if (substr($response, 0, 3) !== '235') {
            $this->log("Password authentication failed: $response");
            return false;
        }
        
        return true;
    }
    
    /**
     * Send MAIL FROM command
     */
    private function mailFrom($email) {
        $this->sendCommand("MAIL FROM:<$email>");
        $response = $this->getResponse();
        
        if (substr($response, 0, 3) !== '250') {
            $this->log("MAIL FROM failed: $response");
            return false;
        }
        
        return true;
    }
    
    /**
     * Send RCPT TO command
     */
    private function rcptTo($email) {
        $this->sendCommand("RCPT TO:<$email>");
        $response = $this->getResponse();
        
        if (substr($response, 0, 3) !== '250') {
            $this->log("RCPT TO failed: $response");
            return false;
        }
        
        return true;
    }
    
    /**
     * Send email data
     */
    private function sendData($to_email, $to_name, $subject, $html_body) {
        $this->sendCommand("DATA");
        $response = $this->getResponse();
        
        if (substr($response, 0, 3) !== '354') {
            $this->log("DATA command failed: $response");
            return false;
        }
        
        // Build email headers and body
        $email_data = $this->buildEmailData($to_email, $to_name, $subject, $html_body);
        
        // Send email data
        $this->sendCommand($email_data . "\r\n.");
        $response = $this->getResponse();
        
        if (substr($response, 0, 3) !== '250') {
            $this->log("Email data sending failed: $response");
            return false;
        }
        
        return true;
    }
    
    /**
     * Build complete email data
     */
    private function buildEmailData($to_email, $to_name, $subject, $html_body) {
        $headers = [];
        $headers[] = "From: {$this->from_name} <{$this->from_email}>";
        $headers[] = "To: $to_name <$to_email>";
        $headers[] = "Subject: $subject";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Date: " . date('r');
        $headers[] = "Message-ID: <" . md5(time()) . "@" . $this->smtp_host . ">";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = "Content-Transfer-Encoding: 8bit";
        
        $body = implode("\r\n", $headers) . "\r\n\r\n";
        $body .= $html_body . "\r\n";
        
        return $body;
    }
    
    /**
     * Send command to SMTP server
     */
    private function sendCommand($command) {
        if ($this->debug) {
            $this->log(">> $command");
        }
        fwrite($this->connection, $command . "\r\n");
    }
    
    /**
     * Get response from SMTP server
     */
    private function getResponse() {
        $response = '';
        while (($line = fgets($this->connection, 512)) !== false) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') {
                break;
            }
        }
        
        if ($this->debug) {
            $this->log("<< " . trim($response));
        }
        
        return $response;
    }
    
    /**
     * Close SMTP connection
     */
    private function close() {
        if ($this->connection) {
            $this->sendCommand("QUIT");
            $this->getResponse();
            fclose($this->connection);
        }
    }
    
    /**
     * Log debug information
     */
    private function log($message) {
        error_log("[SMTPMailer] $message");
        if ($this->debug) {
            echo "[SMTPMailer] $message\n";
        }
    }
}

/**
 * Enhanced Email Handler using SMTP
 */
class EmailHandler {
    public $mailer;
    
    public function __construct() {
        $this->mailer = new SMTPMailer();
    }
    
    /**
     * Send donation invoice email
     */
    public function sendDonationInvoice($donation_data) {
        $to_email = $donation_data['email'];
        $to_name = $donation_data['donor_name'];
        $subject = "Thank you for your donation - Invoice #" . $donation_data['transaction_id'];
        
        // Load email template
        $html_body = $this->getDonationEmailTemplate($donation_data);
        
        // Send email without attachment - receipt is available via download link
        return $this->mailer->send($to_email, $to_name, $subject, $html_body);
    }
    
    /**
     * Send volunteer approval email
     */
    public function sendVolunteerApproval($volunteer_data) {
        $to_email = $volunteer_data['email'];
        $to_name = $volunteer_data['name'];
        $subject = "Congratulations! Your volunteer application has been approved";
        
        // Load email template
        $html_body = $this->getVolunteerApprovalTemplate($volunteer_data);
        
        return $this->mailer->send($to_email, $to_name, $subject, $html_body);
    }
    
    /**
     * Send contact form notification to admin
     */
    public function sendContactNotification($contact_data) {
        // Send to admin email (GMAIL_USERNAME from .env)
        $to_email = SMTP_USERNAME; // This is the admin email
        $to_name = "Admin";
        $subject = "New Contact Form Submission: " . $contact_data['subject'];
        
        // Load email template
        $html_body = $this->getContactNotificationTemplate($contact_data);
        
        return $this->mailer->send($to_email, $to_name, $subject, $html_body);
    }
    
    /**
     * Generate donation email template
     */
    private function getDonationEmailTemplate($donation_data) {
        $transaction_id = $donation_data['transaction_id'];
        $donor_name = $donation_data['donor_name'];
        $amount_usd = number_format($donation_data['amount_usd'], 2);
        $amount_bdt = number_format($donation_data['amount_bdt'], 2);
        $purpose = $donation_data['purpose'];
        $date = date('F j, Y', strtotime($donation_data['created_at']));
        $donation_id = $donation_data['id'];
        
        // Create download link for receipt
        $receipt_link = ORG_WEBSITE . "/pages/receipt.php?id=$donation_id&txn=$transaction_id";
        
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Donation Invoice</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #2c5aa0; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .invoice-details { background-color: white; padding: 15px; margin: 20px 0; border-radius: 5px; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
                .amount { font-size: 24px; font-weight: bold; color: #2c5aa0; }
                .thank-you { background-color: #e8f5e8; padding: 15px; border-left: 4px solid #4CAF50; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Smiling Foundation</h1>
                    <p>Thank you for your generous donation!</p>
                </div>
                
                <div class='content'>
                    <div class='thank-you'>
                        <h2>Dear $donor_name,</h2>
                        <p>Thank you for your generous donation to Smiling Foundation. Your contribution will make a real difference in the lives of those we serve.</p>
                    </div>
                    
                    <div class='invoice-details'>
                        <h3>Donation Details</h3>
                        <table style='width: 100%; border-collapse: collapse;'>
                            <tr style='border-bottom: 1px solid #ddd;'>
                                <td style='padding: 8px; font-weight: bold;'>Transaction ID:</td>
                                <td style='padding: 8px;'>$transaction_id</td>
                            </tr>
                            <tr style='border-bottom: 1px solid #ddd;'>
                                <td style='padding: 8px; font-weight: bold;'>Date:</td>
                                <td style='padding: 8px;'>$date</td>
                            </tr>
                            <tr style='border-bottom: 1px solid #ddd;'>
                                <td style='padding: 8px; font-weight: bold;'>Purpose:</td>
                                <td style='padding: 8px;'>$purpose</td>
                            </tr>
                            <tr style='border-bottom: 1px solid #ddd;'>
                                <td style='padding: 8px; font-weight: bold;'>Amount (USD):</td>
                                <td style='padding: 8px;'><span class='amount'>$$amount_usd</span></td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; font-weight: bold;'>Amount (BDT):</td>
                                <td style='padding: 8px;'><span class='amount'>৳$amount_bdt</span></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div style='background-color: white; padding: 15px; border-radius: 5px;'>
                        <h3>What happens next?</h3>
                        <ul>
                            <li><strong>Download your receipt:</strong> <a href='$receipt_link' style='color: #2c5aa0; text-decoration: none; font-weight: bold;'>Click here to download your official receipt</a></li>
                            <li>We'll keep you updated on how your donation is being used</li>
                            <li>You'll receive our newsletter with updates on our projects</li>
                        </ul>
                        
                        <div style='background-color: #e3f2fd; padding: 15px; border-left: 4px solid #2196f3; margin-top: 15px; border-radius: 4px;'>
                            <h4 style='margin-top: 0; color: #1976d2;'>📄 Your Receipt</h4>
                            <p style='margin-bottom: 0;'>Click the link below to download your official donation receipt:</p>
                            <p style='margin: 10px 0;'>
                                <a href='$receipt_link' style='background-color: #2c5aa0; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>
                                    📥 Download Receipt
                                </a>
                            </p>
                            <p style='font-size: 12px; color: #666; margin-bottom: 0;'>
                                Receipt ID: $transaction_id | Valid for tax purposes
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class='footer'>
                    <p><strong>Smiling Foundation</strong></p>
                    <p>" . ORG_ADDRESS . "</p>
                    <p>Phone: " . ORG_PHONE . "</p>
                    <p>Website: <a href='" . ORG_WEBSITE . "'>" . ORG_WEBSITE . "</a></p>
                    <p><em>This is an automated email. Please do not reply to this email address.</em></p>
                </div>
            </div>
        </body>
        </html>";
        
        return $html;
    }
    
    /**
     * Generate contact notification email template for admin
     */
    private function getContactNotificationTemplate($contact_data) {
        $name = htmlspecialchars($contact_data['name']);
        $email = htmlspecialchars($contact_data['email']);
        $phone = htmlspecialchars($contact_data['phone'] ?? 'Not provided');
        $subject = htmlspecialchars($contact_data['subject']);
        $message = nl2br(htmlspecialchars($contact_data['message']));
        $date = date('F j, Y \a\t g:i A');
        
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>New Contact Form Submission</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #2c5aa0; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .contact-details { background-color: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #2c5aa0; }
                .message-box { background-color: #fff; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #ddd; }
                .info-row { margin: 10px 0; padding: 8px 0; border-bottom: 1px solid #eee; }
                .info-label { font-weight: bold; color: #2c5aa0; display: inline-block; width: 100px; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; border-radius: 0 0 8px 8px; background-color: #f0f0f0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📧 New Contact Form Submission</h1>
                    <p>Someone has submitted a message through your website</p>
                </div>
                
                <div class='content'>
                    <div class='contact-details'>
                        <h2>Contact Information</h2>
                        <div class='info-row'>
                            <span class='info-label'>Name:</span> $name
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Email:</span> <a href='mailto:$email'>$email</a>
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Phone:</span> $phone
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Subject:</span> $subject
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Date:</span> $date
                        </div>
                    </div>
                    
                    <div class='message-box'>
                        <h3>Message:</h3>
                        <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 3px solid #2c5aa0;'>
                            $message
                        </div>
                    </div>
                    
                    <div style='background-color: #e3f2fd; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                        <h3 style='margin-top: 0; color: #1976d2;'>📞 Quick Actions</h3>
                        <p><strong>Reply directly:</strong> <a href='mailto:$email?subject=Re: $subject'>Click here to reply to $name</a></p>
                        <p><strong>Call:</strong> $phone</p>
                        <p><strong>View in admin panel:</strong> Log in to your admin dashboard to manage this contact</p>
                    </div>
                </div>
                
                <div class='footer'>
                    <p><strong>Smiling Foundation - Contact Management System</strong></p>
                    <p>This is an automated notification from your website contact form.</p>
                    <p><em>Received at: $date</em></p>
                </div>
            </div>
        </body>
        </html>";
        
        return $html;
    }
    
    /**
     * Generate volunteer approval email template
     */
    private function getVolunteerApprovalTemplate($volunteer_data) {
        $name = $volunteer_data['name'];
        $volunteer_type = $volunteer_data['volunteer_type'];
        $approved_date = date('F j, Y');
        
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Volunteer Application Approved</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .approval-box { background-color: #e8f5e8; padding: 20px; border-left: 4px solid #4CAF50; margin: 20px 0; border-radius: 5px; }
                .next-steps { background-color: white; padding: 15px; margin: 20px 0; border-radius: 5px; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
                .welcome-badge { background-color: #4CAF50; color: white; padding: 10px 20px; border-radius: 25px; display: inline-block; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎉 Congratulations!</h1>
                    <p>Your volunteer application has been approved</p>
                </div>
                
                <div class='content'>
                    <div class='approval-box'>
                        <h2>Welcome to the Smiling Foundation Team!</h2>
                        <p><strong>Dear $name,</strong></p>
                        <p>We are delighted to inform you that your application to become a volunteer with Smiling Foundation has been <strong>approved</strong>!</p>
                        <div class='welcome-badge'>
                            Welcome aboard as a $volunteer_type volunteer!
                        </div>
                        <p><strong>Approval Date:</strong> $approved_date</p>
                    </div>
                    
                    <div class='next-steps'>
                        <h3>What's Next?</h3>
                        <ol>
                            <li><strong>Orientation Session:</strong> You'll receive an invitation to our next volunteer orientation session within 3-5 business days.</li>
                            <li><strong>Training Materials:</strong> We'll send you training materials specific to your volunteer role.</li>
                            <li><strong>First Assignment:</strong> Our volunteer coordinator will contact you with your first assignment details.</li>
                            <li><strong>Volunteer ID:</strong> You'll receive your official volunteer ID card during the orientation session.</li>
                        </ol>
                    </div>
                    
                    <div class='next-steps'>
                        <h3>Important Information</h3>
                        <ul>
                            <li><strong>Contact Person:</strong> Our volunteer coordinator will be your main point of contact</li>
                            <li><strong>Commitment:</strong> Please remember your commitment as a $volunteer_type volunteer</li>
                            <li><strong>Code of Conduct:</strong> All volunteers must adhere to our code of conduct</li>
                            <li><strong>Flexibility:</strong> We understand life happens - just communicate with us about any changes</li>
                        </ul>
                    </div>
                    
                    <div style='background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; border-radius: 5px; margin: 20px 0;'>
                        <h3>📞 Need Help?</h3>
                        <p>If you have any questions or concerns, please don't hesitate to contact us:</p>
                        <p><strong>Phone:</strong> " . ORG_PHONE . "</p>
                        <p><strong>Email:</strong> " . REPLY_TO_EMAIL . "</p>
                    </div>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <p style='font-size: 18px; color: #4CAF50;'><strong>Thank you for choosing to make a difference with us!</strong></p>
                        <p>Together, we can create positive change in our community.</p>
                    </div>
                </div>
                
                <div class='footer'>
                    <p><strong>Smiling Foundation</strong></p>
                    <p>" . ORG_ADDRESS . "</p>
                    <p>Phone: " . ORG_PHONE . "</p>
                    <p>Website: <a href='" . ORG_WEBSITE . "'>" . ORG_WEBSITE . "</a></p>
                    <p><em>This is an automated email. Please do not reply to this email address.</em></p>
                </div>
            </div>
        </body>
        </html>";
        
        return $html;
    }
}
?>
