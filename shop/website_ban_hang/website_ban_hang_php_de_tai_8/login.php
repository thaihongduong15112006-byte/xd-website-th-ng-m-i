<?php
require_once __DIR__ . '/config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? ''); $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT * FROM users WHERE username=?'); $stmt->execute([$username]); $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = ['id'=>$user['id'],'fullname'=>$user['fullname'],'username'=>$user['username'],'role'=>$user['role']];
        if ($user['role'] === 'admin') { header('Location: admin/index.php'); } else { header('Location: index.php'); } exit;
    } else { $error = 'Tên đăng nhập hoặc mật khẩu không đúng.'; }
}
require_once __DIR__ . '/includes/header.php'; ?>
<div class="form-box"><h1>Đăng nhập</h1><?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?><form method="post"><label>Tên đăng nhập</label><input name="username" required><label>Mật khẩu</label><input type="password" name="password" required><button class="btn" type="submit">Đăng nhập</button></form><p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p></div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
