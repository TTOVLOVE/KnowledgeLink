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

$uploadDir = __DIR__ . '/../uploads/avatars/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['avatar'])) {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
        exit;
    }
    $file = $_FILES['avatar'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Upload error']);
        exit;
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg','jpeg','png','gif','webp'];
    if (!in_array(strtolower($ext), $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
        exit;
    }
    $newName = uniqid('avatar_', true) . '.' . $ext;
    $targetPath = $uploadDir . $newName;
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode(['status' => 'error', 'message' => 'Save failed']);
        exit;
    }
    // 返回完整URL（带端口）
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $url = $protocol . '://' . $host . '/uploads/avatars/' . $newName;
    echo json_encode(['status' => 'success', 'url' => $url]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
exit;
