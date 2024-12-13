<?php
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$code = $data['code'];

// Verify code (in production, check against database)
if (
    isset($_SESSION['reset_code']) && 
    isset($_SESSION['reset_email']) && 
    $_SESSION['reset_code'] === $code && 
    $_SESSION['reset_email'] === $email &&
    (time() - $_SESSION['code_timestamp']) < 600 // 10 minute expiration
) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>