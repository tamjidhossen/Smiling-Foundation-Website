<?php
require_once 'config/config.php';
require_once 'config/database.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Get impact stats
$query = "SELECT * FROM impact_stats";
$result = mysqli_query($conn, $query);
$impact_stats = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get featured projects (ongoing only, limit 3)
$query = "SELECT * FROM projects WHERE is_deleted = 0 ORDER BY id DESC LIMIT 3";
$result = mysqli_query($conn, $query);
$featured_projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get mission content
$query = "SELECT * FROM mission_content";
$result = mysqli_query($conn, $query);
$mission_content = array();
while ($row = mysqli_fetch_assoc($result)) {
    $mission_content[$row['type']] = $row;
}
?>

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

        <!-- Our impact areas -->
        <section class="featured-projects">
            <div class="container">
                <h2 class="section-title slide-in">Our Impact Areas</h2>
                <div class="project-grid">
                    <?php if (empty($featured_projects)): ?>
                        <div class="no-projects">
                            <p>No ongoing projects at the moment.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($featured_projects as $project): ?>
                            <div class="project-card fade-in">
                                <div class="project-image">
                                    <img src="assets/img/projects/<?php echo htmlspecialchars($project['image']); ?>" 
                                        alt="<?php echo htmlspecialchars($project['title']); ?>">
                                </div>
                                <div class="project-content">
                                    <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                                    <p style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars($project['description']); ?></p>
                                    <a href="pages/project-detail.php?id=<?php echo $project['id']; ?>" class="read-more">
                                        Learn More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="text-center" style="margin-top: 2rem;">
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