<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

// TODO: Task 4 — load cart for $_SESSION["customer_id"] (JOIN cart + cart_item + product).
// Handle POST actions: update quantity, remove item.
// Render: line items with product name, price, quantity input, subtotal, remove button.
// Show grand total; link to checkout.php.
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Your Cart</h2>
    <p>TODO: Task 4 — cart view/update/remove.</p>
    <form method="post" action="checkout.php">
        <input type="submit" value="Checkout">
    </form>
</body>
</html>
