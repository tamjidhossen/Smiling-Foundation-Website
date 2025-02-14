<nav class="admin-nav">
    <div class="admin-sidebar">
        <div class="admin-header">
            <h3>Admin Panel</h3>
            <p>Welcome, <?php echo $_SESSION['admin_username']; ?></p>
        </div>
        <ul class="admin-menu">
            <li><a href="<?php echo ADMIN_URL; ?>/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a></li>
            <li><a href="<?php echo ADMIN_URL; ?>/about.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active' : ''; ?>">
                <i class="fas fa-info-circle"></i> About Content
            </a></li>
            <li><a href="<?php echo ADMIN_URL; ?>/projects.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'projects.php' ? 'active' : ''; ?>">
                <i class="fas fa-project-diagram"></i> Projects
            </a></li>
            <li><a href="<?php echo ADMIN_URL; ?>/blogs.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'blogs.php' ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i> Blogs
            </a></li>
            <li><a href="<?php echo ADMIN_URL; ?>/team.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'team.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Team
            </a></li>
            <li><a href="<?php echo ADMIN_URL; ?>/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a></li>
        </ul>
    </div>
</nav>