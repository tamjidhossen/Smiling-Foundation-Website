<?php require_once '../config/config.php'; ?> <!DOCTYPE html>

<html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Contact Us - <?php echo SITE_NAME; ?></title> <link rel="stylesheet" href="../assets/css/style.css"> <link rel="stylesheet" href="../assets/css/animations.css"> <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> </head> <body> <?php include '../includes/header.php'; ?>
<?php $heroImage = getHeroImage('contact'); ?>
    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
<main>
    <section class="page-hero">
        <div class="hero-content fade-in">
            <h1>Contact Us</h1>
            <p>Get in touch with us today</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info fade-in">
                    <h2>Get In Touch</h2>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h3>Address</h3>
                            <p><?php echo CONTACT_ADDRESS?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h3>Phone</h3>
                            <p><?php echo CONTACT_PHONE?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h3>Email</h3>
                            <p><?php echo CONTACT_EMAIL?></p>
                        </div>
                    </div>
                </div>
                <div class="contact-form slide-in">
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="cta-button">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/contact.js"></script>
</body> </html>