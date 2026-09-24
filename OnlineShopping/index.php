<?php

include "includes/db.php";

$products = [];

$query = "SELECT * FROM products ORDER BY id DESC LIMIT 8";

$result = $conn->query($query);

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

}

include "includes/header.php";

?>

<section class="hero">

    <div class="hero-content">

        <p class="hero-small">
            ✨ NEW COLLECTION 2026
        </p>

        <h1>
            Shop Smart.<br>
            Live <span>Better.</span>
        </h1>

        <p class="hero-description">
            Discover trending products, amazing deals and
            everything you need in one place.
        </p>

        <div class="hero-buttons">

            <a href="products.php" class="primary-btn">
                Explore Products →
            </a>

            <a href="#categories" class="secondary-btn">
                Browse Categories
            </a>

        </div>

    </div>

    <div class="hero-card">

        <div class="floating-card card-one">
            ⚡
            <span>Fast Deals</span>
        </div>

        <div class="shopping-circle">
            🛍️
        </div>

        <div class="floating-card card-two">
            ⭐
            <span>Top Rated</span>
        </div>

    </div>

</section>


<section class="features">

    <div class="feature-box">
        <div>🚚</div>
        <h3>Fast Delivery</h3>
        <p>Quick and reliable delivery.</p>
    </div>

    <div class="feature-box">
        <div>🔒</div>
        <h3>Secure Payment</h3>
        <p>Your information stays protected.</p>
    </div>

    <div class="feature-box">
        <div>↩️</div>
        <h3>Easy Returns</h3>
        <p>Simple and hassle-free returns.</p>
    </div>

    <div class="feature-box">
        <div>💬</div>
        <h3>24/7 Support</h3>
        <p>We're here whenever you need us.</p>
    </div>

</section>


<section class="section" id="categories">

    <div class="section-heading">

        <div>
            <p class="section-label">EXPLORE</p>
            <h2>Shop by Category</h2>
        </div>

        <a href="products.php">
            View All →
        </a>

    </div>

    <div class="category-grid">

        <a href="products.php?category=Electronics"
           class="category-card electronics">

            <div class="category-icon">💻</div>

            <div>
                <h3>Electronics</h3>
                <p>Smart gadgets & devices</p>
            </div>

            <span>→</span>

        </a>


        <a href="products.php?category=Fashion"
           class="category-card fashion">

            <div class="category-icon">👟</div>

            <div>
                <h3>Fashion</h3>
                <p>Style for every day</p>
            </div>

            <span>→</span>

        </a>


        <a href="products.php?category=Home"
           class="category-card home">

            <div class="category-icon">🏠</div>

            <div>
                <h3>Home</h3>
                <p>Make your space better</p>
            </div>

            <span>→</span>

        </a>

    </div>

</section>


<section class="section products-section">

    <div class="section-heading">

        <div>
            <p class="section-label">TRENDING NOW</p>
            <h2>Popular Products</h2>
        </div>

        <a href="products.php">
            View All Products →
        </a>

    </div>


    <div class="product-grid">

        <?php foreach ($products as $product): ?>

            <div class="product-card">

                <div class="product-image">

                    <span class="product-badge">
                        NEW
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
                            ₹<?= number_format($product['price'], 2) ?>
                        </strong>


                        <?php if ($product['stock'] > 0): ?>

                            <?php if (isset($_SESSION['user_id'])): ?>

                                <a
                                    href="cart.php?action=add&id=<?= $product['id'] ?>"
                                    class="add-btn">
                                    +
                                </a>

                            <?php else: ?>

                                <a
                                    href="login.php"
                                    class="add-btn">
                                    +
                                </a>

                            <?php endif; ?>

                        <?php else: ?>

                            <span class="out-stock">
                                Out
                            </span>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</section>


<section class="offer-section">

    <div>

        <p class="section-label">
            SPECIAL OFFER
        </p>

        <h2>
            Upgrade your everyday.
        </h2>

        <p>
            Find products designed to make your daily life
            easier, smarter and more stylish.
        </p>

        <a href="products.php" class="primary-btn">
            Shop Now →
        </a>

    </div>

    <div class="offer-icon">
        🛍️
    </div>

</section>


<?php include "includes/footer.php"; ?>