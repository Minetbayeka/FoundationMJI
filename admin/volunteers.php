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
    if ($action === 'update_status') {
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'pending';
        try {
            $stmt = $pdo->prepare("UPDATE volunteer_applications SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $message = '<div class="alert alert-success">Status updated successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("DELETE FROM volunteer_applications WHERE id = ?");
            $stmt->execute([$id]);
            $message = '<div class="alert alert-success">Application deleted successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    }
    $action = 'list';
}

// Get volunteer applications for listing
$volunteers = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM volunteer_applications ORDER BY created_at DESC");
        $volunteers = $stmt->fetchAll();
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading volunteer applications: ' . $e->getMessage() . '</div>';
    }
}

// Get single application for viewing
$volunteer = null;
if ($action === 'view' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM volunteer_applications WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $volunteer = $stmt->fetch();
        if (!$volunteer) {
            $message = '<div class="alert alert-danger">Application not found.</div>';
            $action = 'list';
        }
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading application: ' . $e->getMessage() . '</div>';
        $action = 'list';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteers - Admin Dashboard</title>
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
                        <a class="nav-link active" href="volunteers.php">
                            <i class="fas fa-users me-2"></i>Volunteers
                        </a>
                        <a class="nav-link" href="donations.php">
                            <i class="fas fa-heart me-2"></i>Donations
                        </a>
                        <a class="nav-link" href="projects.php">
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
                                <?php if ($action === 'view'): ?>
                                    View Volunteer Application
                                <?php else: ?>
                                    Volunteer Applications
                                <?php endif; ?>
                            </h4>
                            <div class="navbar-nav ms-auto">
                                <span class="navbar-text me-3">
                                    Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin'); ?>
                                </span>
                                <a href="../index.php" class="btn btn-outline-primary btn-sm me-2 mb-3">
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
                            <!-- Volunteer Applications List -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5>All Volunteer Applications</h5>
                            </div>
                            
                            <div class="table-card">
                                <div class="card-body">
                                    <?php if (empty($volunteers)): ?>
                                        <p class="text-muted mx-3">No volunteer applications found.</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Skills</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($volunteers as $vol): ?>
                                                        <tr>
                                                            <td>
                                                                <strong><?php echo htmlspecialchars($vol['name']); ?></strong>
                                                                <?php if ($vol['phone']): ?>
                                                                    <br><small class="text-muted"><?php echo htmlspecialchars($vol['phone']); ?></small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($vol['email']); ?></td>
                                                            <td><?php echo htmlspecialchars(substr($vol['skills'], 0, 50)) . (strlen($vol['skills']) > 50 ? '...' : ''); ?></td>
                                                            <td>
                                                                <span class="badge bg-<?php 
                                                                    echo $vol['status'] === 'accepted' ? 'success' : 
                                                                        ($vol['status'] === 'rejected' ? 'danger' : 
                                                                        ($vol['status'] === 'reviewed' ? 'info' : 'warning')); 
                                                                ?>">
                                                                    <?php echo ucfirst($vol['status']); ?>
                                                                </span>
                                                            </td>
                                                            <td><?php echo date('M j, Y', strtotime($vol['created_at'])); ?></td>
                                                            <td>
                                                                <a href="?action=view&id=<?php echo $vol['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteApplication(<?php echo $vol['id']; ?>)">
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
                            
                        <?php elseif ($action === 'view'): ?>
                            <!-- View Application -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h5>Application Details</h5>
                                                <a href="volunteers.php" class="btn btn-secondary">
                                                    <i class="fas fa-arrow-left me-2"></i>Back to Applications
                                                </a>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($volunteer['name']); ?></p>
                                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($volunteer['email']); ?></p>
                                                    <?php if ($volunteer['phone']): ?>
                                                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($volunteer['phone']); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Status:</strong> 
                                                        <span class="badge bg-<?php 
                                                            echo $volunteer['status'] === 'accepted' ? 'success' : 
                                                                ($volunteer['status'] === 'rejected' ? 'danger' : 
                                                                ($volunteer['status'] === 'reviewed' ? 'info' : 'warning')); 
                                                        ?>">
                                                            <?php echo ucfirst($volunteer['status']); ?>
                                                        </span>
                                                    </p>
                                                    <p><strong>Date:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($volunteer['created_at'])); ?></p>
                                                    <p><strong>IP Address:</strong> <?php echo htmlspecialchars($volunteer['ip_address'] ?? 'N/A'); ?></p>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Skills:</strong></label>
                                                <div class="border rounded p-3 bg-light">
                                                    <?php echo nl2br(htmlspecialchars($volunteer['skills'])); ?>
                                                </div>
                                            </div>
                                            
                                            <?php if ($volunteer['message']): ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Message:</strong></label>
                                                    <div class="border rounded p-3 bg-light">
                                                        <?php echo nl2br(htmlspecialchars($volunteer['message'])); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <label for="status" class="form-label"><strong>Update Status:</strong></label>
                                                    <select class="form-select d-inline-block w-auto" id="status" onchange="updateStatus(<?php echo $volunteer['id']; ?>, this.value)">
                                                        <option value="pending" <?php echo $volunteer['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="reviewed" <?php echo $volunteer['status'] === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                                        <option value="accepted" <?php echo $volunteer['status'] === 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                                                        <option value="rejected" <?php echo $volunteer['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <a href="mailto:<?php echo htmlspecialchars($volunteer['email']); ?>" class="btn btn-primary">
                                                        <i class="fas fa-reply me-2"></i>Reply via Email
                                                    </a>
                                                    <button class="btn btn-danger" onclick="deleteApplication(<?php echo $volunteer['id']; ?>)">
                                                        <i class="fas fa-trash me-2"></i>Delete Application
                                                    </button>
                                                </div>
                                            </div>
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
        function updateStatus(id, status) {
            if (confirm('Update status to ' + status + '?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="action" value="update_status"><input type="hidden" name="id" value="${id}"><input type="hidden" name="status" value="${status}">`;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function deleteApplication(id) {
            if (confirm('Are you sure you want to delete this application?')) {
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