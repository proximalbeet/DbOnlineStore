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

if (isset($_POST["create"])) {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $image = $_POST["image"];
    $desc = $_POST["product_desc"];
    $actual_qty = $_POST["actual_qty"];
    $advised_qty = $_POST["advised_qty"];
    $is_discontinued = isset($_POST["is_discontinued"]) ? 1 : 0;
    $category = $_POST["category"];

    $stmt = $dbh->prepare("CALL insert_product(?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $image, $desc, $actual_qty, $advised_qty, $is_discontinued, $category]);
    $stmt->closeCursor();

    $message = "Product '$name' created.";
}

$categories = $dbh->query("SELECT category_name FROM category ORDER BY category_name")
    ->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<body>
<?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
<h2>New Product</h2>

<?php if (isset($message)) echo "<p>" . htmlspecialchars($message) . "</p>"; ?>

<form method="post" action="new_product.php">
    Name: <input type="text" name="name" required><br>
    Price: <input type="number" step="0.01" name="price" min="0" required><br>
    Image filename: <input type="text" name="image"><br>
    Description: <br><textarea name="product_desc" rows="3" cols="40"></textarea><br>
    Actual stock: <input type="number" name="actual_qty" min="0" value="0" required><br>
    Advised stock: <input type="number" name="advised_qty" min="0" value="0" required><br>
    Category:
    <select name="category" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat["category_name"]); ?>">
                <?php echo htmlspecialchars($cat["category_name"]); ?>
            </option>
        <?php endforeach; ?>
    </select><br>
    Discontinued: <input type="checkbox" name="is_discontinued" value="1"><br>
    <input type="submit" name="create" value="Create Product">
</form>
</body>
</html>
