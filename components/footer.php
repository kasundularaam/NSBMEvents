<?php
global $sessionIndexNo;
?>


<!-- PRESENTATION -->


<footer class="bg-darker light">
    <br>
    <div class="padding-h40">
        <div class="row gap40">
            <div class="col align-start flex1">
                <h3 class="shaded">About</h3>
                <br>
                <p class="dark">Scanfcode.com <i>CODE WANTS TO BE SIMPLE </i> is an initiative to help the upcoming programmers with the code. Scanfcode focuses on providing the most efficient code or snippets as the code wants to be simple. We will help programmers build up concepts in different programming languages that include C, C++, Java, HTML, CSS, Bootstrap, JavaScript, PHP, Android, SQL and Algorithm.</p>
            </div>

            <div class="col align-start">
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