<?php
header('Content-Type: application/json');
session_start(); // 必须加，保证 session 有效
$allowed_origins = [
    'http://localhost:5173',
    'http://14.103.124.0:5173',
    'https://www.moonbeaut.top',
];
if (isset($_SERVER['HTTP_ORIGIN'])) {
    if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    } else {
        http_response_code(403);
        exit('CORS Forbidden');
    }
}
// 如果没有 Origin，说明不是跨域请求，允许继续执行后续逻辑
require_once __DIR__ . '/../config/Database.php';

$db = Database::getInstance()->getConnection();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['collections' => [], 'error' => '未登录']);
    exit;
}
$user_id = $_SESSION['user_id'];

$stmt = $db->prepare('SELECT id, title, link FROM collections WHERE user_id = ? ORDER BY date DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$collections = [];
while ($row = $result->fetch_assoc()) {
    $collections[] = $row;
}

echo json_encode(['collections' => $collections]);