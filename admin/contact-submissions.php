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
    if ($action === 'mark_read') {
        $id = $_POST['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("UPDATE contact_submissions SET is_read = 1 WHERE id = ?");
            $stmt->execute([$id]);
            $message = '<div class="alert alert-success">Message marked as read!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("DELETE FROM contact_submissions WHERE id = ?");
            $stmt->execute([$id]);
            $message = '<div class="alert alert-success">Message deleted successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    }
    $action = 'list';
}

// Get contact submissions for listing
$submissions = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM contact_submissions ORDER BY created_at DESC");
        $submissions = $stmt->fetchAll();
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading contact submissions: ' . $e->getMessage() . '</div>';
    }
}

// Get single submission for viewing
$submission = null;
if ($action === 'view' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM contact_submissions WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $submission = $stmt->fetch();
        if (!$submission) {
            $message = '<div class="alert alert-danger">Message not found.</div>';
            $action = 'list';
        } else {
            // Mark as read
            $stmt = $pdo->prepare("UPDATE contact_submissions SET is_read = 1 WHERE id = ?");
            $stmt->execute([$_GET['id']]);
        }
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading message: ' . $e->getMessage() . '</div>';
        $action = 'list';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin Dashboard</title>
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
        
        .unread {
            background-color: #f8f9fa;
            font-weight: bold;
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
                        <a class="nav-link active" href="contact-submissions.php">
                            <i class="fas fa-envelope me-2"></i>Contact Messages
                        </a>
                        <a class="nav-link" href="volunteers.php">
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
                                    View Message
                                <?php else: ?>
                                    Contact Messages
                                <?php endif; ?>
                            </h4>
                            <div class="navbar-nav ms-auto">
                                <span class="navbar-text me-3">
                                    Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin'); ?>
                                </span>
                                <a href="../index.php" class="btn btn-outline-primary btn-sm me-2 ">
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
                            <!-- Contact Submissions List -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5>All Contact Messages</h5>
                            </div>
                            
                            <div class="table-card">
                                <div class="card-body">
                                    <?php if (empty($submissions)): ?>
                                        <p class="text-muted mx-3">No contact messages found.</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Subject</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($submissions as $sub): ?>
                                                        <tr class="<?php echo !$sub['is_read'] ? 'unread' : ''; ?>">
                                                            <td>
                                                                <strong><?php echo htmlspecialchars($sub['name']); ?></strong>
                                                                <?php if ($sub['phone']): ?>
                                                                    <br><small class="text-muted"><?php echo htmlspecialchars($sub['phone']); ?></small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($sub['email']); ?></td>
                                                            <td><?php echo htmlspecialchars($sub['subject']); ?></td>
                                                            <td>
                                                                <?php if ($sub['is_read']): ?>
                                                                    <span class="badge bg-success">Read</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-warning">Unread</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo date('M j, Y H:i', strtotime($sub['created_at'])); ?></td>
                                                            <td>
                                                                <a href="?action=view&id=<?php echo $sub['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <?php if (!$sub['is_read']): ?>
                                                                    <button class="btn btn-sm btn-outline-success" onclick="markAsRead(<?php echo $sub['id']; ?>)">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                <?php endif; ?>
                                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteMessage(<?php echo $sub['id']; ?>)">
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
                            <!-- View Message -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h5>Message Details</h5>
                                                <a href="contact-submissions.php" class="btn btn-secondary">
                                                    <i class="fas fa-arrow-left me-2"></i>Back to Messages
                                                </a>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($submission['name']); ?></p>
                                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($submission['email']); ?></p>
                                                    <?php if ($submission['phone']): ?>
                                                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($submission['phone']); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($submission['subject']); ?></p>
                                                    <p><strong>Date:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($submission['created_at'])); ?></p>
                                                    <p><strong>IP Address:</strong> <?php echo htmlspecialchars($submission['ip_address'] ?? 'N/A'); ?></p>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Message:</strong></label>
                                                <div class="border rounded p-3 bg-light">
                                                    <?php echo nl2br(htmlspecialchars($submission['message'])); ?>
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between">
                                                <a href="mailto:<?php echo htmlspecialchars($submission['email']); ?>" class="btn btn-primary">
                                                    <i class="fas fa-reply me-2"></i>Reply via Email
                                                </a>
                                                <button class="btn btn-danger" onclick="deleteMessage(<?php echo $submission['id']; ?>)">
                                                    <i class="fas fa-trash me-2"></i>Delete Message
                                                </button>
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
        function markAsRead(id) {
            if (confirm('Mark this message as read?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="action" value="mark_read"><input type="hidden" name="id" value="${id}">`;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function deleteMessage(id) {
            if (confirm('Are you sure you want to delete this message?')) {
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