<?php
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: donation.php");
    exit();
}

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'redirect_url' => ''
];

try {
    // Validate required fields
    $required_fields = ['first_name', 'last_name', 'email'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Please fill in all required fields.");
        }
    }
    
    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Please enter a valid email address.");
    }
    
    // Get form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $donation_type = $_POST['donation_type'] ?? 'one_time';
    $payment_method = $_POST['payment_method'] ?? 'paypal';
    $project_id = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
    $newsletter = isset($_POST['newsletter']);
    
    // Determine donation amount
    $amount = 0;
    if (!empty($_POST['custom_amount'])) {
        $amount = (float)$_POST['custom_amount'];
    } elseif (!empty($_POST['amount'])) {
        $amount = (float)$_POST['amount'];
    }
    
    if ($amount <= 0) {
        throw new Exception("Please select or enter a valid donation amount.");
    }
    
    // Validate project_id if provided
    if ($project_id) {
        $stmt = $pdo->prepare("SELECT id FROM projects WHERE id = ? AND status = 'active'");
        $stmt->execute([$project_id]);
        if (!$stmt->fetch()) {
            throw new Exception("Invalid project selected.");
        }
    }
    
    // Generate transaction ID
    $transaction_id = 'DON-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
    
    // Insert donation record
    $stmt = $pdo->prepare("
        INSERT INTO donations (
            donor_name, donor_email, amount, currency, payment_method, 
            payment_status, transaction_id, purpose, is_anonymous, 
            message, ip_address, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $donor_name = $first_name . ' ' . $last_name;
    $purpose = $project_id ? "Project donation" : "General donation";
    $message = "Donation from " . $donor_name . " via " . $payment_method;
    
    $stmt->execute([
        $donor_name,
        $email,
        $amount,
        'USD',
        $payment_method,
        'pending',
        $transaction_id,
        $purpose,
        false,
        $message,
        $_SERVER['REMOTE_ADDR'] ?? ''
    ]);
    
    $donation_id = $pdo->lastInsertId();
    
    // Subscribe to newsletter if requested
    if ($newsletter) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO newsletter_subscriptions (email, is_active, subscribed_at) 
                VALUES (?, TRUE, NOW()) 
                ON DUPLICATE KEY UPDATE is_active = TRUE, unsubscribed_at = NULL
            ");
            $stmt->execute([$email]);
        } catch (PDOException $e) {
            // Log error but don't fail the donation
            error_log("Newsletter subscription error: " . $e->getMessage());
        }
    }
    
    // Redirect based on payment method
    switch ($payment_method) {
        case 'paypal':
            $response['redirect_url'] = 'paypal.html?donation_id=' . $donation_id . '&amount=' . $amount;
            break;
        case 'card':
            $response['redirect_url'] = 'sapa.html?donation_id=' . $donation_id . '&amount=' . $amount;
            break;
        case 'bank':
            $response['redirect_url'] = 'bank-transfer.html?donation_id=' . $donation_id . '&amount=' . $amount;
            break;
        default:
            $response['redirect_url'] = 'donation.php?success=1&donation_id=' . $donation_id;
    }
    
    $response['success'] = true;
    $response['message'] = 'Donation submitted successfully!';
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $response['message'] = 'An error occurred while processing your donation. Please try again.';
}

// Return JSON response for AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Redirect for regular form submissions
if ($response['success']) {
    header("Location: " . $response['redirect_url']);
} else {
    header("Location: donation.php?error=" . urlencode($response['message']));
}
exit();
?> 