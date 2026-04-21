<?php
session_start();
require "common.php";

$mode = isset($_GET["mode"]) ? $_GET["mode"] : "login";

// Handle login
if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    if ($role === "employee") {
        $user = authenticateEmployee($username, $password);
        if ($user) {
            $_SESSION["employee_id"] = $user["employee_id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = "employee";
            header("Location: emp_main.php");
            exit();
        }
    } else {
        $user = authenticateCustomer($username, $password);
        if ($user) {
            $_SESSION["customer_id"] = $user["customer_id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = "customer";
            header("Location: customer.php");
            exit();
        }
    }
    $error = "Incorrect username or password.";
}

// Handle registration
if (isset($_POST["register"])) {
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];
    $mode = "register";

    if ($password !== $confirm) {
        $reg_error = "Passwords do not match.";
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
            $reg_success = "Account created! You can now log in.";
            $mode = "login";
        } else {
            $reg_error = "Registration failed: " . $result;
        }
    }
}
?>
<html>
<body>
<?php if ($mode === "login"): ?>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="post" action="login.php">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        Role:
        <select name="role">
            <option value="customer">Customer</option>
            <option value="employee">Employee</option>
        </select><br>
        <input type="submit" name="login" value="Login">
    </form>
    <br>
    <a href="login.php?mode=register">New customer? Create an account</a>

<?php else: ?>
    <h2>Create Account</h2>
    <?php if (isset($reg_error)) echo "<p style='color:red'>$reg_error</p>"; ?>
    <?php if (isset($reg_success)) echo "<p style='color:green'>$reg_success</p>"; ?>
    <form method="post" action="login.php?mode=register">
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
<?php endif; ?>
</body>
</html>