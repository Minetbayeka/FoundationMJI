<?php
$page_title = "Get Involved";
$additional_css = [];
$additional_js = [];

include '../includes/header.php';

// Handle volunteer form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['volunteer_form'])) {
    $volunteer_name = trim($_POST['volunteer_name'] ?? '');
    $volunteer_email = trim($_POST['volunteer_email'] ?? '');
    $volunteer_phone = trim($_POST['volunteer_phone'] ?? '');
    $volunteer_skills = trim($_POST['volunteer_skills'] ?? '');
    $volunteer_message = trim($_POST['volunteer_message'] ?? '');
    
    $volunteer_errors = [];
    
    // Validation
    if (empty($volunteer_name)) $volunteer_errors[] = "Name is required";
    if (empty($volunteer_email)) $volunteer_errors[] = "Email is required";
    if (empty($volunteer_skills)) $volunteer_errors[] = "Skills/interests are required";
    
    if (!empty($volunteer_email) && !filter_var($volunteer_email, FILTER_VALIDATE_EMAIL)) {
        $volunteer_errors[] = "Please enter a valid email address";
    }
    
    if (empty($volunteer_errors)) {
        $volunteer_success = "Thank you for your interest in volunteering! We'll contact you soon.";
        $volunteer_name = $volunteer_email = $volunteer_phone = $volunteer_skills = $volunteer_message = '';
    }
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-image"></div>
    <div class="hero-content">
        <h1>Become a Volunteer</h1>
        <p>Help our cause by Volunteering today</p>
    </div>
</section>

<!-- Hero Images Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <img src="../assets/images/blog13.jpg" alt="Volunteer Work" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6 mb-4">
                <img src="../assets/images/blog12.jpg" alt="Community Service" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Join Us on Our Mission -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <div>
                    <h2 class="text-uppercase text-muted mb-2">Join us on our mission</h2>
                    <h1 class="mb-4">We are bringing people together to end poverty for good.</h1>
                    <p class="lead">Measuring poverty also means measuring people's well-being. We give children in poverty the tools they need to create brighter futures for themselves and their communities. With the support of our donors and sponsors, children and youth gain the skills and confidence they need to create promising futures free from poverty.</p>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <img src="../assets/images/knowledge.jpg" alt="Education and Knowledge" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- What We Do Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <img src="../assets/images/blog9.jpg" alt="Community Work" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6 mb-4">
                <div>
                    <h2 class="text-uppercase text-muted mb-2">What we do</h2>
                    <h1 class="mb-4">We do it for all people.</h1>
                    <p class="lead">We seek to provide better health assistance, education, psychosocial support, economic empowerment, and agriculture to the poor and underprivileged children. We aim to achieve our vision by carrying out the following actions:</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Providing consultation, laboratory tests, medications, psychosocial support/counseling, surgeries and follow-up of patients in community</li>
                        <li><i class="fas fa-check text-success me-2"></i>Providing school materials and tuition fees to children that are unable to afford education costs</li>
                        <li><i class="fas fa-check text-success me-2"></i>Promoting agricultural practices among orphans and vulnerable children</li>
                    </ul>
                    <p class="mt-3">We kindly request you to be a part of this mission. This is giving hope to the hopeless and helping people to build sustainable livelihoods.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Donate Now Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-uppercase text-muted mb-2">Donate Now</h2>
            <h1 class="mb-4">Ways You Can Donate</h1>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-user fa-3x text-secondary"></i>
                        </div>
                        <h4>In Person</h4>
                        <p>You can visit us at our address to donate in person.</p>
                        <p class="text-muted"><strong>Mankon - Bamenda, Cameroon</strong></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-globe fa-3x text-secondary"></i>
                        </div>
                        <h4>Online</h4>
                        <p>Donate online via our secure donation platform</p>
                        <a href="../features/donations/donation.php" class="btn btn-secondary text-white">Donate Now</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-phone fa-3x text-secondary"></i>
                        </div>
                        <h4>Over the Phone</h4>
                        <p>Offline donation is possible by calling us.</p>
                        <p class="text-muted"><strong>+237 6 79267828</strong></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-envelope fa-3x text-secondary"></i>
                        </div>
                        <h4>By Mail</h4>
                        <p>You can write to us via email to learn more about how to donate.</p>
                        <p class="text-muted"><strong>contact@mjlegacyfoundation.org</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Volunteer Form Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2>Become a Volunteer</h2>
                            <p class="text-muted">Join our team and make a difference in the lives of underprivileged children</p>
                        </div>
                        
                        <?php if (!empty($volunteer_errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($volunteer_errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($volunteer_success)): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($volunteer_success); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <input type="hidden" name="volunteer_form" value="1">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="volunteer_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="volunteer_name" name="volunteer_name" 
                                           value="<?php echo htmlspecialchars($volunteer_name ?? ''); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="volunteer_email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="volunteer_email" name="volunteer_email" 
                                           value="<?php echo htmlspecialchars($volunteer_email ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="volunteer_phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="volunteer_phone" name="volunteer_phone" 
                                           value="<?php echo htmlspecialchars($volunteer_phone ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="volunteer_skills" class="form-label">Skills/Interests *</label>
                                    <input type="text" class="form-control" id="volunteer_skills" name="volunteer_skills" 
                                           value="<?php echo htmlspecialchars($volunteer_skills ?? ''); ?>" 
                                           placeholder="e.g., Teaching, Healthcare, IT, etc." required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="volunteer_message" class="form-label">Why do you want to volunteer with us?</label>
                                <textarea class="form-control" id="volunteer_message" name="volunteer_message" rows="4" 
                                          placeholder="Tell us about your motivation and how you'd like to help..."><?php echo htmlspecialchars($volunteer_message ?? ''); ?></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-heart me-2"></i>Join Our Volunteer Team
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Statistics -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="counter-item">
                    <h2 class="counter" data-target="500">0</h2>
                    <p>Children Helped</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="counter-item">
                    <h2 class="counter" data-target="50">0</h2>
                    <p>Volunteers</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="counter-item">
                    <h2 class="counter" data-target="25">0</h2>
                    <p>Communities Served</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="counter-item">
                    <h2 class="counter" data-target="1000">0</h2>
                    <p>Lives Impacted</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="mb-4">Ready to Make a Difference?</h2>
                <p class="lead mb-4">Every contribution, no matter how small, helps us create positive change in the lives of underprivileged children.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="../features/donations/donation.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-heart me-2"></i>Donate Now
                    </a>
                    <a href="contact.php" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>Contact Us
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
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.text-primary {
    color: var(--accent-color) !important;
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

.form-control {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 0.75rem;
}

.form-control:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.2rem rgba(103, 189, 176, 0.25);
}

.bg-primary {
    background-color: var(--primary-color) !important;
}

.counter-item h2 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.counter-item p {
    font-size: 1.1rem;
    opacity: 0.9;
}

@media (max-width: 768px) {
    .counter-item h2 {
        font-size: 2rem;
    }
    
    .counter-item p {
        font-size: 1rem;
    }
}
</style>

<?php include '../includes/footer.php'; ?> 