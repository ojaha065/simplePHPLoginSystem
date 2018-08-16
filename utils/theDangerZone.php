<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */
    
    require_once "../config/config.php";

    session_start();

    if($debugMode == "IKnowWhatIAmDoing" || $_SESSION["accessLevel"] !== "admin" || !isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) != "xmlhttprequest"){
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

    if(isset($_POST["action"])){
        switch($_POST["action"]){
            case "invalidateTokens":
                $query = $connection->prepare("UPDATE users SET rememberMeToken = :rememberMeToken");
                $query->bindParam(":rememberMeToken",$nullToken);
                $query->execute();
                echo "OK";
                break;
            default:
                returnWithError("invalidAction");
        }
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