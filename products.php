<?php 
include 'includes/db.php';
include 'includes/auth.php';
include 'includes/header.php';

$categories = [];
try {
$stmt = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
$categories = [];
}

$selectedCategory = $_GET['category'] ?? '';
$searchQuery = $_GET['search'] ?? '';
$sortBy = $_GET['sort'] ?? 'name';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

$query = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($selectedCategory)) {
$query .= " AND category = ?";
$params[] = $selectedCategory;
}

//Bonus Search filter 
if (!empty($searchQuery)) {
$query .= " AND (name LIKE ? OR description LIKE ?)";
$searchTerm = '%' . $searchQuery . '%';
$params[] = $searchTerm;
$params[] = $searchTerm;
}

//Bonus Price range filter
if (!empty($minPrice) && is_numeric($minPrice)) {
$query .= " AND price >= ?";
$params[] = (float)$minPrice;
}

if (!empty($maxPrice) && is_numeric($maxPrice)) {
$query .= " AND price <= ?";
$params[] = (float)$maxPrice;
}

$validSortOptions = ['name', 'price-asc', 'price-desc', 'newest'];
$sortBy = in_array($sortBy, $validSortOptions) ? $sortBy : 'name';

switch ($sortBy) {
case 'price-asc':
$query .= " ORDER BY price ASC";
break;
case 'price-desc':
$query .= " ORDER BY price DESC";
break;
case 'newest':
$query .= " ORDER BY id DESC";
break;
default:
$query .= " ORDER BY name ASC";
}

try {
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
} catch (Exception $e) {
$products = [];
$error = "Failed to fetch products.";
}
?>

<div class="mb-4">
<div class="container py-4">
<h1 class="mb-4">Browse Products</h1>

<div class="card mb-4 shadow-sm">
<div class="card-body">
<form method="GET" class="row g-3">

<div class="col-12">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search products by name or description..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button class="btn btn-primary" type="submit">Search</button>
        <?php if (!empty($searchQuery)): ?>
            <a href="products.php" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </div>
</div>

<div class="col-md-3">
    <select name="category" class="form-select" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo urlencode($cat); ?>" <?php echo $selectedCategory === $cat ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cat); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="col-md-2">
    <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="<?php echo htmlspecialchars($minPrice); ?>">
</div>

<div class="col-md-2">
    <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="<?php echo htmlspecialchars($maxPrice); ?>">
</div>

<div class="col-md-3">
    <select name="sort" class="form-select" onchange="this.form.submit()">
        <option value="name" <?php echo $sortBy === 'name' ? 'selected' : ''; ?>>Sort by Name (A-Z)</option>
        <option value="price-asc" <?php echo $sortBy === 'price-asc' ? 'selected' : ''; ?>>Price: Low to High</option>
        <option value="price-desc" <?php echo $sortBy === 'price-desc' ? 'selected' : ''; ?>>Price: High to Low</option>
        <option value="newest" <?php echo $sortBy === 'newest' ? 'selected' : ''; ?>>Newest First</option>
    </select>
</div>

<div class="col-12">
    <button type="submit" class="btn btn-primary">Apply Filters</button>
    <a href="products.php" class="btn btn-outline-secondary">Reset All</a>
</div>
</form>
</div>
</div>

<?php if (!empty($searchQuery) || !empty($selectedCategory) || !empty($minPrice) || !empty($maxPrice)): ?>
<div class="alert alert-info mb-4">
<strong>Active Filters:</strong>
<?php if (!empty($searchQuery)): ?>
<span class="badge bg-primary">Search: <?php echo htmlspecialchars($searchQuery); ?></span>
<?php endif; ?>
<?php if (!empty($selectedCategory)): ?>
<span class="badge bg-primary">Category: <?php echo htmlspecialchars($selectedCategory); ?></span>
<?php endif; ?>
<?php if (!empty($minPrice) || !empty($maxPrice)): ?>
<span class="badge bg-primary">Price: $<?php echo htmlspecialchars($minPrice ?: '0'); ?> - $<?php echo htmlspecialchars($maxPrice ?: '∞'); ?></span>
<?php endif; ?>
</div>
<?php endif; ?>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (empty($products)): ?>
<div class="alert alert-info text-center py-5">
<h5>No products found</h5>
<p>Try adjusting your search or filters</p>
<a href="products.php" class="btn btn-primary mt-2">View All Products</a>
</div>
<?php else: ?>
<div class="text-muted mb-3">Found <strong><?php echo count($products); ?></strong> product(s)</div>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
<?php foreach ($products as $product): ?>
<div class="col">
    <div class="card h-100 shadow-sm hover-shadow">
        <?php if (!empty($product['image'])): ?>
            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 200px; object-fit: cover;">
        <?php else: ?>
            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                <span class="text-white">No Image</span>
            </div>
        <?php endif; ?>
        <div class="card-body d-flex flex-column">
            <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($product['category']); ?></span>
            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
            <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 70)) . (strlen($product['description'] ?? '') > 70 ? '...' : ''); ?></p>
            <div class="mt-auto">
                <p class="h5 text-primary fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
                <p class="text-muted small">
                    <?php 
                        if ($product['stock'] > 5) {
                            echo '<span class="text-success">✓ In Stock (' . $product['stock'] . ')</span>';
                        } elseif ($product['stock'] > 0) {
                            echo '<span class="text-warning">⚠ Low Stock (' . $product['stock'] . ')</span>';
                        } else {
                            echo '<span class="text-danger">✕ Out of Stock</span>';
                        }
                    ?>
                </p>
            </div>
        </div>
        <div class="card-footer bg-white border-top">
            <a href="product.php?id=<?php echo urlencode($product['id']); ?>" class="btn btn-primary btn-sm w-100">View Details</a>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</div>

<?php include 'includes/footer.php'; ?>
