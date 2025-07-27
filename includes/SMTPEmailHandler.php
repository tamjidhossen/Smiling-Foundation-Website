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
    public function send($to_email, $to_name, $subject, $html_body, $attachments = []) {
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
            if (!$this->sendData($to_email, $to_name, $subject, $html_body, $attachments)) {
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
        $this->sendCommand("EHLO " . $_SERVER['SERVER_NAME'] ?? 'localhost');
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
                $this->sendCommand("EHLO " . $_SERVER['SERVER_NAME'] ?? 'localhost');
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
    private function sendData($to_email, $to_name, $subject, $html_body, $attachments = []) {
        $this->sendCommand("DATA");
        $response = $this->getResponse();
        
        if (substr($response, 0, 3) !== '354') {
            $this->log("DATA command failed: $response");
            return false;
        }
        
        // Build email headers and body
        $email_data = $this->buildEmailData($to_email, $to_name, $subject, $html_body, $attachments);
        
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
    private function buildEmailData($to_email, $to_name, $subject, $html_body, $attachments = []) {
        $boundary = "----=_Part_" . md5(time());
        
        $headers = [];
        $headers[] = "From: {$this->from_name} <{$this->from_email}>";
        $headers[] = "To: $to_name <$to_email>";
        $headers[] = "Subject: $subject";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: multipart/mixed; boundary=\"$boundary\"";
        $headers[] = "Date: " . date('r');
        $headers[] = "Message-ID: <" . md5(time()) . "@" . $this->smtp_host . ">";
        
        $body = implode("\r\n", $headers) . "\r\n\r\n";
        
        // Add HTML content
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $html_body . "\r\n\r\n";
        
        // Add attachments
        foreach ($attachments as $attachment) {
            if (file_exists($attachment)) {
                $filename = basename($attachment);
                $content = base64_encode(file_get_contents($attachment));
                
                $body .= "--$boundary\r\n";
                $body .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
                $body .= chunk_split($content) . "\r\n";
            }
        }
        
        $body .= "--$boundary--\r\n";
        
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
    private $mailer;
    
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
        
        // Generate PDF invoice
        $pdf_path = $this->generateInvoicePDF($donation_data);
        
        // Send email with attachment
        $attachments = [];
        if ($pdf_path && file_exists($pdf_path)) {
            $attachments[] = $pdf_path;
        }
        
        $result = $this->mailer->send($to_email, $to_name, $subject, $html_body, $attachments);
        
        // Clean up temporary PDF file
        if ($pdf_path && file_exists($pdf_path)) {
            unlink($pdf_path);
        }
        
        return $result;
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
     * Generate donation email template
     */
    private function getDonationEmailTemplate($donation_data) {
        $transaction_id = $donation_data['transaction_id'];
        $donor_name = $donation_data['donor_name'];
        $amount_usd = number_format($donation_data['amount_usd'], 2);
        $amount_bdt = number_format($donation_data['amount_bdt'], 2);
        $purpose = $donation_data['purpose'];
        $date = date('F j, Y', strtotime($donation_data['created_at']));
        
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
                            <li>Your donation receipt is attached to this email</li>
                            <li>We'll keep you updated on how your donation is being used</li>
                            <li>You'll receive our newsletter with updates on our projects</li>
                        </ul>
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
    
    /**
     * Generate PDF invoice for donation
     */
    private function generateInvoicePDF($donation_data) {
        require_once 'InvoicePDFGenerator.php';
        return InvoicePDFGenerator::generateDonationInvoice($donation_data);
    }
}
?>
