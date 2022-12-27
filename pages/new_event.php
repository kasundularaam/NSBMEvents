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

function addEvent($title, $imageUrl, $organizedBy, $place, $description, $date, $time)
{
    try {
        $helper = new DatabaseHelper();

        $helper->connect();

        $sql = "INSERT INTO events (title, imageUrl, organizedBy, place, description, date, time) VALUES (:title, :imageUrl, :organizedBy, :place, :description, :date, :time)";
        $params = ["title" => $title, "imageUrl" => $imageUrl, "organizedBy" => $organizedBy, "place" => $place, "description" => $description, "date" => $date, "time" => $time];
        $helper->query($sql, $params);
        $helper->close();

        header("Location: new_tickets.php");
    } catch (\Throwable $th) {
        $errors["server"] = $th->getMessage();
    }
}


authorize();

$title = $imageUrl = $organizedBy = $place = $description = $date = $time;

$errors = array("title" => "", "imageUrl" => "", "organizedBy" => "", "place" => "", "description" => "", "date" => "", "time" => "", "entrance" => "");

$entrance = array("free" => "", "generalAdmission" => "", "vipPass" => "");

$serverError = "";

if (isset($_POST["submit"])) {

    if (empty($_POST["title"])) {
        $errors["title"] = "Title is required";
    } else {
        $title = $_POST["title"];
        $errors["title"] = "";
    }

    if (empty($_POST["imageUrl"])) {
        $errors["imageUrl"] = "Image Url is required";
    } else {
        $imageUrl = $_POST["imageUrl"];
        $errors["imageUrl"] = "";
    }

    if (empty($_POST["organizedBy"])) {
        $errors["organizedBy"] = "Organized By is required";
    } else {
        $organizedBy = $_POST["organizedBy"];
        $errors["organizedBy"] = "";
    }

    if (empty($_POST["place"])) {
        $errors["place"] = "Place is required";
    } else {
        $place = $_POST["place"];
        $errors["place"] = "";
    }

    if (empty($_POST["description"])) {
        $errors["description"] = "Description is required";
    } else {
        $description = $_POST["description"];
        $errors["description"] = "";
    }

    if (empty($_POST["date"])) {
        $errors["date"] = "Date is required";
    } else {
        $date = $_POST["date"];
        $errors["date"] = "";
    }

    if (empty($_POST["time"])) {
        $errors["time"] = "Time is required";
    } else {
        $time = $_POST["time"];
        $errors["time"] = "";
    }

    if (!array_filter($entrance)) {
        $errors["entrance"] = "Please select at least one entrance";
    } else {
        $errors["entrance"] = "";
    }

    if (isset($_POST["free"])) {
        $entrance["free"] = $_POST["free"];
    }

    if (!array_filter($errors)) {
        addEvent($title, $imageUrl, $organizedBy, $place, $description, $date, $time);
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
                <label for="imageUrl">Image Url</label>
                <input type="url" name="imageUrl" id="imageUrl" value="<?php echo $imageUrl ?>">
                <div class="input-error"><?php echo $errors["imageUrl"] ?></div>
            </div>
            <div class="input-field">
                <label for="organizedBy">Organized By</label>
                <input type="text" name="organizedBy" id="organizedBy" value="<?php echo $organizedBy ?>">
                <div class="input-error"><?php echo $errors["organizedBy"] ?></div>
            </div>
            <div class="input-field">
                <label for="place">Place</label>
                <input type="text" name="place" id="place" value="<?php echo $place ?>">
                <div class="input-error"><?php echo $errors["place"] ?></div>
            </div>
        </div>
        <div class="column">
            <div class="input-field">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" value="<?php echo $date ?>">
                <div class="input-error"><?php echo $errors["date"] ?></div>
            </div>
            <div class="input-field">
                <label for="time">Time</label>
                <input type="time" name="time" id="time" value="<?php echo $time ?>">
                <div class="input-error"><?php echo $errors["time"] ?></div>
            </div>
            <div class="input-field">
                <label for="">Entrance</label>
                <div class="checkbox-field">
                    <input type="checkbox" name="free" id="free" value="<?php echo $entrance["free"] ?>">
                    <label for="free">Free</label>
                </div>
                <div class="checkbox-field">
                    <input type="checkbox" name="generalAdmission" id="generalAdmission" value="<?php echo $entrance["generalAdmission"] ?>">
                    <label for="generalAdmission">General Admission</label>
                </div>
                <div class="checkbox-field">
                    <input type="checkbox" name="vipPass" id="vipPass" value="<?php echo $entrance["vipPass"] ?>">
                    <label for="vipPass">VIP Pass</label>
                </div>
                <div class="input-error"><?php echo $errors["entrance"] ?></div>
            </div>
        </div>
    </div>

    <div class="input-field">
        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10"><?php echo $description ?></textarea>
        <div class="input-error"><?php echo $errors["description"] ?></div>
    </div>

    <input class="auth-btn" type="submit" name="submit" value="Submit">

</form>

<?php include "../components/footer.php" ?>

</html>