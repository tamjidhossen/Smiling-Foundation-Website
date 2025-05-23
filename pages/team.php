<?php 
require_once '../config/config.php'; 
require_once '../config/database.php';

// Get team members from database
$conn = get_database_connection();
$sql = "SELECT * FROM team_members ORDER BY type, created_at ASC";
$result = mysqli_query($conn, $sql);
$team_members = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Separate board members and core team
$board_members = array_filter($team_members, function($member) {
    return $member['type'] === 'board';
});

$core_team = array_filter($team_members, function($member) {
    return $member['type'] === 'core';
});

// Function to format LinkedIn URL
function formatLinkedInUrl($url) {
    if (empty($url)) return '';
    
    // If URL doesn't start with http:// or https://, add https://
    if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
        // Check if it starts with www.
        if (strpos($url, 'www.') === 0) {
            return 'https://' . $url;
        } else {
            return 'https://www.' . $url;
        }
    }
    
    return $url;
}

mysqli_close($conn);
?> 

<!DOCTYPE html>
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Our Team - <?php echo SITE_NAME; ?></title> 
    <link rel="stylesheet" href="../assets/css/style.css"> 
    <link rel="stylesheet" href="../assets/css/animations.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head> 
<body> 
    <?php include '../includes/header.php'; ?>

    <?php $heroImage = getHeroImage('team'); ?>
    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
<main>
    <section class="page-hero">
        <div class="hero-content fade-in">
            <h1>Our Team</h1>
            <p>Meet the dedicated people behind our mission</p>
        </div>
    </section>

    <section class="team-section">
        <div class="container">
            <h2 class="section-title slide-in">Board Members</h2>
            <div class="team-grid">
                <?php if (!empty($board_members)): ?>
                    <?php foreach ($board_members as $member): ?>
                        <div class="team-card fade-in">
                            <div class="member-image">
                                <?php if ($member['image']): ?>
                                    <img src="../assets/img/team/<?php echo htmlspecialchars($member['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($member['name']); ?>">
                                <?php else: ?>
                                    <div style="background: #ddd; width: 100%; height: 200px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="font-size: 3rem; color: #999;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="member-info">
                                <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                                <p class="position"><?php echo htmlspecialchars($member['position']); ?></p>
                                <?php if ($member['bio']): ?>
                                    <p class="bio"><?php echo htmlspecialchars($member['bio']); ?></p>
                                <?php endif; ?>
                                <div class="social-links">
                                    <?php if ($member['linkedin']): ?>
                                        <a href="<?php echo htmlspecialchars(formatLinkedInUrl($member['linkedin'])); ?>" target="_blank">
                                            <i class="fab fa-linkedin"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-members">
                        <p>No board members found.</p>
                    </div>
                <?php endif; ?>
            </div>

            <h2 class="section-title slide-in">Core Team</h2>
            <div class="team-grid">
                <?php if (!empty($core_team)): ?>
                    <?php foreach ($core_team as $member): ?>
                        <div class="team-card fade-in">
                            <div class="member-image">
                                <?php if ($member['image']): ?>
                                    <img src="../assets/img/team/<?php echo htmlspecialchars($member['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($member['name']); ?>">
                                <?php else: ?>
                                    <div style="background: #ddd; width: 100%; height: 200px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="font-size: 3rem; color: #999;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="member-info">
                                <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                                <p class="position"><?php echo htmlspecialchars($member['position']); ?></p>
                                <?php if ($member['bio']): ?>
                                    <p class="bio"><?php echo htmlspecialchars($member['bio']); ?></p>
                                <?php endif; ?>
                                <div class="social-links">
                                    <?php if ($member['linkedin']): ?>
                                        <a href="<?php echo htmlspecialchars(formatLinkedInUrl($member['linkedin'])); ?>" target="_blank">
                                            <i class="fab fa-linkedin"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-members">
                        <p>No core team members found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
</body> 
</html>