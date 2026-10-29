<?php
require_once __DIR__ . '/../includes/auth.php'; requireAdmin(); require_once __DIR__ . '/../config/database.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) { $name = trim($_POST['name'] ?? ''); if ($name !== '') { $stmt=$conn->prepare('INSERT INTO categories(name) VALUES(?)'); $stmt->execute([$name]); $message='Đã thêm danh mục.'; } }
if (isset($_GET['delete'])) { $stmt=$conn->prepare('DELETE FROM categories WHERE id=?'); $stmt->execute([(int)$_GET['delete']]); header('Location: categories.php'); exit; }
$categories=$conn->query('SELECT * FROM categories ORDER BY id DESC')->fetchAll(); require_once __DIR__ . '/../includes/header.php'; ?>
<div class="panel"><h1>Quản lý danh mục</h1><a class="btn gray" href="index.php">← Trang quản trị</a><?php if($message): ?><div class="notice"><?= htmlspecialchars($message) ?></div><?php endif; ?><form method="post"><label>Tên danh mục</label><input name="name" required><button class="btn" name="add">Thêm danh mục</button></form><h2>Danh sách danh mục</h2><div class="table-wrap"><table><tr><th>ID</th><th>Tên danh mục</th><th>Thao tác</th></tr><?php foreach($categories as $c): ?><tr><td><?= $c['id'] ?></td><td><?= htmlspecialchars($c['name']) ?></td><td><a class="btn danger" onclick="return confirm('Xóa danh mục này và sản phẩm thuộc danh mục?')" href="?delete=<?= $c['id'] ?>">Xóa</a></td></tr><?php endforeach; ?></table></div></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
