<?php

require("../config/database_helper.php");


function signOut()
{
    session_start();
    unset($_SESSION["indexNo"]);
    header("Location: index.php");
}

function getUser()
{
    session_start();
    $indexNo = $_SESSION["indexNo"];

    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "SELECT * FROM users WHERE indexNo = :indexNo";
        $params = ["indexNo" => $indexNo];
        $stmt = $helper->query($sql, $params);
        $user = $stmt->fetch();
        $helper->close();

        return $user;
    } catch (\Throwable $th) {
        throw $th;
    }
}



if (isset($_POST["sign-out"])) {
    signOut();
}

$user = getUser();

?>

<!DOCTYPE html>
<html lang="en">

<?php $page = "Profile";
include("../components/header.php"); ?>


<form class="user-details-card card" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

    <div class="details-view">
        <img src="../images/user.png" alt="Avatar" class="avatar">
        <div class="text-view">
            <h2><?php echo $user["name"] ?></h2>
            <div>
                <?php echo $user["email"] ?>
            </div>
            <div>
                <?php echo $user["indexNo"] ?>
            </div>
        </div>
    </div>

    <input class="auth-btn" name="sign-out" type="submit" value="Sign Out">
</form>

<?php include "../components/footer.php" ?>

</html>