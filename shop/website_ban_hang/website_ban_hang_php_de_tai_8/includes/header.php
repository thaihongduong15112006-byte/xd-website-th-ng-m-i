<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Website bán hàng</title><link rel="stylesheet" href="/website_ban_hang_php_de_tai_8/assets/style.css"></head><body>
<header class="top"><a class="brand" href="/website_ban_hang_php_de_tai_8/index.php">SHOP SINH VIÊN</a><nav><a href="/website_ban_hang_php_de_tai_8/index.php">Trang chủ</a>
<?php if (!empty($_SESSION['user'])): ?>
<span>Xin chào, <?= htmlspecialchars($_SESSION['user']['fullname']) ?></span>
<?php if ($_SESSION['user']['role'] === 'admin'): ?><a href="/website_ban_hang_php_de_tai_8/admin/index.php">Quản trị</a><?php endif; ?>
<a href="/website_ban_hang_php_de_tai_8/logout.php">Đăng xuất</a>
<?php else: ?><a href="/website_ban_hang_php_de_tai_8/login.php">Đăng nhập</a><a href="/website_ban_hang_php_de_tai_8/register.php">Đăng ký</a><?php endif; ?></nav></header><main class="container">
