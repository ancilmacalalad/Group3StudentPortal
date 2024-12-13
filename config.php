<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentportal";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => "Connection failed: " . $e->getMessage()
    ]);
    exit;
}
?> 