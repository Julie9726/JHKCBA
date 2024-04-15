<?php
    require "header_template.php";
    require "database.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $email = $_POST["email"];
        $type = $_POST["user_type"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        connect_db();
        try{
            reg_user($firstname, $lastname, $email, $type, $password);
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
            <form action="userreg.php" method="POST">
                <p>
                    <label for="firstname">First name: </label>
                    <input type="text" id="firstname" name="firstname" placeholder="John" required/>
                </p>

                <p>
                    <label for="lastname">Last name: </label>
                    <input type="text" id="lastname" name="lastname" placeholder="Doe" required/>
                </p>

                <p>
                    <label for="email">Email: </label>
                    <input type="email" id="email" name="email" placeholder="xxx@xxx.com" required/>
                </p>

                <p>
                    <label for="user_type">User Type: </label>
                    <select id="user_type" name="user_type" required>
                        <option value="Member">Member</option>
                        <option value="Sponsor">Sponsor</option>
                    </select>
                </p>

                <p>
                    <label for="password">Password: </label>
                    <input type="password" id="password" name="password" oninput="check();" required>
                </p>

                <p>
                    <label for="confirm_password">Confirm Password: </label>
                    <input type="password" id="confirm_password" oninput="check();" required>
                    <span id='message'></span>
                </p>

                <button type="submit" id="submit">Register</button>
                <input type="reset"><br>
            </form>

            <?php
                if(isset($_SESSION["error_message"])){
                    $error_message = $_SESSION["error_message"];
                    echo "<div style=\"color: red;\"> {$error_message}; </div>";
                    unset($_SESSION["error_message"]);
                }
            ?>

            <script>
                function check(){
                    var pass = document.getElementById("password");
                    var cpass = document.getElementById("confirm_password");
            
                    if (pass.value == cpass.value){
                        document.getElementById("message").innerHTML = "";
                        document.getElementById("submit").disabled = false;
                    }
                    else{
                        document.getElementById("message").innerHTML = "Password does not match";
                        document.getElementById("message").style.color = "red";
                        document.getElementById("submit").disabled = true;
                    }
                }
            </script>
        </main>
    </body>
</html>