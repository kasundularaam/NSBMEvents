<?php

$name = $email = $indexNo = $password;
$errors = array("email" => "", "index" => "", "password" => "");

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

    if (empty($_POST["indexNo"])) {
        $errors["indexNo"] = "Index No is required";
    } else {
        $indexNo = $_POST["indexNo"];
        $errors["indexNo"] = "";
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
        <label for="indexNo">Index No</label>
        <input type="text" name="indexNo" id="indexNo" value="<?php echo $indexNo ?>">
        <div class="input-error"><?php echo $errors["indexNo"] ?>
        </div>
    </div>


    <div class="input-field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?php echo $password ?>">
        <div class="input-error"><?php echo $errors["password"] ?>
        </div>
    </div>


    <input class="auth-btn" type="submit" name="submit" value="Sign In">

</form>

<?php include "../components/footer.php" ?>

</html>