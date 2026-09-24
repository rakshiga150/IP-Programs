<?php

include "includes/db.php";

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;

}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT * FROM users WHERE id = ?"
);

$stmt->bind_param("i", $userId);

$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();


$orderStmt = $conn->prepare(
    "SELECT * FROM orders
     WHERE user_id = ?
     ORDER BY order_date DESC"
);

$orderStmt->bind_param("i", $userId);

$orderStmt->execute();

$orders = $orderStmt->get_result();

include "includes/header.php";

?>

<section class="profile-page">

    <div class="profile-header">

        <div class="profile-avatar">
            <?= strtoupper(substr($user['name'], 0, 1)) ?>
        </div>

        <div>

            <p class="section-label">
                MY ACCOUNT
            </p>

            <h1>
                Hello, <?= htmlspecialchars($user['name']) ?>
            </h1>

            <p>
                <?= htmlspecialchars($user['email']) ?>
            </p>

        </div>

    </div>


    <?php if (isset($_GET['order'])): ?>

        <div class="success-box">

            🎉 Order placed successfully!

            <span>
                Thank you for shopping with ShopSphere.
            </span>

        </div>

    <?php endif; ?>


    <div class="orders-section">

        <div class="section-heading">

            <div>

                <p class="section-label">
                    PURCHASE HISTORY
                </p>

                <h2>My Orders</h2>

            </div>

        </div>


        <?php if ($orders->num_rows > 0): ?>

            <div class="order-table">

                <div class="order-head">

                    <span>Order ID</span>
                    <span>Date</span>
                    <span>Total</span>
                    <span>Status</span>

                </div>


                <?php while ($order = $orders->fetch_assoc()): ?>

                    <div class="order-row">

                        <span>
                            #ORD<?= $order['id'] ?>
                        </span>

                        <span>
                            <?= date(
                                "d M Y",
                                strtotime($order['order_date'])
                            ) ?>
                        </span>

                        <span>
                            ₹<?= number_format(
                                $order['total_amount'],
                                2
                            ) ?>
                        </span>

                        <span class="status">
                            <?= htmlspecialchars(
                                $order['status']
                            ) ?>
                        </span>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">

                <div>📦</div>

                <h2>No orders yet</h2>

                <p>
                    Your completed orders will appear here.
                </p>

                <a href="products.php" class="primary-btn">
                    Start Shopping
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>


<?php include "includes/footer.php"; ?>