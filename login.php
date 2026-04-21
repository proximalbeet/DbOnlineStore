<?php
session_start();
require "common.php";

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
            header("Location: employee.php");
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
?>
<html>
<body>
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
<a href="registration.php">New customer? Create an account</a>
</body>
</html>