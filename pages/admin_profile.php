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

<form class="user-details-card card" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

    <div class="details-view">
        <img src="../images/admin.png" alt="Avatar" class="avatar">
        <div class="text-view">
            <h2>Admin</h2>
            admin@nsbmevents.com
            <a href="new_event.php">add new event</a>
        </div>
    </div>

    <input class="auth-btn" name="sign-out" type="submit" value="Sign Out">
</form>

<?php include "../components/footer.php" ?>

</html>