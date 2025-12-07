<?php
header('Content-Type: application/json');
require 'db_config.php'; // Update path if necessary

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$firstName = $data['first_name'] ?? '';
$lastName = $data['last_name'] ?? '';

if (empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
    http_response_code(400); 
    echo json_encode(["message" => "All fields are required."]);
    exit();
}

$stmt = $db->prepare("SELECT client_id FROM Clients WHERE email = :email");
$stmt->execute(['email' => $email]);

if ($stmt->rowCount() > 0) {
    http_response_code(409);
    echo json_encode(["message" => "Email already registered."]);
    exit();
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT); 

$sql = "INSERT INTO Clients (email, password_hash, first_name, last_name) 
        VALUES (:email, :hash, :firstName, :lastName)";

$stmt = $db->prepare($sql);
$success = $stmt->execute([
    'email' => $email,
    'hash' => $passwordHash,
    'firstName' => $firstName,
    'lastName' => $lastName
]);

if ($success) {
    http_response_code(201); 
    echo json_encode(["message" => "Account successfully created."]);
} else {
    http_response_code(500); 
    echo json_encode(["message" => "Failed to create account due to a database error."]);
}
?>