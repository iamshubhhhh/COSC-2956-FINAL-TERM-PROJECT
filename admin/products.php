<?php
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/auth.php';
require_admin();
include_once __DIR__ . '/../includes/header.php';

// Fetch products
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h1>Manage Products</h1>
        <a href="add_product.php" class="btn btn-success">Add Product</a>
    </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-info">No products found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td style="width:90px;"><img src="../images/<?php echo htmlspecialchars($p['image'] ?? ''); ?>" alt="" style="height:50px; object-fit:cover;"></td>
                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                            <td><?php echo htmlspecialchars($p['category']); ?></td>
                            <td>$<?php echo number_format($p['price'],2); ?></td>
                            <td><?php echo (int)$p['stock']; ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <form method="POST" action="delete_product.php" style="display:inline-block; margin:0;">
                                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                    <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Delete this product?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
