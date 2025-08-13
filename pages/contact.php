<?php
$page_title = "Contact Us";
$additional_css = [];
$additional_js = [];

include '../includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    $errors = [];
    
    // Validation
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($subject)) $errors[] = "Subject is required";
    if (empty($message)) $errors[] = "Message is required";
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    if (empty($errors)) {
        // Here you would typically save to database and send email
        // For now, we'll just show a success message
        $success_message = "Thank you for your message! We'll get back to you soon.";
        
        // Clear form data
        $name = $email = $phone = $subject = $message = '';
    }
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-image"></div>
    <div class="hero-content">
        <h1>Feel free to contact us</h1>
        <p>We're here to help and answer any questions you might have</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 mb-4">
                <div class="contact-info">
                    <p class="text-uppercase text-muted mb-2">GET IN TOUCH</p>
                    <h2 class="mb-4">Connect with us today.<br>Let's talk together.</h2>
                    <p class="text-muted mb-4">
                        Like what we stand for? Want more information?<br>
                        Need assistance?
                    </p>

                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-primary me-3 text-black"></i>
                            <span>Mankon - Bamenda, Cameroon</span>
                        </div>
                    </div>
                    
                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone text-primary me-3 text-black"></i>
                            <span>+237 6 79367828</span>
                        </div>
                    </div>
                    
                    <div class="contact-item mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-primary me-3 text-black"></i>
                            <a href="mailto:contact@mjlegacyfoundation.org">contact@mjlegacyfoundation.org</a>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="social-links">
                        <h5 class="mb-3">Follow Us</h5>
                        <div class="d-flex gap-3">
                            <a href="#" class="btn btn-outline-primary text-white"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn btn-outline-primary text-white"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn btn-outline-primary text-white"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="contact-form">
                    <div class="card shadow">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-4">Send us a message</h3>
                            
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($success_message)): ?>
                                <div class="alert alert-success">
                                    <?php echo htmlspecialchars($success_message); ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="subject" class="form-label">Subject *</label>
                                        <input type="text" class="form-control" id="subject" name="subject" 
                                               value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" 
                                              required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">Find Us</h3>
                <div class="ratio ratio-21x9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.819123456789!2d10.123456789012345!3d5.123456789012345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMDcnMjQuNCJOIDEwwrAwNycwMC4wIkU!5e0!3m2!1sen!2scm!4v1234567890123" 
                            style="border:0;" allowfullscreen="" loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-5">Frequently Asked Questions</h3>
                
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                How can I donate to MJL Foundation?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You can donate through our online donation platform, bank transfer, or by visiting our office. Visit our <a href="../features/donations/donation.php">donation page</a> for more details.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                How can I volunteer with your organization?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We welcome volunteers! Please visit our <a href="get-involved.php">Get Involved</a> page to learn about volunteer opportunities and fill out our volunteer application form.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                What areas do you serve?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We primarily serve communities in and around Bamenda, Cameroon, with a focus on rural areas and conflict zones where access to healthcare and education is limited.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.contact-item {
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
}

.contact-item:last-child {
    border-bottom: none;
}

.contact-form .card {
    border: none;
    border-radius: 15px;
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

.accordion-button:not(.collapsed) {
    background-color: var(--accent-color);
    color: white;
}

.accordion-button:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.25rem rgba(103, 189, 176, 0.25);
}
</style>

<?php include '../includes/footer.php'; ?> 