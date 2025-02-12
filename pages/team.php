<?php require_once '../config/config.php'; ?> <!DOCTYPE html>

<html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Our Team - <?php echo SITE_NAME; ?></title> <link rel="stylesheet" href="../assets/css/style.css"> <link rel="stylesheet" href="../assets/css/animations.css"> <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> </head> <body> 
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
                <?php
                $team = loadJsonData('team.json')['board_members'] ?? [];
                foreach ($team as $member): ?>
                    <div class="team-card fade-in">
                        <div class="member-image">
                            <img src="../assets/img/team/<?php echo $member['image']; ?>" 
                                 alt="<?php echo $member['name']; ?>">
                        </div>
                        <div class="member-info">
                            <h3><?php echo $member['name']; ?></h3>
                            <p class="position"><?php echo $member['position']; ?></p>
                            <p class="bio"><?php echo $member['bio']; ?></p>
                            <div class="social-links">
                                <?php if(isset($member['linkedin'])): ?>
                                    <a href="<?php echo $member['linkedin']; ?>" target="_blank">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2 class="section-title slide-in">Core Team</h2>
            <div class="team-grid">
                <?php
                $coreTeam = loadJsonData('team.json')['core_team'] ?? [];
                foreach ($coreTeam as $member): ?>
                    <div class="team-card fade-in">
                        <div class="member-image">
                            <img src="../assets/img/team/<?php echo $member['image']; ?>" 
                                 alt="<?php echo $member['name']; ?>">
                        </div>
                        <div class="member-info">
                            <h3><?php echo $member['name']; ?></h3>
                            <p class="position"><?php echo $member['position']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
</body> </html>