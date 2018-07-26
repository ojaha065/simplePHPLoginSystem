<?php

    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    require_once "credentials.php";

    try{
        $connection = "mysql:host=$host:$port;dbname=$databaseName";
        $connection = new PDO($connection,$databaseUsername,$databasePassword);
        $connection->exec("SET CHARACTER SET utf8;");
    }
    catch(PDOException $e){
        session_start();
        session_unset();
        session_destroy();
        if($errorMessages === "verbose"){
            echo $e;
        }
        else{
            header("location: ../login.php?returnCode=connectionError");
        }
        die();
    }
?>