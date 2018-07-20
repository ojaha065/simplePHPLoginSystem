<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */
    
    echo "If you are reading this, then the login process is taking longer than excepted...";

    require_once "../config/config.php";
    session_start();

    if($debugMode === "IKnowWhatIAmDoing"){
        if(isset($_POST["username"]) && isset($_POST["password"])){
            $username = $_POST["username"];
            $password = $_POST["password"];

            $_SESSION["inputedUsername"] = $username;
            
            if($username === $debugAdminUsername && $password === $debugAdminPassword){
                $_SESSION["username"] = $username;
                $_SESSION["lastActivity"] = time();
                $_SESSION["accessLevel"] = "admin";
                header("location: ../index.php");
                die();
            }
            else{
                returnWithError("usernameNotFound");
            }
        }
        else{
            returnWithError("valuesNotSet");
        }
    }

    require_once "databaseConnect.php";

    if(isset($_POST["username"]) && isset($_POST["password"])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $_SESSION["inputedUsername"] = $username;

        $query = $connection->prepare("SELECT password FROM users WHERE username = BINARY :username");
        $query->bindParam(":username",$username);
        $query->execute();
        $result = $query->fetch();

        if($result == NULL){
            returnWithError("usernameNotFound");
        }

        $result = $result["password"];

        if(passwordIsCorrect($password,$result)){
            $currentTime = date("dmYhia");
            if($mmddyyyy){
                $currentTime = date("mdYhia"); // You silly Americans...
            }
            $query = $connection->prepare("UPDATE users SET lastLogin = :lastLogin WHERE BINARY username = :username");
            $query->bindParam(":lastLogin",$currentTime);
            $query->bindParam(":username",$username);
            $query->execute();

            $_SESSION["username"] = $username;
            $_SESSION["lastActivity"] = time();
            header("location: ../index.php");
        }
        else{
            returnWithError("usernameNotFound");
        }
    }
    else{
        returnWithError("valuesNotSet");
    }

    ///////////////////////////////////////

    function returnWithError($errorCode){
        header("location: ../login.php?returnCode=$errorCode");
        die();
    }

    function passwordIsCorrect($password,$hash){
        return password_verify($password,$hash);
    }
?>