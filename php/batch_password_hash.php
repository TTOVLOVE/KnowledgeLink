<?php
// 批量将 users 表中明文密码加密为 password_hash
require_once __DIR__ . '/config/Database.php';
$db = Database::getInstance()->getConnection();

// 只处理未加密的密码（假设加密后长度小于30，明文一般较短）
$sql = "SELECT id, password FROM users WHERE LENGTH(password) < 30";
$res = $db->query($sql);
$count = 0;
while ($row = $res->fetch_assoc()) {
    $id = $row['id'];
    $plain = $row['password'];
    // 跳过空密码
    if (!$plain) continue;
    $hashed = password_hash($plain, PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param('si', $hashed, $id);
    if ($stmt->execute()) {
        $count++;
        echo "User $id password updated.\n";
    } else {
        echo "Failed to update user $id: " . $stmt->error . "\n";
    }
}
echo "Done. $count users updated.\n";
