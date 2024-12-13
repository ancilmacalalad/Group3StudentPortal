<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email is provided
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        // Generate a 6-digit code
        $code = rand(100000, 999999);
        // Store code in session
        $_SESSION['reset_code'] = $code;
        $_SESSION['email'] = $email;

        // Send email (make sure to configure your mail settings)
        $subject = "Password Reset Code";
        $message = "Your password reset code is: " . $code;
        mail($email, $subject, $message);

        echo "A verification code has been sent to your email.";
    }

    // Handle code verification
    if (isset($_POST['code'])) {
        $enteredCode = $_POST['code'];
        if ($enteredCode == $_SESSION['reset_code']) {
            echo "Code verified. You can now change your password.";
            // Show the password change form
            // You can redirect or handle this in the front-end
        } else {
            echo "Invalid code. Please try again.";
        }
    }

    // Handle password change
    if (isset($_POST['new-password'])) {
        // Here you would typically hash the password and save it in the database
        $newPassword = password_hash($_POST['new-password'], PASSWORD_DEFAULT);
        // Save the new password to the database for the email user
        echo "Your password has been changed successfully.";
        // Clear the session
        session_destroy();
    }
}
?>
