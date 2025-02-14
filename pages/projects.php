<?php
require_once '../config/config.php';
require_once '../config/database.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Get projects from database
$query = "SELECT * FROM projects";
$result = mysqli_query($conn, $query);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>

<html lang="en"> 
    <head> 
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>Projects - <?php echo SITE_NAME; ?></title> 
        <link rel="stylesheet" href="../assets/css/style.css"> 
        <link rel="stylesheet" href="../assets/css/animations.css"> 
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> 
    </head> 
    <body> 
    <?php include '../includes/header.php'; ?>
    <?php $heroImage = getHeroImage('projects'); ?>
    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
    <main>
    <section class="page-hero">
        <div class="hero-content fade-in">
            <h1>Our Projects</h1>
            <p>Making a difference through sustainable initiatives</p>
        </div>
    </section>

    <section class="projects-grid">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="ongoing">Ongoing</button>
                <button class="filter-btn" data-filter="completed">Completed</button>
            </div>
            <div class="project-cards">
                <?php foreach ($projects as $project): ?>
                    <div class="project-card fade-in" data-status="<?php echo htmlspecialchars($project['status']); ?>">
                        <div class="project-image">
                            <img src="../assets/img/projects/<?php echo htmlspecialchars($project['image']); ?>" 
                                alt="<?php echo htmlspecialchars($project['title']); ?>">
                        </div>
                        <div class="project-details">
                            <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                            <p><?php echo htmlspecialchars($project['description']); ?></p>
                            <span class="project-status <?php echo $project['status']; ?>">
                                <?php echo ucfirst($project['status']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/projects.js"></script>
</body> </html>