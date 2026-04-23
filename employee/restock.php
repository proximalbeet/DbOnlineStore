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

if (isset($_POST["restock"])) {
    $product_id = $_POST["product_id"];
    $new_qty = $_POST["new_qty"];

    // Look up old stock first so we can log the delta.
    $oldStmt = $dbh->prepare("SELECT actual_stock_quantity FROM product WHERE product_id = ?");
    $oldStmt->execute([$product_id]);
    $old = $oldStmt->fetch(PDO::FETCH_ASSOC);

    if ($old) {
        $old_stock = $old["actual_stock_quantity"];

        $dbh->prepare("UPDATE product SET actual_stock_quantity = ? WHERE product_id = ?")
            ->execute([$new_qty, $product_id]);

        $logStmt = $dbh->prepare(
            "CALL log_product_update(?, 'UPDATE', NULL, NULL, ?, ?, 'restock', ?, NULL, NULL)"
        );
        $logStmt->execute([$product_id, $old_stock, $new_qty, $employee_id]);
        $logStmt->closeCursor();

        $message = "Product id $product_id restocked from $old_stock to $new_qty.";
    } else {
        $message = "Product id $product_id not found.";
    }
}

$products = $dbh->query(
    "SELECT product_id, name, actual_stock_quantity, advised_stock_quantity
     FROM product
     WHERE is_discontinued = 0
     ORDER BY name"
)->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<body>
<?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
<h2>Restock</h2>

<?php if (isset($message)) echo "<p>" . htmlspecialchars($message) . "</p>"; ?>

<table border="1">
    <tr>
        <th>Product Id</th>
        <th>Name</th>
        <th>Current Stock</th>
        <th>Advised Stock</th>
        <th>New Stock</th>
        <th></th>
    </tr>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?php echo $p["product_id"]; ?></td>
            <td><?php echo htmlspecialchars($p["name"]); ?></td>
            <td><?php echo $p["actual_stock_quantity"]; ?></td>
            <td><?php echo $p["advised_stock_quantity"]; ?></td>
            <td>
                <form method="post" action="restock.php" style="display:inline">
                    <input type="hidden" name="product_id" value="<?php echo $p["product_id"]; ?>">
                    <input type="number" name="new_qty" value="<?php echo $p["actual_stock_quantity"]; ?>" min="0">
                    <input type="submit" name="restock" value="Restock">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
