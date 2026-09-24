<?php

include "includes/db.php";

$search = $_GET['search'] ?? "";
$category = $_GET['category'] ?? "";

if ($search !== "") {

    $stmt = $conn->prepare(
        "SELECT * FROM products
         WHERE name LIKE ?
         OR description LIKE ?
         ORDER BY id DESC"
    );

    $searchValue = "%" . $search . "%";

    $stmt->bind_param(
        "ss",
        $searchValue,
        $searchValue
    );

} elseif ($category !== "") {

    $stmt = $conn->prepare(
        "SELECT * FROM products
         WHERE category = ?
         ORDER BY id DESC"
    );

    $stmt->bind_param("s", $category);

} else {

    $stmt = $conn->prepare(
        "SELECT * FROM products ORDER BY id DESC"
    );

}

$stmt->execute();

$result = $stmt->get_result();

include "includes/header.php";

?>


<section class="shop-header">

    <div>

        <p class="section-label">
            OUR COLLECTION
        </p>

        <h1>
            Discover Products
        </h1>

        <p>
            Find something you'll love.
        </p>

    </div>


    <form method="GET" class="search-box">

        <input
            type="text"
            name="search"
            placeholder="Search products..."
            value="<?= htmlspecialchars($search) ?>">

        <button type="submit">
            🔍
        </button>

    </form>

</section>


<div class="filter-container">

    <a
        href="products.php"
        class="<?= $category === '' ? 'active-filter' : '' ?>">
        All
    </a>


    <a
        href="products.php?category=Electronics"
        class="<?= $category === 'Electronics' ? 'active-filter' : '' ?>">
        Electronics
    </a>


    <a
        href="products.php?category=Fashion"
        class="<?= $category === 'Fashion' ? 'active-filter' : '' ?>">
        Fashion
    </a>


    <a
        href="products.php?category=Home"
        class="<?= $category === 'Home' ? 'active-filter' : '' ?>">
        Home
    </a>

</div>


<section class="section">

    <div class="product-grid">


        <?php if ($result->num_rows > 0): ?>


            <?php while ($product = $result->fetch_assoc()): ?>


                <div class="product-card">


                    <div class="product-image">


                        <span class="product-badge">

                            <?= $product['stock'] < 10
                                ? 'LOW STOCK'
                                : 'AVAILABLE'
                            ?>

                        </span>


                        <!-- PRODUCT IMAGE -->

                        <?php if (!empty($product['image'])): ?>

                            <img
                                src="<?= htmlspecialchars($product['image']) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                                class="product-img">

                        <?php else: ?>

                            <div class="product-emoji">
                                🛍️
                            </div>

                        <?php endif; ?>


                        <button
                            class="heart-btn"
                            onclick="toggleWishlist(this)">
                            ♡
                        </button>


                    </div>


                    <div class="product-info">


                        <p class="product-category">

                            <?= htmlspecialchars($product['category']) ?>

                        </p>


                        <h3>

                            <?= htmlspecialchars($product['name']) ?>

                        </h3>


                        <p class="product-description">

                            <?= htmlspecialchars($product['description']) ?>

                        </p>


                        <div class="product-bottom">


                            <strong>

                                ₹<?= number_format(
                                    $product['price'],
                                    2
                                ) ?>

                            </strong>


                            <?php if ($product['stock'] > 0): ?>


                                <?php if (isset($_SESSION['user_id'])): ?>


                                    <a
                                        href="cart.php?action=add&id=<?= $product['id'] ?>"
                                        class="add-cart-btn">

                                        Add to Cart

                                    </a>


                                <?php else: ?>


                                    <a
                                        href="login.php"
                                        class="add-cart-btn">

                                        Add to Cart

                                    </a>


                                <?php endif; ?>


                            <?php else: ?>


                                <span class="out-stock">

                                    Out of Stock

                                </span>


                            <?php endif; ?>


                        </div>


                    </div>


                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <div class="empty-state">

                <div>🔎</div>

                <h2>
                    No products found
                </h2>

                <p>
                    Try another search or category.
                </p>

                <a
                    href="products.php"
                    class="primary-btn">

                    View All Products

                </a>

            </div>


        <?php endif; ?>


    </div>

</section>


<?php include "includes/footer.php"; ?>