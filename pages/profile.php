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

function getQrCode($bookingId)
{
    global $type;
    $response = file_get_contents("https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$bookingId");
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($response);
    return $base64;
}

function getEventImage($imageUrl)
{
    global $type;
    $response = file_get_contents($imageUrl);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($response);
    return $base64;
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

<!DOCTYPE html>
<html lang="en">

<?php $page = "Profile";
include("../components/header.php"); ?>

<script src="../html2canvas.js"></script>
<script>
    function downloadTicket(bookingId) {
        window.scrollTo(0, 0);
        html2canvas(document.getElementById("3"), {
            // letterRendering: 1,
            allowTaint: true,
        }).then(function(canvas) {
            var ticket = canvas.toDataURL("image/png", 0.9);
            downloadURI("data:" + ticket, `${bookingId}.png`);
        });
    }

    function downloadURI(uri, name) {
        var link = document.createElement("a");
        link.download = name;
        link.href = uri;
        link.click();
    }
</script>


<div class="container">

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


    <div class="card">
        <br>
        <h2>Your Tickets</h2>
        <br>


        <?php foreach ($bookings as $booking) : ?>
            <?php $ticket = getTicketData($booking["ticketId"]); ?>
            <?php $event = getEventData($ticket["eventId"]); ?>
            <div class="card row space-between">
                <div class="booking-details col space-between">
                    <h2><?php echo $event["title"]; ?></h2>
                    <div class="info col gap20">
                        <div class="icon-text">
                            <img class="icon" src="../images/calendar.png" alt="">
                            <?php echo date("d.m.Y", strtotime($event["date"])) . " at " . date('g:ia', strtotime($event["time"])); ?>
                        </div>
                        <div class="icon-text">
                            <img class="icon" src="../images/place.png" alt="">
                            <?php echo $event["place"] ?>
                        </div>
                        <?php if ($ticket["type"] == "generalAdmission") : ?>
                            <div class="generalAdmission entrance-methods">
                                <div class="icon-text">
                                    <img class="icon" src="../images/ticket.png" alt="">

                                    <?php echo "Rs: " . $ticket["price"]; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($ticket["type"] == "vipPass") : ?>
                            <div class="vipPass entrance-methods">
                                <div class="icon-text">
                                    <img class="icon" src="../images/vip.png" alt="">
                                    <?php echo "VIP Rs: " . $ticket["price"]; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button class="auth-btn " id="btn" onclick="downloadTicket();" class="btn">Download Ticket</button>
                </div>


                <div class="rel ticket" id="<?php echo $booking["bookingId"] ?>">
                    <div class="row abs">
                        <img crossOrigin="anonymous" class="event-image" src="<?php echo $event["imageUrl"]  ?>" alt="event">
                        <div class="qr">
                            <img crossOrigin="anonymous" src="<?php echo "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . $booking["bookingId"] ?>" alt="QR">
                        </div>
                    </div>
                    <div class="abs trans-container">
                    </div>

                    <div class="abs event-title">
                        <?php echo $event["title"]; ?>
                    </div>
                    <div class="abs type">
                        <?php if ($ticket["type"] == "generalAdmission") : ?>
                            <div class="generalAdmission entrance-methods">
                                <div class="icon-text">
                                    <img class="icon" src="../images/ticket.png" alt="">

                                    <?php echo "Rs: " . $ticket["price"]; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($ticket["type"] == "vipPass") : ?>
                            <div class="vipPass entrance-methods">
                                <div class="icon-text">
                                    <img class="icon" src="../images/vip.png" alt="">
                                    <?php echo "VIP Rs: " . $ticket["price"]; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <br>

        <?php endforeach; ?>

    </div>

</div>


<?php include "../components/footer.php" ?>

</html>