<?php

include "includes/db.php";

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;

}

$userId = $_SESSION['user_id'];


// ADD PRODUCT
if (isset($_GET['action']) && $_GET['action'] === 'add') {

    $productId = intval($_GET['id']);

    $check = $conn->prepare(
        "SELECT id FROM cart
         WHERE user_id = ? AND product_id = ?"
    );

    $check->bind_param(
        "ii",
        $userId,
        $productId
    );

    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $update = $conn->prepare(
            "UPDATE cart
             SET quantity = quantity + 1
             WHERE user_id = ? AND product_id = ?"
        );

        $update->bind_param(
            "ii",
            $userId,
            $productId
        );

        $update->execute();

    } else {

        $insert = $conn->prepare(
            "INSERT INTO cart
             (user_id, product_id, quantity)
             VALUES (?, ?, 1)"
        );

        $insert->bind_param(
            "ii",
            $userId,
            $productId
        );

        $insert->execute();

    }

    header("Location: cart.php");
    exit;
}


// REMOVE PRODUCT
if (isset($_GET['remove'])) {

    $cartId = intval($_GET['remove']);

    $delete = $conn->prepare(
        "DELETE FROM cart
         WHERE id = ? AND user_id = ?"
    );

    $delete->bind_param(
        "ii",
        $cartId,
        $userId
    );

    $delete->execute();

    header("Location: cart.php");
    exit;
}


// UPDATE QUANTITY
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['cart_id'], $_POST['quantity'])) {

        $cartId = intval($_POST['cart_id']);

        $quantity = intval($_POST['quantity']);

        if ($quantity < 1) {
            $quantity = 1;
        }

        $update = $conn->prepare(
            "UPDATE cart
             SET quantity = ?
             WHERE id = ? AND user_id = ?"
        );

        $update->bind_param(
            "iii",
            $quantity,
            $cartId,
            $userId
        );

        $update->execute();

    }

    header("Location: cart.php");
    exit;
}


$query = "
SELECT
    cart.id AS cart_id,
    cart.quantity,
    products.*
FROM cart
JOIN products
ON cart.product_id = products.id
WHERE cart.user_id = ?
";

$stmt = $conn->prepare($query);

$stmt->bind_param(
    "i",
    $userId
);

$stmt->execute();

$result = $stmt->get_result();

$total = 0;

include "includes/header.php";

?>

<section class="cart-page">

    <div class="section-heading">

        <div>

            <p class="section-label">
                YOUR SHOPPING BAG
            </p>

            <h1>Shopping Cart</h1>

        </div>

        <a href="products.php">
            ← Continue Shopping
        </a>

    </div>


    <?php if ($result->num_rows > 0): ?>

        <div class="cart-layout">

            <div class="cart-items">

                <?php while ($item = $result->fetch_assoc()): ?>

                    <?php

                    $subtotal =
                        $item['price'] * $item['quantity'];

                    $total += $subtotal;

                    ?>

                    <div class="cart-item">

                        <div class="cart-product-icon">

                            <?php

                            if ($item['category'] === 'Electronics') {
                                echo "🎧";
                            } elseif ($item['category'] === 'Fashion') {
                                echo "👕";
                            } else {
                                echo "🏠";
                            }

                            ?>

                        </div>


                        <div class="cart-product-info">

                            <p>
                                <?= htmlspecialchars($item['category']) ?>
                            </p>

                            <h3>
                                <?= htmlspecialchars($item['name']) ?>
                            </h3>

                            <strong>
                                ₹<?= number_format($item['price'], 2) ?>
                            </strong>

                        </div>


                        <form method="POST" class="quantity-form">

                            <input
                                type="hidden"
                                name="cart_id"
                                value="<?= $item['cart_id'] ?>">

                            <input
                                type="number"
                                name="quantity"
                                value="<?= $item['quantity'] ?>"
                                min="1"
                                max="<?= $item['stock'] ?>">

                            <button type="submit">
                                Update
                            </button>

                        </form>


                        <div class="cart-subtotal">

                            <strong>
                                ₹<?= number_format($subtotal, 2) ?>
                            </strong>

                            <a
                                href="cart.php?remove=<?= $item['cart_id'] ?>"
                                class="remove-btn">
                                Remove
                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>


            <div class="cart-summary">

                <h2>Order Summary</h2>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>
                        ₹<?= number_format($total, 2) ?>
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Delivery</span>
                    <strong>FREE</strong>
                </div>

                <hr>

                <div class="summary-total">
                    <span>Total</span>
                    <strong>
                        ₹<?= number_format($total, 2) ?>
                    </strong>
                </div>

                <a
                    href="checkout.php"
                    class="checkout-btn">
                    Proceed to Checkout →
                </a>

            </div>

        </div>

    <?php else: ?>

        <div class="empty-state">

            <div>🛒</div>

            <h2>Your cart is empty</h2>

            <p>
                Looks like you haven't added anything yet.
            </p>

            <a href="products.php" class="primary-btn">
                Start Shopping
            </a>

        </div>

    <?php endif; ?>

</section>


<?php include "includes/footer.php"; ?>