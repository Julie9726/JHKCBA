<?php
//define var + define function to connect to database
?>

<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "JHKCBA";
    $connection = "";

    function connect_db(){
        global $db_server, $db_user, $db_password, $db_name, $connection;
        try{
            $connection = mysqli_connect($db_server, $db_user, $db_password, $db_name);
        }
        catch(mysqli_sql_exception){
            die("Could not connect to database {$db_name}");
        }
    }

    function close_db(){
        global $connection;
        if ($connection){
            mysqli_close($connection);
        }
        else{
            echo "not open";
        }
    }
?>