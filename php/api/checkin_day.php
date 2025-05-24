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
$db = Database::getInstance()->getConnection();

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status'=>'error','error'=>'未登录，无法获取打卡日历']);
    exit;
}
$user_id = intval($_SESSION['user_id']);
$today = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 标记今日全局打卡
    if (!$user_id) {
        http_response_code(400);
        echo json_encode(['status'=>'error','error'=>'用户ID缺失']);
        exit;
    }
    // 检查是否已存在
    $stmt = $db->prepare('SELECT id FROM user_checkin_days WHERE user_id = ? AND checkin_date = ?');
    $stmt->bind_param('is', $user_id, $today);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['status'=>'success','message'=>'今日已全局打卡']);
        exit;
    }
    // 插入
    $stmt2 = $db->prepare('INSERT INTO user_checkin_days (user_id, checkin_date) VALUES (?, ?)');
    $stmt2->bind_param('is', $user_id, $today);
    if ($stmt2->execute()) {
        echo json_encode(['status'=>'success','message'=>'全局打卡成功']);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','error'=>'全局打卡失败: ' . $db->error]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 查询最近7天的全局打卡
    $days = [];
    $stmt = $db->prepare('SELECT checkin_date FROM user_checkin_days WHERE user_id = ? AND checkin_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) ORDER BY checkin_date ASC');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $days[] = $row['checkin_date'];
    }
    echo json_encode(['status'=>'success','data'=>$days]);
    exit;
}

http_response_code(405);
echo json_encode(['status'=>'error','error'=>'不支持的请求方式']);
