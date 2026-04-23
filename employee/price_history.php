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

$dbh = connectDB();

$rows = $dbh->query(
    "SELECT h.product_id, p.name, h.timestamps, h.old_price, h.new_price,
            CASE WHEN h.old_price > 0
                 THEN ((h.new_price - h.old_price) / h.old_price) * 100
                 ELSE NULL
            END AS pct_change,
            h.details, h.employee_id
     FROM history_record h
     JOIN product p ON h.product_id = p.product_id
     WHERE h.old_price IS NOT NULL OR h.new_price IS NOT NULL
     ORDER BY h.timestamps DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<body>
<?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
<h2>Price History</h2>

<?php if (empty($rows)): ?>
    <p>No price history yet.</p>
<?php else: ?>
    <table border="1">
        <tr>
            <th>Time</th>
            <th>Product Id</th>
            <th>Name</th>
            <th>Old Price</th>
            <th>New Price</th>
            <th>% Change</th>
            <th>Details</th>
            <th>Employee Id</th>
        </tr>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?php echo $r["timestamps"]; ?></td>
                <td><?php echo $r["product_id"]; ?></td>
                <td><?php echo htmlspecialchars($r["name"]); ?></td>
                <td>$<?php echo $r["old_price"]; ?></td>
                <td>$<?php echo $r["new_price"]; ?></td>
                <td><?php echo $r["pct_change"] !== null ? number_format($r["pct_change"], 2) . "%" : "-"; ?></td>
                <td><?php echo htmlspecialchars($r["details"]); ?></td>
                <td><?php echo $r["employee_id"]; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>
