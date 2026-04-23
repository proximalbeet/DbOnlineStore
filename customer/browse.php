<?php
session_start();
require __DIR__ . "/../common.php";

$dbh = connectDB();

// Get all categories for dropdown
$catStmt = $dbh->query("SELECT category_name FROM category ORDER BY category_name");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

// Get products if a category was selected
$products = [];
if (isset($_POST["search"]) && !empty($_POST["category"])) {
    $selectedCategory = $_POST["category"];
    $prodStmt = $dbh->prepare("SELECT * FROM product WHERE category = :category AND is_discontinued = 0");
    $prodStmt->bindParam(":category", $selectedCategory);
    $prodStmt->execute();
    $products = $prodStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle add to cart
if (isset($_POST["add_to_cart"])) {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];
    $customer_id = $_SESSION["customer_id"];

    // Create cart if doesn't exist
    $dbh->prepare("INSERT IGNORE INTO cart (customer_id) VALUES (?)")->execute([$customer_id]);

    // Get cart_id
    $cartStmt = $dbh->prepare("SELECT cart_id FROM cart WHERE customer_id = ?");
    $cartStmt->execute([$customer_id]);
    $cart = $cartStmt->fetch(PDO::FETCH_ASSOC);
    $cart_id = $cart["cart_id"];

    // Add item or update quantity
    $dbh->prepare("INSERT INTO cart_item (cart_id, product_id, quantity, price) 
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)")
            ->execute([$cart_id, $product_id, $quantity, $price]);

    $cart_success = "Added product id $product_id to cart successfully!";
}
?>
<html>
<body>
<?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>

<?php if (isset($_SESSION["customer_id"])): ?>
    <h2>Welcome <?php echo htmlspecialchars($_SESSION["username"]); ?>!!</h2>
<?php endif; ?>

<?php if (isset($cart_success)) echo "<p>" . $cart_success . "</p>"; ?>

<form method="post" action="browse.php">
    <select name="category">
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat["category_name"]); ?>"
                    <?php if (isset($selectedCategory) && $selectedCategory == $cat["category_name"]) echo "selected"; ?>>
                <?php echo htmlspecialchars($cat["category_name"]); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="search" value="Search">
</form>

<?php foreach ($products as $product): ?>
    <h3><?php echo htmlspecialchars($product["name"]); ?></h3>
    <?php if ($product["image"]): ?>
        <img src="<?php echo '../images/' . htmlspecialchars($product['image']); ?>" width="200"><br>
    <?php endif; ?>
    <p>Price: <?php echo $product["price"]; ?></p>
    <p>Stock: <?php echo $product["actual_stock_quantity"] > 0 ? "In Stock" : "Out of Stock"; ?></p>
    <?php if (isset($_SESSION["customer_id"])): ?>
        <form method="post" action="browse.php">
            <input type="hidden" name="product_id" value="<?php echo $product["product_id"]; ?>">
            <input type="hidden" name="price" value="<?php echo $product["price"]; ?>">
            <?php if (isset($selectedCategory)) { ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($selectedCategory); ?>">
            <?php } ?>
            Quantity: <input type="number" name="quantity" value="1" min="1"><br>
            <input type="submit" name="add_to_cart" value="Add to Cart">
        </form>
    <?php endif; ?>
<?php endforeach; ?>

</body>
</html>