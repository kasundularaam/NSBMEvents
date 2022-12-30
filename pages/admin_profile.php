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

<div class="padding-h20">
    <br>
    <form class="card row space-between align-start padding-h20 padding-v20" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

        <div class="row gap20">
            <img src="../images/admin.png" alt="Avatar" class="avatar">
            <div class="col align-start gap10">
                <h2>Admin</h2>
                admin@nsbmevents.com
                <a class="primary f-medium" href="new_event.php">add new event</a>
            </div>
        </div>
        <div class="row">
            <img class="icon-smaller" src="../images/exit.png" alt="">
            <input class="no-border red bg-lighter f-medium pointer bold" name="sign-out" type="submit" value="Sign Out">
        </div>
    </form>
    <br>
</div>


<?php include "../components/footer.php" ?>

</html>