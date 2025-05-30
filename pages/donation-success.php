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
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Donation Successful - <?php echo SITE_NAME; ?></title>    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/animations.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/donation.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="success-section">
            <div class="container">
                <div class="success-content fade-in">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>                    <h1>Thank You for Your Donation!</h1>
                    <p class="success-message">Your generous contribution has been received successfully and will make a real difference in the lives of those we serve.</p>
                    
                    <div class="donation-details">
                        <h3>Donation Details</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <strong>Transaction ID:</strong>
                                <span><?php echo htmlspecialchars($donation['transaction_id']); ?></span>
                            </div>                            <div class="detail-item">
                                <strong>Amount:</strong>
                                <span>৳<?php echo number_format($donation['amount_bdt'], 2); ?></span>
                            </div>
                            <div class="detail-item">
                                <strong>Purpose:</strong>
                                <span><?php echo htmlspecialchars($donation['purpose']); ?></span>
                            </div>
                            <?php if (!$donation['is_anonymous']): ?>
                            <div class="detail-item">
                                <strong>Donor:</strong>
                                <span><?php echo htmlspecialchars($donation['donor_name']); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="detail-item">
                                <strong>Date:</strong>
                                <span><?php echo date('F j, Y \a\t g:i A', strtotime($donation['donated_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="success-actions">
                        <a href="receipt.php?id=<?php echo $donation['id']; ?>&txn=<?php echo urlencode($donation['transaction_id']); ?>" class="cta-button" target="_blank">
                            <i class="fas fa-download"></i> Download Receipt
                        </a>
                        <a href="donate.php" class="cta-button secondary">Make Another Donation</a>
                        <a href="../index.php" class="cta-button outline">Return to Home</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
</body>
</html>
