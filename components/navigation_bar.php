<?php

function isActive($active)
{
    global $page;
    if ($active == $page) {
        echo "class='nav-bar-active'";
    }
}

session_start();
$sessionIndexNo = $_SESSION["indexNo"] ?? "Guest";
?>

<nav class="row space-between padding-h40 padding-v20 top-nav">
    <h2> <a href="./index.php">NSBM EVENTS</a></h2>
    <ul class="no-style-type row gap20 top-nav-links">
        <li><a href='./index.php' <?php isActive("NSBM Events"); ?>>Home</a> </li>
        <li><a href="./about_us.php" <?php isActive("About Us"); ?>>About Us</a></li>
        <li><a href="./about_nsbm.php" <?php isActive("About NSBM"); ?>>About NSBM</a></li>
        <li><a href="./contact_us.php" <?php isActive("Contact Us"); ?>>Contact Us</a></li>

        <?php if ($sessionIndexNo == "Guest") : ?>
            <li><a href="./login.php" <?php isActive("Log In"); ?>>Log In</a></li>
        <?php elseif ($sessionIndexNo == "Admin") : ?>
            <li><a href="./admin_profile.php" <?php isActive("Profile"); ?>><?php echo $sessionIndexNo ?></a></li>
        <?php else : ?>
            <li><a href="./profile.php" <?php isActive("Profile"); ?>>Profile</a></li>
        <?php endif; ?>
    </ul>
</nav>