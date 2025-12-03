<?php 
include 'includes/db.php';
include 'includes/auth.php';
include 'includes/header.php';

require_login();

$user = current_user();
$cartItems = [];
$total = 0;

try {
    // Fetch the cart items for the current user
    $stmt = $pdo->prepare("
        SELECT c.id as cart_id, c.product_id, c.quantity, p.id, p.name, p.price, p.image, p.stock
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
        ORDER BY c.id DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $cartItems = $stmt->fetchAll();
    
    // Calculates total
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }
} catch (Exception $e) {
    $error = "Failed to load cart.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
    // Verify CSRF token
    verify_csrf_token();
    $cartId = intval($_POST['cart_id'] ?? 0);
    try {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cartId, $_SESSION['user_id']]);
        header('Location: cart.php');
        exit;
    } catch (Exception $e) {
        $error = "Failed to remove item.";
    }
}

// updates quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    // Verify CSRF token
    verify_csrf_token();
    $cartId = intval($_POST['cart_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    if ($quantity < 1) $quantity = 1;
    
    try {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cartId, $_SESSION['user_id']]);
        header('Location: cart.php');
        exit;
    } catch (Exception $e) {
        $error = "Failed to update quantity.";
    }
}
?>

<h1 class="mb-4">Shopping Cart</h1>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (empty($cartItems)): ?>
<div class="alert alert-info">
Your cart is empty. <a href="products.php" class="alert-link">Continue shopping</a>
</div>
<?php else: ?>
<div class="table-responsive">
<table class="table">
<thead class="table-light">
<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Subtotal</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($cartItems as $item): 
    $subtotal = $item['price'] * $item['quantity'];
?>
<tr>
    <td>
        <a href="product.php?id=<?php echo $item['product_id']; ?>" class="text-decoration-none">
            <?php echo htmlspecialchars($item['name']); ?>
        </a>
    </td>
    <td>$<?php echo number_format($item['price'], 2); ?></td>
    <td>
        <form method="POST" class="d-inline">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
        <input type="number" name="quantity" class="form-control" style="width: 70px; display: inline-block;" 
                value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" required>
        <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
        </form>
    </td>
    <td>$<?php echo number_format($subtotal, 2); ?></td>
    <td>
        <form method="POST" class="d-inline">
        <input type="hidden" name="action" value="remove">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<div class="row mt-4">
<div class="col-md-4 ms-auto">
<div class="card">
<div class="card-body">
    <h5 class="card-title">Order Summary</h5>
    <hr>
    <h4 class="text-primary">Total: $<?php echo number_format($total, 2); ?></h4>
</div>
</div>
</div>
</div>

<div class="mt-4">
<a href="products.php" class="btn btn-outline-secondary">Continue Shopping</a>
<a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
