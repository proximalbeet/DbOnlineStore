<?php
require "db.php";
session_start();
if (isset($_POST["logout"])) {
    session_destroy();
}
if (isset($_POST["login"])) {
    if (authenticate($_POST["username"], $_POST["password"]) == 1) {
        $_SESSION["username"] = $_POST["username"];
        header("LOCATION:main.php");
        return;
    } else {
        echo '<p style="color:red">incorrect username and password</p>';
    }
}
?>

<html>
<body>
    <form method="post" action="login.php">
        username: <input type="text" name="username"><br>
        password: <input type="password" name="password"><br>
        <input type="submit" name="login" value="login">
    </form>
</body>
</html>