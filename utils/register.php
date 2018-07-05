<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */
    
    echo "If you are reading this, then your account creation is taking longer than expected...";

    require_once "../config/config.php";

    if($disableUserSelfRegistration){
        returnWithError("notAllowed");
    }

    require_once "databaseConnect.php"; // Require pysäyttää suorituksen virheen tapahtuessa, toisin kuin include().

    if(isset($_POST["username"]) && isset($_POST["password"])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Validation
        if(usernameNotAvailable($connection,$username)){
            returnWithError("usernameNotAvailable");
        }
        elseif(strlen($username) < $usernameMinLength){
            returnWithError("usernameTooShort");
        }
        elseif(strlen($password) < 8){
            returnWithError("passwordTooShort");
        }
        elseif(strlen($username) > $usernameMaxLength || strlen($password) > 255){
            returnWithError("tooLongInput");
        }
        else{ // Kaikki vaikuttaa olevan OK

            // Käytetään salasanan salakirjoittamiseen ja suolaamiseen uudempaa password_hash()-funktiota crypt-funktion sijaan.
            // On kuitenkin tärkeää huomata, että password_hash() ei toimi kaikkein vanhimmissa PHP:n versioissa.
            $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

            $query = $connection->prepare("INSERT INTO users (username,password) VALUES (:username, :password)");
            $query->bindParam(":username",$username);
            $query->bindParam(":password",$hashedPassword);

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
        $result = $query->fetch(); // NULL jos mitään ei löydy.
        if($result == NULL){
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