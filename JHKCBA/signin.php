<?php
    require "header_template.php";
    require "database.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $email = $_POST["email"];
        $password = $_POST["password"];

        connect_db();

        validation($email, $password);

        close_db();
    }
?>

<!DOCTYPE html>
<html>
    <body>
        <main>
            <form action="signin.php" method="POST">
                <p>
                    <label for="email">Email: </label>
                    <input type="email" id="email" name="email" required>
                </p>

                <p>
                    <label for="password">Password: </label>
                    <input type="password" id="password" name="password" required>
                </p>

                <span>
                    Don't have an account?
                    <a href="userreg.php">
                        <button type="button">Register Now</button>
                    </a>
                </span><br><br>
                
                <button type="submit">Sign In</button>
                <input type="reset"><br>
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

<?php
    
?>