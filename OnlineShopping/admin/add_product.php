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

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category = $_POST['category'];
    $stock = intval($_POST['stock']);

    $stmt = $conn->prepare(
        "INSERT INTO products
        (name, description, price, category, image, stock)
        VALUES (?, ?, ?, ?, '', ?)"
    );

    $stmt->bind_param(
        "ssdsi",
        $name,
        $description,
        $price,
        $category,
        $stock
    );

    if ($stmt->execute()) {

        $message = "Product added successfully!";

    } else {

        $message = "Unable to add product.";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Product</title>

    <link
        rel="stylesheet"
        href="../css/style.css">

</head>

<body class="admin-body">

<div class="admin-navbar">

    <h2>Shop<span>Sphere</span> Admin</h2>

    <div>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="../logout.php">
            Logout
        </a>

    </div>

</div>


<div class="admin-form-container">

    <div class="auth-card">

        <h1>Add Product</h1>

        <?php if ($message): ?>

            <div class="alert success">
                <?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>


        <form method="POST">

            <label>Product Name</label>

            <input
                type="text"
                name="name"
                required>


            <label>Description</label>

            <textarea
                name="description"
                rows="4"
                required></textarea>


            <label>Price</label>

            <input
                type="number"
                step="0.01"
                name="price"
                required>


            <label>Category</label>

            <select name="category">

                <option value="Electronics">
                    Electronics
                </option>

                <option value="Fashion">
                    Fashion
                </option>

                <option value="Home">
                    Home
                </option>

            </select>


            <label>Stock</label>

            <input
                type="number"
                name="stock"
                min="0"
                required>


            <button
                type="submit"
                class="auth-btn">
                Add Product
            </button>

        </form>

    </div>

</div>

</body>
</html>