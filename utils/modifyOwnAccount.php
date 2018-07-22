<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */
    
    require_once "../config/config.php";

    session_start();

    if($debugMode == "IKnowWhatIAmDoing" || !isset($_SESSION["username"]) || $_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest"){
        returnWithError("securityError");
        die();
    }

    if(time() - $_SESSION["lastActivity"] > $timeout){
        session_unset();
        session_destroy();
        echo "timeout";
        die();
    }
    else{
        $_SESSION["lastActivity"] = time();
    }

    if(isset($_POST["oldPassword"]) && isset($_POST["newPassword"])){
        $username = $_SESSION["username"];
        $oldPassword = $_POST["oldPassword"];
        $newPassword = $_POST["newPassword"];

        require_once "databaseConnect.php";

        if($newPassword != "noChange"){
            $query = $connection->prepare("SELECT password FROM users WHERE username = BINARY :username");
            $query->bindParam(":username",$username);
            $query->execute();
            $result = $query->fetch();
            $oldPasswordHash = $result[0];

            if(password_verify($oldPassword,$oldPasswordHash)){
                if(strlen($newPassword) < $passwordMinLength){
                    returnWithError("passwordTooShort");
                }
                elseif(!preg_match($passwordRegExp,$newPassword)){
                    returnWithError("passwordFailedRegExp");
                }
                else{
                    $hashedPassword = password_hash($newPassword,PASSWORD_DEFAULT);
                    $query = $connection->prepare("UPDATE users SET password = :newPassword WHERE BINARY username = :username");
                    $query->bindParam(":username",$username);
                    $query->bindParam(":newPassword",$hashedPassword);
                    $query->execute();
                }
            }
            else{
                returnWithError("wrongPassword");
            }
        }

        // TODO: Error handling
        echo "accountModified";
    }
    else{
        returnWithError("valuesNotSet");
    }

    ///////////////////////////////////////

    function returnWithError($errorCode){
        echo $errorCode;
        die();
    }
?>