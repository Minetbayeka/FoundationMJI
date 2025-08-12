<?php
require_once '../includes/config.php';

echo "<h2>Admin Login Test</h2>";

// Test database connection
try {
    echo "<p><strong>Database Connection:</strong> ";
    $pdo->query("SELECT 1");
    echo "✅ Connected successfully</p>";
} catch (PDOException $e) {
    echo "❌ Failed: " . $e->getMessage() . "</p>";
    exit;
}

// Check if users table exists
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p><strong>Users Table:</strong> ✅ Exists</p>";
    } else {
        echo "<p><strong>Users Table:</strong> ❌ Does not exist</p>";
        exit;
    }
} catch (PDOException $e) {
    echo "<p><strong>Users Table:</strong> ❌ Error: " . $e->getMessage() . "</p>";
    exit;
}

// Check admin user
try {
    $stmt = $pdo->prepare("SELECT id, username, email, full_name, role, is_active FROM users WHERE username = 'admin'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<p><strong>Admin User:</strong> ✅ Found</p>";
        echo "<ul>";
        echo "<li>ID: " . $user['id'] . "</li>";
        echo "<li>Username: " . $user['username'] . "</li>";
        echo "<li>Email: " . $user['email'] . "</li>";
        echo "<li>Full Name: " . $user['full_name'] . "</li>";
        echo "<li>Role: " . $user['role'] . "</li>";
        echo "<li>Active: " . ($user['is_active'] ? 'Yes' : 'No') . "</li>";
        echo "</ul>";
    } else {
        echo "<p><strong>Admin User:</strong> ❌ Not found</p>";
    }
} catch (PDOException $e) {
    echo "<p><strong>Admin User:</strong> ❌ Error: " . $e->getMessage() . "</p>";
}

// Test password verification
$test_password = 'admin123';
$hashed_password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "<p><strong>Password Test:</strong> ";
if (password_verify($test_password, $hashed_password)) {
    echo "✅ Password verification works</p>";
} else {
    echo "❌ Password verification failed</p>";
}

// Create a new admin user if needed
echo "<h3>Create New Admin User</h3>";
echo "<p>If the admin user doesn't exist or you want to reset the password, use this form:</p>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username'] ?? '');
    $new_password = $_POST['password'] ?? '';
    $new_email = trim($_POST['email'] ?? '');
    $new_full_name = trim($_POST['full_name'] ?? '');
    
    if (!empty($new_username) && !empty($new_password)) {
        try {
            // Check if user already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$new_username]);
            
            if ($stmt->rowCount() > 0) {
                // Update existing user
                $stmt = $pdo->prepare("UPDATE users SET password = ?, email = ?, full_name = ? WHERE username = ?");
                $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $new_email, $new_full_name, $new_username]);
                echo "<p>✅ User updated successfully!</p>";
            } else {
                // Create new user
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, 'admin')");
                $stmt->execute([$new_username, $new_email, password_hash($new_password, PASSWORD_DEFAULT), $new_full_name]);
                echo "<p>✅ User created successfully!</p>";
            }
        } catch (PDOException $e) {
            echo "<p>❌ Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<form method="POST" style="max-width: 400px; margin: 20px 0;">
    <div style="margin-bottom: 10px;">
        <label>Username: <input type="text" name="username" value="admin" required></label>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Password: <input type="password" name="password" value="admin123" required></label>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Email: <input type="email" name="email" value="admin@mjlegacyfoundation.org" required></label>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Full Name: <input type="text" name="full_name" value="MJL Foundation Admin" required></label>
    </div>
    <button type="submit">Create/Update Admin User</button>
</form>

<h3>Login Credentials</h3>
<p><strong>Default Admin Credentials:</strong></p>
<ul>
    <li>Username: <code>admin</code></li>
    <li>Password: <code>admin123</code></li>
</ul>

<p><a href="index.php">Go to Admin Login</a></p> 