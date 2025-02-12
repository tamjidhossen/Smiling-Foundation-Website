<?php require_once 'config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Making Lives Better</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php $heroImage = getHeroImage('home'); ?>
    <style>
        .hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content fade-in">
                <h1>Welcome to <?php echo SITE_NAME; ?></h1>
                <p class="slide-in">Together, we can create lasting change</p>
                <div class="hero-buttons">
                    <a href="pages/donate.php" class="cta-button">Donate Now</a>
                </div>
            </div>
        </section>

        <!-- Impact Stats Section -->
        <section class="impact-section">
            <div class="container">
                <div class="impact-grid">
                    <div class="impact-card fade-in">
                        <i class="fas fa-hands-helping"></i>
                        <h3>10,000+</h3>
                        <p>Lives Impacted</p>
                    </div>
                    <div class="impact-card fade-in">
                        <i class="fas fa-project-diagram"></i>
                        <h3>50+</h3>
                        <p>Projects Completed</p>
                    </div>
                    <div class="impact-card fade-in">
                        <i class="fas fa-users"></i>
                        <h3>1,000+</h3>
                        <p>Volunteers</p>
                    </div>
                    <div class="impact-card fade-in">
                        <i class="fas fa-globe-asia"></i>
                        <h3>20+</h3>
                        <p>Communities Served</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Projects Section -->
        <section class="featured-projects">
            <div class="container">
                <h2 class="section-title slide-in">Our Impact Areas</h2>
                <div class="project-grid">
                    <?php
                    $projects = loadJsonData('projects.json')['projects'];
                    $featured_projects = array_slice($projects, 0, 3); // Show only 3 projects
                    foreach ($featured_projects as $project): ?>
                        <div class="project-card fade-in">
                            <div class="project-image">
                                <img src="assets/img/projects/<?php echo $project['image']; ?>" 
                                     alt="<?php echo $project['title']; ?>">
                            </div>
                            <div class="project-content">
                                <h3><?php echo $project['title']; ?></h3>
                                <p><?php echo $project['description']; ?></p>
                                <a href="pages/projects.php" class="read-more">Learn More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center">
                    <a href="pages/projects.php" class="cta-button secondary">View All Projects</a>
                </div>
            </div>
        </section>

        <!-- Mission & Goals Section -->
        <section class="mission-section">
            <div class="container">
                <h2 class="section-title slide-in">Our Mission & Goals</h2>
                <div class="mission-grid">
                    <div class="mission-card fade-in">
                        <i class="fas fa-heart"></i>
                        <h3>Our Mission</h3>
                        <p>To create positive change through sustainable community development and empower underprivileged communities.</p>
                    </div>
                    <div class="mission-card fade-in">
                        <i class="fas fa-bullseye"></i>
                        <h3>Our Vision</h3>
                        <p>A world where every individual has access to basic necessities and opportunities for growth and development.</p>
                    </div>
                    <div class="mission-card fade-in">
                        <i class="fas fa-hands-helping"></i>
                        <h3>Our Approach</h3>
                        <p>Working directly with communities to implement sustainable solutions and create lasting positive impact.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Call to Action Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content fade-in">
                    <h2>Make a Difference Today</h2>
                    <p>Your support can help us create lasting change in communities</p>
                    <div class="cta-buttons">
                        <a href="pages/donate.php" class="cta-button">Donate Now</a>
                        <a href="pages/volunteer.php" class="cta-button secondary">Register Now</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>