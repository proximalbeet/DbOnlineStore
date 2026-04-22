<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$order_id = $_GET["order_id"] ?? null;

// TODO: Task 5 — drill-down view.
// Verify the order belongs to $_SESSION["customer_id"] before showing details.
// SELECT oi.product_id, p.name, oi.quantity, oi.price
//   FROM order_item oi JOIN product p ON p.product_id = oi.product_id
//   WHERE oi.order_id = ?;
// Also show order header (date, status, total) from `order`.
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Order Detail</h2>
    <p>TODO: Task 5 — order_item list for order <?php echo htmlspecialchars($order_id ?? "?"); ?>.</p>
</body>
</html>
