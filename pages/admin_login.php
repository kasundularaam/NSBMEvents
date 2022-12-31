<!DOCTYPE html>
<html lang="en">


<?php include "../components/header.php" ?>

<?php

$hcEmail = "admin@nsbmevents.com";
$hcPassword = "123456";

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

    if (!array_filter($errors)) {
        if ($email == $hcEmail && $password == $hcPassword) {
            session_start();
            $_SESSION["indexNo"] = "Admin";
            header("Location: index.php");
        } else {
            $errors["password"] = "Wrong password";
        }
    }
}

?>

<!-- PRESENTATION -->

<div class="background-img1">
    <br>
    <div class="row space-between padding-h40 auth-form">
        <div></div>
        <form class="card col padding-h60" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <br>
            <h2 class="primary">Admin Log In</h2>
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
            <hr class="width100">
            <div class="dark">Log In as a <a class="primary" href="./login.php">user</a></div>
            <br>
        </form>
    </div>
    <br>
</div>

<?php include "../components/footer.php" ?>

</html>