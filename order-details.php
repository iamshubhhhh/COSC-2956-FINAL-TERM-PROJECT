<?php
include 'includes/db.php';
include 'includes/auth.php';
include 'includes/header.php';

require_login();

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($orderId <= 0) {
    echo "<div class='alert alert-danger'>Invalid order.</div>";
    include 'includes/footer.php';
    exit;
}

// Ensure this order belongs to the logged-in user
$stmt = $pdo->prepare('SELECT id, user_id, total_price, order_date FROM orders WHERE id = ? AND user_id = ?');
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    include 'includes/footer.php';
    exit;
}

// Fetch order items with product info
$stmt = $pdo->prepare('SELECT oi.product_id, oi.quantity, oi.price, p.name, p.image FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?');
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();
?>

<h1 class="mb-3">Order #<?php echo $order['id']; ?></h1>
<p class="text-muted">Placed on <?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?></p>

<div class="card mb-4">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>Product</th>
            <th class="text-center">Qty</th>
            <th class="text-end">Price</th>
            <th class="text-end">Subtotal</th>
          </tr>
        </thead>
        <tbody>
        <?php $computedTotal = 0; foreach ($items as $it): $sub = $it['quantity'] * $it['price']; $computedTotal += $sub; ?>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <?php if (!empty($it['image'])): ?>
                  <img src="images/<?php echo htmlspecialchars($it['image']); ?>" alt="" style="height:48px; width:48px; object-fit:cover; border-radius:8px;">
                <?php endif; ?>
                <div>
                  <div class="fw-semibold"><?php echo htmlspecialchars($it['name']); ?></div>
                  <div class="text-muted small">#<?php echo (int)$it['product_id']; ?></div>
                </div>
              </div>
            </td>
            <td class="text-center"><?php echo (int)$it['quantity']; ?></td>
            <td class="text-end">$<?php echo number_format($it['price'], 2); ?></td>
            <td class="text-end">$<?php echo number_format($sub, 2); ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <hr>
    <div class="d-flex justify-content-end">
      <div style="min-width:260px;">
        <div class="d-flex justify-content-between">
          <span class="text-muted">Total charged:</span>
          <strong>$<?php echo number_format($order['total_price'], 2); ?></strong>
        </div>
        <div class="d-flex justify-content-between text-muted">
          <span>Items subtotal:</span>
          <span>$<?php echo number_format($computedTotal, 2); ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<a href="orders.php" class="btn btn-outline-secondary">Back to Orders</a>
<a href="products.php" class="btn btn-primary">Continue Shopping</a>

<?php include 'includes/footer.php'; ?>
