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

// TODO: Task 6 — restock form.
// GET: list products with current actual_stock_quantity.
// POST: UPDATE product SET actual_stock_quantity = :new_qty WHERE product_id = :pid;
//       then CALL log_product_update(pid, 'UPDATE', NULL, NULL, old_stock, new_stock, 'restock', employee_id, NULL, NULL).
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Restock</h2>
    <p>TODO: Task 6 — restock + log_product_update.</p>
</body>
</html>
