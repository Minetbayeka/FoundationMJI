<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['success' => false, 'message' => 'Please enter a valid email address.'];
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM newsletter_subscriptions WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $response = ['success' => false, 'message' => 'This email is already subscribed to our newsletter.'];
            } else {
                // Insert new subscription
                $stmt = $pdo->prepare("INSERT INTO newsletter_subscriptions (email) VALUES (?)");
                $stmt->execute([$email]);
                
                $response = ['success' => true, 'message' => 'Thank you for subscribing to our newsletter!'];
            }
        } catch (PDOException $e) {
            $response = ['success' => false, 'message' => 'Subscription failed. Please try again.'];
        }
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// If not POST request, redirect to home page
header("Location: ../index.php");
exit();
?> 