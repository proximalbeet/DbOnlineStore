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

// TODO: Task 6 — price history view.
// SELECT product_id, timestamps, old_price, new_price,
//        ((new_price - old_price) / old_price) * 100 AS pct_change
//   FROM history_record WHERE old_price IS NOT NULL OR new_price IS NOT NULL ORDER BY timestamps DESC;
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Price History</h2>
    <p>TODO: Task 6 — price history rows with % change.</p>
</body>
</html>
