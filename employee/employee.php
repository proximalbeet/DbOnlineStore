<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!empty($_SESSION["password_reset_required"])) {
    header("Location: password_change.php");
    exit();
}
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
    <ul>
        <li><a href="restock.php">Restock product</a></li>
        <li><a href="change_price.php">Change price</a></li>
        <li><a href="stock_history.php">Stock history</a></li>
        <li><a href="price_history.php">Price history</a></li>
        <li><a href="new_product.php">New product</a></li>
        <li><a href="password_change.php">Change password</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</body>
</html>
