<?php
    $DBHOST = "localhost:3306";
    $DBUSERNAME = "root";
    $DBPASSWORD = "";
    $DB = "secure_auth";

    $db = new mysqli($DBHOST, $DBUSERNAME, $DBPASSWORD, $DB);
    if($db->connect_error) {
        die("Failed To Connect To Database! <br> Error: ".$db->connect_error."");
    }

?>   