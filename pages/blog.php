<?php
$page_title = "Blog";
$additional_css = [];
$additional_js = [];

include '../includes/header.php';

// Fetch blog posts from database
try {
    $stmt = $pdo->prepare("
        SELECT bp.*, u.full_name as author_name 
        FROM blog_posts bp 
        LEFT JOIN users u ON bp.author_id = u.id 
        WHERE bp.status = 'published' 
        ORDER BY bp.published_at DESC
    ");
    $stmt->execute();
    $blog_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $blog_posts = [];
    error_log("Database error: " . $e->getMessage());
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-image"></div>
    <div class="hero-content">
        <p class="tagline">Stay updated with our latest news</p>
        <h1>Blog & News</h1>
        <p>Read about our latest activities, success stories, and community impact</p>
    </div>
</section>

<!-- Blog Posts Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <?php if (empty($blog_posts)): ?>
                <div class="col-12 text-center">
                    <div class="py-5">
                        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                        <h3>No Blog Posts Yet</h3>
                        <p class="text-muted">We're working on some great content. Check back soon!</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($blog_posts as $post): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="card h-100 shadow-sm">
                            <?php if ($post['featured_image']): ?>
                                <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo date('M d, Y', strtotime($post['published_at'])); ?>
                                    </small>
                                    <?php if ($post['author_name']): ?>
                                        <small class="text-muted ms-3">
                                            <i class="fas fa-user me-1"></i>
                                            <?php echo htmlspecialchars($post['author_name']); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                                <h5 class="card-title">
                                    <a href="blog-detail.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" 
                                       class="text-decoration-none">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 150) . '...'); ?>
                                </p>
                                <a href="blog-detail.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" 
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

<?php include '../includes/footer.php'; ?> 