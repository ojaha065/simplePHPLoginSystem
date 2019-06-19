<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2019
    */
    
    require_once "../config/config.php";

    if($debugMode == "IKnowWhatIAmDoing"){
        returnWithError("securityError");
    }
    if($disableUserSelfRegistration){
        returnWithError("notAllowed");
    }

    require_once "databaseConnect.php";
    session_start();

    if(isset($_POST["username"]) && isset($_POST["password"])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $_SESSION["inputedUsername"] = $username;

        // Validation
        if(usernameNotAvailable($connection,$username)){
            returnWithError("usernameNotAvailable");
        }
        elseif(strlen($username) < $usernameMinLength){
            returnWithError("usernameTooShort");
        }
        elseif(strlen($password) < $passwordMinLength){
            returnWithError("passwordTooShort");
        }
        elseif(strlen($username) > $usernameMaxLength || strlen($password) > 72){
            returnWithError("tooLongInput");
        }
        elseif(!preg_match($usernameRegExp,$username)){
            returnWithError("usernameFailedRegExp");
        }
        elseif(!preg_match($passwordRegExp,$password)){
            returnWithError("passwordFailedRegExp");
        }
        else{

            $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

            $query = $connection->prepare("INSERT INTO users (username,password,accessLevel) VALUES (:username, :password, :accessLevel)");
            $query->bindParam(":username",$username);
            $query->bindParam(":password",$hashedPassword);
            $query->bindParam(":accessLevel",$newAccountAccessLevel);

            $query->execute();
            // TODO: Error handling
            header("location: ../login.php?returnCode=accountCreated");
        }
    }
    else{
        returnWithError("valuesNotSet");
    }

    ///////////////////////////////////////

    function usernameNotAvailable($connection,$username){
        $query = $connection->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindParam(":username",$username);
        $query->execute();
        $result = $query->fetch();
        if($result == NULL && $username != "admin"){
            return false;
        }
        else{
            return true;
        }
    }

    function returnWithError($errorCode){
        header("location: ../register.php?returnCode=$errorCode");
        die();
    }
?>