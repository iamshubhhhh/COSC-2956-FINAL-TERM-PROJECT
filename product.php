<?php 
include 'includes/db.php';
include 'includes/auth.php';
include 'includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid product.</div>";
    include 'includes/footer.php';
    exit;
}

$id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
} catch (Exception $e) {
    $product = null;
}

if (!$product) {
    echo "<div class='alert alert-danger'>Product not found.</div>";
    include 'includes/footer.php';
    exit;
}

$addedToCart = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_logged_in()) {
        header('Location: login.php?redirect=product.php?id=' . urlencode($id));
        exit;
    }
    // Verify CSRF token
    verify_csrf_token();

    $quantity = intval($_POST['quantity'] ?? 1);
    if ($quantity < 1) $quantity = 1;

    try {
        // Checks if product exists and get stock function
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? LIMIT 1");
        $stmt->execute([$_SESSION['user_id'], $id]);
        $existing = $stmt->fetch();

        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            if ($newQty > $product['stock']) {
                $newQty = $product['stock'];
            }
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$newQty, $existing['id'], $_SESSION['user_id']]);
        } else {
            $toInsertQty = $quantity;
            if ($toInsertQty > $product['stock']) $toInsertQty = $product['stock'];
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $id, $toInsertQty]);
        }

        $addedToCart = true;
    } catch (Exception $e) {
        $error = "Failed to add to cart.";
    }
}
?>

<?php if ($addedToCart): ?>
    <div class="alert alert-success alert-dismissible fade show">
        Product added to cart! <a href="cart.php" class="alert-link">View cart</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-5">
    <div class="col-md-5">
        <?php if (!empty($product['image'])): ?>
            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" 
                 class="img-fluid rounded shadow-sm" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <?php else: ?>
            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                <span class="text-white">No Image Available</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-7">
        <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="text-muted fs-4">$<?php echo number_format($product['price'], 2); ?></p>
        
        <p class="text-secondary"><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
        <p class="text-secondary"><strong>Stock:</strong> <?php echo $product['stock']; ?> available</p>

        <p class="mt-4 lead"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

        <?php if ($product['stock'] > 0): ?>
            <form method="POST" class="mt-4">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <div class="mb-3">
                    <label class="form-label">Quantity:</label>
                    <input type="number" name="quantity" class="form-control" style="width: 100px;" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                </div>
                <?php if (is_logged_in()): ?>
                    <button type="submit" class="btn btn-primary btn-lg">
                        🛒 Add to Cart
                    </button>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-lg">Login to Add to Cart</a>
                <?php endif; ?>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">Out of Stock</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
