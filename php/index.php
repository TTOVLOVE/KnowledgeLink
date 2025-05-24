<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 获取请求路径
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// 移除开头的斜杠和可能的 /api 前缀
$path = ltrim($path, '/');
$path = str_replace('api/', '', $path);

// 路由处理
switch ($path) {
    case 'articles':
        require __DIR__ . '/api/articles.php';
        break;
    case 'categories':
        require __DIR__ . '/api/categories.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
} 