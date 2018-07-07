<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    session_start();

    if(!isset($_SESSION["username"])){
        header("location: ../login.php");
        die();
    }
    else{
        require_once "../utils/databaseConnect.php";
        $username = $_SESSION["username"];
        $query = $connection->prepare("SELECT accessLevel FROM users WHERE username = BINARY :username");
        $query->bindParam(":username",$username);
        $query->execute();
        $result = $query->fetch();
        
        if($result == NULL){
            echo "Error: Invalid username"; // This should never happen
        }

        if($result[0] === "admin"){
            $_SESSION["accessLevel"] = "admin";
            header("location: main.php");
        }
        else{
            echo "Sorry, you don't have a permission to access this page.<br /><a href='../index.php'>Go back</a>";
        }
    }
?>