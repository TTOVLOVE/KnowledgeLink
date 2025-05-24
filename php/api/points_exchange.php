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

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
session_start();
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if ($origin === 'http://localhost:5173') {
    header('Access-Control-Allow-Origin: http://localhost:5173');
    header('Access-Control-Allow-Credentials: true');
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/Database.php';

$db = Database::getInstance()->getConnection();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['exchanges' => [], 'error' => '未登录']);
    exit;
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $db->prepare('SELECT id, coins, points, time FROM points_exchange WHERE user_id = ? ORDER BY time DESC');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $exchanges = [];
    while ($row = $result->fetch_assoc()) {
        $exchanges[] = $row;
    }
    echo json_encode(['exchanges' => $exchanges]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $coins = intval($data['coins'] ?? 0);
    $points = intval($data['points'] ?? 0);

    if ($coins <= 0 || $points <= 0) {
        http_response_code(400);
        echo json_encode(['error' => '兑换数量无效']);
        exit;
    }

    // 检查用户积分是否足够
    $stmt = $db->prepare('SELECT points FROM users WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($current_points);
    $stmt->fetch();
    $stmt->close();

    if ($current_points < $points) {
        http_response_code(400);
        echo json_encode(['error' => '积分不足']);
        exit;
    }

    // 扣除积分，增加知识币
    $db->begin_transaction();
    try {
        $stmt = $db->prepare('UPDATE users SET points = points - ?, coins = coins + ? WHERE id = ?');
        $stmt->bind_param('iii', $points, $coins, $user_id);
        $stmt->execute();

        $stmt = $db->prepare('INSERT INTO points_exchange (user_id, coins, points, time) VALUES (?, ?, ?, NOW())');
        $stmt->bind_param('iii', $user_id, $coins, $points);
        $stmt->execute();

        $stmt = $db->prepare('INSERT INTO points_details (user_id, title, time, points, type) VALUES (?, ?, NOW(), ?, ?)');
        $minus = -$points;
        $stmt->bind_param('isis', $user_id, $title1, $minus, $type1);
        $title1 = '兑换知识币';
        $type1 = '积分';
        $stmt->execute();

        $stmt = $db->prepare('INSERT INTO points_details (user_id, title, time, points, type) VALUES (?, ?, NOW(), ?, ?)');
        $stmt->bind_param('isis', $user_id, $title2, $coins, $type2);
        $title2 = '兑换知识币';
        $type2 = '知识币';
        $stmt->execute();

        $db->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $db->rollback();
        http_response_code(500);
        echo json_encode(['error' => '兑换失败: ' . $e->getMessage()]);
    }
    exit;
}