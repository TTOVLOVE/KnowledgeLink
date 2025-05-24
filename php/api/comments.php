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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $article_id = intval($_GET['article_id'] ?? 0);
    $stmt = $db->prepare('SELECT id, username, avatar, text, time FROM comments WHERE article_id = ? ORDER BY time DESC');
    $stmt->bind_param('i', $article_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    echo json_encode(['comments' => $comments]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $article_id = intval($data['article_id'] ?? 0);
    $text = trim($data['text'] ?? '');
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => '未登录']);
        exit;
    }
    if (!$article_id || $text === '') {
        http_response_code(400);
        echo json_encode(['error' => '参数不完整']);
        exit;
    }
    $user_id = $_SESSION['user_id'];
    // 用user_id查最新用户名和头像
    $user_stmt = $db->prepare('SELECT name, avatar FROM users WHERE id = ?');
    $user_stmt->bind_param('i', $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_row = $user_result->fetch_assoc();
    $username = $user_row ? $user_row['name'] : '';
    $avatar = $user_row ? $user_row['avatar'] : '';
    if ($username === '') {
        http_response_code(403);
        echo json_encode(['error' => '用户不存在']);
        exit;
    }
    $stmt = $db->prepare('INSERT INTO comments (article_id, user_id, username, avatar, text, time) VALUES (?, ?, ?, ?, ?, NOW())');
    $stmt->bind_param('iisss', $article_id, $user_id, $username, $avatar, $text);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}