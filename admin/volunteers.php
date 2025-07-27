<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$conn = get_database_connection();

// Handle status update
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $volunteer_id = intval($_POST['volunteer_id']);
    $status = $_POST['status'];
    
    if (in_array($status, ['pending', 'approved', 'rejected'])) {
        $update_sql = "UPDATE volunteers SET status = ?";
        $params = [$status];
        
        if ($status === 'approved') {
            $update_sql .= ", approved_at = NOW(), approved_by = ?";
            $params[] = $_SESSION['admin_id'];
        }
        
        $update_sql .= " WHERE id = ?";
        $params[] = $volunteer_id;
        
        $stmt = mysqli_prepare($conn, $update_sql);
        
        if ($status === 'approved') {
            mysqli_stmt_bind_param($stmt, "sii", $params[0], $params[1], $params[2]);
        } else {
            mysqli_stmt_bind_param($stmt, "si", $params[0], $params[1]);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            // Send approval email if status is 'approved'
            if ($status === 'approved') {
                try {
                    // Get volunteer data for email
                    $volunteer_query = "SELECT * FROM volunteers WHERE id = ?";
                    $volunteer_stmt = mysqli_prepare($conn, $volunteer_query);
                    mysqli_stmt_bind_param($volunteer_stmt, "i", $volunteer_id);
                    mysqli_stmt_execute($volunteer_stmt);
                    $volunteer_result = mysqli_stmt_get_result($volunteer_stmt);
                    $volunteer_data = mysqli_fetch_assoc($volunteer_result);
                    mysqli_stmt_close($volunteer_stmt);
                    
                    if ($volunteer_data) {
                        require_once '../config/email_config.php';
                        require_once '../includes/SMTPEmailHandler.php';
                        
                        $emailHandler = new EmailHandler();
                        $email_sent = $emailHandler->sendVolunteerApproval($volunteer_data);
                        
                        if ($email_sent) {
                            error_log("Volunteer approval email sent successfully to: " . $volunteer_data['email']);
                            $_SESSION['success_message'] = "Volunteer status updated successfully! Approval email sent.";
                        } else {
                            error_log("Failed to send volunteer approval email to: " . $volunteer_data['email']);
                            $_SESSION['success_message'] = "Volunteer status updated successfully! (Email sending failed)";
                        }
                    }
                    
                } catch (Exception $e) {
                    error_log("Email sending error: " . $e->getMessage());
                    $_SESSION['success_message'] = "Volunteer status updated successfully! (Email sending failed)";
                }
            } else {
                $_SESSION['success_message'] = "Volunteer status updated successfully!";
            }
        } else {
            // Store error message in session
            $_SESSION['error_message'] = "Failed to update volunteer status.";
        }
        mysqli_stmt_close($stmt);
        
        // Redirect to prevent form resubmission (PRG pattern)
        $redirect_url = 'volunteers.php';
        if (isset($_GET['status'])) {
            $redirect_url .= '?status=' . urlencode($_GET['status']);
        }
        if (isset($_GET['page'])) {
            $redirect_url .= (strpos($redirect_url, '?') !== false ? '&' : '?') . 'page=' . intval($_GET['page']);
        }
        header('Location: ' . $redirect_url);
        exit;
    }
}

// Check for session messages
$success_message = '';
$error_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Get volunteers with pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

$where_clause = "";
if ($status_filter !== 'all') {
    $where_clause = "WHERE status = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
}

// Get total count
$count_sql = "SELECT COUNT(*) as total FROM volunteers $where_clause";
$count_result = mysqli_query($conn, $count_sql);
$total_volunteers = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_volunteers / $limit);

// Get volunteers
$sql = "SELECT * FROM volunteers $where_clause ORDER BY submitted_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteers Management - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-dashboard">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="page-header">
                <h1>Volunteers Management</h1>
                <div class="filter-options">
                    <form method="GET" style="display: inline-block;">
                        <select name="status" onchange="this.form.submit()" style="padding: 0.5rem; border-radius: 4px; border: 1px solid #e2e8f0;">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo $status_filter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </form>
                </div>
            </div>            <?php if (!empty($success_message)): ?>
                <div class="alert success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <div class="stats-grid">
                <?php
                $pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM volunteers WHERE status = 'pending'"))['count'];
                $approved_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM volunteers WHERE status = 'approved'"))['count'];
                $rejected_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM volunteers WHERE status = 'rejected'"))['count'];
                ?>
                <div class="stat-card">
                    <h3>Pending Applications</h3>
                    <p><?php echo $pending_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Approved Volunteers</h3>
                    <p><?php echo $approved_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Rejected Applications</h3>
                    <p><?php echo $rejected_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Applications</h3>
                    <p><?php echo $total_volunteers; ?></p>
                </div>
            </div>

            <div class="volunteers-table">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Volunteer Type</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($volunteer = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($volunteer['name']); ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['email']); ?></td>
                                    <td><?php echo htmlspecialchars($volunteer['phone']); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $volunteer['volunteer_type']))); ?></td>
                                    <td>
                                        <span class="status <?php echo $volunteer['status']; ?>">
                                            <?php echo ucfirst($volunteer['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($volunteer['submitted_at'])); ?></td>
                                    <td>
                                        <button onclick="viewVolunteer(<?php echo $volunteer['id']; ?>)" class="action-btn view">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($volunteer['status'] === 'pending'): ?>
                                            <button onclick="updateStatus(<?php echo $volunteer['id']; ?>, 'approved')" class="action-btn approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="updateStatus(<?php echo $volunteer['id']; ?>, 'rejected')" class="action-btn reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: #64748b;">No volunteers found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination" style="text-align: center; margin-top: 2rem;">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>" 
                           class="<?php echo $page === $i ? 'active' : ''; ?>"
                           style="padding: 0.5rem 1rem; margin: 0 0.25rem; text-decoration: none; border: 1px solid #e2e8f0; border-radius: 4px; <?php echo $page === $i ? 'background: var(--primary-color); color: white;' : 'color: var(--primary-color);'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Volunteer Details Modal -->
    <div id="volunteerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="volunteerDetails"></div>
        </div>
    </div>

    <!-- Status Update Form -->
    <form id="statusForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="update_status">
        <input type="hidden" name="volunteer_id" id="statusVolunteerId">
        <input type="hidden" name="status" id="statusValue">
    </form>

    <script>
        function viewVolunteer(id) {
            fetch(`volunteer_details.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('volunteerDetails').innerHTML = data;
                    document.getElementById('volunteerModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load volunteer details');
                });
        }

        function updateStatus(id, status) {
            if (confirm(`Are you sure you want to ${status} this volunteer application?`)) {
                document.getElementById('statusVolunteerId').value = id;
                document.getElementById('statusValue').value = status;
                document.getElementById('statusForm').submit();
            }
        }

        // Close modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('volunteerModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('volunteerModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>

    <style>
        .volunteers-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            overflow-x: auto;
        }

        .volunteers-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .volunteers-table th,
        .volunteers-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .volunteers-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #4a5568;
        }

        .status {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
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

        .action-btn {
            padding: 0.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 0.5rem;
            font-size: 0.875rem;
        }

        .action-btn.view {
            background: #e0e7ff;
            color: #3730a3;
        }

        .action-btn.approve {
            background: #dcfce7;
            color: #166534;
        }

        .action-btn.reject {
            background: #fee2e2;
            color: #991b1b;
        }        .filter-options select {
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        /* Enhanced Modal Styles */
        #volunteerModal .modal-content {
            max-height: 85vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }

        #volunteerModal .modal-content::-webkit-scrollbar {
            width: 8px;
        }

        #volunteerModal .modal-content::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 4px;
        }

        #volunteerModal .modal-content::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }

        #volunteerModal .modal-content::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        #volunteerModal h2 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.75rem;
        }

        #volunteerModal .volunteer-detail-grid {
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            #volunteerModal .modal-content {
                width: 95%;
                margin: 1% auto;
                padding: 1rem;
            }
            
            #volunteerModal .volunteer-detail-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
</body>
</html>

<?php mysqli_close($conn); ?>
