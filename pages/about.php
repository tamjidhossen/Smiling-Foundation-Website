<?php require_once '../config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/animations.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php $heroImage = getHeroImage('about'); ?>
    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
    <main>
        <section class="page-hero">
            <div class="hero-content fade-in">
                <h1>Our Mission</h1>
                <p>To create positive change through sustainable community development and support.</p>
            </div>
        </section>

        <section class="about-vision">
            <div class="container">
                <h2 class="slide-in">Our Vision</h2>
                <p class="fade-in">A world where every individual has access to basic necessities and opportunities for growth.</p>
            </div>
        </section>

        <section class="about-values">
            <div class="container">
                <h2 class="slide-in">Our Values</h2>
                <div class="values-grid">
                    <div class="value-card fade-in">
                        <h3>Compassion</h3>
                        <p>We act with kindness and empathy in all our endeavors.</p>
                    </div>
                    <div class="value-card fade-in">
                        <h3>Integrity</h3>
                        <p>We maintain high ethical standards and transparency.</p>
                    </div>
                    <div class="value-card fade-in">
                        <h3>Impact</h3>
                        <p>We focus on creating lasting positive change.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/main.js"></script>
</body>
</html>