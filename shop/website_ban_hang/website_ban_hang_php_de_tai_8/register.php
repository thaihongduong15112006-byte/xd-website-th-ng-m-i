<?php
require_once __DIR__ . '/config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$error = ''; $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? ''); $username = trim($_POST['username'] ?? ''); $password = $_POST['password'] ?? '';
    if ($fullname === '' || $username === '' || strlen($password) < 6) { $error = 'Vui lòng nhập đầy đủ thông tin; mật khẩu tối thiểu 6 ký tự.'; }
    else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username=?'); $stmt->execute([$username]);
        if ($stmt->fetch()) { $error = 'Tên đăng nhập đã tồn tại.'; }
        else { $stmt = $conn->prepare("INSERT INTO users(fullname,username,password,role) VALUES(?,?,?,'user')"); $stmt->execute([$fullname,$username,password_hash($password,PASSWORD_DEFAULT)]); $success = 'Đăng ký thành công. Bạn có thể đăng nhập.'; }
    }
}
require_once __DIR__ . '/includes/header.php'; ?>
<div class="form-box"><h1>Đăng ký tài khoản</h1><?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?><?php if ($success): ?><div class="notice"><?= htmlspecialchars($success) ?></div><?php endif; ?><form method="post"><label>Họ và tên</label><input name="fullname" required><label>Tên đăng nhập</label><input name="username" required><label>Mật khẩu (ít nhất 6 ký tự)</label><input type="password" name="password" minlength="6" required><button class="btn" type="submit">Đăng ký</button></form></div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
