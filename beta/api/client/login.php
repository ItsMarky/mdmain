<?php
session_start();
header('Content-Type: application/json');
require 'db_config.php'; // Update path if necessary

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$passwordAttempt = $data['password'] ?? '';

$stmt = $db->prepare("SELECT client_id, password_hash FROM Clients WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(401); 
    echo json_encode(["message" => "Invalid email or password."]);
    exit();
}

$isValid = password_verify($passwordAttempt, $user['password_hash']);

if ($isValid) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true, // Ensure HTTPS is used!
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    
    session_regenerate_id(true); 

    $_SESSION['client_id'] = $user['client_id'];
    $_SESSION['logged_in'] = true;
    
    http_response_code(200);
    echo json_encode(["message" => "Login successful."]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "Invalid email or password."]);
}
?>