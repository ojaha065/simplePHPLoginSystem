<?php
    session_start(); // Aloitetaan tai palautetaan sessio

    if(!isset($_SESSION["username"])){
        header("location: login.php"); // Tämä käyttäjä ei ole kirjautunut, joten lähetetään HTTP 302 (uudelleenohjaus). Virallisen standardin mukaan URL-osoitteen pitäisi olla absoluuttinen.
        die(); // Ilman tätä alla oleva HTML-sisältö lähetetään selaimelle, jos selain ei noudata uudelleenohjaus-pyyntöä.
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
            echo "<h1>Welcome, ",htmlspecialchars($_SESSION['username']),"</h1>";
        ?>
        <p>Here is my secret content that only registered users can see.</p>
        <a href="utils/logout.php">Logout</a>
    </body>

    <!--
        *Notices your source*
        OwO What's this?
    -->
</html>