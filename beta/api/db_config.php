<?php
// CRITICAL: Replace 'your_db_host' with the actual host name provided by your web host. 
// This is often 'localhost', but sometimes a specific IP or server name.
$host = '167.172.56.243'; 
$db_name = 'ggauzkpwne';
$user = 'ggauzkpwne';
$password = 'x9dMT3WgDv';

try {
    $db = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode([
        "message" => "Database connection failed. Check db_config.php: " . $e->getMessage()
    ]));
}
?>