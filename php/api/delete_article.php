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

// 获取 id 参数（支持 GET 或 DELETE）
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0 && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // DELETE 方法下，尝试从请求体获取 id
    $input = json_decode(file_get_contents('php://input'), true);
    $id = isset($input['id']) ? intval($input['id']) : 0;
}

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => '缺少或错误的文章ID']);
    exit;
}

try {
    $stmt = $db->prepare('DELETE FROM articles WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => '未找到该文章或已被删除']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => '删除失败: ' . $e->getMessage()]);
} 