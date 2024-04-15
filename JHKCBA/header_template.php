<?php
    require "user.php";
    require "event_class.php";
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JHKCBA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1><a href="home.php">JHKCBA</a></h1>
        <nav class="navbar">
            <?php 
                if (isset($_SESSION["user"])){
                    $user = $_SESSION["user"];
                    echo "<a>Welcome {$user->firstname}</a>";
                    echo "<a href=\"signout.php\">Sign out</a>";
                }
                else{
                    echo "<a href=\"signin.php\">Sign in</a>";
                }
            ?>
            <a href="event.php">Event</a>
            <a href="eventreg.php">Event Registration</a>
            <a href="contact.php">Contact Us</a>
            <a href="feedback.php">Feedback</a>
        </nav>
    </header>

</body>
</html>