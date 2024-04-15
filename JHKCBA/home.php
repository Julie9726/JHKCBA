<?php
    require "header_template.php";
    require "database.php";
    connect_db();
    load_events();
    close_db();
?>

<?php
    if (isset($_SESSION["alert"])){
        echo $_SESSION["alert"];
        unset($_SESSION["alert"]);
    }
    if (isset($_SESSION["EventReg"])){
        unset($_SESSION["EventReg"]);
    }
    echo "hello";
?>