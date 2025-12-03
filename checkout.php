<?php
include 'includes/db.php';
include 'includes/auth.php';
include 'includes/header.php';

// login is required to access the checkout code
require_login();

$user = current_user();
$error = '';
$success = false;

// this will get the items in cart 
try {
    $stmt = $pdo->prepare("
        SELECT c.product_id, c.quantity, p.price, p.stock
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $cartItems = $stmt->fetchAll();
} catch (Exception $e) {
    $cartItems = [];
    $error = "Failed to load cart.";
}

if (empty($cartItems)) {
    echo "<div class='alert alert-warning'>Your cart is empty. <a href='products.php' class='alert-link'>Continue shopping</a></div>";
    include 'includes/footer.php';
    exit;
}

//this will calculate total
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// this will process the order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify the CSRF token 
    verify_csrf_token();
    
    try {
        $pdo->beginTransaction();

        foreach ($cartItems as $item) {
            if ($item['quantity'] > $item['stock']) {
                throw new Exception("Insufficient stock for product ID {$item['product_id']}");
            }
        }

        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $total]);
        $orderId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $updateStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        
        foreach ($cartItems as $item) {
            $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
            $updateStock->execute([$item['quantity'], $item['product_id']]);
        }

        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);

        $pdo->commit();
        $success = true;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Failed to process order: " . $e->getMessage();
    }
}

if ($success):
?>
<div class="alert alert-success alert-dismissible fade show">
    <h4>Order Placed Successfully!</h4>
    <p>Your order has been processed. Thank you for your purchase.</p>
    <a href="orders.php" class="btn btn-primary">View My Orders</a>
    <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
</div>
<?php else: ?>
<h1 class="mb-4">Checkout</h1>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="row">
<div class="col-md-8">
<div class="card mb-4">
<div class="card-header bg-light">
    <h5 class="mb-0">Shipping Information</h5>
</div>
<div class="card-body">
    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p class="text-muted small">Note: Full shipping address collection would be added here.</p>
</div>
</div>

<div class="card">
<div class="card-header bg-light">
    <h5 class="mb-0">Order Items</h5>
</div>
<div class="card-body">
    <table class="table table-sm">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td>Product #<?php echo $item['product_id']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
</div>

<!-- // card for order summary and place the order button -->
<div class="col-md-4">
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Order Summary</h5>
        </div>
        <div class="card-body">
            <p class="d-flex justify-content-between">
                <span>Subtotal:</span>
                <strong>$<?php echo number_format($total, 2); ?></strong>
            </p>
            <p class="d-flex justify-content-between">
                <span>Shipping:</span>
                <strong>Free</strong>
            </p>
            <p class="d-flex justify-content-between">
                <span>Tax:</span>
                <strong>$0.00</strong>
            </p>
            <hr>
            <h4 class="d-flex justify-content-between text-primary">
                <span>Total:</span>
                <strong>$<?php echo number_format($total, 2); ?></strong>
            </h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    ✓ Place Order
                </button>
            </form>
            <a href="cart.php" class="btn btn-outline-secondary w-100 mt-2">Back to Cart</a>
        </div>
    </div>
</div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>