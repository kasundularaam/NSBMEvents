<!DOCTYPE html>
<html lang="en">

<?php $page = "Contact Us";
include("../components/header.php"); ?>


<?php

$email = $subject = $message = $sent = "";
$errors = array("email" => "", "subject" => "", "message" => "");


if (isset($_POST["submit"])) {
    if (empty($_POST["email"])) {
        $errors["email"] = "Email is required";
    } else {
        $email = $_POST["email"];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Email is invalid";
        } else {
            $errors["email"] = "";
        }
    }

    if (empty(["subject"])) {
        $errors["subject"] = "Subject is required";
    } else {
        $subject = $_POST["subject"];
        $errors["subject"] = "";
    }

    if (empty(["message"])) {
        $errors["message"] = "Message is required";
    } else {
        $message = $_POST["message"];
        $errors["message"] = "";
    }

    if (!array_filter($errors)) {
        if (mail("gimhanofficial@outlook.com", $subject, $message)) {
            $email = $subject = $message = "";
            $sent = "Message sent\nThank you!";
        } else {
            $sent = "Message not sent!";
        }
    }
}

?>

<div class="width100 background-img7 height400px col justify-center">
    <div class="f-xxxlarge bold lighter">
        Contact Us
    </div>
</div>
<br>

<div class="row space-between padding-h40 gap40 align-start contact-content">
    <div class="dark f-large">
        We value your feedback and inquiries and are happy to assist you with any questions or concerns you may have.
        <br>
        <br>
        To get in touch with us, you can fill out the form provided on this page with your name, email address, and a brief message detailing your inquiry. One of our customer service representatives will get back to you as soon as possible.
        <br>
        <br>
        You can also reach us by phone at +94 70 590 2081 during business hours (Monday-Friday, 9am-5pm EST).
        <br>
        <br>
        We look forward to hearing from you and working with you to resolve any issues or answer any questions you may have. Thank you for choosing our company, and we hope to hear from you soon!
    </div>
    <form class="card col padding-h60 align-start margin-auto" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <br>
        <h2 class="primary margin-auto">Contact Form</h2>
        <hr class="width100">
        <br>
        <div class="f-large col align-start gap10">
            <label class="dark" for="email">Email</label>
            <input class="input" type="email" name="email" id="email" value="<?php echo $email ?>">
            <div class="red"><?php echo $errors["email"] ?></div>
        </div>
        <br>
        <div class="f-large col align-start gap10">
            <label class="dark" for="organizedBy">Subject</label>
            <input class="input" type="text" name="subject" id="subject" value="<?php echo $subject ?>">
            <div class="red"><?php echo $errors["subject"] ?></div>
        </div>
        <br>
        <div class="f-large col align-stretch gap10">
            <label class="dark" for="message">Message</label>
            <textarea class="input" name="message" id="message" cols="30" rows="10"><?php echo $message ?></textarea>
            <div class="red"><?php echo $errors["message"] ?></div>
        </div>
        <br>
        <input class="button margin-auto" type="submit" name="submit" value="Send">
        <?php if ($sent == "") : ?>
        <?php elseif ($sent == "sent") : ?>
            <br>
            <div class="primary f-large bold margin-auto">
                Message Sent <br> Thank You!
            </div>
        <?php else : ?>
            <br>
            <div class="red f-large bold margin-auto">
                Message Not Sent!
            </div>
        <?php endif; ?>
        <br>
    </form>
</div>
<br>

<?php include "../components/footer.php" ?>


</html>