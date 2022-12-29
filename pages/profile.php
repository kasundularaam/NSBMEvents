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



function getBookings($userId)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "SELECT * FROM bookings WHERE userId = :userId";
        $params = ["userId" => $userId];
        $stmt = $helper->query($sql, $params);
        $bookings = $stmt->fetchAll();
        $helper->close();

        return $bookings;
    } catch (\Throwable $th) {
        throw $th;
    }
}

function getQrCode($bookingId)
{
    global $type;
    $response = file_get_contents("https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=HELLO");
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($response);
    return $base64;
}


if (isset($_POST["sign-out"])) {
    signOut();
}

$user = getUser();

$bookings = getBookings($user["indexNo"]);

?>

<!DOCTYPE html>
<html lang="en">

<?php $page = "Profile";
include("../components/header.php"); ?>

<script src="../html2canvas.js"></script>
<script>
    function capture() {
        html2canvas(document.getElementById("3"), {
            letterRendering: 1,
            allowTaint: true,
        }).then(function(canvas) {
            console.log(canvas.toDataURL("image/png", 0.9));
        })

    }
</script>


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

<?php foreach ($bookings as $booking) : ?>
    <div class="card" id="<?php echo $booking["bookingId"] ?>">
        <?php echo $booking["bookingId"] ?>
        <br>
        <img src="<?php echo getQrCode($booking["bookingId"]) ?>" alt="QR">
        <!-- <img src="../images/admin.png" alt="QR"> -->
        <br>
        <?php echo $booking["ticketId"] ?>
        <br>
        <?php echo $booking["timestamp"] ?>
        <br>
    </div>
    <button id="btn" onclick="capture();" class="btn">Download</button>
    <hr>


<?php endforeach; ?>

<?php include "../components/footer.php" ?>

</html>