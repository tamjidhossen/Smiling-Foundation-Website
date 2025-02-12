<?php require_once '../config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section class="error-section">
            <div class="container">
                <div class="error-content text-center">
                    <h1>404</h1>
                    <h2>Page Not Found</h2>
                    <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                    <a href="<?php echo SITE_URL; ?>" class="cta-button">Go to Homepage</a>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>