<?php
    require "header_template.php";
    require "database.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $userID = $_POST["user_id"];
        $email = $_POST["email"];
        $eventID = $_POST["event_id"];
        $numberticket = $_POST["ticket"];

        connect_db();
        try{
            event_registration($userID, $email, $eventID, $numberticket);
        }
        catch(mysqli_sql_exception){
            $_SESSION["error_message"] = "Error registering for the event. Please try again.";
        }
        close_db();
    }

?>

<!DOCTYPE html>
<html>
    <body>
        <main>
            <form action="eventreg.php" method="POST">

                <p>
                    <label for="user_id">Member ID: </label>
                    <input type="text" id="user_id" name="user_id" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']->id : ''; ?>" readonly/>
                </p>

                <p>
                    <label for="email">Email: </label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']->email : ''; ?>" placeholder="xxx@xxx.com" readonly>
                </p>

                <p>
                    <label for="event_id">Event: </label>
                    <select id="event_id" name="event_id" required>
                        <?php
                            dropdown_events();
                        ?>
                    </select>
                </p>

                <p>
                    <label for="ticket">Number of Ticket(s): </label>
                    <input type="number" id="ticket" name="ticket" value="1" min="1" required>
                </p>

                <button type="submit" id="submit">Register</button>
                <input type="reset"><br><br><br>

                <a href="refund.php">
                    <button type="button">Refund</button>
                </a>

            </form>
            <?php
                if(isset($_SESSION["error_message"])){
                    $error_message = $_SESSION["error_message"];
                    echo "<div style=\"color: red;\"> {$error_message}; </div>";
                    unset($_SESSION["error_message"]);
                }
            ?>
        </main>
    </body>
</html>