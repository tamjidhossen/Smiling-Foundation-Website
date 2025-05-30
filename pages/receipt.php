<?php
require_once '../config/config.php';
require_once '../config/database.php';

$donation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$transaction_id = isset($_GET['txn']) ? $_GET['txn'] : '';

if ($donation_id <= 0 || empty($transaction_id)) {
    header('Location: donate.php');
    exit;
}

$conn = get_database_connection();

// Get donation details
$sql = "SELECT * FROM donations WHERE id = ? AND transaction_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "is", $donation_id, $transaction_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header('Location: donate.php');
    exit;
}

$donation = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Simple HTML to PDF conversion using browser's print functionality
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt - <?php echo htmlspecialchars($donation['transaction_id']); ?></title>
    <style>
        @media print {
            @page {
                margin: 0.5in;
                size: A4;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            color: #333;
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .receipt-header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 2.5rem;
        }
        
        .receipt-header p {
            margin: 10px 0 0 0;
            color: #666;
            font-size: 1.1rem;
        }
        
        .receipt-title {
            background: #2563eb;
            color: white;
            text-align: center;
            padding: 15px;
            margin: 20px 0;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .receipt-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 30px 0;
        }
        
        .detail-section h3 {
            color: #2563eb;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #374151;
        }
        
        .detail-value {
            color: #6b7280;
            font-weight: 500;
        }
        
        .amount-highlight {
            background: #f0f9ff;
            border: 2px solid #2563eb;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        
        .amount-highlight .amount {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
        }
        
        .amount-highlight .currency-note {
            color: #6b7280;
            margin-top: 5px;
        }
        
        .footer-note {
            background: #f8fafc;
            border-left: 4px solid #2563eb;
            padding: 20px;
            margin: 30px 0;
            font-style: italic;
            color: #4b5563;
        }
        
        .print-actions {
            text-align: center;
            margin: 30px 0;
        }
        
        .print-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            margin: 0 10px;
            text-decoration: none;
            display: inline-block;
        }
        
        .print-btn:hover {
            background: #1d4ed8;
        }
        
        .print-btn.secondary {
            background: #6b7280;
        }
        
        .print-btn.secondary:hover {
            background: #4b5563;
        }
        
        @media (max-width: 768px) {
            .receipt-details {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .receipt-header h1 {
                font-size: 2rem;
            }
            
            .amount-highlight .amount {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1><?php echo SITE_NAME; ?></h1>
            <p>Making a difference through your generosity</p>
            <p>Email: info@smilingfoundation.org | Phone: +880-XXX-XXXXXX</p>
        </div>
        
        <div class="receipt-title">
            DONATION RECEIPT
        </div>
        
        <div class="amount-highlight">
            <div class="amount">$<?php echo number_format($donation['amount_usd'], 2); ?></div>
            <div class="currency-note">৳<?php echo number_format($donation['amount_bdt'], 2); ?> BDT</div>
        </div>
        
        <div class="receipt-details">
            <div class="detail-section">
                <h3>Donation Information</h3>
                <div class="detail-item">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($donation['transaction_id']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Purpose:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($donation['purpose']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value"><?php echo date('F j, Y \a\t g:i A', strtotime($donation['donated_at'])); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Status:</span>
                    <span class="detail-value"><?php echo ucfirst($donation['payment_status']); ?></span>
                </div>
            </div>
            
            <div class="detail-section">
                <h3>Donor Information</h3>
                <?php if (!$donation['is_anonymous']): ?>
                <div class="detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($donation['donor_name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($donation['email']); ?></span>
                </div>
                <?php if (!empty($donation['phone'])): ?>
                <div class="detail-item">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($donation['phone']); ?></span>
                </div>
                <?php endif; ?>
                <?php else: ?>
                <div class="detail-item">
                    <span class="detail-label">Donor:</span>
                    <span class="detail-value">Anonymous</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($donation['message'])): ?>
        <div class="footer-note">
            <strong>Message:</strong> "<?php echo htmlspecialchars($donation['message']); ?>"
        </div>
        <?php endif; ?>
        
        <div class="footer-note">
            <strong>Thank you for your generous donation!</strong><br>
            Your contribution helps us continue our mission of creating positive change in communities. 
            This receipt serves as acknowledgment of your donation. Please keep this for your records.
        </div>
        
        <div class="print-actions no-print">
            <button onclick="window.print()" class="print-btn">
                🖨️ Print Receipt
            </button>
            <a href="donation-success.php?id=<?php echo $donation['id']; ?>&txn=<?php echo urlencode($donation['transaction_id']); ?>" class="print-btn secondary">
                ← Back to Success Page
            </a>
        </div>
    </div>
    
    <script>
        // Auto-trigger print dialog when page loads
        window.addEventListener('load', function() {
            // Small delay to ensure page is fully rendered
            setTimeout(function() {
                if (window.location.search.includes('autoprint=1')) {
                    window.print();
                }
            }, 500);
        });
    </script>
</body>
</html>
