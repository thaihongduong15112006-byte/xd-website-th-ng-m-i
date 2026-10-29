<?php
require_once __DIR__ . '/config/database.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.id=?');
$stmt->execute([$id]); $product = $stmt->fetch();
require_once __DIR__ . '/includes/header.php';
if (!$product) { echo '<div class="error">Không tìm thấy sản phẩm.</div>'; } else { ?>
<div class="panel"><a href="index.php">← Quay lại trang chủ</a><h1><?= htmlspecialchars($product['name']) ?></h1><p class="muted">Danh mục: <?= htmlspecialchars($product['category_name']) ?></p><img class="detail-image" src="<?= htmlspecialchars($product['image'] ?: 'assets/images/phone.svg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.onerror=null;this.src='assets/images/phone.svg'"><p class="price"><?= number_format($product['price'],0,',','.') ?> đ</p><p><?= nl2br(htmlspecialchars($product['description'] ?? '')) ?></p><p class="muted">Đây là website bài tập minh họa; chức năng đặt hàng chưa được triển khai.</p></div>
<?php } require_once __DIR__ . '/includes/footer.php'; ?>
