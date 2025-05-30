<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$conn = get_database_connection();

// Get donations with pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

$purpose_filter = isset($_GET['purpose']) ? $_GET['purpose'] : 'all';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

$where_conditions = [];
if ($purpose_filter !== 'all') {
    $where_conditions[] = "purpose = '" . mysqli_real_escape_string($conn, $purpose_filter) . "'";
}
if ($status_filter !== 'all') {
    $where_conditions[] = "payment_status = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get total count
$count_sql = "SELECT COUNT(*) as total FROM donations $where_clause";
$count_result = mysqli_query($conn, $count_sql);
$total_donations = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_donations / $limit);

// Get donations
$sql = "SELECT * FROM donations $where_clause ORDER BY donated_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Get statistics
$stats_sql = "SELECT 
    COUNT(*) as total_donations,
    SUM(amount_usd) as total_usd,
    SUM(amount_bdt) as total_bdt,
    COUNT(CASE WHEN payment_status = 'completed' THEN 1 END) as completed_donations,
    COUNT(CASE WHEN payment_status = 'pending' THEN 1 END) as pending_donations
FROM donations";
$stats_result = mysqli_query($conn, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);

// Get purpose-wise statistics
$purpose_sql = "SELECT purpose, COUNT(*) as count, SUM(amount_usd) as total_usd, SUM(amount_bdt) as total_bdt 
                FROM donations 
                WHERE payment_status = 'completed' 
                GROUP BY purpose 
                ORDER BY total_usd DESC";
$purpose_result = mysqli_query($conn, $purpose_sql);

// Get all purposes for filter
$purposes_sql = "SELECT DISTINCT purpose FROM donations ORDER BY purpose";
$purposes_result = mysqli_query($conn, $purposes_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations Management - Admin Panel</title>    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-dashboard">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="page-header">
                <h1>Donations Management</h1>                <div class="filter-options">
                    <form method="GET" class="filter-form">
                        <select name="purpose" onchange="this.form.submit()" class="filter-select">
                            <option value="all" <?php echo $purpose_filter === 'all' ? 'selected' : ''; ?>>All Purposes</option>
                            <?php while ($purpose_row = mysqli_fetch_assoc($purposes_result)): ?>
                                <option value="<?php echo htmlspecialchars($purpose_row['purpose']); ?>" 
                                        <?php echo $purpose_filter === $purpose_row['purpose'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($purpose_row['purpose']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <select name="status" onchange="this.form.submit()" class="filter-select">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="failed" <?php echo $status_filter === 'failed' ? 'selected' : ''; ?>>Failed</option>
                        </select>
                        <input type="hidden" name="page" value="<?php echo $page; ?>">
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['total_donations']); ?></h3>
                        <p>Total Donations</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>                    <div class="stat-content">
                        <h3>$<?php echo number_format($stats['total_usd'] ?? 0, 2); ?></h3>
                        <p>Total in USD</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-content">
                        <h3>৳<?php echo number_format($stats['total_bdt'] ?? 0, 2); ?></h3>
                        <p>Total in BDT</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['completed_donations']); ?></h3>
                        <p>Completed</p>
                    </div>
                </div>
            </div>

            <!-- Purpose-wise Statistics -->
            <div class="purpose-stats">
                <h2>Donations by Purpose</h2>
                <div class="purpose-grid">
                    <?php mysqli_data_seek($purpose_result, 0); ?>
                    <?php while ($purpose_stat = mysqli_fetch_assoc($purpose_result)): ?>
                        <div class="purpose-card">
                            <h3><?php echo htmlspecialchars($purpose_stat['purpose']); ?></h3>
                            <div class="purpose-amount">
                                <span class="usd">$<?php echo number_format($purpose_stat['total_usd'], 2); ?></span>
                                <span class="bdt">৳<?php echo number_format($purpose_stat['total_bdt'], 2); ?></span>
                            </div>
                            <div class="purpose-count"><?php echo $purpose_stat['count']; ?> donation(s)</div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Donations Table -->
            <div class="donations-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Donor</th>
                            <th>Amount</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($donation = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td>#<?php echo $donation['id']; ?></td>
                                    <td>
                                        <?php if ($donation['is_anonymous']): ?>
                                            <em>Anonymous</em>
                                        <?php else: ?>
                                            <?php echo htmlspecialchars($donation['donor_name']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="amount-display">
                                            <strong>$<?php echo number_format($donation['amount_usd'], 2); ?></strong>
                                            <small>৳<?php echo number_format($donation['amount_bdt'], 2); ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($donation['purpose']); ?></td>
                                    <td>
                                        <span class="status <?php echo $donation['payment_status']; ?>">
                                            <?php echo ucfirst($donation['payment_status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($donation['donated_at'])); ?></td>
                                    <td>
                                        <button class="action-btn view" onclick="viewDonation(<?php echo $donation['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>                            <tr>
                                <td colspan="7" class="no-data-message">
                                    No donations found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&purpose=<?php echo urlencode($purpose_filter); ?>&status=<?php echo urlencode($status_filter); ?>" 
                           class="page-link <?php echo $i === $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Donation Details Modal -->
    <div id="donationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="donationDetails"></div>
        </div>
    </div>

    <script>
        function viewDonation(id) {
            fetch(`donation_details.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('donationDetails').innerHTML = data;
                    document.getElementById('donationModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load donation details');
                });
        }

        // Close modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('donationModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('donationModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
