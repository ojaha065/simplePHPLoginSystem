<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    require_once "../config/config.php";

    $install = true;
    require_once "../utils/databaseConnect.php";

    echo "Trying to create table...";

    $query = $connection->prepare("CREATE TABLE IF NOT EXISTS users (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(64) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        accessLevel VARCHAR(10) NOT NULL,
        lastLogin INT(11),
        rememberMeToken VARCHAR(255)
        );");
    if(!$query){
        print_r($connection->errorInfo());
        die();
    }
    $query->execute();
    if(!$query){
        print_r($connection->errorInfo());
        die();
    }

    echo "<br />All done. <a href='index.php?returnCode=stepTwoOK'>Click here to continue.</a>";
?>