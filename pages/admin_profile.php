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

<div class="container">
    <br>
    <form class="card row space-between padding-h40 padding-v20" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

        <div class="row gap20">
            <img src="../images/admin.png" alt="Avatar" class="avatar">
            <div class="col align-start gap10">
                <h2>Admin</h2>
                admin@nsbmevents.com
                <a class="primary" href="new_event.php">add new event</a>
            </div>
        </div>
        <input class="button" name="sign-out" type="submit" value="Sign Out">
    </form>
    <br>
</div>


<?php include "../components/footer.php" ?>

</html>