<?php

include "includes/db.php";

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;

}

$userId = $_SESSION['user_id'];

$query = "
SELECT
    cart.id AS cart_id,
    cart.quantity,
    products.id AS product_id,
    products.name,
    products.price,
    products.stock
FROM cart
JOIN products
ON cart.product_id = products.id
WHERE cart.user_id = ?
";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $userId);

$stmt->execute();

$result = $stmt->get_result();

$items = [];

$total = 0;

while ($row = $result->fetch_assoc()) {

    $items[] = $row;

    $total +=
        $row['price'] * $row['quantity'];
}

if (count($items) === 0) {

    header("Location: cart.php");
    exit;

}


$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $payment = $_POST['payment'];

    if (
        empty($address) ||
        empty($phone)
    ) {

        $message = "Please fill all delivery details.";

    } else {

        $conn->begin_transaction();

        try {

            $order = $conn->prepare(
                "INSERT INTO orders
                (user_id, total_amount, status)
                VALUES (?, ?, 'Pending')"
            );

            $order->bind_param(
                "id",
                $userId,
                $total
            );

            $order->execute();

            $orderId = $conn->insert_id;


            foreach ($items as $item) {

                $itemStmt = $conn->prepare(
                    "INSERT INTO order_items
                    (order_id, product_id, quantity, price)
                    VALUES (?, ?, ?, ?)"
                );

                $itemStmt->bind_param(
                    "iiid",
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                );

                $itemStmt->execute();


                $stockStmt = $conn->prepare(
                    "UPDATE products
                     SET stock = stock - ?
                     WHERE id = ?"
                );

                $stockStmt->bind_param(
                    "ii",
                    $item['quantity'],
                    $item['product_id']
                );

                $stockStmt->execute();

            }


            $clear = $conn->prepare(
                "DELETE FROM cart WHERE user_id = ?"
            );

            $clear->bind_param("i", $userId);

            $clear->execute();


            $conn->commit();

            header(
                "Location: profile.php?order=success"
            );

            exit;

        } catch (Exception $e) {

            $conn->rollback();

            $message =
                "Order could not be placed. Please try again.";

        }

    }

}

include "includes/header.php";

?>

<section class="checkout-page">

    <div class="section-heading">

        <div>

            <p class="section-label">
                FINAL STEP
            </p>

            <h1>Checkout</h1>

        </div>

    </div>


    <?php if ($message): ?>

        <div class="alert error">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>


    <div class="checkout-layout">


        <div class="checkout-form">

            <h2>Delivery Details</h2>

            <form method="POST">

                <label>Delivery Address</label>

                <textarea
                    name="address"
                    rows="5"
                    placeholder="Enter your complete address"
                    required></textarea>


                <label>Phone Number</label>

                <input
                    type="tel"
                    name="phone"
                    placeholder="Enter your phone number"
                    required>


                <label>Payment Method</label>

                <select name="payment">

                    <option value="COD">
                        Cash on Delivery
                    </option>

                    <option value="UPI">
                        UPI
                    </option>

                    <option value="Card">
                        Credit / Debit Card
                    </option>

                </select>


                <button
                    type="submit"
                    class="auth-btn">
                    Place Order →
                </button>

            </form>

        </div>


        <div class="checkout-summary">

            <h2>Your Order</h2>

            <?php foreach ($items as $item): ?>

                <div class="checkout-item">

                    <span>
                        <?= htmlspecialchars($item['name']) ?>
                        × <?= $item['quantity'] ?>
                    </span>

                    <strong>
                        ₹<?= number_format(
                            $item['price'] * $item['quantity'],
                            2
                        ) ?>
                    </strong>

                </div>

            <?php endforeach; ?>

            <hr>

            <div class="summary-total">

                <span>Total</span>

                <strong>
                    ₹<?= number_format($total, 2) ?>
                </strong>

            </div>

        </div>

    </div>

</section>


<?php include "includes/footer.php"; ?>