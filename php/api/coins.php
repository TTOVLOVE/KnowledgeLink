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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $article_id = intval($_GET['article_id'] ?? 0);
    // 统计该文章总投币数
    $stmt = $db->prepare('SELECT SUM(count) as total FROM coins WHERE article_id = ?');
    $stmt->bind_param('i', $article_id);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    $total = $total ?: 0;

    // 查询当前用户知识币余额
    $stmt = $db->prepare('SELECT coins FROM users WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($balance);
    $stmt->fetch();
    $stmt->close();

    echo json_encode([
        'coinCount' => $total,
        'balance' => $balance
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $article_id = intval($data['article_id'] ?? 0);

    // 检查余额
    $stmt = $db->prepare('SELECT coins FROM users WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($balance);
    $stmt->fetch();
    $stmt->close();

    if ($balance < 1) {
        http_response_code(400);
        echo json_encode(['error' => '知识币不足']);
        exit;
    }

    // 扣除用户知识币，给文章加币，并插入明细表
    $db->begin_transaction();
    try {
        $stmt = $db->prepare('UPDATE users SET coins = coins - 1 WHERE id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();

        $stmt = $db->prepare('INSERT INTO coins (article_id, user_id, count, time) VALUES (?, ?, 1, NOW())');
        $stmt->bind_param('ii', $article_id, $user_id);
        $stmt->execute();

        // 新增：插入明细表
        $stmt = $db->prepare('INSERT INTO points_details (user_id, title, time, points, type) VALUES (?, ?, NOW(), ?, ?)');
        $title = '给文章投币';
        $points = -1;
        $type = '知识币';
        $stmt->bind_param('isis', $user_id, $title, $points, $type);
        $stmt->execute();

        $db->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $db->rollback();
        http_response_code(500);
        echo json_encode(['error' => '投币失败: ' . $e->getMessage()]);
    }
    exit;
}