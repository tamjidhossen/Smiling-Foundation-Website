<?php
session_start();
require_once '../config/database.php';
require_once '../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . ADMIN_URL . '/login.php');
    exit;
}

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get statistics
$stats = array();

// Projects stats
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM projects");
$stats['projects'] = mysqli_fetch_assoc($result)['count'];

$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM projects WHERE status = 'ongoing'");
$stats['ongoing_projects'] = mysqli_fetch_assoc($result)['count'];

// Blog stats
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM blogs");
$stats['blogs'] = mysqli_fetch_assoc($result)['count'];

// Team stats
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM team_members");
$stats['team_members'] = mysqli_fetch_assoc($result)['count'];

// Volunteer stats
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM volunteers");
$stats['volunteers'] = mysqli_fetch_assoc($result)['count'];

$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM volunteers WHERE status = 'pending'");
$stats['pending_volunteers'] = mysqli_fetch_assoc($result)['count'];

// Donation stats
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM donations");
$stats['total_donations'] = mysqli_fetch_assoc($result)['count'];

$result = mysqli_query($conn, "SELECT SUM(amount_bdt) as total FROM donations WHERE payment_status = 'completed'");
$total_amount_result = mysqli_fetch_assoc($result);
$stats['total_amount_bdt'] = $total_amount_result['total'] ?? 0;

$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM donations WHERE payment_status = 'pending'");
$stats['pending_donations'] = mysqli_fetch_assoc($result)['count'];

// Get recent projects
$recent_projects = mysqli_query($conn, "SELECT * FROM projects ORDER BY id DESC LIMIT 5");

// Get recent donations
$recent_donations = mysqli_query($conn, "SELECT * FROM donations ORDER BY id DESC LIMIT 5");

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-dashboard">
        <?php include 'includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="dashboard-header">
                <h1>Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-project-diagram"></i>
                    <div class="stat-content">
                        <h3>Total Projects</h3>
                        <p><?php echo $stats['projects']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-tasks"></i>
                    <div class="stat-content">
                        <h3>Ongoing Projects</h3>
                        <p><?php echo $stats['ongoing_projects']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-blog"></i>
                    <div class="stat-content">
                        <h3>Total Blogs</h3>
                        <p><?php echo $stats['blogs']; ?></p>
                    </div>
                </div>                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="stat-content">
                        <h3>Team Members</h3>
                        <p><?php echo $stats['team_members']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-hands-helping"></i>
                    <div class="stat-content">
                        <h3>Total Volunteers</h3>
                        <p><?php echo $stats['volunteers']; ?></p>
                    </div>
                </div>                <div class="stat-card">
                    <i class="fas fa-clock"></i>
                    <div class="stat-content">
                        <h3>Pending Applications</h3>
                        <p><?php echo $stats['pending_volunteers']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-heart"></i>
                    <div class="stat-content">
                        <h3>Total Donations</h3>
                        <p><?php echo $stats['total_donations']; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-dollar-sign"></i>
                    <div class="stat-content">
                        <h3>Total Raised (BDT)</h3>
                        <p>৳<?php echo number_format($stats['total_amount_bdt'], 2); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-hourglass-half"></i>
                    <div class="stat-content">
                        <h3>Pending Donations</h3>
                        <p><?php echo $stats['pending_donations']; ?></p>
                    </div>
                </div>
            </div>            <div class="dashboard-content">
                <div class="dashboard-grid">
                    <div class="recent-projects">
                        <h2>Recent Projects</h2>
                        <div class="projects-list">
                            <?php if (mysqli_num_rows($recent_projects) > 0): ?>
                                <?php while ($project = mysqli_fetch_assoc($recent_projects)): ?>
                                    <div class="project-item">
                                        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                                        <span class="status <?php echo $project['status']; ?>">
                                            <?php echo ucfirst($project['status']); ?>
                                        </span>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No projects found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="recent-donations">
                        <h2>Recent Donations</h2>
                        <div class="donations-list">
                            <?php if (mysqli_num_rows($recent_donations) > 0): ?>
                                <?php while ($donation = mysqli_fetch_assoc($recent_donations)): ?>
                                    <div class="donation-item">
                                        <div class="donation-info">
                                            <h4>
                                                <?php 
                                                if ($donation['is_anonymous']) {
                                                    echo "Anonymous Donor";
                                                } else {
                                                    echo htmlspecialchars($donation['donor_name']);
                                                }
                                                ?>
                                            </h4>
                                            <p><?php echo htmlspecialchars($donation['purpose'] ?? 'General'); ?></p>
                                        </div>
                                        <div class="donation-amount">
                                            <span class="amount">৳<?php echo number_format($donation['amount_bdt'], 2); ?></span>
                                            <span class="status <?php echo $donation['payment_status']; ?>">
                                                <?php echo ucfirst($donation['payment_status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No donations found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>