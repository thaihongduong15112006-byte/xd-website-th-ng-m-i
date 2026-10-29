<?php
require_once __DIR__ . '/config/database.php';
$username = 'admin';
$stmt = $conn->prepare('SELECT id FROM users WHERE username=?'); $stmt->execute([$username]);
if ($stmt->fetch()) { exit('Tài khoản admin đã tồn tại. Hãy xóa file setup_admin.php ngay.'); }
$stmt = $conn->prepare("INSERT INTO users(fullname,username,password,role) VALUES(?,?,?,'admin')");
$stmt->execute(['Quản trị viên', $username, password_hash('Admin123!', PASSWORD_DEFAULT)]);
echo 'Đã tạo admin. Username: admin | Password: Admin123!<br>Hãy xóa file setup_admin.php ngay sau khi sử dụng.';
?>
