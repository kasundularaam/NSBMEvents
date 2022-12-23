<?php

function isActive($active)
{
    global $page;
    if ($active == $page) {
        echo "class='active'";
    }
}

session_start();
$indexNo = $_SESSION["indexNo"] ?? "guest";
?>

<nav class="nav-bar">
    <h2> <a href="./index.php">NSBM EVENTS</a></h2>
    <ul>
        <li> <a href='./index.php' <?php isActive("NSBM Events"); ?>>Home</a> </li>
        <li><a href="./about_us.php" <?php isActive("About Us"); ?>>About Us</a></li>
        <li><a href="./contact_us.php" <?php isActive("Contact Us"); ?>>Contact Us</a></li>

        <?php if ($indexNo == "guest") : ?>
            <li><a href="./sign_in.php" <?php isActive("Sign In"); ?>>Sign In</a></li>
        <?php else : ?>
            <li><a href="./profile.php" <?php isActive("Profile"); ?>><?php echo $indexNo ?></a></li>
        <?php endif; ?>
    </ul>
</nav>