<?php
    require "header_template.php";
    require "database.php";

    display_events();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $index = $_POST["button"];
        $events = $_SESSION["events"];
        echo "<script>alert(\"{$events[$index]->name} has {$events[$index]->availability} places left!\")</script>";
    }
?>

<script>
    function register(){
        window.location.href = "eventreg.php";
    }
</script>