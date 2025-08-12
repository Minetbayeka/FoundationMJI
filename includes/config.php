<?php
// // Database configuration
// define('DB_HOST', 'localhost');
// define('DB_USER', 'mjlfoundation_MJCbyCopilot');
// define('DB_PASS', 'MJCbyCopilot');
// define('DB_NAME', 'mjlfoundation_MJCbyCopilot');


define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mjl_foundation');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Site configuration
define('SITE_NAME', 'Mother Jane Legacy Foundation');
// define('SITE_URL', 'https://mjl-foundation.org/');
define('SITE_URL', 'http://localhost/foundation');
define('ADMIN_EMAIL', 'admin@mjlegacyfoundation.org');

// Session configuration
session_start();

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);