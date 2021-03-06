<?php
    require_once "./config/config.php";
    if($forceHTTPS){
        forceHTTPS();
    }

    session_start();

    if(!isset($_SESSION["username"])){
        header("location: login.php");
        die();
    }
    if(!isset($_SESSION["lastActivity"]) || time() - $_SESSION["lastActivity"] > $timeout){
        require_once "./utils/databaseConnect.php";
        $query = $connection->prepare("UPDATE users SET rememberMeToken = :rememberMeToken WHERE username = BINARY :username");
        $query->bindParam(":username",$_SESSION["username"]);
        $query->bindParam(":rememberMeToken",$nullToken);
        $query->execute();

        setcookie("rememberMeUsername",null,time() - 2592000,"/");
        setcookie("rememberMeToken",null,time() - 2592000,"/");

        session_unset();
        session_destroy();
        header("location: login.php?returnCode=timeout");
        die();
    }
    else{
        $_SESSION["lastActivity"] = time();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <!--
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2019
    -->
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <title>Jani's top secret page</title>
    </head>
    <body>
        <?php
            echo "<h1>Welcome, ",$_SESSION['username'],"</h1>";
            if($debugMode === "IKnowWhatIAmDoing"){
                echo "<br /><strong>Warning, the debug mode is ON</strong>";
            }
        ?>
        <p>Here is my secret content that only registered users can see.</p>
        <a href="account.php">Open account management page</a>
        <br />
        <a href="admin/index.php">Open admin panel (only works with the admin rights)</a>
        <br />
        <a href="utils/logout.php">Logout</a>
    </body>

    <!--
        *Notices your source*
        OwO What's this?
    -->
</html>