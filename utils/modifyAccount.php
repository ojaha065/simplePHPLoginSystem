<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */
    
    require_once "../config/config.php";

    session_start();

    if($debugMode == "IKnowWhatIAmDoing" || $_SESSION["accessLevel"] !== "admin" || $_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest"){
        returnWithError("securityError");
        die();
    }

    if(time() - $_SESSION["lastActivity"] > $adminPanelTimeout){
        session_unset();
        session_destroy();
        echo "timeout";
        die();
    }
    else{
        $_SESSION["lastActivity"] = time();
    }

    require_once "databaseConnect.php";

    if(isset($_POST["username"]) && isset($_POST["accessLevel"]) && isset($_POST["reset"])){
        $username = $_POST["username"];
        $accessLevel = $_POST["accessLevel"];
        $reset = $_POST["reset"];

        $query = $connection->prepare("UPDATE users SET accessLevel = :accessLevel WHERE BINARY username = :username");
        $query->bindParam(":username",$username);
        $query->bindParam(":accessLevel",$accessLevel);
        $query->execute();

        if($reset == "true"){
            $query = $connection->prepare("UPDATE users SET lastLogin = NULL WHERE BINARY username = :username");
            $query->bindParam(":username",$username);
            $query->execute();
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