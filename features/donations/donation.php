<?php
$page_title = "Make a Donation";
$additional_css = ['../donation.css'];
$additional_js = [];

include '../../includes/header.php';

// Fetch featured projects for donation targeting
try {
    $stmt = $pdo->prepare("
        SELECT id, title, slug, target_amount 
        FROM projects 
        WHERE is_featured = 1 AND status = 'active' 
        ORDER BY created_at DESC
    ");
    $stmt->execute();
    $featured_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featured_projects = [];
    error_log("Database error: " . $e->getMessage());
}
?>

<style>

.card-header{
  background-color: #2D394B;
}

</style>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-image"></div>
    <div class="hero-content">
        <p class="tagline">Your support makes a difference</p>
        <h1>Make a Donation</h1>
        <p>Help us continue our mission to support vulnerable children and communities</p>
    </div>
</section>

<!-- Donation Form Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header text-white text-center">
                        <h3 class="mb-0 text-white">
                            <i class="fas fa-heart me-2" style="color:#FF9563;"></i>Support Our Cause
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <form id="donationForm" method="POST" action="process-donation.php">
                            <!-- Donation Amount -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Select Donation Amount</label>
                                <div class="row">
                                    <div class="col-6 col-md-3 mb-2">
                                        <input type="radio" class="btn-check" name="amount" id="amount25" value="25" autocomplete="off">
                                        <label class="btn btn-outline-primary w-100" for="amount25">$25</label>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <input type="radio" class="btn-check" name="amount" id="amount50" value="50" autocomplete="off">
                                        <label class="btn btn-outline-primary  w-100" for="amount50">$50</label>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <input type="radio" class="btn-check" name="amount" id="amount100" value="100" autocomplete="off" checked>
                                        <label class="btn btn-outline-primary w-100" for="amount100">$100</label>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <input type="radio" class="btn-check" name="amount" id="amount250" value="250" autocomplete="off">
                                        <label class="btn btn-outline-primary w-100" for="amount250">$250</label>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="customAmount" class="form-label">Or enter custom amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="customAmount" name="custom_amount" 
                                               placeholder="Enter amount" min="1" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <!-- Project Selection -->
                            <div class="mb-4">
                                <label for="project_id" class="form-label fw-bold">Choose a Project (Optional)</label>
                                <select class="form-select" id="project_id" name="project_id">
                                    <option value="">General Donation</option>
                                    <?php foreach ($featured_projects as $project): ?>
                                        <option value="<?php echo $project['id']; ?>">
                                            <?php echo htmlspecialchars($project['title']); ?> 
                                            (Target: $<?php echo number_format($project['target_amount']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Select a specific project or leave as general donation</div>
                            </div>

                            <!-- Donor Information -->
                            <div class="mb-4">
                                <h5 class="mb-3">Donor Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                                </div>
                            </div>

                            <!-- Donation Type -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Donation Type</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="donation_type" id="oneTime" value="one_time" checked>
                                    <label class="form-check-label" for="oneTime">
                                        One-time donation
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="donation_type" id="monthly" value="monthly">
                                    <label class="form-check-label" for="monthly">
                                        Monthly recurring donation
                                    </label>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Payment Method</label>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <input type="radio" class="btn-check" name="payment_method" id="paypal" value="paypal" autocomplete="off" checked>
                                        <label class="btn btn-outline-success w-100" for="paypal">
                                            <i class="fab fa-paypal me-2"></i>PayPal
                                        </label>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <input type="radio" class="btn-check" name="payment_method" id="card" value="card" autocomplete="off">
                                        <label class="btn btn-outline-info w-100" for="card">
                                            <i class="fas fa-credit-card me-2"></i>Credit Card
                                        </label>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <input type="radio" class="btn-check" name="payment_method" id="bank" value="bank" autocomplete="off">
                                        <label class="btn btn-outline-secondary w-100" for="bank">
                                            <i class="fas fa-university me-2"></i>Bank Transfer
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> and 
                                        <a href="#" class="text-decoration-none">Privacy Policy</a> *
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                    <label class="form-check-label" for="newsletter">
                                        Subscribe to our newsletter for updates on our projects
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-heart me-2"></i>Make Donation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 mb-4">
                <h3>Your Donation Makes a Real Impact</h3>
                <p class="lead">See how your contribution helps vulnerable children and communities</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="p-4">
                    <i class="fas fa-graduation-cap fa-3x  mb-3" style="color:#FF9563;"></i>
                    <h5>Education Support</h5>
                    <p class="text-muted">Help children access quality education and school materials</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="p-4">
                    <i class="fas fa-heartbeat fa-3x mb-3" style="color:#FF9563;"></i>
                    <h5>Healthcare Access</h5>
                    <p class="text-muted">Provide medical care and health services to rural communities</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="p-4">
                    <i class="fas fa-seedling fa-3x  mb-3" style="color:#FF9563;"></i>
                    <h5>Agricultural Training</h5>
                    <p class="text-muted">Teach sustainable farming practices for food security</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customAmountInput = document.getElementById('customAmount');
    const amountRadios = document.querySelectorAll('input[name="amount"]');
    
    // Handle custom amount input
    customAmountInput.addEventListener('input', function() {
        amountRadios.forEach(radio => radio.checked = false);
    });
    
    // Handle radio button selection
    amountRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                customAmountInput.value = '';
            }
        });
    });
    
    // Form validation
    const form = document.getElementById('donationForm');
    form.addEventListener('submit', function(e) {
        const selectedAmount = document.querySelector('input[name="amount"]:checked');
        const customAmount = customAmountInput.value;
        
        if (!selectedAmount && !customAmount) {
            e.preventDefault();
            alert('Please select or enter a donation amount.');
            return;
        }
        
        if (customAmount && parseFloat(customAmount) <= 0) {
            e.preventDefault();
            alert('Please enter a valid donation amount.');
            return;
        }
    });
});
</script>

<?php include '../../includes/footer.php'; ?> 