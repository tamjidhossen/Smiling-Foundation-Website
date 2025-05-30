<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    echo "Unauthorized access";
    exit;
}

$volunteer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($volunteer_id <= 0) {
    echo "Invalid volunteer ID";
    exit;
}

$conn = get_database_connection();

$sql = "SELECT * FROM volunteers WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $volunteer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "Volunteer not found";
    exit;
}

$volunteer = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<h2>Volunteer Application Details</h2>

<div class="volunteer-detail-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 1.5rem;">
    <div class="detail-section">
        <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Personal Information</h3>
        <div class="detail-item">
            <strong>Full Name:</strong>
            <span><?php echo htmlspecialchars($volunteer['name']); ?></span>
        </div>
        <div class="detail-item">
            <strong>Email:</strong>
            <span><?php echo htmlspecialchars($volunteer['email']); ?></span>
        </div>
        <div class="detail-item">
            <strong>Phone:</strong>
            <span><?php echo htmlspecialchars($volunteer['phone']); ?></span>
        </div>
        <div class="detail-item">
            <strong>NID Number:</strong>
            <span><?php echo htmlspecialchars($volunteer['nid']); ?></span>
        </div>
        <?php if (!empty($volunteer['facebook'])): ?>
        <div class="detail-item">
            <strong>Facebook:</strong>
            <span><a href="<?php echo htmlspecialchars($volunteer['facebook']); ?>" target="_blank"><?php echo htmlspecialchars($volunteer['facebook']); ?></a></span>
        </div>
        <?php endif; ?>
    </div>

    <div class="detail-section">
        <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Professional Information</h3>
        <div class="detail-item">
            <strong>Occupation:</strong>
            <span><?php echo htmlspecialchars(ucfirst($volunteer['occupation'])); ?></span>
        </div>
        <div class="detail-item">
            <strong>Volunteer Type:</strong>
            <span><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $volunteer['volunteer_type']))); ?></span>
        </div>
        <?php if (!empty($volunteer['special_skills'])): ?>
        <div class="detail-item">
            <strong>Special Skills:</strong>
            <span><?php echo htmlspecialchars($volunteer['special_skills']); ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="detail-section" style="margin-top: 2rem;">
    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Address Information</h3>
    <div class="detail-item">
        <strong>Division:</strong>
        <span><?php echo htmlspecialchars(ucfirst($volunteer['present_division'])); ?></span>
    </div>
    <div class="detail-item">
        <strong>Full Address:</strong>
        <span><?php echo htmlspecialchars($volunteer['present_address']); ?></span>
    </div>
</div>

<div class="detail-section" style="margin-top: 2rem;">
    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Application Status</h3>
    <div class="detail-item">
        <strong>Current Status:</strong>
        <span class="status <?php echo $volunteer['status']; ?>" style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
            <?php echo ucfirst($volunteer['status']); ?>
        </span>
    </div>
    <div class="detail-item">
        <strong>Submitted Date:</strong>
        <span><?php echo date('F j, Y \a\t g:i A', strtotime($volunteer['submitted_at'])); ?></span>
    </div>
    <?php if ($volunteer['status'] === 'approved' && $volunteer['approved_at']): ?>
    <div class="detail-item">
        <strong>Approved Date:</strong>
        <span><?php echo date('F j, Y \a\t g:i A', strtotime($volunteer['approved_at'])); ?></span>
    </div>
    <?php endif; ?>
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
    min-width: 120px;
    flex-shrink: 0;
}

.detail-item span {
    color: #6b7280;
    text-align: right;
    word-break: break-word;
}

.detail-item a {
    color: var(--primary-color);
    text-decoration: none;
}

.detail-item a:hover {
    text-decoration: underline;
}

.status.pending {
    background: #fef3c7;
    color: #92400e;
}

.status.approved {
    background: #d1fae5;
    color: #065f46;
}

.status.rejected {
    background: #fee2e2;
    color: #991b1b;
}
</style>
