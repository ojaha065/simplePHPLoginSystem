<?php

    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    ////// MODIFY THESE TO FIT YOUR ENVIRONMENT  
    $host = "localhost";
    $port = "3306";
    $databaseName = "mydatabase";
    $databaseUsername = "root";
    $databasePassword = "";

    ////// DO NOT MODIFY ANYTHING BELOW THIS LINE

    try{
        $connection = "mysql:host=$host:$port;dbname=$databaseName";
        $connection = new PDO($connection,$databaseUsername,$databasePassword);
        $connection->exec("SET CHARACTER SET utf8;");
    }
    catch(PDOException $e){
        // Tuhotaan mahdollinen olemassa oleva sessio
        session_start();
        unset($_SESSION["username"]);
        session_destroy(); // Tuhoaa session
        header("location: ../login.php?returnCode=connectionError");
        die();
    }
?>