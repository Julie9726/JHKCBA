<?php
    class User {
        public $firstname;
        public $lastname;
        public $email;
        public $password;
        public $id;
        public $type;

        public function __construct($firstname, $lastname, $email, $password, $id, $type){
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->email = $email;
            $this->password = $password;
            $this->id = $id;
            $this->type = $type;
        }
    }

    function validation($email, $password){
        global $connection;
        $stmt = $connection->prepare("select * from user where email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();

        if ($res && password_verify($password, $res['password'])){
            if (isset($_SESSION["error_message"])){
                unset($_SESSION["error_message"]);
            }
            $user = new User($res['firstname'], $res['lastname'], $res['email'], $res['password'], $res['id'], $res['type']);
            $_SESSION["user"] = $user;
            header("Location: home.php");
            exit();
        }
        else{
            $_SESSION["error_message"] = "Invalid username or password. Please try again.";
        }
    }

    function reg_user($firstname, $lastname, $email, $type, $password){
        global $connection;
        $stmt = $connection->prepare("insert into user (firstname, lastname, email, password, type) values (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $lastname, $email, $password, $type);
        $result = $stmt->execute();

        if ($result === true){
            $id = $stmt->insert_id;
            if (isset($_SESSION["error_message"])){
                unset($_SESSION["error_message"]);
            }
            $user = new User($firstname, $lastname, $email, $password, $id, $type);
            $_SESSION["user"] = $user;
            $_SESSION["alert"] = "<script>alert(\"Registration Successful! Email sent to {$email}, User ID: {$id}\")</script>";
            header("Location: home.php");
            exit();
        }
        else {
            $_SESSION["error_message"] = "Error creating account. Please try again.";
        }
    }

    function get_execs(){
        global $connection;
        $result = $connection->query("select * from user where type='executive'");
        $execs = [];
        while($row = $result->fetch_assoc()) {
            $exec = new User($row['firstname'], $row['lastname'], $row['email'], $row['password'], $row['id'], $row['type']);
            $execs[] = $exec;
        }
        $_SESSION["execs"] = $execs;
    }

    function dropdown_execs(){
        $execs = $_SESSION["execs"];
        foreach ($execs as $e){
            echo "<option value={$e->firstname}>{$e->firstname} {$e->lastname}</option>";
        }
    }

    function save_question($email, $type, $execname, $question){
        global $connection;
        $execs = $_SESSION["execs"];
        foreach ($execs as $e){
            if ($e->firstname == $execname){
                $execemail = $e->email;
            }
        }
        $stmt = $connection->prepare("insert into contact (email, type, execname, execemail, question) values (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $type, $execname, $execemail, $question);
        $result = $stmt->execute();

        if ($result === true){
            $id = $stmt->insert_id;
            if (isset($_SESSION["error_message"])){
                unset($_SESSION["error_message"]);
            }
            $_SESSION["alert"] = "<script>alert(\"Question Sent! Email sent to {$execname} at {$execemail}\")</script>";
            header("Location: home.php");
            exit();

        }
    }

    function save_feedback($uid, $eid, $feedback){
        global $connection;

        $stmt = $connection->prepare("insert into feedback (userid, eventid, feedback) values (?, ?, ?)");
        $stmt->bind_param("iis", $uid, $eid, $feedback);
        $result = $stmt->execute();

        if ($result === true){
            $id = $stmt->insert_id;
            if (isset($_SESSION["error_message"])){
                unset($_SESSION["error_message"]);
            }
            $_SESSION["alert"] = "<script>alert(\"Feedback Sent! Thank You for coming to our event!\")</script>";
            header("Location: home.php");
            exit();

        }
    }
?>