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

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // 处理 GET 请求
        echo json_encode([
            'status' => 'success',
            'message' => 'This is a GET request example',
            'data' => [
                'id' => 1,
                'name' => 'Example Item'
            ]
        ]);
        break;
        
    case 'POST':
        // 处理 POST 请求
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode([
            'status' => 'success',
            'message' => 'Data received successfully',
            'received_data' => $data
        ]);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
} 