<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    $nullToken = "notSet";

    setcookie("rememberMeUsername",null,time() - 2592000,"/");
    setcookie("rememberMeToken",null,time() - 2592000,"/");

    session_start();

    if(isset($_SESSION["username"])){
        $username = $_SESSION["username"];
    }
    else{
        session_unset();
        session_destroy();
        header("location: ../index.php");
        die();
    }

    session_unset();
    session_destroy();

    require_once "databaseConnect.php";
    $query = $connection->prepare("UPDATE users SET rememberMeToken = :rememberMeToken WHERE username = BINARY :username");
    $query->bindParam(":username",$username);
    $query->bindParam(":rememberMeToken",$nullToken);
    $query->execute();

    header("location: ../index.php");
?>