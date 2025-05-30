<?php require_once '../config/config.php'; ?>
<?php require_once '../config/database.php'; ?> 
<!DOCTYPE html>

<html lang="en"> <head> <meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>FAQ - <?php echo SITE_NAME; ?>
</title> <link rel="stylesheet" href="../assets/css/style.css"> 
<link rel="stylesheet" href="../assets/css/animations.css"> 
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 
</head> 
<body> 
    <?php include '../includes/header.php'; ?> 
    <?php $heroImage = getHeroImage('faq'); ?>
    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
    <main> 
        <section class="page-hero"> 
            <div class="hero-content fade-in"> 
                <h1>Frequently Asked Questions</h1> 
                <p>Find answers to common questions about our organization</p> 
            </div> 
        </section>    <section class="faq-section">
        <div class="container">
            <?php
            $conn = get_database_connection();
            
            // Fetch FAQ categories and their questions
            $categories_query = "SELECT * FROM faq_categories WHERE is_deleted = 0 ORDER BY display_order, name";
            $categories_result = mysqli_query($conn, $categories_query);
            
            if (mysqli_num_rows($categories_result) > 0) {
                while ($category = mysqli_fetch_assoc($categories_result)): 
                    // Fetch FAQs for this category
                    $category_id = $category['id'];
                    $faqs_query = "SELECT * FROM faqs WHERE category_id = $category_id AND is_deleted = 0 ORDER BY display_order, id";
                    $faqs_result = mysqli_query($conn, $faqs_query);
                    
                    if (mysqli_num_rows($faqs_result) > 0):
                    ?>
                        <div class="faq-category fade-in">
                            <h2><?php echo htmlspecialchars($category['name']); ?></h2>
                            <div class="faq-list">
                                <?php while ($faq = mysqli_fetch_assoc($faqs_result)): ?>
                                    <div class="faq-item">
                                        <div class="faq-question">
                                            <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                        <div class="faq-answer">
                                            <p><?php echo htmlspecialchars($faq['answer']); ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php 
                    endif;
                endwhile;
            } else {
                echo '<p>No FAQs found</p>';
            }
            mysqli_close($conn);
            ?>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/faq.js"></script>

</body> </html>