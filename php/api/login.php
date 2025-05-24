<?php
session_start();
header('Content-Type: application/json');
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


header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if ($origin === 'http://localhost:5173') {
    header('Access-Control-Allow-Origin: http://localhost:5173');
    header('Access-Control-Allow-Credentials: true');
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/Database.php';
$db = Database::getInstance()->getConnection();

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'] ?? '';
$password = $data['password'] ?? '';

$stmt = $db->prepare('SELECT * FROM users WHERE name=?');
$stmt->bind_param('s', $name);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['avatar'] = $user['avatar'];
    unset($user['password']);
    echo json_encode(['status'=>'success', 'user'=>$user]);
} else {
    echo json_encode(['status'=>'error', 'message'=>'用户名或密码错误']);
}