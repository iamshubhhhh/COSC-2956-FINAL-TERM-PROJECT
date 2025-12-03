<?php
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/auth.php';
require_admin();
include_once __DIR__ . '/../includes/header.php';

// Fetch orders with user info
$stmt = $pdo->query(
    'SELECT o.id, o.user_id, o.total_price, o.order_date, u.name as user_name, u.email as user_email FROM orders o LEFT JOIN users u ON u.id = o.user_id ORDER BY o.order_date DESC'
);
$orders = $stmt->fetchAll();
?>

<div class="container py-4">
    <h1>All Orders</h1>
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">No orders found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><?php echo $o['id']; ?></td>
                        <td><?php echo htmlspecialchars($o['user_name'] ?? $o['user_email']); ?></td>
                        <td>$<?php echo number_format($o['total_price'],2); ?></td>
                        <td><?php echo htmlspecialchars($o['order_date']); ?></td>
                        <td>
                            <?php
                                // Fetching all item summary for the order
                                $stmt2 = $pdo->prepare('SELECT oi.quantity, p.name FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?');
                                $stmt2->execute([$o['id']]);
                                $items = $stmt2->fetchAll();
                                foreach ($items as $it) {
                                    echo htmlspecialchars($it['name']) . ' x' . (int)$it['quantity'] . '<br>';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
