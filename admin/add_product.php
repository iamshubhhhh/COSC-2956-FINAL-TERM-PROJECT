<?php
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/auth.php';
require_admin();
include_once __DIR__ . '/../includes/header.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Verifying the CSRF token
verify_csrf_token();
$name = trim($_POST['name'] ?? '');
$category = trim($_POST['category'] ?? '');
$price = $_POST['price'] ?? 0;
$stock = $_POST['stock'] ?? 0;
$description = trim($_POST['description'] ?? '');
$image = trim($_POST['image'] ?? '');

if ($name === '') $errors[] = 'Name is required.';
if (!is_numeric($price)) $errors[] = 'Price must be a number.';
if (!is_numeric($stock)) $errors[] = 'Stock must be a number.';

if (empty($errors)) {
$stmt = $pdo->prepare('INSERT INTO products (name, category, price, stock, description, image) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->execute([$name, $category, (float)$price, (int)$stock, $description, $image]);
header('Location: products.php');
exit;
}
}
?>

<div class="container py-4">
<h1>Add Product</h1>
<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <ul><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul>
</div>
<?php endif; ?>

<form id="productForm" method="POST" class="row g-3" novalidate>
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
<div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
</div>
<div class="col-md-6">
    <label class="form-label">Category</label>
    <input name="category" class="form-control" value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
</div>
<div class="col-md-3">
    <label class="form-label">Price</label>
    <input name="price" type="number" step="0.01" class="form-control" value="<?php echo htmlspecialchars($_POST['price'] ?? '0'); ?>">
</div>
<div class="col-md-3">
    <label class="form-label">Stock</label>
    <input name="stock" type="number" class="form-control" value="<?php echo htmlspecialchars($_POST['stock'] ?? '0'); ?>">
</div>
<div class="col-12">
    <label class="form-label">Image filename (in /images/)</label>
    <input name="image" class="form-control" value="<?php echo htmlspecialchars($_POST['image'] ?? ''); ?>">
</div>
<div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
</div>
<div class="col-12">
    <button class="btn btn-primary">Add Product</button>
    <a href="products.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
