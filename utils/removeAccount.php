<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */
    
    require_once "../config/config.php";

    session_start();

    if($debugMode == "IKnowWhatIAmDoing" || $_SESSION["accessLevel"] !== "admin"){
        returnWithError("securityError");
        die();
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