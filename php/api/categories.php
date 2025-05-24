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
header('Access-Control-Allow-Headers: Content-Type, Authorization');


$method = $_SERVER['REQUEST_METHOD'];

// 模拟分类数据
$categories = [
    [
        'id' => 1,
        'name' => 'JavaScript',
        'icon' => 'javascript-original.svg',
        'count' => 128
    ],
    [
        'id' => 2,
        'name' => 'TypeScript',
        'icon' => 'typescript-original.svg',
        'count' => 86
    ],
    [
        'id' => 3,
        'name' => 'Python',
        'icon' => 'python-original.svg',
        'count' => 156
    ],
    [
        'id' => 4,
        'name' => 'Java',
        'icon' => 'java-original.svg',
        'count' => 100
    ],
    [
        'id' => 5,
        'name' => 'Go',
        'icon' => 'go-original.svg',
        'count' => 77
    ],
    [
        'id' => 6,
        'name' => 'Node.js',
        'icon' => 'nodejs-original.svg',
        'count' => 92
    ],
    [
        'id' => 7,
        'name' => 'Vue.js',
        'icon' => 'vuejs-original.svg',
        'count' => 110
    ],
    [
        'id' => 8,
        'name' => 'React',
        'icon' => 'react-original.svg',
        'count' => 134
    ]
];

switch ($method) {
    case 'GET':
        try {
            echo json_encode([
                'status' => 'success',
                'data' => $categories
            ]);
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => '获取分类列表失败: ' . $e->getMessage()
            ]);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
} 