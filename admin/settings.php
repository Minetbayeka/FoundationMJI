<?php
require_once '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        foreach ($_POST['settings'] as $key => $value) {
            $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([trim($value), $key]);
        }
        $message = '<div class="alert alert-success">Settings updated successfully!</div>';
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error updating settings: ' . $e->getMessage() . '</div>';
    }
}

// Get current settings
$settings = [];
try {
    $stmt = $pdo->query("SELECT * FROM settings ORDER BY setting_key");
    $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = '<div class="alert alert-danger">Error loading settings: ' . $e->getMessage() . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Dashboard</title>
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
            <div class="col-md-3 col-lg-2 px-0 ">
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
                        <a class="nav-link" href="projects.php">
                            <i class="fas fa-project-diagram me-2"></i>Projects
                        </a>
                        <a class="nav-link" href="team.php">
                            <i class="fas fa-user-tie me-2"></i>Team Members
                        </a>
                        <a class="nav-link active" href="settings.php">
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
            <div class="col-md-9 col-lg-10 px-0 ">
                <div class="main-content">
                    <!-- Top Navbar -->
      <nav class="navbar navbar-expand-lg bg-light py-3 border-bottom">
    <div class="container-fluid flex-wrap">
        <!-- Left: Page Title -->
        <h4 class="mb-2 mb-lg-0">Site Settings</h4>

        <!-- Right: Nav Controls -->
        <div class="ms-auto d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-2">
            <span class="navbar-text">
                Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin'); ?>
            </span>
            <a href="../index.php" class="btn btn-outline-primary btn-sm">
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
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="table-card">
                                    <div class="card-body">
                                        <h5 class="mb-4 mx-3 pt-3">General Settings</h5>
                                        
                                        <form method="POST">
                                            <div class="row mx-3">
                                                <?php foreach ($settings as $setting): ?>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="<?php echo $setting['setting_key']; ?>" class="form-label">
                                                            <?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?>
                                                        </label>
                                                        
                                                        <?php if ($setting['setting_type'] === 'textarea'): ?>
                                                            <textarea class="form-control" 
                                                                      id="<?php echo $setting['setting_key']; ?>" 
                                                                      name="settings[<?php echo $setting['setting_key']; ?>]" 
                                                                      rows="3"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                                                        <?php elseif ($setting['setting_type'] === 'number'): ?>
                                                            <input type="number" class="form-control" 
                                                                   id="<?php echo $setting['setting_key']; ?>" 
                                                                   name="settings[<?php echo $setting['setting_key']; ?>]" 
                                                                   value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                                        <?php else: ?>
                                                            <input type="text" class="form-control" 
                                                                   id="<?php echo $setting['setting_key']; ?>" 
                                                                   name="settings[<?php echo $setting['setting_key']; ?>]" 
                                                                   value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($setting['description']): ?>
                                                            <small class="text-muted"><?php echo htmlspecialchars($setting['description']); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            
                                            <div class="d-flex justify-content-end pb-3 mx-3">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-2"></i>Save Settings
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 