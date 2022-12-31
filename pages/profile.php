<!DOCTYPE html>
<html lang="en">

<?php $page = "Profile";
include("../components/header.php"); ?>

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
    global $sessionIndexNo;

    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "SELECT * FROM users WHERE indexNo = :indexNo";
        $params = ["indexNo" => $sessionIndexNo];
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

        $sql = "SELECT * FROM bookings WHERE userId = :userId ORDER BY bookingId DESC";
        $params = ["userId" => $userId];
        $stmt = $helper->query($sql, $params);
        $bookings = $stmt->fetchAll();
        $helper->close();

        return $bookings;
    } catch (\Throwable $th) {
        throw $th;
    }
}

function getEventData($eventId)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "SELECT * FROM events WHERE eventId = :eventId";
        $params = ["eventId" => $eventId];
        $stmt = $helper->query($sql, $params);
        $event = $stmt->fetch();
        $helper->close();

        return $event;
    } catch (\Throwable $th) {
        throw $th;
    }
}

function getTicketData($ticketId)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "SELECT * FROM tickets WHERE ticketId = :ticketId";
        $params = ["ticketId" => $ticketId];
        $stmt = $helper->query($sql, $params);
        $ticket = $stmt->fetch();
        $helper->close();

        return $ticket;
    } catch (\Throwable $th) {
        throw $th;
    }
}

function getBookingData($ticketId)
{
    $ticket = getTicketData($ticketId);
    $event = getEventData($ticket["eventId"]);
    $bookingData = array();
    $bookingData["bookingId"];
}

function isGeneralAdmission($event)
{
    if (!empty($event["generalAdmission"])) {
        return true;
    } else {
        return false;
    }
}

function isVipPass($event)
{
    if (!empty($event["vipPass"])) {
        return true;
    } else {
        return false;
    }
}


if (isset($_POST["sign-out"])) {
    signOut();
}

$user = getUser();

$bookings = getBookings($user["indexNo"]);

?>


<!-- PRESENTATION -->



<div class="padding-h20">

    <form class="card row space-between align-start padding-h20 padding-v20 profile-view" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

        <div class="row gap20">
            <img src="../images/user.png" alt="Avatar" class="avatar">
            <div class="col align-start gap10 dark">
                <h2><?php echo $user["name"] ?></h2>
                <div>
                    <?php echo $user["email"] ?>
                </div>
                <div>
                    <?php echo $user["indexNo"] ?>
                </div>
            </div>
        </div>

        <div class="row">
            <img class="icon-smaller" src="../images/exit.png" alt="">
            <input class="no-border red bg-lighter f-medium pointer bold" name="sign-out" type="submit" value="Sign Out">
        </div>

    </form>
    <br>
    <hr>
    <br>
    <h2>Your Tickets</h2>
    <br>
    <div class="grid grid-col2 gap20 tickets-grid">
        <?php foreach ($bookings as $booking) : ?>
            <?php $ticket = getTicketData($booking["ticketId"]); ?>
            <?php $event = getEventData($ticket["eventId"]); ?>
            <div class="card col align-start">
                <br>
                <div class="padding-h10">
                    <h2><?php echo $event["title"]; ?></h2>
                </div>
                <br>
                <div class="row width100">
                    <img class="width100 height200px cover ticket-img" src="<?php echo $event["imageUrl"]  ?>" alt="event">
                    <img class="height200px cover qr" src="<?php echo "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . $booking["bookingId"] ?>" alt="QR">
                </div>
                <br>
                <div class="row space-between width100 ticket-details-wrap">
                    <div class="row gap20 padding-h20 ticket-details">
                        <div class="row dark gap10">
                            <img class="icon-small" src="../images/calendar.png" alt="">
                            <?php echo date("d.m.Y", strtotime($event["date"])) . " at " . date('g:ia', strtotime($event["time"])); ?>
                        </div>
                        <div class="row dark gap10">
                            <img class="icon-small" src="../images/place.png" alt="">
                            <?php echo $event["place"] ?>
                        </div>
                        <?php if ($ticket["type"] == "generalAdmission") : ?>
                            <div class="row gap10 red f-large bold">
                                <img class="icon-small" src="../images/ticket.png" alt="">
                                <?php echo "Rs: " . $ticket["price"]; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($ticket["type"] == "vipPass") : ?>
                            <div class="row gap10 gold f-large bold">
                                <img class="icon-small" src="../images/vip.png" alt="">
                                <?php echo "Rs: " . $ticket["price"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="padding-h20">
                        <a class="primary" href="event.php?eventId=<?php echo $event["eventId"]; ?>">More Details...</a>
                    </div>
                </div>
                <br>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<br>


<?php include "../components/footer.php" ?>

</html>