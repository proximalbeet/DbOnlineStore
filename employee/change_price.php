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

// TODO: Task 6 — change-price form.
// POST: UPDATE product SET price = :new_price WHERE product_id = :pid;
//       then CALL log_product_update(pid, 'UPDATE', old_price, new_price, NULL, NULL, 'price change', employee_id, NULL, NULL).
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Change Price</h2>
    <p>TODO: Task 6 — change price + log_product_update.</p>
</body>
</html>
