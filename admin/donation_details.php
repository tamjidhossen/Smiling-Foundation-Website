<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    echo "Unauthorized access";
    exit;
}

$donation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($donation_id <= 0) {
    echo "Invalid donation ID";
    exit;
}

$conn = get_database_connection();

$sql = "SELECT * FROM donations WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $donation_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "Donation not found";
    exit;
}

$donation = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<h2>Donation Details</h2>

<div class="donation-detail-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 1.5rem;">
    <div class="detail-section">
        <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Transaction Information</h3>
        <div class="detail-item">
            <strong>Transaction ID:</strong>
            <span><?php echo htmlspecialchars($donation['transaction_id']); ?></span>
        </div>
        <div class="detail-item">
            <strong>Amount (USD):</strong>
            <span style="color: #059669; font-weight: 600;">$<?php echo number_format($donation['amount_usd'], 2); ?></span>
        </div>
        <div class="detail-item">
            <strong>Amount (BDT):</strong>
            <span style="color: #059669; font-weight: 600;">৳<?php echo number_format($donation['amount_bdt'], 2); ?></span>
        </div>
        <div class="detail-item">
            <strong>Purpose:</strong>
            <span><?php echo htmlspecialchars($donation['purpose']); ?></span>
        </div>
        <div class="detail-item">
            <strong>Payment Status:</strong>
            <span class="status <?php echo $donation['payment_status']; ?>" style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                <?php echo ucfirst($donation['payment_status']); ?>
            </span>
        </div>
        <?php if (!empty($donation['payment_method'])): ?>
        <div class="detail-item">
            <strong>Payment Method:</strong>
            <span><?php echo htmlspecialchars($donation['payment_method']); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <div class="detail-section">
        <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Donor Information</h3>
        <?php if (!$donation['is_anonymous']): ?>
        <div class="detail-item">
            <strong>Name:</strong>
            <span><?php echo htmlspecialchars($donation['donor_name']); ?></span>
        </div>
        <div class="detail-item">
            <strong>Email:</strong>
            <span><?php echo htmlspecialchars($donation['email']); ?></span>
        </div>
        <?php if (!empty($donation['phone'])): ?>
        <div class="detail-item">
            <strong>Phone:</strong>
            <span><?php echo htmlspecialchars($donation['phone']); ?></span>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="detail-item">
            <strong>Donor:</strong>
            <span style="font-style: italic; color: #6b7280;">Anonymous Donation</span>
        </div>
        <?php endif; ?>
        <div class="detail-item">
            <strong>Anonymous:</strong>
            <span><?php echo $donation['is_anonymous'] ? 'Yes' : 'No'; ?></span>
        </div>
        <div class="detail-item">
            <strong>Receipt Generated:</strong>
            <span><?php echo $donation['receipt_generated'] ? 'Yes' : 'No'; ?></span>
        </div>
    </div>
</div>

<div class="detail-section" style="margin-top: 2rem;">
    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Timeline</h3>
    <div class="detail-item">
        <strong>Donation Date:</strong>
        <span><?php echo date('F j, Y \a\t g:i A', strtotime($donation['donated_at'])); ?></span>
    </div>
    <div class="detail-item">
        <strong>Created Date:</strong>
        <span><?php echo date('F j, Y \a\t g:i A', strtotime($donation['created_at'])); ?></span>
    </div>
    <div class="detail-item">
        <strong>Last Updated:</strong>
        <span><?php echo date('F j, Y \a\t g:i A', strtotime($donation['updated_at'])); ?></span>
    </div>
</div>

<?php if (!empty($donation['message'])): ?>
<div class="detail-section" style="margin-top: 2rem;">
    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Donor Message</h3>
    <div class="message-box" style="background: #f8fafc; padding: 1rem; border-radius: 8px; border-left: 4px solid var(--primary-color);">
        <p style="margin: 0; color: #374151; line-height: 1.6; font-style: italic;">
            "<?php echo htmlspecialchars($donation['message']); ?>"
        </p>
    </div>
</div>
<?php endif; ?>

<div class="detail-actions" style="margin-top: 2rem; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 1.5rem;">
    <a href="../pages/receipt.php?id=<?php echo $donation['id']; ?>&txn=<?php echo urlencode($donation['transaction_id']); ?>" 
       target="_blank" 
       class="action-btn view" 
       style="padding: 0.75rem 1.5rem; text-decoration: none; background: #2563eb; color: white; border-radius: 6px; margin: 0 0.5rem;">
        <i class="fas fa-receipt"></i> View Receipt
    </a>
</div>

<style>
.detail-section {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item strong {
    color: #374151;
    min-width: 140px;
    flex-shrink: 0;
}

.detail-item span {
    color: #6b7280;
    text-align: right;
    word-break: break-word;
}

.status.completed {
    background: #d1fae5;
    color: #065f46;
}

.status.pending {
    background: #fef3c7;
    color: #92400e;
}

.status.failed {
    background: #fee2e2;
    color: #991b1b;
}

.action-btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

@media (max-width: 768px) {
    .donation-detail-grid {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    
    .detail-actions {
        text-align: center;
    }
    
    .detail-actions .action-btn {
        display: block;
        margin: 0.5rem auto !important;
        max-width: 250px;
    }
}
</style>
