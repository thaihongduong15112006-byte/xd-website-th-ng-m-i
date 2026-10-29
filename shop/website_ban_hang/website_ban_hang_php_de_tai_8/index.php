<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';
$sql = "SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id=c.id WHERE 1=1";
$params = [];
if (!empty($_GET['keyword'])) { $sql .= " AND p.name LIKE ?"; $params[] = '%' . trim($_GET['keyword']) . '%'; }
if (!empty($_GET['category_id'])) { $sql .= " AND p.category_id = ?"; $params[] = (int)$_GET['category_id']; }
$sql .= " ORDER BY p.id DESC";
$stmt = $conn->prepare($sql); $stmt->execute($params); $products = $stmt->fetchAll();
$categories = $conn->query('SELECT * FROM categories ORDER BY name')->fetchAll();
?>
<section class="hero"><div><p class="eyebrow">SHOP SINH VIÊN</p><h1>Mua sắm dễ dàng mỗi ngày</h1><p>Khám phá sản phẩm công nghệ và phụ kiện với hình ảnh minh họa trực quan.</p></div><div class="hero-icon">🛍️</div></section><h2>Sản phẩm nổi bật</h2><p class="muted">Tìm kiếm theo tên hoặc lọc theo danh mục.</p>
<form class="search" method="get"><input type="text" name="keyword" placeholder="Nhập tên sản phẩm..." value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"><select name="category_id"><option value="">-- Tất cả danh mục --</option><?php foreach ($categories as $cat): ?><option value="<?= $cat['id'] ?>" <?= (($_GET['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option><?php endforeach; ?></select><button class="btn" type="submit">Tìm kiếm</button></form>
<div class="products"><?php if (!$products): ?><p>Không tìm thấy sản phẩm phù hợp.</p><?php endif; ?><?php foreach ($products as $p): ?><article class="product"><img class="product-image" src="<?= htmlspecialchars($p['image'] ?: 'assets/images/phone.svg') ?>" alt="<?= htmlspecialchars($p['name']) ?>" onerror="this.onerror=null;this.src='assets/images/phone.svg'"><h3><?= htmlspecialchars($p['name']) ?></h3><p class="muted">Danh mục: <?= htmlspecialchars($p['category_name']) ?></p><p class="price"><?= number_format($p['price'], 0, ',', '.') ?> đ</p><p><?= nl2br(htmlspecialchars($p['description'] ?? '')) ?></p><a class="btn" href="product.php?id=<?= $p['id'] ?>">Xem chi tiết</a></article><?php endforeach; ?></div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
