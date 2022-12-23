<?php

$indexNo = $name = $email = $password;
$errors = array("index" => "", "name" => "", "email" => "", "password" => "");

if (isset($_POST["submit"])) {

    if (empty($_POST["indexNo"])) {
        $errors["indexNo"] = "Index No is required";
    } else {
        $indexNo = $_POST["indexNo"];
        $errors["indexNo"] = "";
    }

    if (empty($_POST["email"])) {
        $errors["email"] = "Email is required";
    } else {
        $email = $_POST["email"];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Email is invalid";
        } else {
            $errors["email"] = "";
        }
    }

    if (empty($_POST["name"])) {
        $errors["name"] = "Name is required";
    } else {
        $name = $_POST["name"];
        $errors["name"] = "";
    }

    if (empty($_POST["password"])) {
        $errors["password"] = "Password is required";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 6) {
            $errors["password"] = "Password must be at least 6 characters";
        } else {
            $errors["password"] = "";
        }
    }

    if (array_filter($errors)) {
    } else {

        include("../config/db_connect.php");

        try {
            $sql = "INSERT INTO users (indexNo, name, email, password) VALUES (:indexNo, :name, :email, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["indexNo" => $indexNo, "name" => $name, "email" => $email, "password" => $password]);
            session_start();
            $_SESSION["indexNo"] = $indexNo;
            header("Location: index.php");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage;
        }
    }
}


?>




<!DOCTYPE html>
<html lang="en">

<?php $page = "Sign In";
include("../components/header.php"); ?>


<form class="auth-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

    <h2>Sign In</h2>

    <div class="input-field">
        <label for="indexNo">Index No</label>
        <input type="text" name="indexNo" id="indexNo" value="<?php echo $indexNo ?>">
        <div class="input-error"><?php echo $errors["indexNo"] ?>
        </div>
    </div>

    <div class="input-field">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?php echo $name ?>">
        <div class="input-error"><?php echo $errors["name"] ?></div>
    </div>


    <div class="input-field">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo $email ?>">
        <div class="input-error"><?php echo $errors["email"] ?></div>
    </div>

    <div class="input-field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?php echo $password ?>">
        <div class="input-error"><?php echo $errors["password"] ?>
        </div>
    </div>


    <input class="auth-btn" type="submit" name="submit" value="Sign In">

    <div class="else">If you already have an account <a href="./login.php">Log In</a></div>

</form>

<?php include "../components/footer.php" ?>

</html>