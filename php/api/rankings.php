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
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
require_once __DIR__ . '/../config/Database.php';
$db = Database::getInstance()->getConnection();

$type = $_GET['type'] ?? 'experts';

if ($type === 'experts') {
    // 知识达人榜：直接查users.coins字段
    $sql = "SELECT id, name, avatar, coins FROM users ORDER BY coins DESC, id ASC LIMIT 50";
    $res = $db->query($sql);
    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'avatar' => (isset($row['avatar']) && $row['avatar'] && !preg_match('#^https?://#', $row['avatar']) ?
    ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . ltrim($row['avatar'], '/')) :
    ($row['avatar'] ?: 'https://via.placeholder.com/40')),
            'coins' => (int)$row['coins']
        ];
    }
    echo json_encode(['status' => 'success', 'data' => $data]);
    exit;
} 

if ($type === 'checkins') {
    // 打卡榜：统计每个用户总打卡天数（简单演示版，兼容所有MySQL）
    $sql = "SELECT u.id, u.name, u.avatar, COUNT(c.id) AS days
            FROM users u
            LEFT JOIN user_checkin_days c ON u.id = c.user_id
            GROUP BY u.id
            ORDER BY days DESC, u.id ASC
            LIMIT 50";
    $res = $db->query($sql);
    if ($res === false) {
        echo json_encode(['status'=>'error','message'=>'SQL Error: '.$db->error]);
        exit;
    }
    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'avatar' => (isset($row['avatar']) && $row['avatar'] && !preg_match('#^https?://#', $row['avatar']) ?
    ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . ltrim($row['avatar'], '/')) :
    ($row['avatar'] ?: 'https://via.placeholder.com/40')),
            'days' => (int)$row['days']
        ];
    }
    echo json_encode(['status' => 'success', 'data' => $data]);
    exit;
}

echo json_encode(['status'=>'error','message'=>'Invalid type']);
exit;
