<?php
$page_title = "Blog Post";
$additional_css = [];
$additional_js = [];

include '../includes/header.php';

// Get blog slug from URL
$blog_slug = $_GET['slug'] ?? '';

if (empty($blog_slug)) {
    header("Location: blog.php");
    exit();
}

// Fetch blog post details from database
try {
    $stmt = $pdo->prepare("
        SELECT bp.*, u.full_name as author_name 
        FROM blog_posts bp 
        LEFT JOIN users u ON bp.author_id = u.id 
        WHERE bp.slug = ? AND bp.status = 'published'
    ");
    $stmt->execute([$blog_slug]);
    $blog_post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$blog_post) {
        header("Location: blog.php");
        exit();
    }
    
    // Update view count
    $update_stmt = $pdo->prepare("UPDATE blog_posts SET view_count = view_count + 1 WHERE id = ?");
    $update_stmt->execute([$blog_post['id']]);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    header("Location: blog.php");
    exit();
}

$page_title = $blog_post['title'];
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-image"></div>
    <div class="hero-content">
        <p class="tagline">Blog & News</p>
        <h1><?php echo htmlspecialchars($blog_post['title']); ?></h1>
        <div class="d-flex align-items-center justify-content-center text-white">
            <small class="me-3">
                <i class="fas fa-calendar me-1"></i>
                <?php echo date('M d, Y', strtotime($blog_post['published_at'])); ?>
            </small>
            <?php if ($blog_post['author_name']): ?>
                <small class="me-3">
                    <i class="fas fa-user me-1"></i>
                    <?php echo htmlspecialchars($blog_post['author_name']); ?>
                </small>
            <?php endif; ?>
            <small>
                <i class="fas fa-eye me-1"></i>
                <?php echo number_format($blog_post['view_count'] + 1); ?> views
            </small>
        </div>
    </div>
</section>

<!-- Blog Content Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="blog-post">
                    <?php if ($blog_post['featured_image']): ?>
                        <div class="mb-4">
                            <img src="<?php echo htmlspecialchars($blog_post['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($blog_post['title']); ?>" 
                                 class="img-fluid rounded shadow">
                        </div>
                    <?php endif; ?>
                    
                    <!-- Post Meta -->
                    <div class="mb-4">
                        <div class="d-flex flex-wrap align-items-center text-muted">
                            <span class="me-3">
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo date('F d, Y', strtotime($blog_post['published_at'])); ?>
                            </span>
                            <?php if ($blog_post['author_name']): ?>
                                <span class="me-3">
                                    <i class="fas fa-user me-1"></i>
                                    By <?php echo htmlspecialchars($blog_post['author_name']); ?>
                                </span>
                            <?php endif; ?>
                            <span>
                                <i class="fas fa-eye me-1"></i>
                                <?php echo number_format($blog_post['view_count'] + 1); ?> views
                            </span>
                        </div>
                    </div>
                    
                    <!-- Post Content -->
                    <div class="blog-content">
                        <?php if ($blog_post['excerpt']): ?>
                            <div class="lead mb-4">
                                <?php echo htmlspecialchars($blog_post['excerpt']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($blog_post['content'])); ?>
                        </div>
                    </div>
                    
                    <!-- Share Buttons -->
                    <div class="mt-5 pt-4 border-top">
                        <h5>Share this post:</h5>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f me-1"></i>Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($blog_post['title']); ?>" 
                               target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter me-1"></i>Twitter
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" class="btn btn-outline-secondary btn-sm">
                                <i class="fab fa-linkedin-in me-1"></i>LinkedIn
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Related Posts Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3>Related Posts</h3>
                <p class="lead text-muted">Read more from our blog</p>
            </div>
        </div>
        
        <div class="row">
            <?php
            // Fetch related blog posts (excluding current post)
            try {
                $stmt = $pdo->prepare("
                    SELECT bp.*, u.full_name as author_name 
                    FROM blog_posts bp 
                    LEFT JOIN users u ON bp.author_id = u.id 
                    WHERE bp.status = 'published' AND bp.id != ?
                    ORDER BY bp.published_at DESC
                    LIMIT 3
                ");
                $stmt->execute([$blog_post['id']]);
                $related_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $related_posts = [];
                error_log("Database error: " . $e->getMessage());
            }
            ?>
            
            <?php if (empty($related_posts)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No related posts found.</p>
                </div>
            <?php else: ?>
                <?php foreach ($related_posts as $related_post): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="card h-100 shadow-sm">
                            <?php if ($related_post['featured_image']): ?>
                                <img src="<?php echo htmlspecialchars($related_post['featured_image']); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($related_post['title']); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo date('M d, Y', strtotime($related_post['published_at'])); ?>
                                    </small>
                                    <?php if ($related_post['author_name']): ?>
                                        <small class="text-muted ms-3">
                                            <i class="fas fa-user me-1"></i>
                                            <?php echo htmlspecialchars($related_post['author_name']); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                                <h5 class="card-title">
                                    <a href="blog-detail.php?slug=<?php echo htmlspecialchars($related_post['slug']); ?>" 
                                       class="text-decoration-none">
                                        <?php echo htmlspecialchars($related_post['title']); ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars($related_post['excerpt'] ?: substr(strip_tags($related_post['content']), 0, 150) . '...'); ?>
                                </p>
                                <a href="blog-detail.php?slug=<?php echo htmlspecialchars($related_post['slug']); ?>" 
                                   class="btn btn-outline-primary btn-sm">
                                    Read More
                                </a>
                            </div>
                        </article>
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
                <h3>Stay Updated</h3>
                <p class="lead mb-4">Subscribe to our newsletter to get the latest updates on our projects and activities.</p>
                <a href="blog.php" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-newspaper me-2"></i>View All Posts
                </a>
                <a href="../features/donations/donation.php" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-heart me-2"></i>Support Our Cause
                </a>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?> 