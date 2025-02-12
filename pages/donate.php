<?php require_once '../config/config.php'; ?> <!DOCTYPE html>

<html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Donate - <?php echo SITE_NAME; ?></title> <link rel="stylesheet" href="../assets/css/style.css"> <link rel="stylesheet" href="../assets/css/animations.css"> <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> </head> <body> 
    <?php include '../includes/header.php'; ?>
    <?php $heroImage = getHeroImage('donate'); ?>
    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
    <main>
    <section class="page-hero">
        <div class="hero-content fade-in">
            <h1>Make a Difference</h1>
            <p>Your contribution helps us create lasting change</p>
        </div>
    </section>

    <section class="donate-section">
        <div class="container">
            <div class="donate-grid">
                <div class="donation-info fade-in">
                    <h2>Why Donate?</h2>
                    <p>Your donation helps us continue our mission of supporting communities and creating positive change.</p>
                    
                    <div class="impact-stats">
                        <div class="stat-item">
                            <h3>1000+</h3>
                            <p>Lives Impacted</p>
                        </div>
                        <div class="stat-item">
                            <h3>50+</h3>
                            <p>Projects Completed</p>
                        </div>
                        <div class="stat-item">
                            <h3>20+</h3>
                            <p>Communities Served</p>
                        </div>
                    </div>
                </div>

                <div class="donation-form slide-in">
                    <h2>Make a Donation</h2>
                    <form id="donateForm">
                        <div class="amount-options">
                            <button type="button" class="amount-btn" data-amount="10">$10</button>
                            <button type="button" class="amount-btn" data-amount="25">$25</button>
                            <button type="button" class="amount-btn" data-amount="50">$50</button>
                            <button type="button" class="amount-btn" data-amount="100">$100</button>
                        </div>
                        <div class="form-group">
                            <label for="customAmount">Custom Amount</label>
                            <input type="number" id="customAmount" name="amount" min="1" placeholder="Enter amount">
                        </div>
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <button type="submit" class="cta-button">Donate Now</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/donate.js"></script>
</body> </html>