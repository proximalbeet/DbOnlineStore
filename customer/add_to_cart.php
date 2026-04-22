<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

// TODO: Task 4 — POST handler.
// 1) INSERT IGNORE INTO cart (customer_id) VALUES (?) to lazy-create the cart.
// 2) SELECT cart_id FROM cart WHERE customer_id = ?.
// 3) Look up current product price from product table.
// 4) INSERT INTO cart_item (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)
//    ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity).
// 5) Redirect back to browse.php or cart.php.
header("Location: cart.php");
exit();
