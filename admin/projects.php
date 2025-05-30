<?php
session_start();
require_once '../config/config.php';
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . ADMIN_URL . '/login.php');
    exit;
}

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Auto-update project status based on end date
$update_query = "UPDATE projects 
                SET status = 'completed' 
                WHERE end_date IS NOT NULL 
                AND end_date <= CURDATE() 
                AND status = 'ongoing'";
mysqli_query($conn, $update_query);

// Get all projects
$query = "SELECT * FROM projects WHERE is_deleted = 0 ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Manage Projects - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .auto-completed {
            background-color: #e8f5e8;
            border-left: 4px solid #4caf50;
        }
        
        .auto-completed .status {
            position: relative;
        }
        
        .auto-completed .status::after {
            content: " (Auto)";
            font-size: 0.8em;
            font-weight: normal;
            color: #666;
        }
        
        .end-date-warning {
            color: #ff9800;
            font-weight: bold;
        }
        
        .end-date-passed {
            color: #f44336;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="admin-main">
            <div class="page-header">
                <h1>Manage Projects</h1>
                <button class="cta-button" onclick="showAddForm()">
                    <i class="fas fa-plus"></i> Add New Project
                </button>
            </div>            <?php if (isset($_GET['success'])): ?>
                <div class="alert success">
                    <?php 
                    switch($_GET['success']) {
                        case 'add':
                            echo 'Project added successfully!';
                            break;
                        case 'edit':
                            echo 'Project updated successfully!';
                            break;
                        case 'delete':
                            echo 'Project deleted successfully!';
                            break;
                        default:
                            echo 'Operation completed successfully!';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <div class="alert info" style="background: #e3f2fd; color: #1976d2; border: 1px solid #bbdefb; margin-bottom: 1rem;">
                <i class="fas fa-info-circle"></i> 
                <strong>Auto-completion:</strong> Projects with end dates are automatically marked as "completed" when their end date passes.
                Projects highlighted in green have been auto-completed.
            </div>

            <div class="projects-table">
                <table>                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>                        <?php foreach ($projects as $project): ?>
                            <?php 
                            $isAutoCompleted = $project['status'] === 'completed' && 
                                             $project['end_date'] && 
                                             $project['end_date'] <= date('Y-m-d');
                            $rowClass = $isAutoCompleted ? 'auto-completed' : '';
                            ?>
                            <tr class="<?php echo $rowClass; ?>">
                                <td>
                                    <img src="../assets/img/projects/<?php echo htmlspecialchars($project['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($project['title']); ?>"
                                         class="table-image">
                                </td>
                                <td><?php echo htmlspecialchars($project['title']); ?></td>
                                <td><?php echo substr(htmlspecialchars($project['description']), 0, 100) . '...'; ?></td>                                <td>
                                    <span class="status <?php echo $project['status']; ?>">
                                        <?php echo ucfirst($project['status']); ?>
                                        <?php if ($isAutoCompleted): ?>
                                            <small style="display: block; color: #666; font-size: 0.8em;">Auto-completed</small>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    if ($project['end_date']) {
                                        $endDate = strtotime($project['end_date']);
                                        $today = strtotime(date('Y-m-d'));
                                        $dateClass = '';
                                        
                                        if ($endDate < $today && $project['status'] === 'ongoing') {
                                            $dateClass = 'end-date-passed';
                                        } elseif ($endDate <= strtotime('+7 days') && $project['status'] === 'ongoing') {
                                            $dateClass = 'end-date-warning';
                                        }
                                        
                                        echo '<span class="' . $dateClass . '">' . date('M j, Y', $endDate) . '</span>';
                                    } else {
                                        echo '<em>No end date</em>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button onclick="editProject(<?php echo $project['id']; ?>)" class="action-btn edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteProject(<?php echo $project['id']; ?>)" class="action-btn delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Add/Edit Project Modal -->
    <div id="projectModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Add New Project</h2>
            <form id="projectForm" action="project_handler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="project_id" id="project_id">
                
                <div class="form-group">
                    <label>Project Title</label>
                    <input type="text" name="title" id="project_title" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="project_description" required></textarea>
                </div>
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="project_status" required>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                        <option value="paused">Paused</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>End Date (Optional)</label>
                    <input type="date" name="end_date" id="project_end_date">
                    <small>Leave empty for ongoing projects without a fixed end date</small>
                </div>
                
                <div class="form-group">
                    <label>Project Image</label>
                    <input type="file" name="image" id="project_image" accept="image/*">
                </div>
                
                <button type="submit" class="cta-button">Save Project</button>
            </form>
        </div>
    </div>

    <script>
    // Show/Hide Modal
    const modal = document.getElementById('projectModal');
    const closeBtn = document.getElementsByClassName('close')[0];
    const form = document.getElementById('projectForm');

    function showAddForm() {
        document.getElementById('modalTitle').textContent = 'Add New Project';
        form.reset();
        form.action.value = 'add';
        modal.style.display = 'block';
    }

    function editProject(id) {
        document.getElementById('modalTitle').textContent = 'Edit Project';
        form.action.value = 'edit';
        form.project_id.value = id;
          // Fetch project data and populate form
        fetch('get_project.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('project_title').value = data.title;
                document.getElementById('project_description').value = data.description;
                document.getElementById('project_status').value = data.status;
                document.getElementById('project_end_date').value = data.end_date || '';
            });
        
        modal.style.display = 'block';
    }    function deleteProject(id) {
        if (confirm('Are you sure you want to delete this project?')) {
            fetch('project_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=delete&project_id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'projects.php?success=delete';
                } else {
                    alert('Error deleting project. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting project. Please try again.');
            });
        }
    }

    closeBtn.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    </script>
</body>
</html>