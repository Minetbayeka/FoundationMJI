<?php
require_once '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$action = $_GET['action'] ?? 'list';
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'new' || $action === 'edit') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category = $_POST['category'] ?? 'health';
        $status = $_POST['status'] ?? 'active';
        $target_amount = floatval($_POST['target_amount'] ?? 0);
        $location = trim($_POST['location'] ?? '');
        $beneficiaries_count = intval($_POST['beneficiaries_count'] ?? 0);
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        
        // Generate slug from title
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        if (empty($title) || empty($description)) {
            $message = '<div class="alert alert-danger">Title and description are required.</div>';
        } else {
            try {
                if ($action === 'new') {
                    $stmt = $pdo->prepare("
                        INSERT INTO projects (title, slug, description, content, category, status, target_amount, location, beneficiaries_count, start_date, end_date, is_featured) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$title, $slug, $description, $content, $category, $status, $target_amount, $location, $beneficiaries_count, $start_date, $end_date, $is_featured]);
                    $message = '<div class="alert alert-success">Project created successfully!</div>';
                    $action = 'list';
                } else {
                    $id = $_POST['id'] ?? 0;
                    $stmt = $pdo->prepare("
                        UPDATE projects 
                        SET title = ?, slug = ?, description = ?, content = ?, category = ?, status = ?, target_amount = ?, location = ?, beneficiaries_count = ?, start_date = ?, end_date = ?, is_featured = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$title, $slug, $description, $content, $category, $status, $target_amount, $location, $beneficiaries_count, $start_date, $end_date, $is_featured, $id]);
                    $message = '<div class="alert alert-success">Project updated successfully!</div>';
                    $action = 'list';
                }
            } catch (PDOException $e) {
                $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
            }
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
            $stmt->execute([$id]);
            $message = '<div class="alert alert-success">Project deleted successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
        $action = 'list';
    }
}

// Get projects for listing
$projects = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
        $projects = $stmt->fetchAll();
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading projects: ' . $e->getMessage() . '</div>';
    }
}

// Get single project for editing
$project = null;
if ($action === 'edit' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $project = $stmt->fetch();
        if (!$project) {
            $message = '<div class="alert alert-danger">Project not found.</div>';
            $action = 'list';
        }
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading project: ' . $e->getMessage() . '</div>';
        $action = 'list';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0c2a2d;
            --secondary-color: #ff5e28;
            --accent-color: #67BDB0;
        }
        
        .sidebar {
            background: var(--primary-color);
            min-height: 100vh;
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.25rem;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <img src="../assets/images/logo.png" alt="MJL Foundation" height="50" class="mb-2">
                        <h6>MJL Foundation</h6>
                        <small>Admin Panel</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="blog-posts.php">
                            <i class="fas fa-blog me-2"></i>Blog Posts
                        </a>
                        <a class="nav-link" href="contact-submissions.php">
                            <i class="fas fa-envelope me-2"></i>Contact Messages
                        </a>
                        <a class="nav-link" href="volunteers.php">
                            <i class="fas fa-users me-2"></i>Volunteers
                        </a>
                        <a class="nav-link" href="donations.php">
                            <i class="fas fa-heart me-2"></i>Donations
                        </a>
                        <a class="nav-link active" href="projects.php">
                            <i class="fas fa-project-diagram me-2"></i>Projects
                        </a>
                        <a class="nav-link" href="team.php">
                            <i class="fas fa-user-tie me-2"></i>Team Members
                        </a>
                        <a class="nav-link" href="settings.php">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <hr class="my-3">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content">
                    <!-- Top Navbar -->
                    <nav class="navbar navbar-expand-lg">
                        <div class="container-fluid">
                            <h4 class="mb-0">
                                <?php if ($action === 'new'): ?>
                                    New Project
                                <?php elseif ($action === 'edit'): ?>
                                    Edit Project
                                <?php else: ?>
                                    Projects
                                <?php endif; ?>
                            </h4>
                            <div class="navbar-nav ms-auto">
                                <span class="navbar-text me-3">
                                    Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin'); ?>
                                </span>
                                <a href="../index.php" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-external-link-alt me-1"></i>View Site
                                </a>
                                <a href="logout.php" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </a>
                            </div>
                        </div>
                    </nav>
                    
                    <!-- Content -->
                    <div class="p-4">
                        <?php echo $message; ?>
                        
                        <?php if ($action === 'list'): ?>
                            <!-- Projects List -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5>All Projects</h5>
                                <a href="?action=new" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>New Project
                                </a>
                            </div>
                            
                            <div class="table-card">
                                <div class="card-body">
                                    <?php if (empty($projects)): ?>
                                        <p class="text-muted mx-3">No projects found.</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Category</th>
                                                        <th>Status</th>
                                                        <th>Target Amount</th>
                                                        <th>Featured</th>
                                                        <th>Created</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($projects as $project): ?>
                                                        <tr>
                                                            <td>
                                                                <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                                                                <br><small class="text-muted"><?php echo htmlspecialchars($project['location']); ?></small>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-info"><?php echo ucfirst($project['category']); ?></span>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-<?php echo $project['status'] === 'active' ? 'success' : ($project['status'] === 'completed' ? 'primary' : 'warning'); ?>">
                                                                    <?php echo ucfirst($project['status']); ?>
                                                                </span>
                                                            </td>
                                                            <td>$<?php echo number_format($project['target_amount'], 2); ?></td>
                                                            <td>
                                                                <?php if ($project['is_featured']): ?>
                                                                    <span class="badge bg-warning">Featured</span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo date('M j, Y', strtotime($project['created_at'])); ?></td>
                                                            <td>
                                                                <a href="?action=edit&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteProject(<?php echo $project['id']; ?>)">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                        <?php elseif ($action === 'new' || $action === 'edit'): ?>
                            <!-- Project Form -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-card">
                                        <div class="card-body">
                                            <form method="POST">
                                                <?php if ($action === 'edit'): ?>
                                                    <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                                <?php endif; ?>
                                                
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="mb-3">
                                                            <label for="title" class="form-label">Title *</label>
                                                            <input type="text" class="form-control" id="title" name="title" 
                                                                   value="<?php echo htmlspecialchars($project['title'] ?? ''); ?>" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="description" class="form-label">Description *</label>
                                                            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($project['description'] ?? ''); ?></textarea>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="content" class="form-label">Content</label>
                                                            <textarea class="form-control" id="content" name="content" rows="10"><?php echo htmlspecialchars($project['content'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="category" class="form-label">Category</label>
                                                            <select class="form-select" id="category" name="category">
                                                                <option value="health" <?php echo ($project['category'] ?? '') === 'health' ? 'selected' : ''; ?>>Health</option>
                                                                <option value="education" <?php echo ($project['category'] ?? '') === 'education' ? 'selected' : ''; ?>>Education</option>
                                                                <option value="agriculture" <?php echo ($project['category'] ?? '') === 'agriculture' ? 'selected' : ''; ?>>Agriculture</option>
                                                                <option value="psychosocial" <?php echo ($project['category'] ?? '') === 'psychosocial' ? 'selected' : ''; ?>>Psychosocial</option>
                                                                <option value="economic" <?php echo ($project['category'] ?? '') === 'economic' ? 'selected' : ''; ?>>Economic</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="status" class="form-label">Status</label>
                                                            <select class="form-select" id="status" name="status">
                                                                <option value="active" <?php echo ($project['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                                                <option value="completed" <?php echo ($project['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                                <option value="planned" <?php echo ($project['status'] ?? '') === 'planned' ? 'selected' : ''; ?>>Planned</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="target_amount" class="form-label">Target Amount ($)</label>
                                                            <input type="number" class="form-control" id="target_amount" name="target_amount" 
                                                                   value="<?php echo $project['target_amount'] ?? ''; ?>" step="0.01">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="location" class="form-label">Location</label>
                                                            <input type="text" class="form-control" id="location" name="location" 
                                                                   value="<?php echo htmlspecialchars($project['location'] ?? ''); ?>">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="beneficiaries_count" class="form-label">Beneficiaries Count</label>
                                                            <input type="number" class="form-control" id="beneficiaries_count" name="beneficiaries_count" 
                                                                   value="<?php echo $project['beneficiaries_count'] ?? ''; ?>">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="start_date" class="form-label">Start Date</label>
                                                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                                                   value="<?php echo $project['start_date'] ?? ''; ?>">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="end_date" class="form-label">End Date</label>
                                                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                                                   value="<?php echo $project['end_date'] ?? ''; ?>">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                                       <?php echo ($project['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="is_featured">
                                                                    Featured Project (will appear on homepage)
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between">
                                                    <a href="projects.php" class="btn btn-secondary">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">
                                                        <?php echo $action === 'new' ? 'Create Project' : 'Update Project'; ?>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteProject(id) {
            if (confirm('Are you sure you want to delete this project?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="${id}">`;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html> 