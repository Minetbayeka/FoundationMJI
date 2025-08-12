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
        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $social_facebook = trim($_POST['social_facebook'] ?? '');
        $social_twitter = trim($_POST['social_twitter'] ?? '');
        $social_linkedin = trim($_POST['social_linkedin'] ?? '');
        $sort_order = intval($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($name) || empty($position)) {
            $message = '<div class="alert alert-danger">Name and position are required.</div>';
        } else {
            try {
                if ($action === 'new') {
                    $stmt = $pdo->prepare("
                        INSERT INTO team_members (name, position, bio, email, phone, social_facebook, social_twitter, social_linkedin, sort_order, is_active) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$name, $position, $bio, $email, $phone, $social_facebook, $social_twitter, $social_linkedin, $sort_order, $is_active]);
                    $message = '<div class="alert alert-success">Team member created successfully!</div>';
                    $action = 'list';
                } else {
                    $id = $_POST['id'] ?? 0;
                    $stmt = $pdo->prepare("
                        UPDATE team_members 
                        SET name = ?, position = ?, bio = ?, email = ?, phone = ?, social_facebook = ?, social_twitter = ?, social_linkedin = ?, sort_order = ?, is_active = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$name, $position, $bio, $email, $phone, $social_facebook, $social_twitter, $social_linkedin, $sort_order, $is_active, $id]);
                    $message = '<div class="alert alert-success">Team member updated successfully!</div>';
                    $action = 'list';
                }
            } catch (PDOException $e) {
                $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
            }
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = ?");
            $stmt->execute([$id]);
            $message = '<div class="alert alert-success">Team member deleted successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
        $action = 'list';
    }
}

// Get team members for listing
$team_members = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM team_members ORDER BY sort_order ASC, name ASC");
        $team_members = $stmt->fetchAll();
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading team members: ' . $e->getMessage() . '</div>';
    }
}

// Get single team member for editing
$member = null;
if ($action === 'edit' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM team_members WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $member = $stmt->fetch();
        if (!$member) {
            $message = '<div class="alert alert-danger">Team member not found.</div>';
            $action = 'list';
        }
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading team member: ' . $e->getMessage() . '</div>';
        $action = 'list';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Members - Admin Dashboard</title>
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
        
        .team-member-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
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
                        <a class="nav-link" href="projects.php">
                            <i class="fas fa-project-diagram me-2"></i>Projects
                        </a>
                        <a class="nav-link active" href="team.php">
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
                                    New Team Member
                                <?php elseif ($action === 'edit'): ?>
                                    Edit Team Member
                                <?php else: ?>
                                    Team Members
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
                            <!-- Team Members List -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5>All Team Members</h5>
                                <a href="?action=new" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>New Team Member
                                </a>
                            </div>
                            
                            <div class="table-card">
                                <div class="card-body">
                                    <?php if (empty($team_members)): ?>
                                        <p class="text-muted mx-3">No team members found.</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Name</th>
                                                        <th>Position</th>
                                                        <th>Email</th>
                                                        <th>Status</th>
                                                        <th>Order</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($team_members as $member): ?>
                                                        <tr>
                                                            <td>
                                                                <?php if ($member['image']): ?>
                                                                    <img src="../assets/images/<?php echo htmlspecialchars($member['image']); ?>" 
                                                                         alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                                                         class="team-member-image">
                                                                <?php else: ?>
                                                                    <div class="team-member-image bg-secondary d-flex align-items-center justify-content-center">
                                                                        <i class="fas fa-user text-white"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <strong><?php echo htmlspecialchars($member['name']); ?></strong>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($member['position']); ?></td>
                                                            <td><?php echo htmlspecialchars($member['email']); ?></td>
                                                            <td>
                                                                <?php if ($member['is_active']): ?>
                                                                    <span class="badge bg-success">Active</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">Inactive</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo $member['sort_order']; ?></td>
                                                            <td>
                                                                <a href="?action=edit&id=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteMember(<?php echo $member['id']; ?>)">
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
                            <!-- Team Member Form -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-card">
                                        <div class="card-body">
                                            <form method="POST">
                                                <?php if ($action === 'edit'): ?>
                                                    <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                                <?php endif; ?>
                                                
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Name *</label>
                                                            <input type="text" class="form-control" id="name" name="name" 
                                                                   value="<?php echo htmlspecialchars($member['name'] ?? ''); ?>" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="position" class="form-label">Position *</label>
                                                            <input type="text" class="form-control" id="position" name="position" 
                                                                   value="<?php echo htmlspecialchars($member['position'] ?? ''); ?>" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="bio" class="form-label">Bio</label>
                                                            <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo htmlspecialchars($member['bio'] ?? ''); ?></textarea>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="email" name="email" 
                                                                   value="<?php echo htmlspecialchars($member['email'] ?? ''); ?>">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="phone" class="form-label">Phone</label>
                                                            <input type="text" class="form-control" id="phone" name="phone" 
                                                                   value="<?php echo htmlspecialchars($member['phone'] ?? ''); ?>">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="social_facebook" class="form-label">Facebook URL</label>
                                                            <input type="url" class="form-control" id="social_facebook" name="social_facebook" 
                                                                   value="<?php echo htmlspecialchars($member['social_facebook'] ?? ''); ?>">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="social_twitter" class="form-label">Twitter URL</label>
                                                            <input type="url" class="form-control" id="social_twitter" name="social_twitter" 
                                                                   value="<?php echo htmlspecialchars($member['social_twitter'] ?? ''); ?>">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="social_linkedin" class="form-label">LinkedIn URL</label>
                                                            <input type="url" class="form-control" id="social_linkedin" name="social_linkedin" 
                                                                   value="<?php echo htmlspecialchars($member['social_linkedin'] ?? ''); ?>">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="sort_order" class="form-label">Sort Order</label>
                                                            <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                                                   value="<?php echo $member['sort_order'] ?? 0; ?>">
                                                            <small class="text-muted">Lower numbers appear first</small>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                                       <?php echo ($member['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="is_active">
                                                                    Active
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between">
                                                    <a href="team.php" class="btn btn-secondary">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">
                                                        <?php echo $action === 'new' ? 'Create Team Member' : 'Update Team Member'; ?>
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
        function deleteMember(id) {
            if (confirm('Are you sure you want to delete this team member?')) {
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