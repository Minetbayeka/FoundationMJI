<?php
require_once '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get statistics
try {
    // Blog posts count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM blog_posts WHERE status = 'published'");
    $published_posts = $stmt->fetch()['count'];
    
    // Contact submissions count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM contact_submissions WHERE is_read = 0");
    $unread_contacts = $stmt->fetch()['count'];
    
    // Volunteer applications count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM volunteer_applications WHERE status = 'pending'");
    $pending_volunteers = $stmt->fetch()['count'];
    
    // Total donations
    $stmt = $pdo->query("SELECT SUM(amount) as total FROM donations WHERE payment_status = 'completed'");
    $total_donations = $stmt->fetch()['total'] ?? 0;
    
    // Recent contact submissions
    $stmt = $pdo->query("SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT 5");
    $recent_contacts = $stmt->fetchAll();
    
    // Recent volunteer applications
    $stmt = $pdo->query("SELECT * FROM volunteer_applications ORDER BY created_at DESC LIMIT 5");
    $recent_volunteers = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MJL Foundation</title>
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
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .bg-primary-custom {
            background: var(--accent-color);
        }
        
        .bg-success-custom {
            background: #28a745;
        }
        
        .bg-warning-custom {
            background: #ffc107;
        }
        
        .bg-info-custom {
            background: #17a2b8;
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
                     <!-- <button class="btn text-white d-lg-none m-3" id="sidebarToggle">
                     <i class="fas fa-bars"></i> 
                     </button> -->
                    <nav class="nav flex-column"   >
                        <a class="nav-link active" href="dashboard.php">
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
                            <h4 class="mb-0">Dashboard</h4>
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
                    
                    <!-- Dashboard Content -->
                    <div class="p-4">
                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="stat-card">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-primary-custom me-3">
                                            <i class="fas fa-blog"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-1"><?php echo $published_posts; ?></h3>
                                            <p class="text-muted mb-0">Published Posts</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="stat-card">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-warning-custom me-3">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-1"><?php echo $unread_contacts; ?></h3>
                                            <p class="text-muted mb-0">Unread Messages</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="stat-card">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-success-custom me-3">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-1"><?php echo $pending_volunteers; ?></h3>
                                            <p class="text-muted mb-0">Pending Volunteers</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="stat-card">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-info-custom me-3">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-1">$<?php echo number_format($total_donations, 2); ?></h3>
                                            <p class="text-muted mb-0">Total Donations</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Activity -->
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <div class="table-card">
                                    <div class="card-header bg-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-envelope me-2 mx-3"></i>Recent Contact Messages
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (empty($recent_contacts)): ?>
                                            <p class="text-muted mx-3">No recent contact messages.</p>
                                        <?php else: ?>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Subject</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($recent_contacts as $contact): ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                                                <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                                                <td><?php echo date('M j', strtotime($contact['created_at'])); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <a href="contact-submissions.php" class="btn btn-sm btn-outline-primary">View All</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 mb-4">
                                <div class="table-card">
                                    <div class="card-header bg-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-users me-2 mx-3"></i>Recent Volunteer Applications
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (empty($recent_volunteers)): ?>
                                            <p class="text-muted mx-3">No recent volunteer applications.</p>
                                        <?php else: ?>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Skills</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($recent_volunteers as $volunteer): ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($volunteer['name']); ?></td>
                                                                <td><?php echo htmlspecialchars(substr($volunteer['skills'], 0, 30)) . '...'; ?></td>
                                                                <td><?php echo date('M j', strtotime($volunteer['created_at'])); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <a href="volunteers.php" class="btn btn-sm btn-outline-primary">View All</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="table-card">
                                    <div class="card-header bg-white mx-3">
                                        <h5 class="mb-0">
                                            <i class="fas fa-bolt me-2"></i>Quick Actions
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <a href="blog-posts.php?action=new" class="btn btn-primary w-100 mx-3">
                                                    <i class="fas fa-plus me-2"></i>New Blog Post
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <a href="projects.php?action=new" class="btn btn-success w-100">
                                                    <i class="fas fa-plus me-2"></i>New Project
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <a href="team.php?action=new" class="btn btn-info w-100">
                                                    <i class="fas fa-plus me-2"></i>Add Team Member
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <a href="settings.php" class="btn btn-warning w-100  mr-3">
                                                    <i class="fas fa-cog me-2"></i>Site Settings
                                                </a>
                                            </div>
                                        </div>
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