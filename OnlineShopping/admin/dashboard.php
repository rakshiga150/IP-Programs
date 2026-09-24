<?php

include "../includes/db.php";

session_start();

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {

    header("Location: ../login.php");
    exit;

}

$productCount =
    $conn->query(
        "SELECT COUNT(*) AS total FROM products"
    )->fetch_assoc()['total'];

$userCount =
    $conn->query(
        "SELECT COUNT(*) AS total FROM users"
    )->fetch_assoc()['total'];

$orderCount =
    $conn->query(
        "SELECT COUNT(*) AS total FROM orders"
    )->fetch_assoc()['total'];

$revenue =
    $conn->query(
        "SELECT COALESCE(SUM(total_amount),0) AS total
         FROM orders"
    )->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - ShopSphere</title>

    <link
        rel="stylesheet"
        href="../css/style.css">

</head>

<body class="admin-body">

<div class="admin-navbar">

    <h2>
        Shop<span>Sphere</span> Admin
    </h2>

    <div>

        <a href="../index.php">
            View Website
        </a>

        <a href="../logout.php">
            Logout
        </a>

    </div>

</div>


<div class="admin-container">

    <div class="admin-heading">

        <div>

            <p class="section-label">
                ADMIN PANEL
            </p>

            <h1>Dashboard</h1>

        </div>

        <a
            href="add_product.php"
            class="primary-btn">
            + Add Product
        </a>

    </div>


    <div class="dashboard-cards">

        <div class="dashboard-card">

            <span>🛍️</span>

            <p>Total Products</p>

            <h2>
                <?= $productCount ?>
            </h2>

        </div>


        <div class="dashboard-card">

            <span>👥</span>

            <p>Registered Users</p>

            <h2>
                <?= $userCount ?>
            </h2>

        </div>


        <div class="dashboard-card">

            <span>📦</span>

            <p>Total Orders</p>

            <h2>
                <?= $orderCount ?>
            </h2>

        </div>


        <div class="dashboard-card">

            <span>💰</span>

            <p>Total Revenue</p>

            <h2>
                ₹<?= number_format($revenue, 2) ?>
            </h2>

        </div>

    </div>


    <div class="admin-links">

        <a href="add_product.php">
            ➕ Add New Product
        </a>

        <a href="manage_products.php">
            📋 Manage Products
        </a>

        <a href="../products.php">
            🛒 View Store
        </a>

    </div>

</div>

</body>
</html>