<?php 
require_once '../config/config.php';
require_once '../config/database.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Get about content
$query = "SELECT * FROM about_content WHERE is_deleted = 0";
$result = mysqli_query($conn, $query);
$about_content = array();
while ($row = mysqli_fetch_assoc($result)) {
    $about_content[$row['section']] = $row;
}

// Default values if data is missing
$mission_title = isset($about_content['mission']) ? $about_content['mission']['title'] : 'Our Mission';
$mission_content = isset($about_content['mission']) ? $about_content['mission']['content'] : 'To create positive change through sustainable community development and support.';
$vision_title = isset($about_content['vision']) ? $about_content['vision']['title'] : 'Our Vision';
$vision_content = isset($about_content['vision']) ? $about_content['vision']['content'] : 'Vision content coming soon';
$values_title = isset($about_content['values']) ? $about_content['values']['title'] : 'Our Values';
$values_content = isset($about_content['values']) ? json_decode($about_content['values']['content'], true) : [];

if (empty($values_content)) {
    $values_content = [
        ['title' => 'Integrity', 'description' => 'We maintain highest ethical standards'],
        ['title' => 'Compassion', 'description' => 'We serve with empathy and understanding'],
        ['title' => 'Excellence', 'description' => 'We strive for quality in everything we do']
    ];
}

mysqli_close($conn);
?>

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
                <h1><?php echo htmlspecialchars($mission_title); ?></h1>
                <p><?php echo htmlspecialchars($mission_content); ?></p>
            </div>
        </section>

        <section class="about-vision">
            <div class="container">
                <h2 class="slide-in"><?php echo htmlspecialchars($vision_title); ?></h2>
                <p class="fade-in"><?php echo htmlspecialchars($vision_content); ?></p>
            </div>
        </section>

        <section class="about-values">
            <div class="container">
                <h2 class="slide-in"><?php echo htmlspecialchars($values_title); ?></h2>
                <div class="values-grid">
                    <?php foreach ($values_content as $value): ?>
                        <div class="value-card fade-in">
                            <h3><?php echo htmlspecialchars($value['title']); ?></h3>
                            <p><?php echo htmlspecialchars($value['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/main.js"></script>
</body>
</html>