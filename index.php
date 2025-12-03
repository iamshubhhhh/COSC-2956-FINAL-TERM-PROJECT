<?php 
include 'includes/db.php';
include 'includes/auth.php';
include 'includes/header.php';

$products = [];
try {
    $stmt = $pdo->query("SELECT * FROM products LIMIT 8");
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
}
?>

<!-- Hero Video Section -->
<section class="hero-video-section mb-5">
<video autoplay muted loop playsinline preload="metadata">
<source src="./videos/homepagevideo.mp4" type="video/mp4">
Your browser does not support HTML5 video.
</video>
<div class="hero-video-overlay">
<div class="hero-video-content">
<h1 class="brand-gradient" style="text-shadow:none;">Welcome to G7 Store</h1>
<p>Find the latest computer products and accessories</p>
<?php if (!isset($_SESSION['user_id'])): ?>
    <a href="register.php" class="btn btn-primary btn-lg me-2">Get Started</a>
    <a href="login.php" class="btn btn-outline-primary btn-lg">Login</a>
<?php else: ?>
    <a href="products.php" class="btn btn-primary btn-lg">Shop Now</a>
<?php endif; ?>
</div>
</div>
</section>

<!-- Products -->
<section>
    <h2 class="mb-4">Featured Products</h2>
    
    <?php if (empty($products)): ?>
        <div class="alert alert-info">No products available yet.</div>
    <?php else: ?>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
<?php foreach ($products as $product): ?>
<div class="col">
<div class="card h-100 shadow-sm">
<?php if (!empty($product['image'])): ?>
    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 200px; object-fit: cover;">
<?php else: ?>
    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
        <span class="text-white">No Image</span>
    </div>
<?php endif; ?>
<div class="card-body d-flex flex-column">
    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
    <p class="card-text text-muted small"><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 50)); ?>...</p>
    <div class="mt-auto">
        <p class="h5 text-primary">$<?php echo number_format($product['price'], 2); ?></p>
    </div>
</div>
<div class="card-footer bg-white border-top">
    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm w-100">View Details</a>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
    <?php endif; ?>
    
    <div class="text-center mt-5">
        <a href="products.php" class="btn btn-lg btn-outline-primary">View All Products</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
