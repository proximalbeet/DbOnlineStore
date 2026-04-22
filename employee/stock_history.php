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

// TODO: Task 6 — stock history view.
// Base: SELECT * FROM history_record WHERE old_stock IS NOT NULL OR new_stock IS NOT NULL ORDER BY timestamps DESC;
// Rubric may require customer-purchase-driven decrements too. Options:
//   (a) modify checkout proc to CALL log_product_update (preferred, single source of truth), OR
//   (b) UNION a synthetic view of order_item + `order`.
// See CLAUDE.md "Conventions / gotchas" for rationale.
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Stock History</h2>
    <p>TODO: Task 6 — stock history rows.</p>
</body>
</html>
