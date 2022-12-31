<?php
global $sessionIndexNo;
?>


<!-- PRESENTATION -->


<footer class="bg-darker light">
    <br>
    <div class="padding-h40">
        <div class="row gap40">
            <div class="col align-start flex1 footer-about">
                <h3 class="shaded">About</h3>
                <br>
                <p class="dark">green tickets is the first Lanka's ticket selling platform in the university level.We are the official marketplace provide you with a secure and safe platform to buy and sell tickets in NSBM Green University, If you are looking for tickets for upcoming events green ticket is the place to find them by giving our customers the maximum service with a new experience in online ticket purchasing.We're constantly rolling out new features to improve our service with an outstanding selection of tickets.</p>
            </div>

            <div class="col align-start footer-nav">
                <h3 class="shaded">Quick Links</h3>
                <br>
                <ul class="no-style-type col gap10 align-start">
                    <li><a class="dark" href="./index.php">Home</a></li>
                    <li><a class="dark" href="./about_us.php">About Us</a></li>
                    <li><a class="dark" href="./about_nsbm.php">About NSBM</a></li>
                    <li><a class="dark" href="./contact_us.php">Contact Us</a></li>
                    <?php if ($sessionIndexNo == "Guest") : ?>
                        <li><a class="dark" href="./login.php">Log In</a></li>
                    <?php elseif ($sessionIndexNo == "Admin") : ?>
                        <li><a class="dark" href="./admin_profile.php">Admin</a></li>
                    <?php else : ?>
                        <li><a class="dark" href="./profile.php">Profile</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <hr class="hr-dark">

        <div class="shaded text-center f-medium">
            Copyright &copy; 2022 All Rights Reserved by <a class="primary" href="./index.php"> nsbmevents.com</a>
        </div>
        <br>
    </div>
</footer>

</body>

</html>