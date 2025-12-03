<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

if (!isset($_SESSION['csrf_token'])) {
try {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
} catch (Exception $e) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>G7 Store</title>

<!-- CSS Files will be going here-->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/style.css">
<link rel="icon" href="data:,">

<script src="js/darkmodetoggle.js"></script>
</head>

<body>
<header class="border-bottom bg-body sticky-top">
<nav class="container d-flex justify-content-between align-items-center py-2">
    <a href="index.php" class="fs-4 fw-semibold text-decoration-none d-flex align-items-center gap-2">
        <img src="images/logo.png" alt="G7 Store Logo" class="brand-logo" onerror="this.style.display='none'">
        <span class="brand-gradient">G7 Store</span>
    </a>

    <div class="d-flex gap-4 align-items-center">

        <a href="products.php" class="nav-link">Products</a>
        <a href="cart.php" class="nav-link">Cart</a>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="nav-link">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-link">Login</a>
        <?php endif; ?>

        <!-- THEME DROPDOWN AVAILABLE HERE -->
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                    id="bd-theme"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                🌓 Theme
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <button type="button" class="dropdown-item"
                            data-bs-theme-value="light">
                        ☀️ Light
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item"
                            data-bs-theme-value="dark">
                        🌙 Dark
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item"
                            data-bs-theme-value="auto">
                        🔄 Auto
                    </button>
                </li>
            </ul>
        </div>

    </div>
</nav>
</header>

<main class="container py-4">
