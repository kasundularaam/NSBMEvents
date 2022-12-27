<?php

function isActive($active)
{
    global $page;
    if ($active == $page) {
        echo "class='active'";
    }
}

session_start();
$sessionIndexNo = $_SESSION["indexNo"] ?? "guest";
?>

<nav class="nav-bar">
    <h2> <a href="./index.php">NSBM EVENTS</a></h2>
    <ul>
        <li> <a href='./index.php' <?php isActive("NSBM Events"); ?>>Home</a> </li>
        <li><a href="./about_us.php" <?php isActive("About Us"); ?>>About Us</a></li>
        <li><a href="./contact_us.php" <?php isActive("Contact Us"); ?>>Contact Us</a></li>

        <?php if ($sessionIndexNo == "guest") : ?>
            <li><a href="./login.php" <?php isActive("Log In"); ?>>Log In</a></li>
        <?php elseif ($sessionIndexNo == "Admin") : ?>
            <li><a href="./admin_profile.php" <?php isActive("Profile"); ?>><?php echo $sessionIndexNo ?></a></li>
        <?php else : ?>
            <li><a href="./profile.php" <?php isActive("Profile"); ?>><?php echo $sessionIndexNo ?></a></li>
        <?php endif; ?>
    </ul>
</nav>