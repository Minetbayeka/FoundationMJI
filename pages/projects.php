<?php
$page_title = "Our Projects";
$additional_css = [];
$additional_js = [];

include '../includes/header.php';

// Fetch projects from database
try {
    $stmt = $pdo->prepare("
        SELECT * FROM projects 
        WHERE status = 'active' 
        ORDER BY is_featured DESC, created_at DESC
    ");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $projects = [];
    error_log("Database error: " . $e->getMessage());
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-image"></div>
    <div class="hero-content">
        <p class="tagline">Making a difference in communities</p>
        <h1>Our Projects</h1>
        <p>Discover the impactful projects we're working on to help vulnerable children and communities</p>
    </div>
</section>

<!-- Featured Projects Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="mb-3">Featured Projects</h2>
                <p class="lead text-muted">Our flagship initiatives making the biggest impact</p>
            </div>
        </div>
        
        <div class="row">
            <?php 
            $featured_projects = array_filter($projects, function($project) {
                return $project['is_featured'] == 1;
            });
            ?>
            
            <?php if (empty($featured_projects)): ?>
                <div class="col-12 text-center">
                    <div class="py-5">
                        <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                        <h3>No Featured Projects Yet</h3>
                        <p class="text-muted">We're working on some amazing projects. Check back soon!</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($featured_projects as $project): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm project-card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <span class="badge bg-primary">Featured</span>
                                    <span class="badge bg-secondary ms-1"><?php echo ucfirst(htmlspecialchars($project['category'])); ?></span>
                                </div>
                                <h5 class="card-title"><?php echo htmlspecialchars($project['title']); ?></h5>
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars(substr($project['description'], 0, 150) . '...'); ?>
                                </p>
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Target</small>
                                        <div class="fw-bold">$<?php echo number_format($project['target_amount']); ?></div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Beneficiaries</small>
                                        <div class="fw-bold"><?php echo number_format($project['beneficiaries_count']); ?></div>
                                    </div>
                                </div>
                                <a href="project-detail.php?slug=<?php echo htmlspecialchars($project['slug']); ?>" 
                                   class="btn btn-primary w-100">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- All Projects Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="mb-3">All Our Projects</h2>
                <p class="lead text-muted">Complete list of our ongoing and planned initiatives</p>
            </div>
        </div>
        
        <div class="row">
            <?php if (empty($projects)): ?>
                <div class="col-12 text-center">
                    <div class="py-5">
                        <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                        <h3>No Projects Yet</h3>
                        <p class="text-muted">We're working on some amazing projects. Check back soon!</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm project-card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <?php if ($project['is_featured']): ?>
                                        <span class="badge bg-primary">Featured</span>
                                    <?php endif; ?>
                                    <span class="badge bg-secondary ms-1"><?php echo ucfirst(htmlspecialchars($project['category'])); ?></span>
                                    <span class="badge bg-<?php echo $project['status'] == 'active' ? 'success' : 'warning'; ?> ms-1">
                                        <?php echo ucfirst(htmlspecialchars($project['status'])); ?>
                                    </span>
                                </div>
                                <h5 class="card-title"><?php echo htmlspecialchars($project['title']); ?></h5>
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars(substr($project['description'], 0, 120) . '...'); ?>
                                </p>
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Target</small>
                                        <div class="fw-bold">$<?php echo number_format($project['target_amount']); ?></div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Location</small>
                                        <div class="fw-bold small"><?php echo htmlspecialchars($project['location']); ?></div>
                                    </div>
                                </div>
                                <a href="project-detail.php?slug=<?php echo htmlspecialchars($project['slug']); ?>" 
                                   class="btn btn-outline-primary w-100">
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

<!-- Call to Action -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h3>Want to Support Our Projects?</h3>
                <p class="lead mb-4">Your donation can make a real difference in the lives of vulnerable children and communities.</p>
                <a href="../features/donations/donation.php" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-heart me-2"></i>Donate Now
                </a>
                <a href="get-involved.php" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-hands-helping me-2"></i>Get Involved
                </a>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?> 