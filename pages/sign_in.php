<?php

require("../config/database_helper.php");

function checkAlreadyRegistered($indexNo)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "SELECT * FROM users WHERE indexNo = :indexNo";
        $params = ["indexNo" => $indexNo];
        $stmt = $helper->query($sql, $params);
        $user = $stmt->fetch();
        $helper->close();

        if ($user) {
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        throw $th;
    }
}

function checkEmailRegistered($email)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "SELECT * FROM users WHERE email = :email";
        $params = ["email" => $email];
        $stmt = $helper->query($sql, $params);
        $user = $stmt->fetch();
        $helper->close();

        if ($user) {
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        throw $th;
    }
}

function register($indexNo, $name, $email, $password)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "INSERT INTO users (indexNo, name, email, password) VALUES (:indexNo, :name, :email, :password)";
        $params = ["indexNo" => $indexNo, "name" => $name, "email" => $email, "password" => $password];
        $helper->query($sql, $params);
        $helper->close();

        session_start();
        $_SESSION["indexNo"] = $indexNo;
        header("Location: index.php");
    } catch (\Throwable $th) {
        $errors["server"] = $th->getMessage();
    }
}


$indexNo = $name = $email = $password = "";
$errors = array("index" => "", "name" => "", "email" => "", "password" => "");
$serverError = "";

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

    if (!array_filter($errors)) {
        if (checkAlreadyRegistered($indexNo)) {
            $serverError = "This Index Number already registered";
        } else {
            $serverError = "";
            if (checkEmailRegistered($email)) {
                $serverError = "This Email already registered";
            } else {
                $serverError = "";
            }
        }
    }

    if (!$serverError) {
        register($indexNo, $name, $email, $password);
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

    <div class="input-error"><?php echo $serverError ?></div>

    <div class="else">If you already have an account <a href="./login.php">Log In</a></div>

</form>

<?php include "../components/footer.php" ?>

</html>