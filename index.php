<?php
    require_once "config/config.php";

    session_start();

    if(!isset($_SESSION["username"]) || time() - $_SESSION["lastActivity"] > 900){
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
        (C) Jani Haiko, 2018
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
        <a href="admin">Open admin panel</a>
        <br />
        <a href="utils/logout.php">Logout</a>
    </body>

    <!--
        *Notices your source*
        OwO What's this?
    -->
</html>