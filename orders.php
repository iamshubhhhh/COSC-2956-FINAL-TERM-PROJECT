<?php
include 'includes/db.php';
include 'includes/auth.php';
include 'includes/header.php';

require_login();

$user = current_user();
$orders = [];

try {
    $stmt = $pdo->prepare("
        SELECT * FROM orders 
        WHERE user_id = ? 
        ORDER BY order_date DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll();
} catch (Exception $e) {
    $error = "Failed to load orders.";
}
?>

<h1 class="mb-4">My Orders</h1>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (empty($orders)): ?>
    <div class="alert alert-info">
        No orders yet. <a href="products.php" class="alert-link">Start shopping</a>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                    <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                    <td>
                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="products.php" class="btn btn-primary">Continue Shopping</a>
</div>

<?php include 'includes/footer.php'; ?>
