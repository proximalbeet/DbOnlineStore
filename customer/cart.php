<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$customer_id = $_SESSION["customer_id"];
$dbh = connectDB();

// Handle remove item
if (isset($_POST["remove"])) {
    $product_id = $_POST["product_id"];
    $cartStmt = $dbh->prepare("SELECT cart_id FROM cart WHERE customer_id = ?");
    $cartStmt->execute([$customer_id]);
    $cart = $cartStmt->fetch(PDO::FETCH_ASSOC);
    if ($cart) {
        $dbh->prepare("DELETE FROM cart_item WHERE cart_id = ? AND product_id = ?")
                ->execute([$cart["cart_id"], $product_id]);
        $message = "The item has been removed!";
    }
}

// Handle update quantity
if (isset($_POST["update"])) {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $cartStmt = $dbh->prepare("SELECT cart_id FROM cart WHERE customer_id = ?");
    $cartStmt->execute([$customer_id]);
    $cart = $cartStmt->fetch(PDO::FETCH_ASSOC);
    if ($cart) {
        $dbh->prepare("UPDATE cart_item SET quantity = ? WHERE cart_id = ? AND product_id = ?")
                ->execute([$quantity, $cart["cart_id"], $product_id]);
        $message = "The quantity has been updated!";
    }
}

// Load cart items
$stmt = $dbh->prepare("SELECT ci.product_id, p.name, ci.price, ci.quantity
    FROM cart_item ci
    JOIN cart c ON ci.cart_id = c.cart_id
    JOIN product p ON ci.product_id = p.product_id
    WHERE c.customer_id = ?");
$stmt->execute([$customer_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<body>
<?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
<h2>Welcome <?php echo htmlspecialchars($_SESSION["username"]); ?>!!</h2>

<?php if (isset($message)) echo "<p>" . $message . "</p>"; ?>

<h3>Your Shopping Cart</h3>
<?php if (empty($items)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table border="1">
        <tr>
            <th>Product Id</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th></th>
            <th></th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo $item["product_id"]; ?></td>
                <td><?php echo htmlspecialchars($item["name"]); ?></td>
                <td>$<?php echo $item["price"]; ?></td>
                <td>
                    <form method="post" action="cart.php" style="display:inline">
                        <input type="hidden" name="product_id" value="<?php echo $item["product_id"]; ?>">
                        <input type="number" name="quantity" value="<?php echo $item["quantity"]; ?>" min="1">
                        <input type="submit" name="update" value="Update">
                    </form>
                </td>
                <td>
                    <form method="post" action="cart.php" style="display:inline">
                        <input type="hidden" name="product_id" value="<?php echo $item["product_id"]; ?>">
                        <input type="submit" name="remove" value="Remove">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <form method="post" action="checkout.php">
        <input type="submit" value="Checkout">
    </form>
<?php endif; ?>
</body>
</html>