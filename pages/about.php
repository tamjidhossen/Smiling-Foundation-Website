<?php 
require_once '../config/config.php';
require_once '../config/database.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Get all about content
$query = "SELECT * FROM about_content WHERE is_deleted = 0";
$result = mysqli_query($conn, $query);
$about_content = array();
while ($row = mysqli_fetch_assoc($result)) {
    $about_content[$row['section']] = $row;
}

// Helper function to get content safely
function getContent($section, $field, $default = '') {
    global $about_content;
    if (isset($about_content[$section]) && isset($about_content[$section][$field])) {
        return $about_content[$section][$field];
    }
    return $default;
}

function getJsonContent($section, $default = []) {
    global $about_content;
    if (isset($about_content[$section]) && !empty($about_content[$section]['content'])) {
        return json_decode($about_content[$section]['content'], true);
    }
    return $default;
}

// Assign content to variables
$who_we_are_title = getContent('who_we_are', 'title', 'Who We Are');
$who_we_are_content = getContent('who_we_are', 'content', 'Content not available.');

$mission_title = getContent('mission', 'title', 'Our Mission');
$mission_content = getContent('mission', 'content', 'Content not available.');
$vision_title = getContent('vision', 'title', 'Our Vision');
$vision_content = getContent('vision', 'content', 'Content not available.');
$values_title = getContent('values', 'title', 'Our Values');
$values_content = getJsonContent('values', []);

$goals_title = getContent('goals', 'title', 'Our Goals & Objectives');
$goals_content = getJsonContent('goals', []);

$activities_title = getContent('activities', 'title', 'Our Activities');
$activities_content = getContent('activities', 'content', 'Content not available.');

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
    <style>
        .page-hero { --hero-bg: url('<?php echo getHeroImage('about'); ?>'); }
        .about-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }
        .tab-button {
            padding: 1rem 2rem;
            cursor: pointer;
            border: 2px solid transparent;
            background-color: #f1f5f9;
            color: var(--text-color);
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .tab-button:hover {
            background-color: #e2e8f0;
        }
        .tab-button.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        .tab-content {
            display: none;
            animation: fadeIn 0.5s;
        }
        .tab-content.active {
            display: block;
        }
        .content-section {
            background: var(--light-bg);
            padding: 3rem 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        .content-section h2 {
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .values-grid, .goals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        .value-card, .goal-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
        }
        .value-card h3, .goal-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        /* --- Block Text Styles --- */
        .content-section p {
            background-color: #ffffff; /* White background for the block */
            border: 1px solid #e0e0e0; /* Light border */
            padding: 1.5rem; /* Padding inside the block */
            margin-bottom: 1rem; /* Space between blocks if multiple paragraphs */
            border-radius: 8px; /* Slightly rounded corners */
            line-height: 1.6; /* Improve readability */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); /* Subtle shadow */
            text-align: justify; /* Justify text for a cleaner block look */
        }
        /* If you have multiple paragraphs that should appear as distinct blocks */
        .content-section p + p {
            margin-top: 1rem; /* Add more space between consecutive paragraphs if needed */
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="page-hero">
            <div class="hero-content fade-in">
                <h1>About Us</h1>
                <p>Learn more about our mission, vision, and the work we do.</p>
            </div>
        </section>

        <section class="about-section" style="padding: 5rem 2rem;">
            <div class="container">
                <div class="about-tabs">
                    <button class="tab-button active" onclick="openTab(event, 'who-we-are')">Who We Are</button>
                    <button class="tab-button" onclick="openTab(event, 'mission-vision')">Mission, Vision & Values</button>
                    <button class="tab-button" onclick="openTab(event, 'goals')">Goals & Objectives</button>
                    <button class="tab-button" onclick="openTab(event, 'activities')">Activities</button>
                </div>

                <div id="who-we-are" class="tab-content active">
                    <div class="content-section">
                        <h2><?php echo htmlspecialchars($who_we_are_title); ?></h2>
                        <p><?php echo nl2br(htmlspecialchars($who_we_are_content)); ?></p>
                    </div>
                </div>

                <div id="mission-vision" class="tab-content">
                    <div class="content-section">
                        <h2><?php echo htmlspecialchars($mission_title); ?></h2>
                        <p><?php echo nl2br(htmlspecialchars($mission_content)); ?></p>
                    </div>
                    <div class="content-section">
                        <h2><?php echo htmlspecialchars($vision_title); ?></h2>
                        <p><?php echo nl2br(htmlspecialchars($vision_content)); ?></p>
                    </div>
                    <div class="content-section">
                        <h2><?php echo htmlspecialchars($values_title); ?></h2>
                        <div class="values-grid">
                            <?php if (!empty($values_content)): ?>
                                <?php foreach ($values_content as $value): ?>
                                    <div class="value-card">
                                        <h3><?php echo htmlspecialchars($value['title']); ?></h3>
                                        <p><?php echo htmlspecialchars($value['description']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Our core values will be listed here soon.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div id="goals" class="tab-content">
                    <div class="content-section">
                        <h2><?php echo htmlspecialchars($goals_title); ?></h2>
                        <div class="goals-grid">
                             <?php if (!empty($goals_content)): ?>
                                <?php foreach ($goals_content as $goal): ?>
                                    <div class="goal-card">
                                        <h3><?php echo htmlspecialchars($goal['title']); ?></h3>
                                        <p><?php echo htmlspecialchars($goal['description']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Our goals and objectives will be listed here soon.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div id="activities" class="tab-content">
                     <div class="content-section">
                        <h2><?php echo htmlspecialchars($activities_title); ?></h2>
                        <p><?php echo nl2br(htmlspecialchars($activities_content)); ?></p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/main.js"></script>
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tab-button");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>
</html>