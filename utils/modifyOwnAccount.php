<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2019
    */
    
    require_once "../config/config.php";

    session_start();

    if($debugMode == "IKnowWhatIAmDoing" || !isset($_SESSION["username"]) || !isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) != "xmlhttprequest"){
        returnWithError("securityError");
        die();
    }

    if(time() - $_SESSION["lastActivity"] > $timeout){
        require_once "databaseConnect.php";
        $query = $connection->prepare("UPDATE users SET rememberMeToken = :rememberMeToken WHERE username = BINARY :username");
        $query->bindParam(":username",$_SESSION["username"]);
        $query->bindParam(":rememberMeToken",$nullToken);
        $query->execute();

        setcookie("rememberMeUsername",null,time() - 2592000,"/");
        setcookie("rememberMeToken",null,time() - 2592000,"/");
        
        session_unset();
        session_destroy();
        echo "timeout";
        die();
    }
    else{
        $_SESSION["lastActivity"] = time();
    }

    if(isset($_POST["oldPassword"]) && isset($_POST["newPassword"]) && isset($_POST["newUsername"])){
        $username = $_SESSION["username"];
        $oldPassword = $_POST["oldPassword"];
        $newPassword = $_POST["newPassword"];
        $newUsername = $_POST["newUsername"];

        require_once "databaseConnect.php";

        $problems = false;

        $query = $connection->prepare("UPDATE users SET rememberMeToken = :rememberMeToken WHERE username = BINARY :username");
        $query->bindParam(":username",$_SESSION["username"]);
        $query->bindParam(":rememberMeToken",$nullToken);
        $query->execute();

        if($newPassword != -1){
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
                elseif(strlen($newPassword) > 72){
                    returnWithError("tooLongInput");
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
        if($newUsername != -1 && $allowUsernameChange){
            if(usernameNotAvailable($connection,$newUsername)){
                returnWithError("usernameNotAvailable");
            }
            elseif(strlen($newUsername) < $usernameMinLength){
                returnWithError("usernameTooShort");
            }
            elseif(strlen($newUsername) > $usernameMaxLength){
                returnWithError("tooLongInput");
            }
            elseif(!preg_match($usernameRegExp,$newUsername)){
                returnWithError("usernameFailedRegExp");
            }
            else{
                $query = $connection->prepare("UPDATE users SET username = :newUsername WHERE BINARY username = :username");
                $query->bindParam(":newUsername",$newUsername);
                $query->bindParam(":username",$username);
                $query->execute();

                if(!$query){
                  $problems = true;  
                }
                else{
                    $_SESSION["username"] = $newUsername;
                }
            }
        }

        if($problems){
            returnWithError("errorOccurred");
        }
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
?>