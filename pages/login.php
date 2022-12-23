<?php

$email = $password;
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

    if (array_filter($errors)) {
    } else {

        include("../config/db_connect.php");

        try {
            $sql = "INSERT INTO user (name, email, indexNo, password) VALUES (:name, :email, :indexNo, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["name" => $name, "email" => $email, "indexNo" => $indexNo, "password" => $password]);
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

    <h2>Log In</h2>

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

    <input class="auth-btn" type="submit" name="submit" value="Log In">

    <div class="else">If you don't have an account <a href="./sign_in.php">Sign In</a></div>

</form>

<?php include "../components/footer.php" ?>

</html>