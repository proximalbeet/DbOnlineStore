<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$customer_id = $_SESSION["customer_id"];
$dbh = connectDB();

// Get all orders for this customer
$stmt = $dbh->prepare("SELECT order_id, order_date, order_status, total_order_price 
    FROM `order` 
    WHERE customer_id = ? 
    ORDER BY order_date DESC");
$stmt->execute([$customer_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<body>
<?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
<h2>Welcome <?php echo htmlspecialchars($_SESSION["username"]); ?>!!</h2>

<p>Here are your (id: <?php echo $customer_id; ?>) orders:</p>

<?php if (empty($orders)): ?>
    <p>You have no orders yet.</p>
<?php else: ?>
    <?php foreach ($orders as $i => $order): ?>
        <p><?php echo $i + 1; ?>. Order id: <?php echo $order["order_id"]; ?></p>
        <p>Order time: <?php echo $order["order_date"]; ?></p>
        <p>Total amount: <?php echo $order["total_order_price"]; ?></p>

        <?php
        // Get order items for this order
        $itemStmt = $dbh->prepare("SELECT oi.product_id, p.name, oi.price, oi.quantity
            FROM order_item oi
            JOIN product p ON oi.product_id = p.product_id
            WHERE oi.order_id = ?");
        $itemStmt->execute([$order["order_id"]]);
        $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <table border="1">
            <tr>
                <th>prod_id</th>
                <th>prod_name</th>
                <th>price</th>
                <th>quantity</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo $item["product_id"]; ?></td>
                    <td><?php echo htmlspecialchars($item["name"]); ?></td>
                    <td><?php echo $item["price"]; ?></td>
                    <td><?php echo $item["quantity"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>