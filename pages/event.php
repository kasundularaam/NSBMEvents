<!DOCTYPE html>
<html lang="en">

<?php $page = "Event";
include("../components/header.php"); ?>

<?php
require("../config/database_helper.php");

global $sessionIndexNo;

$eventId = $_GET['eventId'];
$error = "";

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


<!-- PRESENTATION -->


<img class="width100 height400px cover" src="<?php echo $event["imageUrl"] ?>" alt="">

<div class="container">
    <br>
    <div class="row space-between">
        <div class="col align-start gap10">
            <h1><?php echo $event["title"] ?></h1>
            <div class="dark f-large">
                Organized by <?php echo $event["organizedBy"] ?>
            </div>
        </div>
        <div class="row gap30">
            <?php if (isFree($event)) : ?>
                <div class="row align-center gap10 primary bold f-large">
                    <img class="icon-small" src="../images/free.png" alt="">
                    Free
                </div>
            <?php endif; ?>
            <div class="f-large dark row gap10 align-center">
                <img class="icon-small" src="../images/calendar.png" alt="">
                <?php echo date("d.m.Y", strtotime($event["date"])) . " at " . date('g:ia', strtotime($event["time"])); ?>

            </div>
            <div class="f-large dark row gap10 align-center">
                <img class="icon-small" src="../images/place.png" alt="">
                <?php echo $event["place"] ?>
            </div>
        </div>
    </div>
    <hr>
    <div class="row space-between gap30 align-start">
        <div class="dark f-medium">
            <?php echo $event["description"] ?>
        </div>
        <?php if (!isNoTickets($event)) : ?>
            <div class="card padding10">
                <h2>Buy Tickets</h2>
                <hr>
                <div class="col gap20 align-start">
                    <?php if (isFree($event)) : ?>
                        <div class="row align-center gap10 primary bold f-large">
                            <img class="icon-small" src="../images/free.png" alt="">
                            Free
                        </div>
                    <?php endif; ?>

                    <?php if (isGeneralAdmission($event)) : ?>
                        <div class="row red bold space-between width100">
                            <div class="row gap10">
                                <img class="icon-small" src="../images/ticket.png" alt="">
                                General Admission
                            </div>
                            <?php echo "Rs: " . getTicket($event, "generalAdmission")["price"]; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isVipPass($event)) : ?>
                        <div class="gold bold f-large row space-between width100">
                            <div class="row gap10 ">
                                <img class="icon-small" src="../images/vip.png" alt="">
                                VIP Pass
                            </div>
                            <?php echo "Rs: " . getTicket($event, "vipPass")["price"]; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <hr>
                <div class="row space-between gap10">
                    <form action="<?php echo $_SERVER['PHP_SELF'] . "?eventId=" . $eventId ?>" method="post">
                        <input type="hidden" name="ticketId" value="<?php echo getTicket($event, "generalAdmission")["ticketId"]; ?>">
                        <input type="hidden" name="userId" value="<?php echo $sessionIndexNo; ?>">
                        <input class="button bg-red" name="generalBuy" type="submit" value="BUY GENERAL">
                    </form>
                    <form action="<?php echo $_SERVER['PHP_SELF'] . "?eventId=" . $eventId ?>" method="post">
                        <input type="hidden" name="ticketId" value="<?php echo getTicket($event, "vipPass")["ticketId"]; ?>">
                        <input type="hidden" name="userId" value="<?php echo $sessionIndexNo; ?>">
                        <input class="button bg-gold" name="vipBuy" type="submit" value="BUY VIP PASS">
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<br>

<?php include "../components/footer.php" ?>


</html>