<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$customer_id = $_SESSION["customer_id"];
$dbh = connectDB();

$stmt = $dbh->prepare("CALL checkout(?, @order_id, @out_of_stock)");
$stmt->execute([$customer_id]);
$stmt->closeCursor();

$result = $dbh->query("SELECT @order_id AS order_id, @out_of_stock AS out_of_stock")->fetch(PDO::FETCH_ASSOC);

if ($result["out_of_stock"] !== null) {
    // Get stock info for that product
    $stockStmt = $dbh->prepare("SELECT actual_stock_quantity FROM product WHERE product_id = ?");
    $stockStmt->execute([$result["out_of_stock"]]);
    $stock = $stockStmt->fetch(PDO::FETCH_ASSOC);
    $message = "There are only " . $stock["actual_stock_quantity"] . " left for product id " . $result["out_of_stock"] . ". Please update your cart.";
} else {
    $message = "Order placed successfully! Your order number is " . $result["order_id"] . ".";
}
?>
<html>
<body>
<?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
<h2>Welcome <?php echo htmlspecialchars($_SESSION["username"]); ?>!!</h2>
<p><?php echo $message; ?></p>
<a href="cart.php">Back to Cart</a> | <a href="browse.php">Continue Shopping</a>
</body>
</html>