<?php
    class Event{
        public $id;
        public $name;
        public $datetime;
        public $availability;
        public $location;
        public $ticketprice;
        public $details;

        public function __construct($id, $name, $datetime, $availability, $location, $ticketprice, $details){
            $this->id = $id;
            $this->name = $name;
            $this->datetime = $datetime;
            $this->availability = $availability;
            $this->location = $location;
            $this->ticketprice = $ticketprice;
            $this->details = $details;
        }
    }

    class EventReg{
        public $uid;
        public $email;
        public $eid;
        public $numberticket;
        public $rid;
        public $totalprice;
        public $status;

        public function __construct($uid, $email, $eid, $numberticket, $rid, $totalprice, $status){
            $this->uid = $uid;
            $this->email = $email;
            $this->eid = $eid;
            $this->numberticket = $numberticket;
            $this->rid = $rid;
            $this->totalprice = $totalprice;
            $this->status = $status;

        }
    }

    function load_events(){
        global $connection;
        $result = $connection->query("select * from event");
        $events = [];
        while($row = $result->fetch_assoc()) {
            $event = new Event($row['id'], $row['name'], $row['datetime'], $row['availability'], $row['location'], $row['ticketprice'], $row['details']);
            $events[] = $event;
        }
        $_SESSION["events"] = $events;
    }

    function display_events(){
        $events = $_SESSION["events"];
        echo "<div>";
        $count = 0;
        foreach ($events as $e){
            echo "<div class=\"event\">";
            echo "<p> Event ID: {$e->id} </p>";
            echo "<p> Event Name: {$e->name} </p>";
            echo "<p> Event Time: {$e->datetime} </p>";
            echo "<p> Event Location: {$e->location} </p>";
            echo "<p> Event Details: {$e->details} </p>";
            echo "<form action=\"event.php\" method=\"POST\"><button type=\"submit\" name=\"button\" value=\"{$count}\">Check Availability</button></form>";
            echo "<button onclick=\"register()\">Register</button>";
            echo "</div>";
            $count += 1;
        }
        echo "</div>";
    }

    function dropdown_events(){
        $events = $_SESSION["events"];
        foreach ($events as $e){
            echo "<option value={$e->id}> {$e->id}: {$e->name}</option>";
        }
    }

    function event_registration($uid, $email, $eid, $numberticket){
        global $connection;
        $events = $_SESSION["events"];
        foreach ($events as $e){
            if ($e->id == $eid){
                if ($e->availability >= $numberticket){
                    $totalprice = $e->ticketprice * $numberticket;
                    $status = "Unpaid";
                    $stmt = $connection->prepare("insert into eventreg (userid, email, eventid, numberticket, totalprice, status) values (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isiids", $uid, $email, $eid, $numberticket, $totalprice, $status);
                    $result = $stmt->execute();

                    if ($result === true){
                        $rid = $stmt->insert_id;
                        if (isset($_SESSION["error_message"])){
                            unset($_SESSION["error_message"]);
                        }
                        $eventreg = new EventReg($uid, $email, $eid, $numberticket, $rid, $totalprice, $status);
                        $_SESSION["EventReg"] = $eventreg;
                        header("Location: payment.php");
                        exit();
                    }
                    else {
                        $_SESSION["error_message"] = "Error registering for the event. Please try again.";
                    }
                }
                else {
                    $_SESSION["error_message"] = "Not enough tickets left. Please try again";
                }
            }
        }
    }

    function payment(){
        global $connection;
        if (isset($_SESSION["EventReg"])){
            $regEvent = $_SESSION["EventReg"];
            $stmt1 = $connection->prepare("update eventreg set status = 'Paid' where regid = ?");
            $stmt1->bind_param("i", $regEvent->rid);

            $stmt2 = $connection->prepare("update event set availability = availability - ? where id = ?");
            $stmt2->bind_param("ii", $regEvent->numberticket, $regEvent->eid);

            $result1 = $stmt1->execute();
            $result2 = $stmt2->execute();

            if ($result1 === true && $result2 === true){
                if (isset($_SESSION["error_message"])){
                    unset($_SESSION["error_message"]);
                }
                $_SESSION["alert"] = "<script>alert(\"Payment Successful! Email sent to {$regEvent->email}, Registration ID: {$regEvent->rid}\")</script>";
                header("Location: home.php");
                exit();
            }
        }
        else{
            $_SESSION["error_message"] = "Payment Error.";
        }
    }
    
    function load_registrations(){
        global $connection;
        $uid = $_SESSION["user"]->id;
        $stmt = $connection->prepare("select * from eventreg where status = 'Paid' and userid = ?");
        $stmt->bind_param("i", $uid);
        $res = $stmt->execute();
        if ($res === true){
            $userRegs = [];
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                $userReg = new EventReg($row['userid'], $row['email'], $row['eventid'], $row['numberticket'], $row['regid'], $row['totalprice'], $row['status']);
                $userRegs[] = $userReg;
            }
        $_SESSION["userRegs"] = $userRegs;
        }
    }

    function userregs_dropdown(){
        $userRegs = $_SESSION["userRegs"];
        $events = $_SESSION["events"];
        foreach ($userRegs as $ur){
            foreach ($events as $e){
                if ($ur->eid == $e->id){
                    $event = $e;
                    break;
                }
            }
            echo "<option value={$ur->rid}> {$event->name} : {$ur->numberticket} tickets for a total of {$ur->totalprice}$</option>";
        }
    }

    function refund($rid){
        global $connection;
        $stmt1 = $connection->prepare("update eventreg set status = 'Refunded' where regid = ?");
        $stmt1->bind_param("i", $rid);

        $userRegs = $_SESSION["userRegs"];
        foreach($userRegs as $ur){
            if ($ur->rid == $rid){
                $userReg = $ur;
            }
        }

        $stmt2 = $connection->prepare("update event set availability = availability + ? where id = ?");
        $stmt2->bind_param("ii", $userReg->numberticket, $userReg->eid);

        $result1 = $stmt1->execute();
        $result2 = $stmt2->execute();

        if ($result1 === true && $result2 === true){
            if (isset($_SESSION["error_message"])){
                unset($_SESSION["error_message"]);
            }
            $_SESSION["alert"] = "<script>alert(\"Refund Successful! Email sent to {$userReg->email}\")</script>";
            header("Location: home.php");
            exit();
        }
    }
?>