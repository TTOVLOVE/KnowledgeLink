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

require_once __DIR__ . '/../config/Database.php';
$db = Database::getInstance()->getConnection();

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status'=>'error','error'=>'未登录，无法获取任务']);
    exit;
}
$user_id = intval($_SESSION['user_id']);
$today = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 获取所有任务及今日打卡状态
    $tasks = [];
    $stmt = $db->prepare('SELECT id, name, icon FROM user_tasks WHERE user_id = ? ORDER BY id ASC');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['checked'] = false;
        $tasks[] = $row;
    }
    // 查询今日已打卡任务
    $stmt2 = $db->prepare('SELECT task_id FROM checkin_records WHERE user_id = ? AND checkin_date = ?');
    $stmt2->bind_param('is', $user_id, $today);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $checkedTaskIds = [];
    while ($row2 = $result2->fetch_assoc()) {
        $checkedTaskIds[] = $row2['task_id'];
    }
    // 标记 checked
    foreach ($tasks as &$task) {
        if (in_array($task['id'], $checkedTaskIds)) {
            $task['checked'] = true;
        }
    }
    echo json_encode(['status'=>'success','data'=>$tasks]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $task_id = intval($data['task_id'] ?? 0);
    if (!$user_id || !$task_id) {
        http_response_code(400);
        echo json_encode(['status'=>'error','error'=>'参数不完整']);
        exit;
    }
    // 检查是否已打卡
    $stmt = $db->prepare('SELECT id FROM checkin_records WHERE user_id = ? AND task_id = ? AND checkin_date = ?');
    $stmt->bind_param('iis', $user_id, $task_id, $today);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['status'=>'success','message'=>'今日已打卡']);
        exit;
    }
    // 插入打卡记录
    $stmt2 = $db->prepare('INSERT INTO checkin_records (user_id, task_id, checkin_date) VALUES (?, ?, ?)');
    $stmt2->bind_param('iis', $user_id, $task_id, $today);
    if ($stmt2->execute()) {
        echo json_encode(['status'=>'success','message'=>'打卡成功']);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','error'=>'打卡失败: ' . $db->error]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['status'=>'error','error'=>'不支持的请求方式']);
