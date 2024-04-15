<?php
    require "header_template.php";
    require "database.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        connect_db();
        try{
            payment();
        }
        catch(mysqli_sql_exception){
            $_SESSION["error_message"] = "Error when making the payment. Please try again.";
        }
        close_db();
    }
?>
<!DOCTYPE html>
<html>
    <body>
        <main>
            <form action="payment.php" method="POST">
                <p>
                    <label for="card_number">Card Number:</label>
                    <input type="text" id="card_number" name="card_number" pattern="[0-9]*" minlength="16" maxlength="16" placeholder="16 digit card number" required>
                </p>

                <p>
                    <label for="expiry_date">Expiry Date:</label>
                    <input type="text" id="expiry_date" name="expiry_date" pattern="(0[1-9]|1[0-2])\/20(2[4-9]|[3-9][0-9])" placeholder="MM/YYYY" required>
                </p>

                <p>
                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" pattern="[0-9]*" minlength="3" maxlength="3" placeholder="CVV" required>
                </p>

                <p>
                    <label for="cardholder_name">Cardholder Name:</label>
                    <input type="text" id="cardholder_name" name="cardholder_name" required>
                </p>

                <p>
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" min="1" value="<?php echo isset($_SESSION['EventReg']) ? $_SESSION['EventReg']->totalprice : '0'; ?>" readonly>
                </p>

                    <button type="submit" <?php echo isset($_SESSION['EventReg']) ? "" : "disabled"; ?>>Pay Now</button>
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
