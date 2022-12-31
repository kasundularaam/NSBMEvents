<!DOCTYPE html>
<html lang="en">

<?php $page = "About Us";
include("../components/header.php"); ?>

<?php

$team = array(
    ["name" => "Gimhan Ramanayaka", "linkedin" => "http://linkedin.com", "about" => "Hi, my name is Gimhan Ramanayaka and I am an undergraduate student at NSBM Green University, pursuing a BSc Hons in Computer Science", "img" => "../images/team/1.png"],
    ["name" => "Akalanka Rathnayaka", "linkedin" => "http://linkedin.com", "about" => "Hi, my name is Akalanka Rathnayaka and I am an undergraduate student at NSBM Green University, pursuing a BSc Hons in Computer Science", "img" => "../images/team/2.png"],
    ["name" => "Devindi  Tharudini", "linkedin" => "http://linkedin.com", "about" => "Hi, my name is Devindi  Tharudini and I am an undergraduate student at NSBM Green University, pursuing a BSc Hons in Computer Science", "img" => "../images/team/3.png"],
    ["name" => "Thisara Navod", "linkedin" => "http://linkedin.com", "about" => "Hi, my name is Thisara Navod and I am an undergraduate student at NSBM Green University, pursuing a BSc Hons in Computer Science", "img" => "../images/team/4.png"],
    ["name" => "Raveena Silva", "linkedin" => "http://linkedin.com", "about" => "Hi, my name is Raveena Silva and I am an undergraduate student at NSBM Green University, pursuing a BSc Hons in Computer Science", "img" => "../images/team/5.png"],
    ["name" => "Sahan Hirushan", "linkedin" => "http://linkedin.com", "about" => "Hi, my name is Sahan Hirushan and I am an undergraduate student at NSBM Green University, pursuing a BSc Hons in Computer Science", "img" => "../images/team/6.png"],
    ["name" => "Punara Manodya", "linkedin" => "http://linkedin.com", "about" => "Hi, my name is Punara Manodya and I am an undergraduate student at NSBM Green University, pursuing a BSc Hons in Computer Science", "img" => "../images/team/7.png"],
);



?>

<div class="width100 background-img6 height400px col justify-center">
    <div class="f-xxxlarge bold lighter">
        About Us
    </div>
</div>
<br>
<div class="padding-h40">

    <div class="primary bold f-xxlarge">
        Who We Are
    </div>
    <br>
    <div class="dark f-large">
        We are a group of seven university students who are passionate about using our skills and knowledge to make a positive impact in the world. Our company was founded as a way for us to combine our diverse backgrounds and talents to create innovative solutions to real-world problems. We are committed to using our resources responsibly and working collaboratively to achieve our goals. We are excited to have the opportunity to share our ideas and work with others who share our passion for making a difference
    </div>
    <br>
    <div class="primary bold f-xxlarge">
        Our Team
    </div>
    <br>
    <div class="grid grid-col5 gap20 team-grid">

        <?php foreach ($team as $member) : ?>
            <div class="card col">
                <img class="width100 height600px cover member-image" src="<?php echo $member["img"]; ?>" alt="">
                <br>
                <div class="col padding-h20">

                    <div class="f-xlarge bold dark"><?php echo $member["name"]; ?></div>
                    <hr class="width100">
                    <div class="dark f-medium text-center">
                        <?php echo $member["about"]; ?>
                    </div>

                </div>
                <br>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<br>

<?php include "../components/footer.php" ?>

</html>