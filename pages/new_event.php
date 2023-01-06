<!DOCTYPE html>
<html lang="en">

<?php $page = "New Event";
include("../components/header.php"); ?>

<?php

require("../config/database_helper.php");


function authorize()
{
    global $sessionIndexNo;
    if ($sessionIndexNo != "Admin") {
        header("Location: index.php");
    }
}

function addEvent($title, $imageUrl, $organizedBy, $place, $description, $date, $time, $entrance)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "INSERT INTO events (title, imageUrl, organizedBy, place, description, date, time, free, generalAdmission, vipPass) VALUES (:title, :imageUrl, :organizedBy, :place, :description, :date, :time, :free, :generalAdmission, :vipPass)";
        $params = ["title" => $title, "imageUrl" => $imageUrl, "organizedBy" => $organizedBy, "place" => $place, "description" => $description, "date" => $date, "time" => $time, "free" => $entrance["free"], "generalAdmission" => $entrance["generalAdmission"], "vipPass" => $entrance["vipPass"]];
        $helper->query($sql, $params);
        $db = $helper->getDB();
        $eventId = $db->lastInsertId();
        $helper->close();
        if (empty($entrance["generalAdmission"]) && empty($entrance["vipPass"])) {
            return "NO";
        } else {
            return $eventId;
        }
    } catch (\Throwable $th) {
        $errors["server"] = $th->getMessage();
    }
}

function updateEvent($eventId, $ticketType, $ticketId)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "UPDATE events SET " . $ticketType . " = :ticketId WHERE eventId = :eventId";
        $params = ["ticketId" => $ticketId, "eventId" => $eventId];
        $helper->query($sql, $params);
        $helper->close();
    } catch (\Throwable $th) {
        $errors["server"] = $th->getMessage();
    }
}

function addTicket($eventId, $ticket)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "INSERT INTO tickets (eventId, type, price, ticketLimit) VALUES (:eventId, :type, :price, :ticketLimit)";
        $params = ["eventId" => $eventId, "type" => $ticket["type"], "price" => $ticket["price"], "ticketLimit" => $ticket["ticketLimit"]];
        $helper->query($sql, $params);
        $db = $helper->getDB();
        $ticketId = $db->lastInsertId();
        $helper->close();
        return $ticketId;
    } catch (\Throwable $th) {
        $errors["server"] = $th->getMessage();
    }
}

authorize();

$title = $imageUrl = $organizedBy = $place = $description = $date = $time = "";

$errors = array("title" => "", "image" => "", "organizedBy" => "", "place" => "", "description" => "", "date" => "", "time" => "", "entrance" => "");

$entrance = array("free" => "", "generalAdmission" => "", "vipPass" => "");

$tickets = array();

$serverError = "";

function getInput($field)
{
    global $errors;
    if (empty($_POST[$field])) {
        $errors[$field] = "This field is required";
    } else {
        $errors[$field] = "";
        return $_POST[$field];
    }
}

function getImage()
{
    global $errors;
    if (empty($_FILES["image"])) {
        $errors["image"] = "Image is required";
    } else {
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

        $extensions = array("jpeg", "jpg", "png");

        if (in_array($file_ext, $extensions) === false) {
            $errors["image"] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors["image"] = 'File size must be less than 2 MB';
        }


        if (empty($errors["image"])) {
            $uniqueName = uniqid("event");
            $path = "../images/events/" . $uniqueName . "." . $file_ext;
            $fileData = ["path" => $path, "file" => $file_tmp];
            return $fileData;
        }
    }
}

function getTicketInputs($type, $priceTag, $limitTag)
{
    global $errors;
    if (!empty($_POST[$priceTag]) && !empty($_POST[$limitTag])) {
        return array("type" => $type, "price" => $_POST[$priceTag], "ticketLimit" => $_POST[$limitTag]);
    } else {
        $errors["entrance"] = "Some necessary fields are empty";
    }
}

if (isset($_POST["generalAdmission"])) {
    echo "General admission";
}

if (isset($_POST["submit"])) {

    $title = getInput("title");
    $organizedBy = getInput("organizedBy");
    $place = getInput("place");
    $description = getInput("description");
    $date = getInput("date");
    $time = getInput("time");

    if ($_POST["free"] == "free") {
        $entrance["free"] = "YES";
    } else {
        $entrance["free"] = "";
    }

    if ($_POST["generalAdmission"] == "generalAdmission") {
        $entrance["generalAdmission"] = "YES";
    } else {
        $entrance["generalAdmission"] = "";
    }

    if ($_POST["vipPass"] == "vipPass") {
        $entrance["vipPass"] = "YES";
    } else {
        $entrance["vipPass"] = "";
    }

    if (!array_filter($entrance)) {
        $errors["entrance"] = "Please select at least one entrance";
    } else {
        $errors["entrance"] = "";
    }

    if ($entrance["generalAdmission"] == "YES") {
        $ticket = getTicketInputs("generalAdmission", "priceGA", "limitGA");
        array_push($tickets, $ticket);
    }

    if ($entrance["vipPass"] == "YES") {
        $ticket = getTicketInputs("vipPass", "priceVIP", "limitVIP");
        array_push($tickets, $ticket);
    }

    $fileData = getImage();

    if (!array_filter($errors)) {
        if (!move_uploaded_file($fileData["file"], $fileData["path"])) {
            $errors["image"] = "Unable to upload image";
        } else {
            $errors["image"] = "";
        }
    }

    if (!array_filter($errors)) {
        $eventId = addEvent($title, $fileData["path"], $organizedBy, $place, $description, $date, $time, $entrance);

        if ($eventId == "NO") {
            header("Location: index.php");
        } else {
            if ($tickets) {
                foreach ($tickets as $ticket) {
                    $ticketId = addTicket($eventId, $ticket);
                    updateEvent($eventId, $ticket["type"], $ticketId);
                }
            }
            header("Location: index.php");
        }
    }
}


?>


<!-- PRESENTATION -->


<br>

<form class="card margin-auto fit-content padding-h20" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
    <br>
    <h2 class="primary">Add New Event</h2>
    <hr>
    <div class="row gap50">
        <div class="col align-start">
            <div class="f-large col align-start gap10">
                <label class="dark" for="title">Title</label>
                <input class="input" type="text" name="title" id="title" value="<?php echo $title ?>">
                <div class="red"><?php echo $errors["title"] ?></div>
            </div>
            <br>
            <div class="f-large col align-start gap10">
                <label class="dark" for="place">Place</label>
                <input class="input" type="text" name="place" id="place" value="<?php echo $place ?>">
                <div class="red"><?php echo $errors["place"] ?></div>
            </div>
        </div>
        <div class="col align-start">
            <div class="f-large col align-start gap10">
                <label class="dark" for="image">Image</label>
                <input class="input" type="file" name="image" id="image" value="<?php echo $image ?>">
                <div class="red"><?php echo $errors["image"] ?></div>
            </div>
            <br>
            <div class="f-large col align-start gap10">
                <label class="dark" for="date">Date</label>
                <input class="input" type="date" name="date" id="date" value="<?php echo $date ?>">
                <div class="red"><?php echo $errors["date"] ?></div>
            </div>
        </div>
        <div class="col align-start">
            <div class="f-large col align-start gap10">
                <label class="dark" for="organizedBy">Organized By</label>
                <input class="input" type="text" name="organizedBy" id="organizedBy" value="<?php echo $organizedBy ?>">
                <div class="red"><?php echo $errors["organizedBy"] ?></div>
            </div>
            <br>
            <div class="f-large col align-start gap10">
                <label class="dark" for="time">Time</label>
                <input class="input" type="time" name="time" id="time" value="<?php echo $time ?>">
                <div class="red"><?php echo $errors["time"] ?></div>
            </div>

        </div>
    </div>
    <hr>
    <div class="f-large col align-stretch gap10">
        <label class="dark" for="description">Description</label>
        <textarea class="input" name="description" id="description" cols="30" rows="10"><?php echo $description ?></textarea>
        <div class="red"><?php echo $errors["description"] ?></div>
    </div>
    <hr>
    <div class="f-large col align-stretch gap10">
        <label class="dark" for="">Entrance</label>
        <div class="card padding-v10 padding-h20 radius4 row space-between">
            <div class="row gap10 dark">
                <input class="icon-smaller" type="checkbox" name="free" id="free" value="free">
                <label for="free">Free</label>
            </div>
        </div>

        <div class="card padding-v10 padding-h20 radius4 row space-between">
            <div class="row gap10 dark" onclick="myFunction('generalAdmission', 'ticket-inputs-GA')">
                <input class="icon-smaller" type="checkbox" name="generalAdmission" id="generalAdmission" value="generalAdmission">
                <label for="generalAdmission">General Admission</label>
            </div>
            <div class="hide row gap20" id="ticket-inputs-GA">
                <div class="f-large col align-start gap10">
                    <label class="dark" for="priceGA">Price</label>
                    <input class="input" type="number" name="priceGA" id="priceGA" value="<?php echo $priceGA ?>">
                    <div class="red"><?php echo $errors["priceGA"] ?></div>
                </div>
                <div class="f-large col align-start gap10">
                    <label class="dark" for="limitGA">Limit</label>
                    <input class="input" type="number" name="limitGA" id="limitGA" value="<?php echo $limitGA ?>">
                    <div class="red"><?php echo $errors["limitGA"] ?></div>
                </div>
            </div>

        </div>

        <div class="card padding-v10 padding-h20 radius4 row space-between">
            <div class="row gap10 dark" onclick="myFunction('vipPass', 'ticket-inputs-VIP')">
                <input class="icon-smaller" type="checkbox" name="vipPass" id="vipPass" value="vipPass">
                <label for="vipPass">VIP Pass</label>
            </div>

            <div class="hide row gap20" id="ticket-inputs-VIP">

                <div class="f-large col align-start gap10">
                    <label class="dark" for="priceVIP">Price</label>
                    <input class="input" type="number" name="priceVIP" id="priceVIP" value="<?php echo $priceVIP ?>">
                    <div class="red"><?php echo $errors["priceVIP"] ?></div>
                </div>
                <div class="f-large col align-start gap10">
                    <label class="dark" for="limitVIP">Limit</label>
                    <input class="input" type="number" name="limitVIP" id="limitVIP" value="<?php echo $limitVIP ?>">
                    <div class="red"><?php echo $errors["limitVIP"] ?></div>
                </div>
            </div>
        </div>
        <div class="input-error"><?php echo $errors["entrance"] ?></div>
    </div>
    <hr>
    <div class="width100 row align-end">
        <input class="button m-auto-left" type="submit" name="submit" value="Submit">
    </div>
    <br>
</form>
<br>
<script>
    function myFunction(id, item) {
        var checkBox = document.getElementById(id);
        var inputs = document.getElementById(item);
        if (checkBox.checked == true) {
            inputs.style.display = "flex";
        } else {
            inputs.style.display = "none";
        }
    }
</script>

<?php include "../components/footer.php" ?>

</html>