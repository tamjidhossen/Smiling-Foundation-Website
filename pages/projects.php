<?php
require_once '../config/config.php';
require_once '../config/database.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Auto-update project status based on end date
$update_query = "UPDATE projects 
                SET status = 'completed' 
                WHERE end_date IS NOT NULL 
                AND end_date <= CURDATE() 
                AND status = 'ongoing'";
mysqli_query($conn, $update_query);

// Get projects from database
$query = "SELECT * FROM projects ORDER BY id DESC";
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
    <?php $heroImage = getHeroImage('projects'); ?>    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
        
        .project-status {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: capitalize;
        }
        
        .project-status.ongoing {
            background: #e3f2fd;
            color: #1976d2;
            border: 1px solid #bbdefb;
        }
        
        .project-status.completed {
            background: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        
        .project-status.paused {
            background: #fff3e0;
            color: #f57c00;
            border: 1px solid #ffcc02;
        }
        
        .project-status.cancelled {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
          .project-end-info {
            font-size: 0.85em;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid currentColor;
            width: fit-content;
        }
        
        .project-end-info i {
            font-size: 1em;
        }
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
                                <p class="project-excerpt"><?php echo substr(htmlspecialchars($project['description']), 0, 150) . '...'; ?></p>                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span class="project-status <?php echo $project['status']; ?>">
                                        <?php echo ucfirst($project['status']); ?>
                                    </span>
                                    <a href="project-detail.php?id=<?php echo $project['id']; ?>" class="read-more">
                                        Learn More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/project.js"></script>
</body> </html>