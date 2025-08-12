<?php
$page_title = "About Us";
$additional_css = [];
$additional_js = [];

include '../includes/header.php';

// Fetch team members from database
try {
    $stmt = $pdo->prepare("SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order ASC, name ASC");
    $stmt->execute();
    $team_members = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $team_members = [];
    error_log("Database error: " . $e->getMessage());
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-image"></div>
    <div class="hero-content">
        <p class="tagline">All about our organization</p>
        <h1>About Mother Jane Legacy Foundation</h1>
        <p>Learn about our mission, vision, and the people behind our organization</p>
    </div>
</section>

<!-- Founder Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <img src="../assets/images/founder.jpg" alt="Dr Akwa Gilbert Mua" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6 mb-4">
                <div>
                    <h2 class="text-uppercase text-muted mb-2">Founder</h2>
                    <h1 class="mb-4">Dr Akwa Gilbert Mua</h1>
                    <p class="lead">Dr Akwa Gilbert is a medical doctor and passionate for the poor and the needy. His story is a beautiful one because he started from below and has accomplished many things.</p>
                    <p>"Everything I have accomplished so far is thanks to my mother of blessed Memory Rev. Sr. Jane Mankaa, a woman who dedicated her life to serving orphans and vulnerable children through education, Health and agriculture. She taught me (Dr Gilbert) to have a vision, dream to reach out to the poor and needy particularly to the orphans and vulnerable children."</p>
                    <p class="mb-0">Mother Jane never failed to remind me constantly that I was picked from the street and constantly encouraged me to take care of the poor and needy in my later life. I am eternally grateful to mother Jane and has taken up this legacy and I have decided to continue in her footstep.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Health Need Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <div class="p-4">
                    <p class="lead">We engage in meaningful actions that leaves no one behind in ensuring children (boys and girls) as well as their parents meet their health needs.</p>
                </div>
            </div>
                               <div class="col-lg-6 mb-4">
                       <div class="ratio ratio-16x9">
                           <iframe src="https://www.youtube.com/embed/XIM4toNMhqY?si=x8G2a5cntgIehj-4" 
                                   title="Mother Jane Legacy Foundation Video" 
                                   frameborder="0" 
                                   allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                   allowfullscreen 
                                   class="rounded shadow">
                           </iframe>
                       </div>
                   </div>
        </div>
    </div>
</section>

<!-- What We Do Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <img src="../assets/images/blog9.jpg" alt="Our Work" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6 mb-4">
                <div>
                    <h2 class="text-uppercase text-muted mb-2">What We Do</h2>
                    <h1 class="mb-4">About Our Work</h1>
                    <p class="lead">We accompany each project and allocate funds according to needs. Each contribution will be spent for its intended purpose.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Health assistance</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Education</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Psycho-social support/Counseling</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Agriculture</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Economic empowerment</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Meet Our Team Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-uppercase text-muted mb-2">Meet Our Team</h2>
            <h1 class="mb-4">Do it for humanity. Join us</h1>
        </div>
        
        <?php if (empty($team_members)): ?>
            <!-- Fallback to static content if no team members in database -->
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100">
                        <img src="../assets/images/Akwa.jpg" alt="Dr. Akwa Gilbert Mua" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Dr. Akwa Gilbert Mua</h5>
                            <p class="card-text text-muted">Executive Director</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100">
                        <img src="../assets/images/Ateh.jpg" alt="Dr. Ateh Cavour" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Dr. Ateh Cavour</h5>
                            <p class="card-text text-muted">Field Agent</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100">
                        <img src="../assets/images/fon.jpg" alt="Fon Blaise Fru" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Fon Blaise Fru</h5>
                            <p class="card-text text-muted">Media Manager</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100">
                        <img src="../assets/images/tita.jpg" alt="Aben Cistus Tita" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Aben Cistus Tita</h5>
                            <p class="card-text text-muted">Field Agent</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Dynamic team members from database -->
            <div class="row">
                <?php foreach ($team_members as $member): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card text-center h-100">
                            <?php if ($member['image']): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($member['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($member['name']); ?>" class="card-img-top">
                            <?php else: ?>
                                <img src="../assets/images/doctor.jpg" 
                                     alt="<?php echo htmlspecialchars($member['name']); ?>" class="card-img-top">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($member['name']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($member['position']); ?></p>
                                <?php if ($member['bio']): ?>
                                    <p class="card-text small"><?php echo htmlspecialchars(substr($member['bio'], 0, 100)) . (strlen($member['bio']) > 100 ? '...' : ''); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if ($member['social_facebook'] || $member['social_twitter'] || $member['social_linkedin']): ?>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if ($member['social_facebook']): ?>
                                            <a href="<?php echo htmlspecialchars($member['social_facebook']); ?>" class="text-muted" target="_blank">
                                                <i class="fab fa-facebook"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($member['social_twitter']): ?>
                                            <a href="<?php echo htmlspecialchars($member['social_twitter']); ?>" class="text-muted" target="_blank">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($member['social_linkedin']): ?>
                                            <a href="<?php echo htmlspecialchars($member['social_linkedin']); ?>" class="text-muted" target="_blank">
                                                <i class="fab fa-linkedin"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="mb-4">Ready to Make a Difference?</h2>
                <p class="lead mb-4">Join us in our mission to help underprivileged children and communities.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="get-involved.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-heart me-2"></i>Volunteer Now
                    </a>
                    <a href="../features/donations/donation.php" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-donate me-2"></i>Donate Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card-img-top {
    height: 250px;
    object-fit: cover;
    border-radius: 15px 15px 0 0;
}

.btn-primary {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
    border-radius: 8px;
    padding: 0.75rem 2rem;
}

.btn-primary:hover {
    background-color: #e54d1a;
    border-color: #e54d1a;
}

.btn-outline-primary {
    border-color: var(--accent-color);
    color: var(--accent-color);
}

.btn-outline-primary:hover {
    background-color: var(--accent-color);
    border-color: var(--accent-color);
}

@media (max-width: 768px) {
    .card-img-top {
        height: 200px;
    }
}
</style>

<?php include '../includes/footer.php'; ?> 