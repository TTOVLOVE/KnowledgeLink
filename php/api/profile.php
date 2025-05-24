<?php
header('Content-Type: application/json');

// 允许的跨域源
$allowed_origins = [
    'http://localhost:5173',
    'http://14.103.124.0:5173',
    'https://www.moonbeaut.top',
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
} elseif (isset($_SERVER['HTTP_ORIGIN'])) {
    // 非白名单直接拒绝
    http_response_code(403);
    exit('CORS Forbidden');
}
// 没有 Origin 说明不是跨域请求，继续执行


require_once __DIR__ . '/../config/Database.php';

$db = Database::getInstance()->getConnection();

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '未登录']);
    exit;
}
$user_id = $_SESSION['user_id'];

// 查询用户信息
$user_stmt = $db->prepare("SELECT id, name, nickname, title, avatar, coins, points FROM users WHERE id = ?");
$user_stmt->bind_param('i', $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
// 补全头像地址，所有非 https 开头的都转为 https，防止 Mixed Content
if ($user && $user['avatar']) {
    // 不是 https 开头
    if (!preg_match('#^https://#', $user['avatar'])) {
        if (preg_match('#^http://#', $user['avatar'])) {
            // http:// 开头，替换为 https://
            $user['avatar'] = preg_replace('#^http://#', 'https://', $user['avatar']);
        } else {
            // 相对路径，拼接为 https://host/path
            $protocol = 'https';
            $host = $_SERVER['HTTP_HOST'];
            $user['avatar'] = $protocol . '://' . $host . '/' . ltrim($user['avatar'], '/');
        }
    }
}
// 查询收藏
$collections_stmt = $db->prepare("SELECT id, type, title, date FROM collections WHERE user_id = ? ORDER BY date DESC");
$collections_stmt->bind_param('i', $user_id);
$collections_stmt->execute();
$collections_result = $collections_stmt->get_result();

$collections = [];
while ($row = $collections_result->fetch_assoc()) {
    $collections[] = $row;
}

echo json_encode([
    'user' => $user,
    'collections' => $collections
]);