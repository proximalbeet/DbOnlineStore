<?php
session_start();
require __DIR__ . "/../common.php";

// TODO: Task 3 — list categories and products (no login required to browse).
// Query: SELECT category_name, description FROM category ORDER BY category_name;
// If ?category=... provided: SELECT * FROM product WHERE category = :c AND is_discontinued = 0;
// Show name, price, image, stock status (in stock / out of stock based on actual_stock_quantity).
// Add-to-cart button should only render when $_SESSION["customer_id"] is set, and POST to add_to_cart.php.
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Browse</h2>
    <p>TODO: Task 3 — category/product listing.</p>
</body>
</html>
