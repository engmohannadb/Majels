<?php
header('Content-Type: application/json');

$host = 'localhost';
$db   = 'designerswall';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// Simple token authentication
function checkAuth($pdo) {
    $headers = getallheaders();
    if (empty($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    $token = $headers['Authorization'];
    $stmt = $pdo->prepare("SELECT ID, Name, Email FROM users WHERE api_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    if (!$user) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token']);
        exit;
    }
    return $user;
}
