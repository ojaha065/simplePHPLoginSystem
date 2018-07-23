<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    require_once "../config/config.php";
    require_once "../utils/databaseConnect.php";

    echo "Trying to create table...";

    $query = $connection->prepare("CREATE TABLE IF NOT EXISTS users (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(64) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        accessLevel VARCHAR(10) NOT NULL,
        lastLogin VARCHAR(16)
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

    echo "<br />All done. You may leave this page.<br />I recommend removing /install folder.";
?>