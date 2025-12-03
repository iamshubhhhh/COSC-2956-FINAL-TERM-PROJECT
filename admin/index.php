// Admin Dashboard

<?php
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/auth.php';
require_admin();
include_once __DIR__ . '/../includes/header.php';

?>
<div class="container py-4">
    <h1>Admin Dashboard</h1>
    <p class="lead">Quick links</p>
    <div class="list-group">
        <a href="products.php" class="list-group-item list-group-item-action">Manage Products</a>
        <a href="orders.php" class="list-group-item list-group-item-action">View Orders</a>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
