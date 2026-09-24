<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ShopSphere - Online Shopping</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header class="navbar">

    <div class="logo">
        <span>Shop</span>Sphere
    </div>

    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Shop</a>
        <a href="products.php?category=Electronics">Electronics</a>
        <a href="products.php?category=Fashion">Fashion</a>
    </nav>

    <div class="nav-actions">

        <a href="cart.php" class="cart-btn">
            🛒 Cart
            <span id="cartCount">0</span>
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>

            <a href="profile.php" class="user-link">
    👤 <?= htmlspecialchars($_SESSION['user_name']) ?>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

        <small class="role-badge admin-badge">
            ADMIN
        </small>

    <?php else: ?>

        <small class="role-badge user-badge">
            USER
        </small>

    <?php endif; ?>

</a>

            <a href="logout.php" class="login-btn">
                Logout
            </a>

        <?php else: ?>

            <a href="login.php" class="login-btn">
                Login
            </a>

        <?php endif; ?>

    </div>

</header>