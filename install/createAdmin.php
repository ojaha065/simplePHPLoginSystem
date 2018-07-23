<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    require_once "../config/config.php";
    require_once "../utils/databaseConnect.php";

    echo "Trying to create user account...";

    $randomPassword = random_int(100000,999999999);
    echo "The password of the created account will be: $randomPassword";
    $hashedPassword = password_hash($randomPassword,PASSWORD_DEFAULT);

    $query = $connection->prepare("INSERT INTO users (username,password,accessLevel) VALUES ('admin', :password, 'admin')");
    $query->bindParam(":password",$hashedPassword);
    if(!$query){
        print_r($connection->errorInfo());
        die();
    }
    $query->execute();
    if(!$query){
        print_r($connection->errorInfo());
        die();
    }

    echo "<br />All done. You may leave this page.<br />In production environment you MUST remove the /install folder.";
?>