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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'like') {
    // 点赞接口
    $data = json_decode(file_get_contents('php://input'), true);
    $answer_id = intval($data['answer_id'] ?? 0);
    if (!$answer_id) {
        http_response_code(400);
        echo json_encode(['status'=>'error','error'=>'参数不完整']);
        exit;
    }
    $stmt = $db->prepare('UPDATE answers SET likes = likes + 1 WHERE id = ?');
    $stmt->bind_param('i', $answer_id);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','error'=>'点赞失败: ' . $db->error]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $question_id = intval($data['question_id'] ?? 0);
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['status'=>'error','error'=>'未登录']);
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $content = trim($data['content'] ?? '');
    if (!$question_id || !$content) {
        http_response_code(400);
        echo json_encode(['status'=>'error','error'=>'参数不完整']);
        exit;
    }
    $stmt = $db->prepare('INSERT INTO answers (question_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->bind_param('iis', $question_id, $user_id, $content);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'success','id'=>$db->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','error'=>'提交失败: ' . $db->error]);
    }
    exit;
}
http_response_code(405);
echo json_encode(['status'=>'error','error'=>'不支持的请求方式']);