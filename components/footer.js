const footer = document.querySelector('#footer');

const footerContent = `

<footer class="site-footer">
        <div class="footer-content">
            <div class="footer-section about">
                <div  class="logo-footer"><img src="../../assets/images/logo.png"  alt="logo-image"/>MJL Foundation</div>
                <p>Good deeds are available to everyone! And there are a lot of responsive people around who are ready to help.</p>
            </div>

             <div class="footer-newsletter">
            <h3>Subscribe to our newsletters</h3>
            <p>By signing and clicking Submit, you affirm you have read and agree to the Privacy Policy and Terms and Conditions and want to receive news.</p>
            <div class="newsletter-form">
                <input type="email" placeholder="Your Email">
                <button type="submit">Subscribe</button>
            </div>
        </div>

           
        </div>

        <div class="contact-section">
           <div class="footer-section contact">
                <h3>CONTACT US</h3>
                <div class="border"></div>
                <p><i class="fas fa-map-marker-alt"></i> Mankon - Bamenda, Cameroon</p>
                <p><i class="fas fa-phone"></i> +237 6 79267828</p>
                <p><i class="fas fa-envelope"></i> <a href="mailto:contact@mjlegacyfoundation.org">contact@mjlegacyfoundation.org</a></p>
            </div>

            <div class="footer-section information">
                <h3>INFORMATION</h3>
                <div class="border"></div>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Projects</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Volunteer Agreement</a></li>
                    <li><a href="#">Contact us</a></li>
                </ul>
            </div>

            <div class="footer-section social">
                <h3>FOLLOW US ON</h3>
                <div class="border"></div>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
                    <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
                </div>
            </div>
        </div>

       

        <div class="footer-bottom">
            <p>&copy; 2022 Save Poor - All Rights Reserved.</p>
        </div>
    </footer>

`

footer.innerHTML = footerContent;