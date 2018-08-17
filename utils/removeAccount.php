<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */
    
    require_once "../config/config.php";

    session_start();

    if($debugMode == "IKnowWhatIAmDoing" || !isset($_SESSION["username"]) || $_SESSION["accessLevel"] !== "admin" || !isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) != "xmlhttprequest"){
        returnWithError("securityError");
        die();
    }
    if(time() - $_SESSION["lastActivity"] > $adminPanelTimeout){
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

    require_once "databaseConnect.php";

    if(isset($_POST["username"])){
        $username = $_POST["username"];
        if($_SESSION["username"] === $username){
            returnWithError("accountActive");
        }

        $query = $connection->prepare("DELETE FROM users WHERE BINARY username = :username");
        $query->bindParam(":username",$username);

        $query->execute();
        // TODO: Error handling
        echo "accountDeleted";
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