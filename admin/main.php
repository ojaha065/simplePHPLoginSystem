<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    session_start();

    if(!isset($_SESSION["username"]) || !isset($_SESSION["accessLevel"])){
        header("location: ../login.php");
        die();
    }
    elseif($_SESSION["accessLevel"] === "admin"){
        echo "This will be the front page of the admin panel.";
    }
    else{
        header("location: index.php");
        die();
    }
?>