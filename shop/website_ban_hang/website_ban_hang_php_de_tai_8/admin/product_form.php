<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);
$product = ['name'=>'', 'description'=>'', 'price'=>'', 'category_id'=>'', 'image'=>''];
if ($id > 0) {
    $stmt = $conn->prepare('SELECT * FROM products WHERE id=?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) $product = $found;
}
$categories = $conn->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $image = $product['image'] ?? '';

    if ($name === '' || $price < 0 || $category_id < 1) {
        $error = 'Vui lòng nhập tên, giá hợp lệ và chọn danh mục.';
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $error = 'Tải ảnh lên không thành công.';
            } else {
                $tmp = $_FILES['image']['tmp_name'];
                $info = getimagesize($tmp);
                $allowed = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/gif'=>'gif', 'image/webp'=>'webp'];
                $mime = $info['mime'] ?? '';
                if (!$info || !isset($allowed[$mime])) {
                    $error = 'Ảnh phải có định dạng JPG, PNG, GIF hoặc WEBP.';
                } elseif ($_FILES['image']['size'] > 3 * 1024 * 1024) {
                    $error = 'Dung lượng ảnh tối đa là 3 MB.';
                } else {
                    $uploadDir = __DIR__ . '/../uploads/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $fileName = uniqid('product_', true) . '.' . $allowed[$mime];
                    if (move_uploaded_file($tmp, $uploadDir . $fileName)) {
                        if ($image !== '' && strpos($image, 'uploads/') === 0) {
                            $oldFile = __DIR__ . '/../' . $image;
                            if (is_file($oldFile)) unlink($oldFile);
                        }
                        $image = 'uploads/' . $fileName;
                    } else {
                        $error = 'Không lưu được ảnh. Hãy kiểm tra quyền thư mục uploads.';
                    }
                }
            }
        }
        if ($error === '') {
            if ($id > 0) {
                $stmt = $conn->prepare('UPDATE products SET name=?, description=?, price=?, category_id=?, image=? WHERE id=?');
                $stmt->execute([$name, $description, $price, $category_id, $image, $id]);
            } else {
                $stmt = $conn->prepare('INSERT INTO products(name,description,price,category_id,image) VALUES(?,?,?,?,?)');
                $stmt->execute([$name, $description, $price, $category_id, $image]);
            }
            header('Location: products.php');
            exit;
        }
    }
    $product = array_merge($product, ['name'=>$name, 'description'=>$description, 'price'=>$price, 'category_id'=>$category_id, 'image'=>$image]);
}
require_once __DIR__ . '/../includes/header.php';
?>
<div class="form-box">
    <h1><?= $id ? 'Sửa sản phẩm' : 'Thêm sản phẩm' ?></h1>
    <a href="products.php">← Quay lại</a>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!$categories): ?><div class="error">Bạn cần tạo danh mục trước.</div><?php else: ?>
    <form method="post" enctype="multipart/form-data">
        <label>Tên sản phẩm</label><input name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        <label>Mô tả</label><textarea name="description" rows="4"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
        <label>Giá (VNĐ)</label><input type="number" min="0" step="1000" name="price" value="<?= htmlspecialchars((string)$product['price']) ?>" required>
        <label>Danh mục</label><select name="category_id" required><option value="">-- Chọn danh mục --</option><?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>" <?= ($product['category_id']==$c['id'])?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option><?php endforeach; ?></select>
        <label>Ảnh sản phẩm (JPG, PNG, GIF hoặc WEBP; tối đa 3 MB)</label><input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
        <?php if (!empty($product['image'])): ?><p>Ảnh hiện tại:</p><img class="form-preview" src="../<?= htmlspecialchars($product['image']) ?>" alt="Ảnh sản phẩm"><?php endif; ?>
        <button class="btn" type="submit">Lưu sản phẩm</button>
    </form><?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
