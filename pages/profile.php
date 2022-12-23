<?php
session_start();
if (isset($_POST["sign-out"])) {
    unset($_SESSION["indexNo"]);
    header("Location: index.php");
}

?>





<!DOCTYPE html>
<html lang="en">

<?php $page = "Profile";
include("../components/header.php"); ?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <div class="sign-out">
        <input class="auth-btn" name="sign-out" type="submit" value="Sign Out">
    </div>
</form>

<?php include "../components/footer.php" ?>

</html>