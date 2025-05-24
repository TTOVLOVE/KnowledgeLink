<?php
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


require_once __DIR__ . '/../config/Database.php';

session_start();
$db = Database::getInstance()->getConnection();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    http_response_code(401);
    echo json_encode(['error' => '未登录']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$article_id = intval($data['article_id'] ?? 0);
$title = $data['title'] ?? '';
$link = $data['link'] ?? '';

if (!$article_id || !$title || !$link) {
    http_response_code(400);
    echo json_encode(['error' => '参数不完整']);
    exit;
}

// 检查是否已收藏
$stmt = $db->prepare('SELECT id FROM collections WHERE user_id = ? AND link = ?');
$stmt->bind_param('is', $user_id, $link);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // 已收藏，取消收藏
    $stmt = $db->prepare('DELETE FROM collections WHERE user_id = ? AND link = ?');
    $stmt->bind_param('is', $user_id, $link);
    $stmt->execute();
    echo json_encode(['isFavorite' => false]);
} else {
    // 未收藏，添加收藏
    $type = '资源收藏';
    $date = date('Y-m-d');
    $stmt = $db->prepare('INSERT INTO collections (user_id, type, title, date, link) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('issss', $user_id, $type, $title, $date, $link);
    $stmt->execute();
    echo json_encode(['isFavorite' => true]);
}