<?php require_once '../config/config.php'; ?> 
<!DOCTYPE html>

<html lang="en"> <head> <meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>Volunteer - <?php echo SITE_NAME; ?></title> 
<link rel="stylesheet" href="../assets/css/style.css"> 
<link rel="stylesheet" href="../assets/css/animations.css"> 
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> 
</head> 
<body> 
    <?php include '../includes/header.php'; ?> 
    <?php $heroImage = getHeroImage('volunteer'); ?>
    <style>
        .page-hero { --hero-bg: url('<?php echo $heroImage; ?>'); }
    </style>
    <main> 
        <section class="page-hero"> 
            <div class="hero-content fade-in"> 
                <h1>Join Our Volunteer Team</h1> 
                <p>Make a difference in your community</p> 
            </div> 
        </section>
    <section class="volunteer-section">
        <div class="container">
            <div class="volunteer-grid">
                <div class="volunteer-info fade-in">
                    <h2>Why Volunteer With Us?</h2>
                    <div class="benefits-list">
                        <div class="benefit-item">
                            <i class="fas fa-hands-helping"></i>
                            <h3>Make an Impact</h3>
                            <p>Help create positive change in communities</p>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-users"></i>
                            <h3>Join a Community</h3>
                            <p>Meet like-minded people and build connections</p>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-graduation-cap"></i>
                            <h3>Learn New Skills</h3>
                            <p>Gain valuable experience and knowledge</p>
                        </div>
                    </div>
                </div>

            <div class="volunteer-form slide-in">
                <h2>Volunteer Registration Form</h2>
                <p class="form-notice">* Marked fields are mandatory</p>
                <form id="volunteerForm">
                    <!-- Personal Information -->
                    <div class="form-section">
                        <h3>Personal Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="facebook">Facebook Profile Link</label>
                                <input type="url" id="facebook" name="facebook" placeholder="https://facebook.com/username">
                            </div>
                            <div class="form-group">
                                <label for="nid">NID Number *</label>
                                <input type="text" id="nid" name="nid" required>
                            </div>
                        </div>
                    </div>

                    <!-- Educational & Professional Information -->
                    <div class="form-section">
                        <h3>Educational & Professional Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="occupation">Occupation *</label>
                                <select id="occupation" name="occupation" required>
                                    <option value="">Select Occupation</option>
                                    <option value="student">Student</option>
                                    <option value="employed">Employed</option>
                                    <option value="self-employed">Self Employed</option>
                                    <option value="business">Business</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="volunteer_type">Volunteer For *</label>
                                <select id="volunteer_type" name="volunteer_type" required>
                                    <option value="">Select Type</option>
                                    <option value="regular">Regular Volunteer</option>
                                    <option value="event">Event-based Volunteer</option>
                                    <option value="online">Online Volunteer</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="special_skills">Special Skills</label>
                                <textarea id="special_skills" name="special_skills" placeholder="List any special skills you have (e.g., first aid, teaching, web development)"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Present Address -->
                    <div class="form-section">
                        <h3>Present Address</h3>
                        <div class="form-grid" id="present_address_section">
                            <div class="form-group">
                                <label for="present_division">Division *</label>
                                <select id="present_division" name="present_division" required>
                                    <option value="">Select Division</option>
                                    <option value="barishal">Barishal</option>
                                    <option value="chattogram">Chattogram</option>
                                    <option value="dhaka">Dhaka</option>
                                    <option value="khulna">Khulna</option>
                                    <option value="mymensingh">Mymensingh</option>
                                    <option value="rajshahi">Rajshahi</option>
                                    <option value="rangpur">Rangpur</option>
                                    <option value="sylhet">Sylhet</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="present_address">Full Address *</label>
                                <textarea id="present_address" name="present_address" required></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="cta-button">Submit Application</button>
                </form>
            </div>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/volunteer.js"></script></body> </html>