<?php
    require "header_template.php";
    require "database.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $uid = $_POST["user_id"];
        $eid = $_POST["event"];
        $feedback = $_POST["feedback"];

        connect_db();
        try{
            save_feedback($uid, $eid, $feedback);
        }
        catch(mysqli_sql_exception){
            $_SESSION["error_message"] = "Error creating account. Please try again.";
        }
        close_db();
    }
?>

<!DOCTYPE html>
<html>
    <body>
        <main>
            <form action="feedback.php" method="POST">
                <p>
                    <label for="user_id">Member ID: </label>
                    <input type="text" id="user_id" name="user_id" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']->id : ''; ?>" readonly/>
                </p>


                <p>
                    <label for="event">Attended Event: </label>
                    <select id="event" name="event" required>
                        <?php
                            dropdown_events();
                        ?>
                    </select>
                </p>

                <p>
                    <label for="feedback">Feedback: </label>
                    <textarea id="feedback" name="feedback" required></textarea>
                </p>

                <button type="submit" id="submit">Send</button>
                <input type="reset"><br>

            </form>
        </main>
    </body>
</html>

<?php
    
?>