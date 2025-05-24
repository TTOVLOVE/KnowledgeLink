<?php

// 调试信息
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 记录请求信息
file_put_contents(__DIR__ . '/../debug.log', 
    "Request: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . "\n" .
    "GET: " . print_r($_GET, true) . "\n" .
    "POST: " . print_r($_POST, true) . "\n",
    FILE_APPEND
);

file_put_contents(__DIR__ . '/../phpinfo_api.txt', "PHP Version: " . phpversion() . "\n", FILE_APPEND);
file_put_contents(__DIR__ . '/../phpinfo_api.txt', "Loaded ini: " . php_ini_loaded_file() . "\n", FILE_APPEND);
file_put_contents(__DIR__ . '/../phpinfo_api.txt', "SAPI: " . php_sapi_name() . "\n", FILE_APPEND);


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

$method = $_SERVER['REQUEST_METHOD'];
$db = Database::getInstance()->getConnection();

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status'=>'error','error'=>'未登录']);
    exit;
}
$user_id = intval($_SESSION['user_id']);

// 获取文章ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 如果有ID参数，则处理文章详情
if ($id > 0) {
    try {
        // 查询文章详情（不做join，直接查articles表）
        $stmt = $db->prepare("
            SELECT 
                a.id,
                a.title,
                a.content,
                a.created_at,
                u.name as author_name,
                u.avatar as author_avatar
            FROM articles a
            LEFT JOIN users u ON a.user_id = u.id
            WHERE a.id = ?
        ");
        if ($stmt === false) {
            http_response_code(500);
            echo json_encode(['error' => 'SQL预处理失败: ' . $db->error]);
            exit;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            http_response_code(404);
            echo json_encode(['error' => '文章不存在']);
            exit;
        }

        $article = $result->fetch_assoc();

        // 格式化返回数据
        $response = [
            'id' => $article['id'],
            'title' => $article['title'],
            'content' => $article['content'],
            'publishDate' => $article['created_at'],
            'author' => [
                'id' => '',
                'name' => $article['author_name'],
                'avatar' => $article['author_avatar'] ?: 'https://via.placeholder.com/40'
            ]
        ];

        echo json_encode($response);
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => '服务器错误: ' . $e->getMessage()]);
        exit;
    }
}

// 处理文章列表
switch ($method) {
    case 'GET':
        $type = $_GET['type'] ?? 'recommended';
        $categoryId = $_GET['category_id'] ?? null;

        $sql = 'SELECT 
                a.id, 
                a.title, 
                a.user_id, 
                u.name as author_name, 
                u.avatar as author_avatar, 
                c.name as category, 
                a.rating, 
                a.cover, 
                a.summary, 
                a.created_at
                FROM articles a 
                JOIN categories c ON a.category_id = c.id
                LEFT JOIN users u ON a.user_id = u.id';

        $where = [];
        $params = [];
        $param_types = '';
        // 仅当请求参数 my=1 时，才查当前用户的文章，否则查所有文章
if (isset($_GET['my']) && $_GET['my'] == '1') {
    $where[] = 'a.user_id = ?';
    $params[] = $user_id;
    $param_types .= 'i';
}
if ($categoryId) {
            $where[] = 'a.category_id = ?';
            $params[] = $categoryId;
            $param_types .= 'i';
        }
        if (!empty($_GET['search'])) {
            $where[] = '(a.title LIKE ? OR a.summary LIKE ? OR a.content LIKE ?)';
            $search = '%' . $_GET['search'] . '%';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $param_types .= 'sss';
        }
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if ($type === 'trending') {
            $sql .= ' ORDER BY a.rating DESC';
        } else {
            $sql .= ' ORDER BY a.created_at DESC';
        }

        // 预处理
        if ($where) {
            $stmt = $db->prepare($sql);
            if ($stmt === false) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'SQL预处理失败: ' . $db->error]);
                exit;
            }
            $stmt->bind_param($param_types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $db->query($sql);
        }

        if ($result === false) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => '数据库查询失败: ' . $db->error
            ]);
            exit;
        }

        $articles = [];
        while ($row = $result->fetch_assoc()) {
    $articles[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'category' => $row['category'],
        'rating' => $row['rating'],
        'cover' => $row['cover'],
        'summary' => $row['summary'],
        'created_at' => $row['created_at'],
        'author' => [
            'id' => $row['user_id'],
            'name' => $row['author_name'],
            'avatar' => (isset($row['author_avatar']) && $row['author_avatar'] && !preg_match('#^https?://#', $row['author_avatar']) ?
    ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . ltrim($row['author_avatar'], '/')) :
    ($row['author_avatar'] ?: 'https://via.placeholder.com/40'))
        ]
    ];
}

        echo json_encode([
            'status' => 'success',
            'data' => $articles
        ]);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
} 