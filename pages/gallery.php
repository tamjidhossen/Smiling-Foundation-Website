<?php require_once '../config/config.php'; ?> 
<?php require_once '../config/database.php'; ?> 
<?php
function get_youtube_embed_url($url) {
    if (preg_match('/(youtube\.com|youtu\.be)\/(watch\?v=|embed\/|v\/|)([\w\-]{11})/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[3];
    }
    return ''; // Return empty string if no valid ID is found
}
?>
<!DOCTYPE html>
<html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Gallery - <?php echo SITE_NAME; ?></title> <link rel="stylesheet" href="../assets/css/style.css"> <link rel="stylesheet" href="../assets/css/animations.css"> <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> </head> <body> 
    <?php include '../includes/header.php'; ?>
    <?php $heroImage = getHeroImage('gallery'); ?>
    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
        .gallery-item iframe { width: 100%; height: 100%; border: none; }
    </style>
    <main>
    <section class="page-hero">
        <div class="hero-content fade-in">
            <h1>Our Gallery</h1>
            <p>Moments captured during our journey</p>
        </div>
    </section>
    <section class="gallery-section">
        <div class="container">            <div class="gallery-filter">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="projects">Projects</button>
                <button class="filter-btn" data-filter="events">Events</button>
                <button class="filter-btn" data-filter="general">General</button>
            </div>
            <div class="gallery-grid">
                <?php
                $conn = get_database_connection();
                $gallery_query = "SELECT * FROM gallery WHERE is_deleted = 0 ORDER BY created_at DESC";
                $gallery_result = mysqli_query($conn, $gallery_query);
                
                if (mysqli_num_rows($gallery_result) == 0) {
                    echo '<p>No gallery items found</p>';
                } else {
                    while ($item = mysqli_fetch_assoc($gallery_result)): ?>
                        <div class="gallery-item fade-in" data-category="<?php echo htmlspecialchars($item['category']); ?>">
                            <?php if ($item['type'] === 'image' && !empty($item['image'])): ?>
                                <img src="<?php echo SITE_URL; ?>/assets/img/gallery/<?php echo htmlspecialchars($item['image']); ?>" 
                                    alt="<?php echo htmlspecialchars($item['title']); ?>"
                                    onerror="this.src='<?php echo SITE_URL; ?>/assets/img/gallery/default.jpg'">
                            <?php elseif ($item['type'] === 'video' && !empty($item['video_url'])): ?>
                                <?php $embed_url = get_youtube_embed_url($item['video_url']); ?>
                                <?php if ($embed_url): ?>
                                    <iframe src="<?php echo $embed_url; ?>" 
                                            title="<?php echo htmlspecialchars($item['title']); ?>" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen></iframe>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="gallery-overlay">
                                <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                <p><?php echo htmlspecialchars($item['description']); ?></p>
                            </div>
                        </div>
                    <?php endwhile;
                }
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/gallery.js"></script>
</body> </html>