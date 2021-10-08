<?php 
session_start();
require_once('classes/functions.class.php');


class login {
    function GetLogin($username, $password) {
        global $db, $password_salt;
        $func = new functions();
        $password = sha1(md5($username.$password."TEST1337"));

        $finduser = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $finduser->bind_param("ss", $username, $password);
        $finduser->execute();
        $results = $finduser->get_result();
        if ($results->num_rows > 0) {
            // Found Existing Record
            $data = $results->fetch_assoc();
            $INSERTHASH = $db->prepare("INSERT INTO sessions (user_id, hash, ip) VALUES(?, ?, ?)");
            $INSERTHASH->bind_param("iss", $data['id'], $this->CreateUserHash($username), $func->GetUserIp());
            if ($INSERTHASH) {
                $_SESSION['id'] = intval($data['id']);
                $_SESSION['username'] = $data['username'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['password'] = $data['password'];
                $_SESSION['loggedin'] = true;
                return true;
            } else {
                return false;
            }
        }
       
    }

    function Logout() {
        session_destroy();
    }

    function CreateUserHash($username) {
        global $db;
        $num = rand(1, 99999);
        $hash = md5($username.$num);
        return $num;
    }

     function register($username, $email, $password) {
         global $db, $password_salt;
         $checked = $db->query("SELECT * FROM users");
         if ($checked->num_rows > 0) {
            $chk = $checked->fetch_assoc();
            if ($chk['username']) {
             }
         }
         $password = sha1(md5($username.$password.$password_salt));
         $insertuser = $db->prepare("INSERT INTO users (username, email, password) VALUES(?, ?, ?)");
         $insertuser->bind_param("sss", $username, $email, $password);
         $insertuser->execute();
         if ($insertuser) {
             return true;
         } else {
             return false;
         }
     }
 }

?>