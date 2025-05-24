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

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
require_once __DIR__ . '/../config/Database.php';
$db = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    if ($id > 0) {
        // 单个问题详情
        $stmt = $db->prepare('SELECT q.*, u.name as author_name, u.avatar as author_avatar FROM questions q LEFT JOIN users u ON q.user_id = u.id WHERE q.id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $question = $result->fetch_assoc();
        if ($question) {
            // 补全问题作者头像
            if (isset($question['author_avatar']) && $question['author_avatar'] && !preg_match('#^https?://#', $question['author_avatar'])) {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'];
                $question['author_avatar'] = $protocol . '://' . $host . '/' . ltrim($question['author_avatar'], '/');
            }
            // 查询回答
            $stmt2 = $db->prepare('SELECT a.*, u.name as author_name, u.avatar as author_avatar FROM answers a LEFT JOIN users u ON a.user_id = u.id WHERE a.question_id = ? ORDER BY a.created_at ASC');
            $stmt2->bind_param('i', $id);
            $stmt2->execute();
            $answers = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
            // 补全回答作者头像
            foreach ($answers as &$a) {
                if (isset($a['author_avatar']) && $a['author_avatar'] && !preg_match('#^https?://#', $a['author_avatar'])) {
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $host = $_SERVER['HTTP_HOST'];
                    $a['author_avatar'] = $protocol . '://' . $host . '/' . ltrim($a['author_avatar'], '/');
                }
            }
            $question['answers'] = $answers;
            echo json_encode(['status'=>'success','data'=>$question]);
        } else {
            http_response_code(404);
            echo json_encode(['status'=>'error','error'=>'未找到该问题']);
        }
        exit;
    }
    // 发布新问题
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $title = trim($data['title'] ?? '');
    $content = trim($data['content'] ?? '');
    $user_id = intval($data['user_id'] ?? 0);
    $reward = intval($data['reward'] ?? 0);
    $tags = $data['tags'] ?? [];
    if (!$title || !$content || !$user_id) {
        http_response_code(400);
        echo json_encode(['status'=>'error','error'=>'参数不完整']);
        exit;
    }
    // tags 允许数组或字符串
    if (is_array($tags)) {
        $tags = implode(',', $tags);
    }
    $stmt = $db->prepare('INSERT INTO questions (title, content, user_id, reward, tags, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
    $stmt->bind_param('ssiss', $title, $content, $user_id, $reward, $tags);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'success','id'=>$db->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','error'=>'发布失败: ' . $db->error]);
    }
    exit;
}

// 问题列表
    $sql = 'SELECT q.*, u.name as author_name, u.avatar as author_avatar FROM questions q LEFT JOIN users u ON q.user_id = u.id';
    $params = [];
    $types = '';
    $where = [];
    if ($search !== '') {
        $where[] = '(q.title LIKE ? OR q.content LIKE ? OR q.tags LIKE ?)';
        $searchKey = "%$search%";
        $params = [$searchKey, $searchKey, $searchKey];
        $types = 'sss';
    }
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY q.created_at DESC LIMIT 20';
    if ($params) {
        $stmt = $db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $db->query($sql);
    }
    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $row['tags'] = $row['tags'] ? explode(',', $row['tags']) : [];
        // 查询回答数
        $stmt3 = $db->prepare('SELECT COUNT(*) FROM answers WHERE question_id = ?');
        $stmt3->bind_param('i', $row['id']);
        $stmt3->execute();
        $stmt3->bind_result($answerCount);
        $stmt3->fetch();
        $stmt3->close();
        $row['answerCount'] = $answerCount;
        $questions[] = $row;
    }
    echo json_encode(['status'=>'success','data'=>$questions]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['status'=>'error','error'=>'未登录，无法发布问题']);
        exit;
    }
    $user_id = intval($_SESSION['user_id']);
    $data = json_decode(file_get_contents('php://input'), true);
    $title = trim($data['title'] ?? '');
    $content = trim($data['content'] ?? '');
    $reward = intval($data['reward'] ?? 0);
    $tags = isset($data['tags']) && is_array($data['tags']) ? implode(',', $data['tags']) : '';
    if (!$title || !$content) {
        http_response_code(400);
        echo json_encode(['status'=>'error','error'=>'参数不完整']);
        exit;
    }
    $stmt = $db->prepare('INSERT INTO questions (user_id, title, content, reward, tags, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
    $stmt->bind_param('issis', $user_id, $title, $content, $reward, $tags);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'success','id'=>$db->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['status'=>'error','error'=>'发布失败: ' . $db->error]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['status'=>'error','error'=>'不支持的请求方式']);
