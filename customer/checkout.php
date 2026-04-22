<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

// TODO: Task 4 — call the checkout stored procedure.
// $dbh = connectDB();
// $stmt = $dbh->prepare("CALL checkout(?, @o, @oos)");
// $stmt->execute([$_SESSION["customer_id"]]);
// $stmt->closeCursor();
// $out = $dbh->query("SELECT @o AS order_id, @oos AS out_of_stock_product")->fetch(PDO::FETCH_ASSOC);
// If $out["out_of_stock_product"] is set: show "cannot complete order — <product> is out of stock".
// Else: show "order #<order_id> placed".
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Checkout</h2>
    <p>TODO: Task 4 — CALL checkout(?, @o, @oos) and display result.</p>
</body>
</html>
