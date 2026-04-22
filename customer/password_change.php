<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$customer_id = $_SESSION["customer_id"];

if (isset($_POST["change_password"])) {
    $old = $_POST["old_password"];
    $new = $_POST["new_password"];
    $confirm = $_POST["confirm_password"];
    if ($new !== $confirm) {
        $error = "New passwords do not match.";
    } else {
        $result = changePassword($customer_id, $old, $new);
        if ($result === true) {
            $success = "Password changed successfully.";
        } else {
            $error = "Old password is incorrect.";
        }
    }
}
?>
<html>
<body>
    <?php $nav_base = "../"; require __DIR__ . "/../includes/nav.php"; ?>
    <h2>Change Password</h2>
    <?php if (isset($error)) echo "<p style='color:red'>" . htmlspecialchars($error) . "</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:green'>" . htmlspecialchars($success) . "</p>"; ?>
    <form method="post" action="password_change.php">
        Old Password: <input type="password" name="old_password"><br>
        New Password: <input type="password" name="new_password"><br>
        Confirm New Password: <input type="password" name="confirm_password"><br>
        <input type="submit" name="change_password" value="Change Password">
    </form>
</body>
</html>
