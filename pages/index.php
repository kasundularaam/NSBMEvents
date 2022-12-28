<?php

require("../config/database_helper.php");

$events = array();

$error = "";

function getAllEvents()
{

  try {
    $helper = new DatabaseHelper();

    $helper->connect();

    $sql = "SELECT * FROM events";
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







<!DOCTYPE html>
<html lang="en">

<?php $page = "NSBM Events";
include("../components/header.php"); ?>

<div class="container">
  <h2>All Events</h2>

  <div class="events-grid">

    <?php foreach ($events as $event) : ?>
      <div class="event-card">
        <img src="<?php echo $event["imageUrl"] ?>" alt="">
        <div class="details">
          <h2> <a href="http://"> <?php echo $event["title"] ?></a></h2>
          <br>
          <div class="info">
            <div class="icon-text">
              <img class="icon" src="../images/calendar.png" alt="">
              <?php echo date("d.m.Y", strtotime($event["date"])) . " at " . date('g:ia', strtotime($event["time"])); ?>
            </div>
            <div class="icon-text">
              <img class="icon" src="../images/place.png" alt="">
              <?php echo $event["place"] ?>
            </div>
          </div>
          <hr>
          <p><?php echo $event["description"] ?></p>
          <br>
          <div class="entrance-details">
            <h4>Entrance</h4>

            <?php if (isFree($event)) : ?>
              <div class="free entrance-methods">
                <div class="icon-text">
                  <img class="icon" src="../images/free.png" alt="">

                  Free
                </div>
              </div>
            <?php endif; ?>

            <?php if (isGeneralAdmission($event)) : ?>
              <div class="generalAdmission entrance-methods">
                <div class="icon-text">
                  <img class="icon" src="../images/ticket.png" alt="">

                  <?php echo "Rs: " . getTicket($event, "generalAdmission")["price"]; ?>
                </div>
              </div>
            <?php endif; ?>

            <?php if (isVipPass($event)) : ?>
              <div class="vipPass entrance-methods">
                <div class="icon-text">
                  <img class="icon" src="../images/vip.png" alt="">
                  <?php echo "VIP Rs: " . getTicket($event, "vipPass")["price"]; ?>
                </div>
              </div>
            <?php endif; ?>

          </div>
        </div>

      </div>
    <?php endforeach; ?>

  </div>
</div>




<?php include "../components/footer.php" ?>

</html>