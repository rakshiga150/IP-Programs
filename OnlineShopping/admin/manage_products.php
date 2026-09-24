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


// DELETE PRODUCT

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $conn->prepare(
        "DELETE FROM products WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    header("Location: manage_products.php");

    exit;
}


$result = $conn->query(
    "SELECT * FROM products ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Products</title>

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

        <a href="dashboard.php">
            Dashboard
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
                INVENTORY
            </p>

            <h1>Manage Products</h1>

        </div>

        <a
            href="add_product.php"
            class="primary-btn">
            + Add Product
        </a>

    </div>


    <div class="product-table">

        <div class="table-header">

            <span>ID</span>
            <span>Product</span>
            <span>Category</span>
            <span>Price</span>
            <span>Stock</span>
            <span>Action</span>

        </div>


        <?php while ($product = $result->fetch_assoc()): ?>

            <div class="table-row">

                <span>
                    #<?= $product['id'] ?>
                </span>

                <strong>
                    <?= htmlspecialchars(
                        $product['name']
                    ) ?>
                </strong>

                <span>
                    <?= htmlspecialchars(
                        $product['category']
                    ) ?>
                </span>

                <span>
                    ₹<?= number_format(
                        $product['price'],
                        2
                    ) ?>
                </span>

                <span>
                    <?= $product['stock'] ?>
                </span>

                <a
                    href="manage_products.php?delete=<?= $product['id'] ?>"
                    class="delete-btn"
                    onclick="return confirm('Delete this product?')">
                    Delete
                </a>

            </div>

        <?php endwhile; ?>

    </div>

</div>

</body>
</html>