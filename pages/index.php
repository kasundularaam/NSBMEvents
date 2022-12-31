<!DOCTYPE html>
<html lang="en">

<?php $page = "NSBM Events";
include("../components/header.php"); ?>

<?php

require("../config/database_helper.php");

$events = array();

$error = "";

function getFeaturedEvents()
{

  try {
    $helper = new DatabaseHelper();

    $helper->connect();

    $sql = "SELECT * FROM events ORDER BY eventId DESC LIMIT 6";
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

function getOrganizers($events)
{
  $organizers = array();
  foreach ($events as $event) {
    array_push($organizers, $event["organizedBy"]);
  }
  $organizers = array_unique($organizers);
  return array_slice($organizers, 0, 4);
}

$allEvents = getAllEvents();


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

$events = getFeaturedEvents();
$organizers = getOrganizers($allEvents);

?>


<!-- PRESENTATION -->



<div class="width100 height400px background-img2">
  <div class="padding-h40 col justify-center height100 gap20">
    <div class="lighter f-xxxlarge bold text-center">GREEN TICKETS</div>
    <p class="light text-center">book tickets online in NSBM events, movie, drama, concert and more...</p>
  </div>
</div>

<div class="padding-h20">
  <br>
  <div class="row space-between">
    <h2>FEATURED EVENTS</h2>
    <a class="primary f-larger bold" href="events.php">SEE ALL</a>
  </div>
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


<div class="width100 background-img3 padding-v40 display1">
  <div class="row space-around">
    <img class="padding-h60 height400px width400px cover" src="../images/display_img1.jpeg" alt="">
    <div class="padding-h40 col align-start gap20">
      <h1 class="lighter">ABOUT GREEN TICKETS</h1>
      <p class="light">green tickets is the first Lanka's ticket selling platform in the university level.We are the official marketplace provide you with a secure and safe platform to buy and sell tickets in NSBM Green University, If you are looking for tickets for upcoming events green ticket is the place to find them by giving our customers the maximum service with a new experience in online ticket purchasing.We're constantly rolling out new features to improve our service with an outstanding selection of tickets.</p>
    </div>
  </div>
</div>

<div class="bg-lighter padding-h40 col gap20">
  <br>
  <div class="col gap10 text-center">
    <div class="f-xxlarge bold primary">SUBSCRIBE TO OUR NEWSLETTER</div>
    <p>Stay informed and up-to-date on the latest news and events by subscribing to our newsletter.</p>
  </div>
  <hr class="width100">
  <div class="col">
    <div class="f-large col gap10">
      <label class="dark" for="email">Your Email</label>
      <input class="input" type="email" name="email" id="email" value="<?php echo $email ?>">
      <div class="red"><?php echo $errors["email"] ?></div>
    </div>
    <br>
    <input class="button" type="submit" value="subscribe">
  </div>
  <br>
</div>


<div class="padding-h20 padding-v100 background-img5 organizers-container">
  <div class="row space-around organizers">

    <div class="f-xxxlarge bold primary title">
      <div>
        OUR
      </div>
      <div>
        MAIN
      </div>
      <div>
        ORGANIZERS
      </div>
    </div>

    <div class="row content">
      <?php foreach ($organizers as $organizer) : ?>
        <div class="col padding-h60 flex1">
          <img class="width100px" src="../images/organizer.png" alt="">
          <hr class="width100 hr-primary">
          <h1><?php print_r($organizer); ?></h1>
        </div>
      <?php endforeach; ?>
    </div>
    <br>
  </div>
</div>



<?php include "../components/footer.php" ?>

</html>