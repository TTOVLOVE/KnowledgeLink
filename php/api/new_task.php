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
// 移除多余和硬编码 localhost:5173 的重复设置，避免与 credentials 冲突
require_once __DIR__ . '/../config/Database.php';
$db = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['status'=>'error','error'=>'未登录，无法新建任务']);
        exit;
    }
    $user_id = intval($_SESSION['user_id']);
    $data = json_decode(file_get_contents('php://input'), true);
    $name = trim($data['name'] ?? '');
    $type = trim($data['type'] ?? '');
    $start_date = trim($data['start_date'] ?? '');
    $icon = trim($data['icon'] ?? '');
    if (!$name) {
        http_response_code(400);
        echo json_encode(['status'=>'error','error'=>'参数不完整']);
        exit;
    }
    $stmt = $db->prepare('INSERT INTO user_tasks (user_id, name, icon, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->bind_param('iss', $user_id, $name, $icon);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'success','message'=>'任务创建成功']);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','error'=>'任务创建失败: ' . $db->error]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['status'=>'error','error'=>'不支持的请求方式']);
