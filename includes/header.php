<header>
    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo SITE_URL; ?>" class="logo">
                <img src="<?php echo SITE_URL; ?>/assets/img/logo/logo.png" alt="<?php echo SITE_NAME; ?> Logo">
                <h1><?php echo SITE_NAME; ?></h1>
            </a>
            <button class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <!-- Navigation links in center -->
            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="<?php echo SITE_URL; ?>" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/projects.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'projects.php' ? 'active' : ''; ?>">Projects</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/blog.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'blog.php' ? 'active' : ''; ?>">Blog</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/gallery.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'gallery.php' ? 'active' : ''; ?>">Gallery</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/about.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active' : ''; ?>">About</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/team.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'team.php' ? 'active' : ''; ?>">Team</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                </ul>
            </div>

            <!-- Action buttons on the right -->
            <div class="nav-actions">
                <a href="<?php echo SITE_URL; ?>/pages/volunteer.php" class="cta-button volunteer">Volunteer</a>
                <a href="<?php echo SITE_URL; ?>/pages/donate.php" class="cta-button secondary">Donate</a>
            </div>
        </div>
    </nav>
</header>