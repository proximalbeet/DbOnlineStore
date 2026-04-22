<?php
session_start();
require __DIR__ . "/../common.php";

if (isset($_POST["register"])) {
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $result = registerCustomer(
            $_POST["username"],
            $_POST["first_name"],
            $_POST["last_name"],
            $_POST["email"],
            $password,
            $_POST["shipping_address"]
        );
        if ($result === true) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed: " . $result;
        }
    }
}
?>
<html>
<body>
    <h2>Create Account</h2>
    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="post" action="registration.php">
        Username: <input type="text" name="username"><br>
        First Name: <input type="text" name="first_name"><br>
        Last Name: <input type="text" name="last_name"><br>
        Email: <input type="text" name="email"><br>
        Shipping Address: <input type="text" name="shipping_address"><br>
        Password: <input type="password" name="password"><br>
        Confirm Password: <input type="password" name="confirm_password"><br>
        <input type="submit" name="register" value="Register">
    </form>
    <br>
    <a href="login.php">Already have an account? Login here</a>
</body>
</html>