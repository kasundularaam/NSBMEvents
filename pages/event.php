<?php
require("../config/database_helper.php");

$eventId = $_GET['eventId'];
$error = "";

function getUserId()
{
    session_start();
    $indexNo = $_SESSION["indexNo"] ?? "Guest";
    return $indexNo;
}

function getEvent($eventId)
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
        $error = $th->getMessage();
    }
}
$event  = getEvent($eventId);

function buy()
{
    $userId = $_POST["userId"];
    $ticketId = $_POST["ticketId"];
    if ($userId == "Guest") {
        header("Location: login.php");
    } else if ($userId == "Admin") {
        echo "Dear Admin Its Working :)";
    } else {
        if (buyTicket($userId, $ticketId)) {
            header("Location: profile.php");
        }
    }
}

function getTicket($event, $type)
{
    $ticketId = $event[$type];

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
        $error = $th->getMessage();
    }
}

function getEntranceMethods($event)
{
    $entranceMethods = array();
    if ($event["free"] == "YES") {
        array_push($entranceMethods, "Free");
    }
}

function isFree($event)
{
    if ($event["free"] == "YES") {
        return true;
    } else {
        return false;
    }
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

function isNoTickets($event)
{
    if (!isGeneralAdmission($event) && !isVipPass($event)) {
        return true;
    } else {
        return false;
    }
}

function buyTicket($userId, $ticketId)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "INSERT INTO bookings (userId, ticketId) VALUES (:userId, :ticketId)";
        $params = ["userId" => $userId, "ticketId" => $ticketId];
        $helper->query($sql, $params);
        $helper->close();
        return true;
    } catch (\Throwable $th) {
        $errors["server"] = $th->getMessage();
    }
}



if (isset($_POST["generalBuy"])) {
    buy();
}
if (isset($_POST["vipBuy"])) {
    buy();
}
?>



<!DOCTYPE html>
<html lang="en">

<?php $page = "Event";
include("../components/header.php"); ?>

<img class="event-top-img" src="<?php echo $event["imageUrl"] ?>" alt="">

<div class="container">
    <div class="row space-between">
        <div class="col">
            <h1><?php echo $event["title"] ?></h1>
            <p>Organized by <?php echo $event["organizedBy"] ?></p>
        </div>
        <div class="info row gap20 align-center">
            <?php if (isFree($event)) : ?>
                <div class="free entrance-methods">
                    <div class="icon-text">
                        <img class="icon" src="../images/free.png" alt="">
                        Free
                    </div>
                </div>
            <?php endif; ?>
            <div class="icon-text">
                <img class="icon" src="../images/calendar.png" alt="">
                <p>
                    <?php echo date("d.m.Y", strtotime($event["date"])) . " at " . date('g:ia', strtotime($event["time"])); ?>
                </p>
            </div>
            <div class="icon-text">
                <img class="icon" src="../images/place.png" alt="">
                <p>
                    <?php echo $event["place"] ?>
                </p>
            </div>
        </div>
    </div>
    <br>
    <div class="row space-between gap20">
        <p><?php echo $event["description"] ?></p>
        <?php if (!isNoTickets($event)) : ?>
            <div class="col">
                <div class="card mxc">
                    <h2>Buy Tickets</h2>
                    <hr>
                    <div class="col gap20">
                        <?php if (isFree($event)) : ?>
                            <div class="free entrance-methods">
                                <div class="icon-text">
                                    <img class="icon" src="../images/free.png" alt="">

                                    Free
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (isGeneralAdmission($event)) : ?>
                            <div class="generalAdmission entrance-methods row space-between gap50">
                                <div class="icon-text">
                                    <img class="icon" src="../images/ticket.png" alt="">
                                    General Admission

                                </div>
                                <?php echo "Rs: " . getTicket($event, "generalAdmission")["price"]; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isVipPass($event)) : ?>
                            <div class="vipPass entrance-methods row space-between gap50">
                                <div class="icon-text">
                                    <img class="icon" src="../images/vip.png" alt="">
                                    VIP Pass
                                </div>
                                <?php echo "Rs: " . getTicket($event, "vipPass")["price"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <div class="row space-between align-center gap20">
                        <form action="<?php echo $_SERVER['PHP_SELF'] . "?eventId=" . $eventId ?>" method="post">
                            <input type="hidden" name="ticketId" value="<?php echo getTicket($event, "generalAdmission")["ticketId"]; ?>">
                            <input type="hidden" name="userId" value="<?php echo getUserId(); ?>">
                            <input class="buy-btn general-btn" name="generalBuy" type="submit" value="BUY GENERAL">
                        </form>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . "?eventId=" . $eventId ?>" method="post">
                            <input type="hidden" name="ticketId" value="<?php echo getTicket($event, "vipPass")["ticketId"]; ?>">
                            <input type="hidden" name="userId" value="<?php echo getUserId(); ?>">
                            <input class="buy-btn vip-btn" name="vipBuy" type="submit" value="BUY VIP PASS">
                        </form>

                    </div>
                </div>

            </div>
        <?php endif; ?>
    </div>
</div>





<?php include "../components/footer.php" ?>


</html>