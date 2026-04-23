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

$employee_id = $_SESSION["employee_id"];
$dbh = connectDB();

if (isset($_POST["change_price"])) {
    $product_id = $_POST["product_id"];
    $new_price = $_POST["new_price"];

    // Look up old price first so we can log the delta.
    $oldStmt = $dbh->prepare("SELECT price FROM product WHERE product_id = ?");
    $oldStmt->execute([$product_id]);
    $old = $oldStmt->fetch(PDO::FETCH_ASSOC);

    if ($old) {
        $old_price = $old["price"];

        $dbh->prepare("UPDATE product SET price = ? WHERE product_id = ?")
            ->execute([$new_price, $product_id]);

        $logStmt = $dbh->prepare(
            "CALL log_product_update(?, 'UPDATE', ?, ?, NULL, NULL, 'price change', ?, NULL, NULL)"
        );
        $logStmt->execute([$product_id, $old_price, $new_price, $employee_id]);
        $logStmt->closeCursor();

        $message = "Product id $product_id price changed from \$$old_price to \$$new_price.";
    } else {
        $message = "Product id $product_id not found.";
    }
}

$products = $dbh->query(
    "SELECT product_id, name, price
     FROM product
     WHERE is_discontinued = 0
     ORDER BY name"
)->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<body>
<?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
<h2>Change Price</h2>

<?php if (isset($message)) echo "<p>" . htmlspecialchars($message) . "</p>"; ?>

<table border="1">
    <tr>
        <th>Product Id</th>
        <th>Name</th>
        <th>Current Price</th>
        <th>New Price</th>
        <th></th>
    </tr>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?php echo $p["product_id"]; ?></td>
            <td><?php echo htmlspecialchars($p["name"]); ?></td>
            <td>$<?php echo $p["price"]; ?></td>
            <td>
                <form method="post" action="change_price.php" style="display:inline">
                    <input type="hidden" name="product_id" value="<?php echo $p["product_id"]; ?>">
                    <input type="number" step="0.01" name="new_price" value="<?php echo $p["price"]; ?>" min="0">
                    <input type="submit" name="change_price" value="Change Price">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
