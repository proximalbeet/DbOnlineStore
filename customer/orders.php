<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

// TODO: Task 5 — list orders for this customer.
// SELECT order_id, order_date, order_status, total_order_price
//   FROM `order` WHERE customer_id = ? ORDER BY order_date DESC;
// Render each row with a link to order_detail.php?order_id=<id>.
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>My Orders</h2>
    <p>TODO: Task 5 — order list.</p>
</body>
</html>
