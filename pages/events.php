<!DOCTYPE html>
<html lang="en">

<?php $page = "Events";
include("../components/header.php"); ?>

<?php

require("../config/database_helper.php");

$events = array();

$error = "";

function getAllEvents()
{

    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "SELECT * FROM events ORDER BY eventId DESC";
        $params = [];
        $stmt = $helper->query($sql, $params);
        $events = $stmt->fetchAll();
        $helper->close();
        return $events;
    } catch (\Throwable $th) {
        $error = $th->getMessage();
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

$events = getAllEvents();
?>


<!-- PRESENTATION -->



<div class="padding-h20">

    <br>
    <div class="grid grid-col3 gap20 events-grid">

        <?php foreach ($events as $event) : ?>
            <div class="card">
                <img class="width100 card-image" src="<?php echo $event["imageUrl"] ?>" alt="">
                <div class="container">
                    <br>
                    <h2> <a href="event.php?eventId=<?php echo $event['eventId'] ?>"> <?php echo $event["title"] ?></a></h2>
                    <br>
                    <div class="row space-between">
                        <div class="row gap10 dark f-large">
                            <img class="icon-small" src="../images/calendar.png" alt="">
                            <?php echo date("d.m.Y", strtotime($event["date"])) . " at " . date('g:ia', strtotime($event["time"])); ?>
                        </div>









                        <div class="row gap10 dark f-large">
                            <img class="icon-small" src="../images/place.png" alt="">
                            <?php echo $event["place"] ?>
                        </div>
                    </div>
                    <hr>
                    <div class="card-description dark f-large">
                        <?php echo $event["description"] ?>
                    </div>
                    <hr>
                    <div class="row space-around">
                        <?php if (isFree($event)) : ?>
                            <div class="row align-center gap10 primary bold f-large">
                                <img class="icon-small" src="../images/free.png" alt="">
                                Free
                            </div>
                        <?php endif; ?>

                        <?php if (isGeneralAdmission($event)) : ?>
                            <div class="row gap10 red bold">
                                <img class="icon-small" src="../images/ticket.png" alt="">
                                <?php echo "Rs: " . getTicket($event, "generalAdmission")["price"]; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isVipPass($event)) : ?>
                            <div class="row gap10 gold bold">
                                <img class="icon-small" src="../images/vip.png" alt="">
                                <?php echo "VIP Rs: " . getTicket($event, "vipPass")["price"]; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <br>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<br>
<?php include "../components/footer.php" ?>

</html>