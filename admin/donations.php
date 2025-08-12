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
            $stmt = $pdo->prepare("UPDATE donations SET payment_status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $message = '<div class="alert alert-success">Status updated successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("DELETE FROM donations WHERE id = ?");
            $stmt->execute([$id]);
            $message = '<div class="alert alert-success">Donation deleted successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    }
    $action = 'list';
}

// Get donations for listing
$donations = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM donations ORDER BY created_at DESC");
        $donations = $stmt->fetchAll();
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading donations: ' . $e->getMessage() . '</div>';
    }
}

// Get single donation for viewing
$donation = null;
if ($action === 'view' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM donations WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $donation = $stmt->fetch();
        if (!$donation) {
            $message = '<div class="alert alert-danger">Donation not found.</div>';
            $action = 'list';
        }
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading donation: ' . $e->getMessage() . '</div>';
        $action = 'list';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations - Admin Dashboard</title>
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
                        <a class="nav-link active" href="donations.php">
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
                                    View Donation
                                <?php else: ?>
                                    Donations
                                <?php endif; ?>
                            </h4>
                            <div class="navbar-nav ms-auto">
                                <span class="navbar-text me-3">
                                    Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin'); ?>
                                </span>
                                <a href="../index.php" class="btn btn-outline-primary btn-sm me-2 mb-3 mt-2">
                                    <i class="fas fa-external-link-alt me-1"></i>View Site
                                </a>
                                <a href="logout.php" class="btn btn-outline-danger btn-sm mb-3 mt-2">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </a>
                            </div>
                        </div>
                    </nav>
                    
                    <!-- Content -->
                    <div class="p-4">
                        <?php echo $message; ?>
                        
                        <?php if ($action === 'list'): ?>
                            <!-- Donations List -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5>All Donations</h5>
                            </div>
                            
                            <div class="table-card">
                                <div class="card-body">
                                    <?php if (empty($donations)): ?>
                                        <p class="text-muted mx-3">No donations found.</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Donor</th>
                                                        <th>Amount</th>
                                                        <th>Payment Method</th>
                                                        <th>Status</th>
                                                        <th>Purpose</th>
                                                        <th>Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($donations as $don): ?>
                                                        <tr>
                                                            <td>
                                                                <strong><?php echo htmlspecialchars($don['donor_name']); ?></strong>
                                                                <br><small class="text-muted"><?php echo htmlspecialchars($don['donor_email']); ?></small>
                                                                <?php if ($don['is_anonymous']): ?>
                                                                    <br><span class="badge bg-secondary">Anonymous</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <strong><?php echo $don['currency'] . ' ' . number_format($don['amount'], 2); ?></strong>
                                                            </td>
                                                            <td><?php echo ucfirst(str_replace('_', ' ', $don['payment_method'])); ?></td>
                                                            <td>
                                                                <span class="badge bg-<?php 
                                                                    echo $don['payment_status'] === 'completed' ? 'success' : 
                                                                        ($don['payment_status'] === 'failed' ? 'danger' : 
                                                                        ($don['payment_status'] === 'refunded' ? 'warning' : 'info')); 
                                                                ?>">
                                                                    <?php echo ucfirst($don['payment_status']); ?>
                                                                </span>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($don['purpose'] ?? 'General'); ?></td>
                                                            <td><?php echo date('M j, Y', strtotime($don['created_at'])); ?></td>
                                                            <td>
                                                                <a href="?action=view&id=<?php echo $don['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteDonation(<?php echo $don['id']; ?>)">
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
                            <!-- View Donation -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h5>Donation Details</h5>
                                                <a href="donations.php" class="btn btn-secondary">
                                                    <i class="fas fa-arrow-left me-2"></i>Back to Donations
                                                </a>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Donor Name:</strong> <?php echo htmlspecialchars($donation['donor_name']); ?></p>
                                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($donation['donor_email']); ?></p>
                                                    <p><strong>Amount:</strong> <?php echo $donation['currency'] . ' ' . number_format($donation['amount'], 2); ?></p>
                                                    <p><strong>Payment Method:</strong> <?php echo ucfirst(str_replace('_', ' ', $donation['payment_method'])); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Status:</strong> 
                                                        <span class="badge bg-<?php 
                                                            echo $donation['payment_status'] === 'completed' ? 'success' : 
                                                                ($donation['payment_status'] === 'failed' ? 'danger' : 
                                                                ($donation['payment_status'] === 'refunded' ? 'warning' : 'info')); 
                                                        ?>">
                                                            <?php echo ucfirst($donation['payment_status']); ?>
                                                        </span>
                                                    </p>
                                                    <p><strong>Purpose:</strong> <?php echo htmlspecialchars($donation['purpose'] ?? 'General'); ?></p>
                                                    <p><strong>Date:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($donation['created_at'])); ?></p>
                                                    <p><strong>Anonymous:</strong> <?php echo $donation['is_anonymous'] ? 'Yes' : 'No'; ?></p>
                                                </div>
                                            </div>
                                            
                                            <?php if ($donation['transaction_id']): ?>
                                                <div class="mb-3">
                                                    <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($donation['transaction_id']); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($donation['message']): ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Message:</strong></label>
                                                    <div class="border rounded p-3 bg-light">
                                                        <?php echo nl2br(htmlspecialchars($donation['message'])); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <label for="status" class="form-label"><strong>Update Status:</strong></label>
                                                    <select class="form-select d-inline-block w-auto" id="status" onchange="updateStatus(<?php echo $donation['id']; ?>, this.value)">
                                                        <option value="pending" <?php echo $donation['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="completed" <?php echo $donation['payment_status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="failed" <?php echo $donation['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                                        <option value="refunded" <?php echo $donation['payment_status'] === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <a href="mailto:<?php echo htmlspecialchars($donation['donor_email']); ?>" class="btn btn-primary">
                                                        <i class="fas fa-reply me-2"></i>Reply via Email
                                                    </a>
                                                    <button class="btn btn-danger" onclick="deleteDonation(<?php echo $donation['id']; ?>)">
                                                        <i class="fas fa-trash me-2"></i>Delete Donation
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
        
        function deleteDonation(id) {
            if (confirm('Are you sure you want to delete this donation?')) {
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