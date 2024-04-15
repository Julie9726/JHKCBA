<?php
    require "header_template.php";
    require "database.php";

    connect_db();
    get_execs();
    close_db();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $email = $_POST["email"];
        $type = $_POST["user_type"];
        $execname = $_POST["executive"];
        $question = $_POST["question"];

        connect_db();
        try{
            save_question($email, $type, $execname, $question);
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
            <form action="contact.php" method="POST">
                <p>
                    <label for="email">Email: </label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']->email : ''; ?>" placeholder="xxx@xxx.com" readonly>
                </p>


                <p>
                    <label for="user_type">User Type: </label>
                    <input type="user_type" id="user_type" name="user_type" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']->type : ''; ?>" readonly>
                </p>

                <p>
                    <label for="executive">Executive: </label>
                    <select id="executive" name="executive" required>
                        <?php 
                            dropdown_execs();
                        ?>
                    </select>
                </p>

                <p>
                    <label for="question">Question: </label>
                    <textarea id="question" name="question" required></textarea>
                </p>

                <button type="submit" id="submit">Send</button>
                <input type="reset"><br>

            </form>
        </main>
    </body>
</html>

<?php
    
?>