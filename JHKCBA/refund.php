<?php
    require "header_template.php";
    require "database.php";

    connect_db();
    load_registrations();
    close_db();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $rid = $_POST["rid"];

        connect_db();
        try{
            refund($rid);
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
            <form action="refund.php" method="POST">
                <p>
                    <label for="user_id">Member ID: </label>
                    <input type="text" id="user_id" name="user_id" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']->id : ''; ?>" readonly/>
                </p>

                <p>
                    <label for="email">Email: </label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']->email : ''; ?>" placeholder="xxx@xxx.com" readonly>
                </p>
                <p>
                    <label for="rid">Your Registrations: </label>
                    <select id="rid" name="rid" required>
                        <?php 
                            userregs_dropdown();
                        ?>
                    </select>
                </p>
                    <button type="submit">Refund</button>
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

