<!DOCTYPE html>
<html lang="en">

<?php $page = "Sign In";
include("../components/header.php"); ?>

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



<!-- PRESENTATION -->


<div class="background-img1">
    <br>
    <div class="padding-h40 row space-between auth-form">
        <div></div>

        <form class="card col padding-h60" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <br>
            <h2 class="primary">Sign In</h2>
            <hr class="width100">
            <br>
            <div class="f-large col align-start gap10">
                <label class="dark" for="indexNo">Index No</label>
                <input class="input" type="text" name="indexNo" id="indexNo" value="<?php echo $indexNo ?>">
                <div class="red"><?php echo $errors["indexNo"] ?>
                </div>
            </div>
            <br>
            <div class="f-large col align-start gap10">
                <label class="dark" for="name">Name</label>
                <input class="input" type="text" name="name" id="name" value="<?php echo $name ?>">
                <div class="red"><?php echo $errors["name"] ?></div>
            </div>

            <br>
            <div class="f-large col align-start gap10">
                <label class="dark" for="email">Email</label>
                <input class="input" type="email" name="email" id="email" value="<?php echo $email ?>">
                <div class="red"><?php echo $errors["email"] ?></div>
            </div>
            <br>
            <div class="f-large col align-start gap10">
                <label class="dark" for="password">Password</label>
                <input class="input" type="password" name="password" id="password" value="<?php echo $password ?>">
                <div class="red"><?php echo $errors["password"] ?></div>
            </div>
            <br>
            <input class="button" type="submit" name="submit" value="Sign In">
            <div class="red"><?php echo $serverError ?></div>
            <hr class="width100">
            <div class="dark">If you already have an account <a class="primary" href="./login.php">Log In</a></div>
            <br>
        </form>
    </div>
    <br>
</div>

<?php include "../components/footer.php" ?>

</html>