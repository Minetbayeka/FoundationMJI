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
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $status = $_POST['status'] ?? 'draft';
        $meta_title = trim($_POST['meta_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $meta_keywords = trim($_POST['meta_keywords'] ?? '');
        
        // Generate slug from title
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        if (empty($title) || empty($content)) {
            $message = '<div class="alert alert-danger">Title and content are required.</div>';
        } else {
            try {
                if ($action === 'new') {
                    $stmt = $pdo->prepare("
                        INSERT INTO blog_posts (title, slug, excerpt, content, author_id, status, meta_title, meta_description, meta_keywords, published_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;
                    $stmt->execute([$title, $slug, $excerpt, $content, $_SESSION['user_id'], $status, $meta_title, $meta_description, $meta_keywords, $published_at]);
                    $message = '<div class="alert alert-success">Blog post created successfully!</div>';
                    $action = 'list';
                } else {
                    $id = $_POST['id'] ?? 0;
                    $stmt = $pdo->prepare("
                        UPDATE blog_posts 
                        SET title = ?, slug = ?, excerpt = ?, content = ?, status = ?, meta_title = ?, meta_description = ?, meta_keywords = ?, published_at = ?
                        WHERE id = ?
                    ");
                    $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;
                    $stmt->execute([$title, $slug, $excerpt, $content, $status, $meta_title, $meta_description, $meta_keywords, $published_at, $id]);
                    $message = '<div class="alert alert-success">Blog post updated successfully!</div>';
                    $action = 'list';
                }
            } catch (PDOException $e) {
                $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
            }
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
            $stmt->execute([$id]);
            $message = '<div class="alert alert-success">Blog post deleted successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
        $action = 'list';
    }
}

// Get blog posts for listing
$blog_posts = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("
            SELECT bp.*, u.full_name as author_name 
            FROM blog_posts bp 
            LEFT JOIN users u ON bp.author_id = u.id 
            ORDER BY bp.created_at DESC
        ");
        $blog_posts = $stmt->fetchAll();
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading blog posts: ' . $e->getMessage() . '</div>';
    }
}

// Get single post for editing
$post = null;
if ($action === 'edit' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $post = $stmt->fetch();
        if (!$post) {
            $message = '<div class="alert alert-danger">Blog post not found.</div>';
            $action = 'list';
        }
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error loading blog post: ' . $e->getMessage() . '</div>';
        $action = 'list';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts - Admin Dashboard</title>
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
                        <a class="nav-link active" href="blog-posts.php">
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
                            <h4 class="mb-0">
                                <?php if ($action === 'new'): ?>
                                    New Blog Post
                                <?php elseif ($action === 'edit'): ?>
                                    Edit Blog Post
                                <?php else: ?>
                                    Blog Posts
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
                            <!-- Blog Posts List -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5>All Blog Posts</h5>
                                <a href="?action=new" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>New Blog Post
                                </a>
                            </div>
                            
                            <div class="table-card">
                                <div class="card-body">
                                    <?php if (empty($blog_posts)): ?>
                                        <p class="text-muted  mx-3">No blog posts found.</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Author</th>
                                                        <th>Status</th>
                                                        <th>Created</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($blog_posts as $post): ?>
                                                        <tr>
                                                            <td>
                                                                <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                                                                <br><small class="text-muted"><?php echo htmlspecialchars($post['slug']); ?></small>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($post['author_name'] ?? 'Unknown'); ?></td>
                                                            <td>
                                                                <span class="badge bg-<?php echo $post['status'] === 'published' ? 'success' : ($post['status'] === 'draft' ? 'warning' : 'secondary'); ?>">
                                                                    <?php echo ucfirst($post['status']); ?>
                                                                </span>
                                                            </td>
                                                            <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                                            <td>
                                                                <a href="?action=edit&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <button class="btn btn-sm btn-outline-danger" onclick="deletePost(<?php echo $post['id']; ?>)">
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
                            <!-- Blog Post Form -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-card">
                                        <div class="card-body">
                                            <form method="POST">
                                                <?php if ($action === 'edit'): ?>
                                                    <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                                                <?php endif; ?>
                                                
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="mb-3">
                                                            <label for="title" class="form-label">Title *</label>
                                                            <input type="text" class="form-control" id="title" name="title" 
                                                                   value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="excerpt" class="form-label">Excerpt</label>
                                                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="content" class="form-label">Content *</label>
                                                            <textarea class="form-control" id="content" name="content" rows="15" required><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="status" class="form-label">Status</label>
                                                            <select class="form-select" id="status" name="status">
                                                                <option value="draft" <?php echo ($post['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                                                <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                                                                <option value="archived" <?php echo ($post['status'] ?? '') === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="meta_title" class="form-label">Meta Title</label>
                                                            <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                                                   value="<?php echo htmlspecialchars($post['meta_title'] ?? ''); ?>">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="meta_description" class="form-label">Meta Description</label>
                                                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3"><?php echo htmlspecialchars($post['meta_description'] ?? ''); ?></textarea>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                                                   value="<?php echo htmlspecialchars($post['meta_keywords'] ?? ''); ?>">
                                                            <small class="text-muted">Separate keywords with commas</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between">
                                                    <a href="blog-posts.php" class="btn btn-secondary">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">
                                                        <?php echo $action === 'new' ? 'Create Post' : 'Update Post'; ?>
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
        function deletePost(id) {
            if (confirm('Are you sure you want to delete this blog post?')) {
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