<?php
session_start();

if (isset($_POST["confirm_logout"])) {
    $_SESSION = [];
    session_destroy();
    header("Location: ../customer/browse.php");
    exit();
}
?>
<html>
<body>
<p>You are currently logged in. Would you like to logout?</p>
<form method="post" action="logout.php">
    <input type="submit" name="confirm_logout" value="Logout">
</form>
</body>
</html>