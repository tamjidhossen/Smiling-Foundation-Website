<?php

class EmailHandler {
    private $smtp_host;
    private $smtp_port;
    private $smtp_username;
    private $smtp_password;
    private $from_email;
    private $from_name;
    
    public function __construct() {
        $this->smtp_host = SMTP_HOST;
        $this->smtp_port = SMTP_PORT;
        $this->smtp_username = SMTP_USERNAME;
        $this->smtp_password = SMTP_PASSWORD;
        $this->from_email = FROM_EMAIL;
        $this->from_name = FROM_NAME;
    }
    
    /**
     * Send email using Gmail SMTP
     */
    public function sendEmail($to_email, $to_name, $subject, $html_body, $plain_body = '') {
        try {
            // Headers
            $headers = [];
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-Type: text/html; charset=UTF-8";
            $headers[] = "From: {$this->from_name} <{$this->from_email}>";
            $headers[] = "Reply-To: " . REPLY_TO_EMAIL;
            $headers[] = "X-Mailer: PHP/" . phpversion();
            $headers[] = "X-Priority: 3";
            
            // For local development, use PHP's mail() function
            // In production, you would use PHPMailer or similar
            $header_string = implode("\r\n", $headers);
            
            // Send email
            $result = mail($to_email, $subject, $html_body, $header_string);
            
            if ($result) {
                error_log("Email sent successfully to: $to_email");
                return true;
            } else {
                error_log("Failed to send email to: $to_email");
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Email error: " . $e->getMessage());
            return false;
        }
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
        $result = $this->sendEmail($to_email, $to_name, $subject, $html_body);
        
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
        
        return $this->sendEmail($to_email, $to_name, $subject, $html_body);
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
}
?>
