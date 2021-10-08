<?php 
require_once("classes/functions.class.php");
require_once('classes/login.class.php');
include "config/config.php";
 $login = new login();
 $func = new functions();
global $db, $password_salt;

// Register
if (isset($_POST['register'])) {
    $username = $db->real_escape_string($_POST['username']);
    $password = $db->real_escape_string($_POST['password']);
    $email = $db->real_escape_string($_POST['email']);

    if (empty(trim($_POST['username']))) {
        $ErrorMessage = "Please Enter A Username!";
        die($ErrorMessage);
    } else {
        try {
            $find = $db->query("SELECT id FROM users WHERE username = '{$username}'");
            if ($find->num_rows > 0) {
                $ErrorMessage = "Username Is Already Taken";
                die($ErrorMessage);
            }
        } catch (Exception $e) {
            $ErrorMessage = "A Unkown Error Has Occurred";
            die($ErrorMessage);
        }
    }

    if (strlen($password) < 6) {
        $ErrorMessage = "Please Enter Password Which Is More Than 6 Characters!";
        die($ErrorMessage);
    }

    if (!isset($ErrorMessage)) {
        try {
            $passwordHash = sha1(md5($username.$password."CRYPT6969"));
            // Prepare Statement
            $sql = "INSERT INTO users (username, email, password) VALUES(?, ?, ?)";
            $insert = $db->prepare($sql);
            $insert->bind_param("sss", $username, $email, $passwordHash); // S = String | I = Int
            $insert->execute();
            if ($insert) {
                $SuccessMessage = $username."Has Successfully Been Registered!";
                die($SuccessMessage);
            } else {
                $ErrorMessage = "Something Went Wrong While Registering!";
                die($ErrorMessage);
            }
        } catch (Exception $e) {
            $ErrorMessage = $e;
            die($ErrorMessage);
        }
    }
}
 

// LOGIN

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    try {
        $fetch = $login->GetLogin($username, $password);
         if ($fetch) {
             header("location: examplehome.php");
         } else {
             die("Invalid Credentials");
         }
    } catch (Exception $e) {
        die($e);
    }
}


?>  




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Dev Site | Login / Register</title>
</head>
<body>
    <style>
    body {
        background-image:linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url(../8bit.gif);
        color: #ffffff;
    }
        .formgroup {
            width: 520px;
            height: 510px;
            top:50%;
            left: 50%;
            position: absolute;
            padding: 30px, 30px;
           border-radius: 10px;
           background-image:linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url(../back.gif);
           transform: translate(-50%, -50%);
           box-sizing: border-box;
        }
        .inside {
            width: 250px;
            height: 70px;
            text-align: center;
            transform: translate(55%,200%);
            
        }
        .frminput { 
            border: none;
            outline: none;
            border-bottom: 1px solid blue;
            background: transparent;
            margin: 1%;
            padding: 10px;
            color: white;
        }
        .frminput:focus {
            animation: expand_center 700ms;
            animation-fill-mode: forwards;
        }
        .byt {
            margin: 2%;
        }

        @keyframes expand_center {
          0% {
          border-bottom: 1px solid #ff0004;
          clip-path: polygon(50% 100%,50% 0,50% 0,50% 100%);
            -webkit-clip-path: polygon(50% 100%,50% 0,50% 0,50% 100%);
         }
          100%{
           border-bottom: 1px solid purple;
          clip-path: polygon(0 100%, 0 0, 100% 0, 100% 100%);
            -webkit-clip-path: polygon(0 100%, 0 0, 100% 0, 100% 100%);
          }
        }

    </style>
    <form method="post">
        <div class="formgroup">
        <div class="inside">
            <input type="text" name="username" class="frminput" placeholder="Username">
            <input type="password" name="password" class="frminput" placeholder="Password">
            <input type="email" name="email"  class="frminput" placeholder="Email">
            <div class="buttons">
                <input type="submit" value="Login" class="btn btn-primary byt" name="login">
                <input type="submit" value="Register" class="btn btn-primary byt" name="register">
            </div>
        </div>
        </div>
    </form>

</body>
</html>