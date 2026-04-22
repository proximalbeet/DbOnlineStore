<?php
session_start();
require __DIR__ . "/../common.php";

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$employee_id = $_SESSION["employee_id"];
$forced = !empty($_SESSION["password_reset_required"]);

if (isset($_POST["change_password"])) {
    $old = $_POST["old_password"];
    $new = $_POST["new_password"];
    $confirm = $_POST["confirm_password"];
    if ($new !== $confirm) {
        $error = "New passwords do not match.";
    } else {
        $result = changeEmployeePassword($employee_id, $old, $new);
        if ($result === true) {
            $_SESSION["password_reset_required"] = 0;
            header("Location: employee.php");
            exit();
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
    <?php if ($forced): ?>
        <p style="color:orange">You must change your password before continuing.</p>
    <?php endif; ?>
    <?php if (isset($error)) echo "<p style='color:red'>" . htmlspecialchars($error) . "</p>"; ?>
    <form method="post" action="password_change.php">
        Old Password: <input type="password" name="old_password"><br>
        New Password: <input type="password" name="new_password"><br>
        Confirm New Password: <input type="password" name="confirm_password"><br>
        <input type="submit" name="change_password" value="Change Password">
    </form>
</body>
</html>
