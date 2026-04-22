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

// TODO: Task 6 (optional) — new product form.
// POST: CALL insert_product(:name, :price, :image, :desc, :actual_qty, :advised_qty, :is_discontinued, :category).
// Category is a string PK — populate a <select> from the category table.
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>New Product</h2>
    <p>TODO: Task 6 — CALL insert_product(...).</p>
</body>
</html>
