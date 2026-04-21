// Customer main page
<?php
session_start();
require "common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION["customer_id"];

// Handle logout
if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle password change
if (isset($_POST["change_password"])) {
    $old = $_POST["old_password"];
    $new = $_POST["new_password"];
    $confirm = $_POST["confirm_password"];
    if ($new !== $confirm) {
        $pw_error = "New passwords do not match.";
    } else {
        $result = changePassword($customer_id, $old, $new);
        if ($result === true) {
            $pw_success = "Password changed successfully.";
        } else {
            $pw_error = "Old password is incorrect.";
        }
    }
}
?>
<html>
<body>
    <h2>Welcome, <?php echo $_SESSION["username"]; ?></h2>
    <form method="post" action="customer.php">
        <input type="submit" name="logout" value="Logout">
    </form>

    <hr>

    <h3>Change Password</h3>
    <?php if (isset($pw_error)) echo "<p style='color:red'>$pw_error</p>"; ?>
    <?php if (isset($pw_success)) echo "<p style='color:green'>$pw_success</p>"; ?>
    <form method="post" action="customer.php">
        Old Password: <input type="password" name="old_password"><br>
        New Password: <input type="password" name="new_password"><br>
        Confirm New Password: <input type="password" name="confirm_password"><br>
        <input type="submit" name="change_password" value="Change Password">
    </form>
</body>
</html>
