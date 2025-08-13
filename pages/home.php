<?php
$page_title = "Home";
$additional_css = [];
$additional_js = [];

include '../includes/header.php';

// Fetch featured projects from database
try {
    $stmt = $pdo->prepare("
        SELECT * FROM projects 
        WHERE is_featured = 1 AND status = 'active' 
        ORDER BY created_at ASC
        LIMIT 3
    ");
    $stmt->execute();
    $featured_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featured_projects = [];
    error_log("Database error: " . $e->getMessage());
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-image"></div>
    <div class="hero-content">
        <p class="tagline">YES, IT'S POSSIBLE</p>
        <h1>A world where the rights &<br> dignity of the underprivilege<br> are met</h1>
        <a href="#about" class="learn-btn">Learn More</a>
    </div>
</section>

<!-- Vision Section -->
<section class="vission-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <h3>Our Vision</h3>
                    <p>
                        We envisage communities in which women, men, youths and children can claim/exercise their health, economic, civil and social rights, so as to live dignified and fulfilling lives.
                    </p>
                    <a href="about.php">Learn More</a>
                    <div class="card-border teal"></div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <h3>About Mother Jane Foundation</h3>
                    <p>
                        We engage in meaningful actions that leaves no one behind in ensuring children (boys and girls) as well as their parents meet their health needs.
                    </p>
                    <a href="about.php">Learn More</a>
                    <div class="card-border orange"></div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <h3>Our Mission</h3>
                    <p>
                        MJ Legacy foundation seeks to provide better health assistance, education, psychosocial support, economic empowerment, agriculture to the poor and the underprivileged children.
                    </p>
                    <a href="contact.php">Contact us</a>
                    <div class="card-border teal"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mother Jane Legacy Section -->
<section class="container">
    <div class="title">
        <h1>Mother Jane Legacy</h1>
        <p>Mother Jane being a Mother of all children with a legacy of finding, raising and providing to those who are in need.</p>
    </div>
    
    <div class="row">
        <?php if (empty($featured_projects)): ?>
            <!-- Fallback to static content if no featured projects in database -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-1">
                    <img src="../assets/images/health.jpg" alt="Health Problem">
                    <h3>Health Problem</h3>
                    <p>Coupled with the ongoing Anglophone crisis, many rural areas / conflict zones have no health facilities.</p>
                    <a href="../features/donations/donation.php" class="donate-button">Donate Now</a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-1">
                    <img src="../assets/images/well.jpg" alt="Well Being">
                    <h3>Well Being</h3>
                    <p>The impact of the crisis has caused many to lose their jobs and everyone has turned to farming as the last resort.</p>
                    <a href="../features/donations/donation.php" class="donate-button">Donate Now</a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-1">
                    <img src="../assets/images/education.jpg" alt="Education">
                    <h3>Education</h3>
                    <p>Going to school becomes impossible to some people.</p>
                    <a href="../features/donations/donation.php" class="donate-button">Donate Now</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($featured_projects as $project): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card-1">
                        <?php if ($project['featured_image']): ?>
                            <img src="<?php echo htmlspecialchars($project['featured_image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
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
                            <img src="<?php echo $fallback_image; ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p><?php echo htmlspecialchars($project['description']); ?></p>
                        <a href="project-detail.php?slug=<?php echo htmlspecialchars($project['slug']); ?>" class="donate-button">Learn More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- About Us Section -->
<section class="container-one" id="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <div class="image-container">
                    <img src="../assets/images/blog7.jpg" alt="Foundation image" class="base-img">
                    <img src="../assets/images/help.jpg" alt="Heart icon" class="overlay-img">
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="text-section">
                    <h1>About Us</h1>
                    <h2>We are tender heart MJL Foundation.</h2>
                    <p>The charitable foundation is created by people who know from their own experience about life's difficulties. We want to return faith in good and give hope to those in need.</p>
                    <div class="links">
                        <a href="about.php">Who are we</a>
                        <a href="projects.php">Our Project</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Meet the Visionary Section -->
<section class="meet-visionary">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <div>
                    <h1>Meet the visionary</h1>
                    <h2>We work together! We listen. We advice.</h2>
                    <p>We use rights-based approaches to development to promote women/men/boys/girls health, human rights and livelihoods in inclusive and environmentally conscious and friendly ways with a recognition of the importance of the intersection of multiple inequalities based on the intersection of the different positions that women and girls as well as men and boys hold in relation to gender, ethnicity, class, and other social categories.</p>
                    <button class="btn">
                        Volunteer Now
                    </button>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div>
                    <img src="../assets/images/blog8.jpg" alt="Visionary Image" class="visionary-image">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Vision Map -->
<section class="vission">
    <div class="container">
        <div class="position-relative">
            <img src="../assets/images/map.jpg" alt="World Map" class="img-fluid">
            <div class="visson-content">
                <h1 class="text-dark">Our Vision</h1>
                <p class="text-dark">A world where the rights and dignity of underprivileged children are met</p>
            </div>
        </div>
    </div>
</section>

<!-- Donate and Help Section -->
<section class="donate">
    <div class="container">
        <div class="position-relative">
            <img src="../assets/images/donate.jpg" alt="Donation" class="img-fluid" style="width: 100%;">
            <div class="donate-content">
                <h1><i class="fas fa-heart"></i></h1>
                <h2>Be of help to the helpless</h2>
                <a href="../features/donations/donation.php" class="btn">Donate Now</a>
            </div>
        </div> 
    </div>
</section>

<!-- Mission Section -->
<section class="mission">
    <div class="container">
        <h1>Our Mission and Goals</h1>
        <p class="faith">The charitable foundation is created by people who know from their own experience about life's difficulties. We want to return faith in good and give hope to those in need.</p>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card1">
                    <div class="card-title">
                        <img src="../assets/images/heart.png" alt="Health" />
                        <h1>Health</h1> 
                    </div>
                    <div>
                        <p>With greater funding resources, our target is to visit rural communities and provide the following with the team:</p>
                        <div>
                            <p>1. Consultations</p>
                            <p>2. Laboratory investigations</p>
                            <p>3. Medications</p>
                            <p>4. Counselling</p>
                            <p>5. Health awareness campaigns</p>
                            <p>6. Surgeries</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card1">
                    <div class="card-title">
                        <img src="../assets/images/book.png" alt="Education" />
                        <h1>Education</h1> 
                    </div>
                    <div>
                        <p>Education should be provided a platform to study, with sufficient funding for all educational materials needed.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card1">
                    <div class="card-title">
                        <img src="../assets/images/leave.png" alt="Agriculture" />
                        <h1>Agriculture</h1> 
                    </div>
                    <div>
                        <p>To further ensure that children can sustain themselves with agricultural practices, we provide materials to help.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card1">
                    <div class="card-title">
                        <img src="../assets/images/icon1.png" alt="Support" />
                        <h1>Psycho-social Support / Counseling</h1> 
                    </div>
                    <div>
                        <p>Due to the impact of the ongoing crisis, there is a need for psycho-social support to help those affected.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8 col-md-6 mb-4">
                <div class="volunteer">
                    <h1>Become a Volunteer</h1>
                    <h2>Join us bring help to the needy? Send your details.</h2>
                    <p>You can help out in your own way. Join our team of volunteers to bring the help that this children need</p>
                    <a href="get-involved.php" class="btn">Join Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?> 