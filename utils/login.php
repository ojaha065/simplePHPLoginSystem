<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */
    
    echo "If you are reading this, then the login process is taking longer than excepted...";

    require_once "databaseConnect.php";

    if(isset($_POST["username"]) && isset($_POST["password"])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Haetaan tämän käyttäjän salasana tietokannasta.
        $query = $connection->prepare("SELECT password FROM users WHERE username = BINARY :username"); // Binary == Huomioi kirjainkoko
        $query->bindParam(":username",$username);
        $query->execute();
        $result = $query->fetch(); // NULL jos mitään ei löydy.

        if($result == NULL){
            returnWithError("usernameNotFound");
        }

        $result = $result["password"];

        if(passwordIsCorrect($password,$result)){
            session_start();
            $_SESSION["username"] = $username;
            header("location: ../index.php");
        }
        else{
            returnWithError("usernameNotFound"); // On turvallisuusriski kertoa, että nimenomaan salasana oli väärin, koska tällöin 'pahis' saa tietoonsa, että käyttäjätunnus on olemassa.
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
        return password_verify($password,$hash); // Palauttaa true tai false
    }
?>