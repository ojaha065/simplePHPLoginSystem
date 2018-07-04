<?php
    /*
        Simple PHP registration and login system
        (C) Jani Haiko, 2018
    */

    session_start();
    unset($_SESSION["username"]);
    session_destroy(); // Tuhoaa session

    header("location: ../index.php");
?>