<?php
$page_title = "Project Details";
$additional_css = [];
$additional_js = [];

include '../includes/header.php';

// Get project slug from URL
$project_slug = $_GET['slug'] ?? '';

if (empty($project_slug)) {
    header("Location: projects.php");
    exit();
}

// Fetch project details from database
try {
    $stmt = $pdo->prepare("
        SELECT * FROM projects 
        WHERE slug = ? AND status = 'active'
    ");
    $stmt->execute([$project_slug]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$project) {
        header("Location: projects.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    header("Location: projects.php");
    exit();
}

$page_title = $project['title'];
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-image"></div>
    <div class="hero-content">
        <p class="tagline">Our Projects</p>
        <h1><?php echo htmlspecialchars($project['title']); ?></h1>
        <p><?php echo htmlspecialchars($project['description']); ?></p>
    </div>
</section>

<!-- Project Details Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Project Image -->
            <div class="col-lg-6 mb-4">
                <?php if ($project['featured_image']): ?>
                    <img src="<?php echo htmlspecialchars($project['featured_image']); ?>" 
                         alt="<?php echo htmlspecialchars($project['title']); ?>" 
                         class="img-fluid rounded shadow">
                <?php else: ?>
                    <?php 
                    // Fallback images based on category
                    $fallback_image = '../assets/images/';
                    switch($project['category']) {
                        case 'health':
                            $fallback_image .= 'health.jpg';
                            break;
                        case 'education':
                            $fallback_image .= 'education.jpg';
                            break;
                        case 'agriculture':
                            $fallback_image .= 'well.jpg';
                            break;
                        default:
                            $fallback_image .= 'health.jpg';
                    }
                    ?>
                    <img src="<?php echo $fallback_image; ?>" 
                         alt="<?php echo htmlspecialchars($project['title']); ?>" 
                         class="img-fluid rounded shadow">
                <?php endif; ?>
            </div>
            
            <!-- Project Information -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <?php if ($project['is_featured']): ?>
                                <span class="badge bg-primary me-2">Featured Project</span>
                            <?php endif; ?>
                            <span class="badge bg-secondary"><?php echo ucfirst(htmlspecialchars($project['category'])); ?></span>
                        </div>
                        
                        <h2 class="card-title"><?php echo htmlspecialchars($project['title']); ?></h2>
                        <p class="card-text lead"><?php echo htmlspecialchars($project['description']); ?></p>
                        
                        <?php if ($project['content']): ?>
                            <div class="mb-4">
                                <?php echo nl2br(htmlspecialchars($project['content'])); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Project Statistics -->
                        <div class="row text-center mb-4">
                            <?php if ($project['target_amount']): ?>
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-1">$<?php echo number_format($project['target_amount']); ?></h4>
                                        <small class="text-muted">Target Amount</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($project['raised_amount']): ?>
                                <div class="col-6">
                                    <h4 class="text-success mb-1">$<?php echo number_format($project['raised_amount']); ?></h4>
                                    <small class="text-muted">Raised Amount</small>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($project['target_amount'] && $project['raised_amount']): ?>
                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">Progress</small>
                                    <small class="text-muted">
                                        <?php echo round(($project['raised_amount'] / $project['target_amount']) * 100); ?>%
                                    </small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?php echo min(100, ($project['raised_amount'] / $project['target_amount']) * 100); ?>%">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Project Details -->
                        <div class="row mb-4">
                            <?php if ($project['location']): ?>
                                <div class="col-6 mb-3">
                                    <strong>Location:</strong><br>
                                    <span class="text-muted"><?php echo htmlspecialchars($project['location']); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($project['beneficiaries_count']): ?>
                                <div class="col-6 mb-3">
                                    <strong>Beneficiaries:</strong><br>
                                    <span class="text-muted"><?php echo number_format($project['beneficiaries_count']); ?> people</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($project['start_date']): ?>
                                <div class="col-6 mb-3">
                                    <strong>Start Date:</strong><br>
                                    <span class="text-muted"><?php echo date('M d, Y', strtotime($project['start_date'])); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($project['end_date']): ?>
                                <div class="col-6 mb-3">
                                    <strong>End Date:</strong><br>
                                    <span class="text-muted"><?php echo date('M d, Y', strtotime($project['end_date'])); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="../features/donations/donation.php?project_id=<?php echo $project['id']; ?>" 
                               class="btn btn-primary btn-lg">
                                <i class="fas fa-heart me-2"></i>Support This Project
                            </a>
                            <a href="projects.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to All Projects
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Projects Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3>Other Projects You Might Like</h3>
                <p class="lead text-muted">Explore our other initiatives making a difference</p>
            </div>
        </div>
        
        <div class="row">
            <?php
            // Fetch related projects (same category, excluding current project)
            try {
                $stmt = $pdo->prepare("
                    SELECT * FROM projects 
                    WHERE category = ? AND id != ? AND status = 'active'
                    ORDER BY is_featured DESC, created_at DESC
                    LIMIT 3
                ");
                $stmt->execute([$project['category'], $project['id']]);
                $related_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $related_projects = [];
                error_log("Database error: " . $e->getMessage());
            }
            ?>
            
            <?php if (empty($related_projects)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No related projects found.</p>
                </div>
            <?php else: ?>
                <?php foreach ($related_projects as $related_project): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="mb-2">
                                    <?php if ($related_project['is_featured']): ?>
                                        <span class="badge bg-primary">Featured</span>
                                    <?php endif; ?>
                                    <span class="badge bg-secondary ms-1"><?php echo ucfirst(htmlspecialchars($related_project['category'])); ?></span>
                                </div>
                                <h5 class="card-title"><?php echo htmlspecialchars($related_project['title']); ?></h5>
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars(substr($related_project['description'], 0, 120) . '...'); ?>
                                </p>
                                <a href="project-detail.php?slug=<?php echo htmlspecialchars($related_project['slug']); ?>" 
                                   class="btn btn-outline-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?> 