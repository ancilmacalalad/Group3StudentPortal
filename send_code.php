<?php
header('Content-Type: application/json');
session_start();

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];

// Generate 6-digit code
$code = sprintf("%06d", mt_rand(0, 999999));

// Store code in session (in production, use database)
$_SESSION['reset_code'] = $code;
$_SESSION['reset_email'] = $email;
$_SESSION['code_timestamp'] = time();

// Send email
$to = $email;
$subject = "Password Reset Code";
$message = "Your password reset code is: " . $code;
$headers = "From: your-email@domain.com";

if(mail($to, $subject, $message, $headers)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send email']);
}
?>