<?php require_once '../config/config.php'; ?> 
<!DOCTYPE html>

<html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Gallery - <?php echo SITE_NAME; ?></title> <link rel="stylesheet" href="../assets/css/style.css"> <link rel="stylesheet" href="../assets/css/animations.css"> <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> </head> <body> 
    <?php include '../includes/header.php'; ?>
    <?php $heroImage = getHeroImage('gallery'); ?>
    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
    <main>
    <section class="page-hero">
        <div class="hero-content fade-in">
            <h1>Our Gallery</h1>
            <p>Moments captured during our journey</p>
        </div>
    </section>

    <section class="gallery-section">
        <div class="container">
            <div class="gallery-filter">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="projects">Projects</button>
            </div>
            <div class="gallery-grid">
                <?php
                $gallery = loadJsonData('gallery.json');
                if (!$gallery || !isset($gallery['gallery_items'])) {
                    echo '<p>No gallery items found</p>';
                } else {
                    $gallery_items = $gallery['gallery_items'];
                    foreach ($gallery_items as $item): ?>
                        <div class="gallery-item fade-in" data-category="<?php echo htmlspecialchars($item['category']); ?>">
                            <img src="<?php echo SITE_URL . htmlspecialchars($item['image_url']); ?>" 
                                alt="<?php echo htmlspecialchars($item['title']); ?>"
                                onerror="this.src='<?php echo SITE_URL; ?>/assets/img/gallery/default.jpg'">
                            <div class="gallery-overlay">
                                <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                <p><?php echo htmlspecialchars($item['description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach;
                } ?>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/gallery.js"></script>
</body> </html>