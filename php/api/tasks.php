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

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
require_once __DIR__ . '/../config/Database.php';
$db = Database::getInstance()->getConnection();

$user_id = intval($_GET['user_id'] ?? ($_POST['user_id'] ?? 1));

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tasks = [];
    $stmt = $db->prepare('SELECT id, name, icon, created_at FROM user_tasks WHERE user_id = ? ORDER BY id ASC');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    echo json_encode(['status'=>'success','data'=>$tasks]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $task_id = intval($data['task_id'] ?? 0);
    if (!$user_id || !$task_id) {
        http_response_code(400);
        echo json_encode(['status'=>'error','error'=>'参数不完整']);
        exit;
    }
    $stmt = $db->prepare('DELETE FROM user_tasks WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $task_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'success','message'=>'删除成功']);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','error'=>'删除失败: ' . $db->error]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['status'=>'error','error'=>'不支持的请求方式']);
