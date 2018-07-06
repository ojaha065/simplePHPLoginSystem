<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    session_start();
    unset($_SESSION["username"]);
    session_destroy();

    header("location: ../index.php");
?>