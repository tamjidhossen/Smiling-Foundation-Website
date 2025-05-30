<?php require_once '../config/config.php'; ?> <!DOCTYPE html>

<html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Donate - <?php echo SITE_NAME; ?></title> <link rel="stylesheet" href="../assets/css/style.css"> <link rel="stylesheet" href="../assets/css/animations.css"> <link rel="stylesheet" href="../assets/css/donation.css"> <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> </head> <body> 
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
                </div>                <div class="donation-form slide-in">
                    <h2>Make a Donation</h2>
                    <form id="donateForm" action="../donation_handler.php" method="POST">
                        <input type="hidden" name="currency" value="bdt">
                        
                        <div class="amount-options">
                            <button type="button" class="amount-btn" data-amount="1000">৳1000</button>
                            <button type="button" class="amount-btn" data-amount="2500">৳2500</button>
                            <button type="button" class="amount-btn" data-amount="5000">৳5000</button>
                            <button type="button" class="amount-btn" data-amount="10000">৳10000</button>
                        </div>                        <div class="form-group">
                            <label for="customAmount">Custom Amount (BDT)</label>
                            <div class="amount-input-wrapper">
                                <input type="number" id="customAmount" name="amount" min="1" placeholder="Enter amount in BDT" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="purpose">Donation Purpose *</label>
                            <select id="purpose" name="purpose" required>
                                <option value="">Select a purpose</option>
                                <option value="Education">Education</option>
                                <option value="Medicine">Medicine</option>
                                <option value="Flood Relief">Flood Relief</option>
                                <option value="Winter Cloth">Winter Cloth</option>
                                <option value="Clean Water">Clean Water</option>
                                <option value="Food Security">Food Security</option>
                                <option value="General">General</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message (Optional)</label>
                            <textarea id="message" name="message" rows="3" placeholder="Share your thoughts or dedication..."></textarea>
                        </div>
                          <div class="form-group checkbox-group">
                            <label class="anonymous-checkbox">
                                <input type="checkbox" id="anonymous" name="anonymous" value="1">
                                <span class="anonymous-text">I want to donate anonymously</span>
                                <span class="anonymous-indicator">(Name will be hidden from public records)</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="cta-button">Donate Now</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/donate.js?v=<?php echo time(); ?>"></script>
<!-- Cache busting timestamp: <?php echo date('Y-m-d H:i:s'); ?> -->
</body> </html>