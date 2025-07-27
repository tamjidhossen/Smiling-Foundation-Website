<?php
/**
 * Simple PDF Generator for Donation Invoices
 * Creates HTML-based invoices that can be converted to PDF
 */
class InvoicePDFGenerator {
    
    /**
     * Generate donation invoice PDF
     */
    public static function generateDonationInvoice($donation_data) {
        $transaction_id = $donation_data['transaction_id'];
        $pdf_filename = "invoice_" . $transaction_id . ".html";
        $pdf_path = PDF_TEMP_PATH . $pdf_filename;
        
        $html_content = self::getDonationInvoiceHTML($donation_data);
        
        // Write HTML to temporary file
        if (file_put_contents($pdf_path, $html_content)) {
            return $pdf_path;
        }
        
        return false;
    }
    
    /**
     * Generate HTML invoice content
     */
    private static function getDonationInvoiceHTML($donation_data) {
        $transaction_id = $donation_data['transaction_id'];
        $donor_name = htmlspecialchars($donation_data['donor_name']);
        $email = htmlspecialchars($donation_data['email']);
        $phone = htmlspecialchars($donation_data['phone'] ?? 'N/A');
        $amount_usd = number_format($donation_data['amount_usd'], 2);
        $amount_bdt = number_format($donation_data['amount_bdt'], 2);
        $purpose = htmlspecialchars($donation_data['purpose']);
        $date = date('F j, Y', strtotime($donation_data['created_at']));
        
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Donation Invoice - $transaction_id</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0; 
                    padding: 20px; 
                    color: #333;
                    line-height: 1.6;
                }
                .invoice-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                }
                .invoice-header {
                    background: linear-gradient(135deg, #2c5aa0, #1e4080);
                    color: white;
                    padding: 30px;
                    text-align: center;
                    margin-bottom: 30px;
                }
                .invoice-header h1 {
                    margin: 0;
                    font-size: 2.5em;
                    font-weight: bold;
                }
                .invoice-header p {
                    margin: 10px 0 0 0;
                    font-size: 1.2em;
                    opacity: 0.9;
                }
                .invoice-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 30px;
                    padding: 0 20px;
                }
                .invoice-details, .organization-details {
                    flex: 1;
                    margin: 0 10px;
                }
                .invoice-details h3, .organization-details h3 {
                    color: #2c5aa0;
                    border-bottom: 2px solid #2c5aa0;
                    padding-bottom: 5px;
                    margin-bottom: 15px;
                }
                .details-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                    background: white;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                .details-table th, .details-table td {
                    padding: 12px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                .details-table th {
                    background: #f8f9fa;
                    font-weight: bold;
                    color: #2c5aa0;
                }
                .amount-highlight {
                    font-size: 1.4em;
                    font-weight: bold;
                    color: #2c5aa0;
                }
                .thank-you-section {
                    background: #e8f5e8;
                    padding: 20px;
                    margin: 30px 0;
                    border-left: 5px solid #4CAF50;
                    text-align: center;
                }
                .footer {
                    background: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    color: #666;
                    font-size: 0.9em;
                    border-top: 2px solid #2c5aa0;
                    margin-top: 30px;
                }
                .watermark {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotate(-45deg);
                    font-size: 6em;
                    color: rgba(44, 90, 160, 0.1);
                    z-index: -1;
                    font-weight: bold;
                }
                @media print {
                    body { margin: 0; }
                    .invoice-container { box-shadow: none; }
                }
            </style>
        </head>
        <body>
            <div class='watermark'>PAID</div>
            
            <div class='invoice-container'>
                <div class='invoice-header'>
                    <h1>SMILING FOUNDATION</h1>
                    <p>Donation Receipt & Invoice</p>
                </div>
                
                <div class='invoice-info'>
                    <div class='invoice-details'>
                        <h3>Invoice Details</h3>
                        <p><strong>Invoice #:</strong> $transaction_id</p>
                        <p><strong>Date Issued:</strong> $date</p>
                        <p><strong>Status:</strong> <span style='color: #4CAF50; font-weight: bold;'>PAID</span></p>
                    </div>
                    
                    <div class='organization-details'>
                        <h3>Organization Details</h3>
                        <p><strong>Smiling Foundation</strong></p>
                        <p>12/A, Trishal, Mymensingh, Bangladesh</p>
                        <p>Phone: +880 1712345678</p>
                        <p>Website: http://localhost/smilingfoundation</p>
                    </div>
                </div>
                
                <table class='details-table'>
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center; background: #2c5aa0; color: white; font-size: 1.2em;'>
                                DONOR INFORMATION
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Donor Name:</strong></td>
                            <td>$donor_name</td>
                        </tr>
                        <tr>
                            <td><strong>Email Address:</strong></td>
                            <td>$email</td>
                        </tr>
                        <tr>
                            <td><strong>Phone Number:</strong></td>
                            <td>$phone</td>
                        </tr>
                    </tbody>
                </table>
                
                <table class='details-table'>
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center; background: #2c5aa0; color: white; font-size: 1.2em;'>
                                DONATION DETAILS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Donation Purpose:</strong></td>
                            <td>$purpose</td>
                        </tr>
                        <tr>
                            <td><strong>Amount (USD):</strong></td>
                            <td class='amount-highlight'>$$amount_usd</td>
                        </tr>
                        <tr>
                            <td><strong>Amount (BDT):</strong></td>
                            <td class='amount-highlight'>৳$amount_bdt</td>
                        </tr>
                        <tr>
                            <td><strong>Payment Status:</strong></td>
                            <td><span style='color: #4CAF50; font-weight: bold;'>✓ COMPLETED</span></td>
                        </tr>
                    </tbody>
                </table>
                
                <div class='thank-you-section'>
                    <h2 style='margin-top: 0; color: #4CAF50;'>🙏 Thank You for Your Generosity!</h2>
                    <p style='font-size: 1.1em; margin: 15px 0;'>
                        Your donation of <strong>$$amount_usd USD (৳$amount_bdt BDT)</strong> will make a real difference in the lives of those we serve.
                    </p>
                    <p style='color: #666;'>
                        This receipt serves as proof of your charitable contribution and may be used for tax purposes where applicable.
                    </p>
                </div>
                
                <div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;'>
                    <h3 style='margin-top: 0; color: #856404;'>📋 What Happens Next?</h3>
                    <ul style='margin: 0; padding-left: 20px;'>
                        <li>Your donation has been processed successfully</li>
                        <li>You will receive updates on how your donation is being used</li>
                        <li>You'll be added to our newsletter for project updates</li>
                        <li>Keep this receipt for your records</li>
                    </ul>
                </div>
                
                <div class='footer'>
                    <p><strong>This is a computer-generated invoice and serves as an official receipt.</strong></p>
                    <p>Generated on " . date('F j, Y \a\t g:i A') . "</p>
                    <p style='margin-top: 15px;'>
                        <strong>Smiling Foundation</strong> | 
                        Email: info@smilingfoundation.org | 
                        Phone: +880 1712345678
                    </p>
                    <p style='font-size: 0.8em; margin-top: 10px; color: #999;'>
                        © " . date('Y') . " Smiling Foundation. All rights reserved.
                    </p>
                </div>
            </div>
        </body>
        </html>";
        
        return $html;
    }
}
?>
