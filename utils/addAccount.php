<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    require_once "../config/config.php";

    session_start();

    if($debugMode == "IKnowWhatIAmDoing" || !isset($_SESSION["accessLevel"]) || $_SESSION["accessLevel"] !== "admin" || !isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) != "xmlhttprequest"){
        returnWithError("securityError");
        die();
    }

    if(time() - $_SESSION["lastActivity"] > $adminPanelTimeout){
        session_unset();
        session_destroy();
        returnWithError("timeout");
        die();
    }
    else{
        $_SESSION["lastActivity"] = time();
    }

    if(!isset($_POST["username"]) || !isset($_POST["accessLevel"])){
        returnWithError("valuesNotSet");
    }

    require_once "../utils/databaseConnect.php";

    $username = $_POST["username"];
    if(usernameNotAvailable($connection,$username)){
        returnWithError("usernameNotAvailable");
    }
    elseif(strlen($username) < $usernameMinLength){
        returnWithError("usernameTooShort");
    }
    elseif(strlen($username) > $usernameMaxLength){
        returnWithError("tooLongInput");
    }
    elseif(!preg_match($usernameRegExp,$username)){
        returnWithError("usernameFailedRegExp");
    }

    if($_POST["accessLevel"] === "admin" || $_POST["accessLevel"] === "user"){
        $accessLevel = $_POST["accessLevel"];
    }
    else{
        returnWithError("securityError");
        die();
    }

    $randomPassword = random_int(100000,999999999);
    $hashedPassword = password_hash($randomPassword,PASSWORD_DEFAULT);

    $query = $connection->prepare("INSERT INTO users (username,password,accessLevel) VALUES (:username, :password, :accessLevel)");
    $query->bindParam(":password",$hashedPassword);
    $query->bindParam(":username",$username);
    $query->bindParam(":accessLevel",$accessLevel);
    if(!$query){
        print_r($connection->errorInfo());
        die();
    }
    $query->execute();
    if(!$query){
        print_r($connection->errorInfo());
    }
    else{
        echo $randomPassword;
    }

    ///////////////////

    function returnWithError($errorCode){
        echo $errorCode;
        die();
    }

    function usernameNotAvailable($connection,$username){
        $query = $connection->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindParam(":username",$username);
        $query->execute();
        $result = $query->fetch();
        if($result == NULL){
            return false;
        }
        else{
            return true;
        }
    }
?>