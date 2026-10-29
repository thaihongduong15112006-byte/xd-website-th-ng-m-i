<?php
if (session_status() === PHP_SESSION_NONE) session_start();
function requireLogin() {
    if (empty($_SESSION['user'])) {
        header('Location: /website_ban_hang_php_de_tai_8/login.php');
        exit;
    }
}
function requireAdmin() {
    requireLogin();
    if ($_SESSION['user']['role'] !== 'admin') {
        header('Location: /website_ban_hang_php_de_tai_8/index.php');
        exit;
    }
}
?>
