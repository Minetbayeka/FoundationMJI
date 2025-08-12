    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section about">
                        <div class="logo-footer d-flex align-items-center mb-3">
                            <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="logo-image" height="40" class="me-2"/>
                            MJL Foundation
                        </div>
                        <p>Good deeds are available to everyone! And there are a lot of responsive people around who are ready to help.</p>
                    </div>
                </div>
                
                <div class="col-lg-8 col-md-6 mb-4">
                    <div class="footer-newsletter">
                        <h3>Subscribe to our newsletters</h3>
                        <p>By signing and clicking Submit, you affirm you have read and agree to the Privacy Policy and Terms and Conditions and want to receive news.</p>
                        <form class="newsletter-form" method="POST" action="<?php echo SITE_URL; ?>/includes/newsletter-subscribe.php">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Your Email" name="email" required>
                                <button type="submit" class="btn btn-primary">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section contact">
                        <h3>CONTACT US</h3>
                        <div class="border mb-3 "></div>
                        <p><i class="fas fa-map-marker-alt me-2"></i> Mankon - Bamenda, Cameroon</p>
                        <p><i class="fas fa-phone me-2"></i> +237 6 79267828</p>
                        <p><i class="fas fa-envelope me-2"></i> <a href="mailto:contact@mjlegacyfoundation.org">contact@mjlegacyfoundation.org</a></p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section information">
                        <h3>INFORMATION</h3>
                        <div class="border mb-3"></div>
                        <ul class="list-unstyled">
                            <li><a href="<?php echo SITE_URL; ?>/pages/about.php">About Us</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/pages/projects.php">Projects</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/pages/blog.php">Blog</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/pages/get-involved.php">Volunteer Agreement</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/pages/contact.php">Contact us</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section social">
                        <h3>FOLLOW US ON</h3>
                        <div class="border mb-3"></div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f me-2"></i> Facebook</a>
                            <a href="#"><i class="fab fa-twitter me-2"></i> Twitter</a>
                            <a href="#"><i class="fab fa-instagram me-2"></i> Instagram</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom text-center">
                <p>&copy; <?php echo date('Y'); ?> Mother Jane Legacy Foundation - All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
    <?php if(isset($additional_js)): ?>
        <?php foreach($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 