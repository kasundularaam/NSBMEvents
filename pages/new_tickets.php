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

function getTicketTypes()
{
    $eventId = $_GET["eventId"] ?? "";
    if (!empty($eventId)) {
        try {
            $helper = new DatabaseHelper();
            $helper->connect();
            $sql = "SELECT * FROM events WHERE eventId = :eventId";
            $params = ["eventId" => $eventId];
            $stmt = $helper->query($sql, $params);
            $event = $stmt->fetch();
            $helper->close();
            $types = array();
            if (!empty($event["generalAdmission"])) {
                array_push($types, "generalAdmission");
            }
            if (!empty($event["vipPass"])) {
                array_push($types, "vipPass");
            }
            return $types;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

authorize();

$ticketTypes = getTicketTypes();

?>

<!DOCTYPE html>
<html lang="en">

<?php $page = "New Tickets";
include("../components/header.php"); ?>

<form class="auth-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <?php foreach ($ticketTypes as $ticketType) : ?>
        <div class="input-field">
            <label for="indexNo">Index No</label>
            <input type="text" name="indexNo" id="indexNo" value="<?php echo $indexNo ?>">
            <div class="input-error"><?php echo $errors["indexNo"] ?>
            </div>
        </div>
    <?php endforeach; ?>
</form>

<?php include "../components/footer.php" ?>

</html>