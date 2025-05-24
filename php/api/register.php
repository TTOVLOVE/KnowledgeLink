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

$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if ($origin) {
    header('Access-Control-Allow-Origin: ' . $origin);
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
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$nickname = $data['nickname'] ?? '';

if (!$name || !$password || !$nickname) {
    echo json_encode(['status'=>'error','message'=>'请填写所有必填项']);
    exit;
}

// 检查用户名唯一
$stmt = $db->prepare('SELECT id FROM users WHERE name=?');
$stmt->bind_param('s', $name);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['status'=>'error','message'=>'用户名已存在']);
    exit;
}

// 插入新用户
$stmt = $db->prepare('INSERT INTO users (name, password, nickname) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $name, $hashed_password, $nickname);
if ($stmt->execute()) {
    $new_user_id = $stmt->insert_id;
    $_SESSION['user_id'] = $new_user_id; // 注册成功后自动登录
    echo json_encode(['status'=>'success', 'user'=>['id'=>$new_user_id, 'name'=>$name, 'nickname'=>$nickname]]);
} else {
    echo json_encode(['status'=>'error','message'=>'注册失败']);
}