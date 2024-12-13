<?php
session_start();
require_once 'config.php';

// Check if token exists in URL
$showResetForm = false;
$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verify token
    $stmt = $pdo->prepare("SELECT * FROM studentportal WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user) {
        $showResetForm = true;
    } else {
        $message = "Invalid or expired reset link.";
    }
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    
    // Update password
    $stmt = $pdo->prepare("UPDATE studentportal SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
    $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $token]);
    
    $message = "Password has been successfully reset.";
    header("Location: user_login.php"); // Redirect to login page
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <?php if ($showResetForm): ?>
        <!-- Show reset password form -->
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <h2>Reset Password</h2>
            <div>
                <label>Enter New Password:</label>
                <input type="password" name="new_password" required>
            </div>
            <div>
                <button type="submit">Reset Password</button>
            </div>
        </form>
    <?php elseif (!isset($_GET['token'])): ?>
        <!-- Show email form -->
        <form id="forgotPasswordForm">
            <h2>Forgot Password</h2>
            <div>
                <label>Enter your email:</label>
                <input type="email" id="email" required>
            </div>
            <div>
                <button type="button" onclick="sendResetLink()">Send Reset Link</button>
            </div>
        </form>
    <?php else: ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <script>
    function sendResetLink() {
        const email = document.getElementById('email').value;
        
        fetch('verify_and_send_reset.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    </script>
</body>
</html>
