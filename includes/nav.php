<?php
$role = $_SESSION["role"] ?? null;
$base = $nav_base ?? "";
?>
<nav>
<?php if ($role === "customer"): ?>
    <a href="<?php echo $base; ?>index.php">Home</a>
    | <a href="<?php echo $base; ?>customer/browse.php">Browse</a>
    | <a href="<?php echo $base; ?>customer/cart.php">Cart</a>
    | <a href="<?php echo $base; ?>customer/orders.php">Orders</a>
    | <a href="<?php echo $base; ?>customer/customer.php">Account</a>
    | <a href="<?php echo $base; ?>auth/logout.php">Logout</a>
<?php elseif ($role === "employee"): ?>
    <a href="<?php echo $base; ?>employee/employee.php">Dashboard</a>
    | <a href="<?php echo $base; ?>employee/restock.php">Restock</a>
    | <a href="<?php echo $base; ?>employee/change_price.php">Prices</a>
    | <a href="<?php echo $base; ?>employee/stock_history.php">Stock history</a>
    | <a href="<?php echo $base; ?>employee/price_history.php">Price history</a>
    | <a href="<?php echo $base; ?>auth/logout.php">Logout</a>
<?php else: ?>
    <a href="<?php echo $base; ?>index.php">Home</a>
    | <a href="<?php echo $base; ?>auth/login.php">Login</a>
    | <a href="<?php echo $base; ?>auth/registration.php">Register</a>
<?php endif; ?>
</nav>
<hr>
