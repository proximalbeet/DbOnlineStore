// Shared helper functions for customer and employees
<?php
require "db.php";

function authenticateCustomer($username, $password) {
    try {
        $dbh = connectDB();
        $stmt = $dbh->prepare("SELECT * FROM customer WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($password, $row['hashed_password'])) {
            return $row;
        }
        return false;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

function authenticateEmployee($username, $password) {
    try {
        $dbh = connectDB();
        $stmt = $dbh->prepare("SELECT * FROM employee WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($password, $row['hashed_password'])) {
            return $row;
        }
        return false;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

function registerCustomer($username, $firstName, $lastName, $email, $password, $address) {
    try {
        $dbh = connectDB();
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $dbh->prepare("INSERT INTO customer 
            (username, first_name, last_name, email, hashed_password, shipping_address)
            VALUES (:username, :first, :last, :email, :pass, :addr)");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":first", $firstName);
        $stmt->bindParam(":last", $lastName);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":pass", $hashed);
        $stmt->bindParam(":addr", $address);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
?>
