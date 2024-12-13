<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

try {
    // Check database connection
    if (!$pdo) {
        throw new PDOException('Database connection failed');
    }

    // Check if email exists in studentportal table
    $stmt = $pdo->prepare("SELECT * FROM studentportal WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Generate token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Update token
        $updateStmt = $pdo->prepare("UPDATE studentportal SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $updateStmt->execute([$token, $expiry, $email]);

        // Send email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ancilmacalalad14@gmail.com';
            $mail->Password = 'xiqx nrec zdrk cjhd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            
            // Set timeout and keep alive
            $mail->Timeout = 60;
            $mail->SMTPKeepAlive = true;
            
            // Recipients
            $mail->setFrom('ancilmacalalad14@gmail.com', 'Student Portal Admin');
            $mail->addAddress($email);

            // Content
            $resetLink = "http://localhost/Portal/forgot_password.php?token=" . $token;
            
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "
                <h2>Password Reset Request</h2>
                <p>Click the link below to reset your password:</p>
                <p><a href='{$resetLink}'>Reset Password</a></p>
                <p>This link will expire in 1 hour.</p>
                <p>If you didn't request this, please ignore this email.</p>
            ";

            $mail->send();
            echo json_encode([
                'success' => true,
                'message' => 'Password reset link has been sent to your email'
            ]);
            exit; // Add exit after JSON response
        } catch (Exception $e) {
            throw new Exception('Error sending email: ' . $mail->ErrorInfo);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'If your email is registered, you will receive a password reset link'
        ]);
        exit; // Add exit after JSON response
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred: ' . $e->getMessage()
    ]);
    exit; // Add exit after JSON response
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit; // Add exit after JSON response
}
?>