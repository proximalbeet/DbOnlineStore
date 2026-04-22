<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
    <ul>
        <li><a href="browse.php">Browse products</a></li>
        <li><a href="cart.php">View cart</a></li>
        <li><a href="orders.php">My orders</a></li>
        <li><a href="password_change.php">Change password</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</body>
</html>
