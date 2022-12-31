<!DOCTYPE html>
<html lang="en">

<?php $page = "Log In";
include("../components/header.php"); ?>

<?php

require("../config/database_helper.php");

function login($email, $password)
{
    $helper = new DatabaseHelper();

    $helper->connect();

    $sql = "SELECT * FROM users WHERE email = :email";
    $params = ["email" => $email];
    $stmt = $helper->query($sql, $params);
    $user = $stmt->fetch();
    $helper->close();
    if ($user["password"] == $password) {
        session_start();
        $_SESSION["indexNo"] = $user["indexNo"];
        header("Location: index.php");
    } else {
        $errors["password"] = "Wrong password";
    }
}

$email = $password = "";
$errors = array("email" => "", "password" => "");

if (isset($_POST["submit"])) {

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
        login($email, $password);
    }
}


?>


<!-- PRESENTATION -->


<div class=" background-img1">
    <br>
    <div class="padding-h40 row space-between auth-form">
        <div></div>
        <form class="card col padding-h60" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <br>
            <h2 class="primary">Log In</h2>
            <hr class="width100">
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
                <div class="red"><?php echo $errors["password"] ?>
                </div>
            </div>
            <br>
            <input class="button" type="submit" name="submit" value="Log In">
            <br>
            <div class="dark">If you don't have an account <a class="primary" href="./sign_in.php">Sign In</a></div>
            <hr class="width100">
            <div class="dark">Log In as an <a class="primary" href="./admin_login.php">admin</a></div>
            <br>
        </form>
    </div>
    <br>
</div>

<?php include "../components/footer.php" ?>

</html>