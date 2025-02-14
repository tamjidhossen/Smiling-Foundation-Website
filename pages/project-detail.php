<?php
require_once '../config/config.php';
require_once '../config/database.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$id = $_GET['id'] ?? 0;
$query = "SELECT * FROM projects WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$project = mysqli_fetch_assoc($result);

if (!$project) {
    header('Location: 404.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/animations.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <section class="project-detail-section">
            <div class="container">
                <div class="project-detail-content">
                    <img src="../assets/img/projects/<?php echo htmlspecialchars($project['image']); ?>" 
                         alt="<?php echo htmlspecialchars($project['title']); ?>"
                         class="project-detail-image fade-in">
                    <div class="project-detail-info">
                        <h1 class="slide-in"><?php echo htmlspecialchars($project['title']); ?></h1>
                        <span class="project-status <?php echo $project['status']; ?>">
                            <?php echo ucfirst($project['status']); ?>
                        </span>
                        <div class="project-description fade-in">
                            <?php echo nl2br(htmlspecialchars($project['description'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/main.js"></script>
</body>
</html>