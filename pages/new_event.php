<?php

require("../config/database_helper.php");


function authorize()
{
    session_start();
    $indexNo = $_SESSION["indexNo"];
    if ($indexNo != "Admin") {
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
            header("Location: index.php");
        } else {
            header("Location: new_tickets.php?eventId=" . $eventId);
        }
    } catch (\Throwable $th) {
        $errors["server"] = $th->getMessage();
    }
}

authorize();

$title = $imageUrl = $organizedBy = $place = $description = $date = $time = "";

$errors = array("title" => "", "imageUrl" => "", "organizedBy" => "", "place" => "", "description" => "", "date" => "", "time" => "", "entrance" => "");

$entrance = array("free" => "", "generalAdmission" => "", "vipPass" => "");

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

if (isset($_POST["generalAdmission"])) {
    echo "General admission";
}

if (isset($_POST["submit"])) {

    $title = getInput("title");
    $imageUrl = getInput("imageUrl");
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



    if (!array_filter($errors)) {
        addEvent($title, $imageUrl, $organizedBy, $place, $description, $date, $time, $entrance);
    }
}


?>







<!DOCTYPE html>
<html lang="en">

<?php $page = "New Event";
include("../components/header.php"); ?>

<form class="event-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <h2>Add New Event</h2>
    <div class="row">
        <div class="column">
            <div class="input-field">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?php echo $title ?>">
                <div class="input-error"><?php echo $errors["title"] ?></div>
            </div>
            <div class="input-field">
                <label for="place">Place</label>
                <input type="text" name="place" id="place" value="<?php echo $place ?>">
                <div class="input-error"><?php echo $errors["place"] ?></div>
            </div>
        </div>
        <div class="column">
            <div class="input-field">
                <label for="imageUrl">Image Url</label>
                <input type="url" name="imageUrl" id="imageUrl" value="<?php echo $imageUrl ?>">
                <div class="input-error"><?php echo $errors["imageUrl"] ?></div>
            </div>
            <div class="input-field">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" value="<?php echo $date ?>">
                <div class="input-error"><?php echo $errors["date"] ?></div>
            </div>
        </div>
        <div class="column">
            <div class="input-field">
                <label for="organizedBy">Organized By</label>
                <input type="text" name="organizedBy" id="organizedBy" value="<?php echo $organizedBy ?>">
                <div class="input-error"><?php echo $errors["organizedBy"] ?></div>
            </div>

            <div class="input-field">
                <label for="time">Time</label>
                <input type="time" name="time" id="time" value="<?php echo $time ?>">
                <div class="input-error"><?php echo $errors["time"] ?></div>
            </div>

        </div>
    </div>

    <div class="input-field">
        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10"><?php echo $description ?></textarea>
        <div class="input-error"><?php echo $errors["description"] ?></div>
    </div>

    <div class="input-field">
        <label for="">Entrance</label>
        <div class="ticket-input-field">


            <div class="checkbox-field">
                <input type="checkbox" name="free" id="free" value="free">
                <label for="free">Free</label>
            </div>
        </div>

        <div class="ticket-input-field">

            <div class="checkbox-field" onclick="myFunction('generalAdmission', 'ticket-inputs-GA')">
                <input type="checkbox" name="generalAdmission" id="generalAdmission" value="generalAdmission">
                <label for="generalAdmission">General Admission</label>
            </div>
            <div class="ticket-inputs" id="ticket-inputs-GA">
                <div class="input-field">
                    <label for="priceGA">Price</label>
                    <input type="number" name="priceGA" id="priceGA" value="<?php echo $priceGA ?>">
                    <div class="input-error"><?php echo $errors["priceGA"] ?></div>
                </div>
                <div class="input-field">
                    <label for="limitGA">Limit</label>
                    <input type="number" name="limitGA" id="limitGA" value="<?php echo $limitGA ?>">
                    <div class="input-error"><?php echo $errors["limitGA"] ?></div>
                </div>
            </div>

        </div>

        <div class="ticket-input-field">
            <div class="checkbox-field" onclick="myFunction('vipPass', 'ticket-inputs-VIP')">
                <input type="checkbox" name="vipPass" id="vipPass" value="vipPass">
                <label for="vipPass">VIP Pass</label>
            </div>

            <div class="ticket-inputs" id="ticket-inputs-VIP">

                <div class="input-field">
                    <label for="priceVIP">Price</label>
                    <input type="number" name="priceVIP" id="priceVIP" value="<?php echo $priceVIP ?>">
                    <div class="input-error"><?php echo $errors["priceVIP"] ?></div>
                </div>
                <div class="input-field">
                    <label for="limitVIP">Limit</label>
                    <input type="number" name="limitVIP" id="limitVIP" value="<?php echo $limitVIP ?>">
                    <div class="input-error"><?php echo $errors["limitVIP"] ?></div>
                </div>

            </div>
        </div>
        <div class="input-error"><?php echo $errors["entrance"] ?></div>
    </div>

    <input class="auth-btn" type="submit" name="submit" value="Submit">

</form>

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