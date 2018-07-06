<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    // Also use similar setting in config.js to disable UI-elements.
    $disableUserSelfRegistration = false;

    // Make sure to also use same values in config.js. Otherwise things might break.
    $usernameMinLength = 3;
    $usernameMaxLength = 30;
    $passwordMinLength = 8; // I recommend values over 8

    // Only usernames and passwords that validate againt these regular expressions are accepted.
    // Do not modify these unless you know what you are doing.
    $usernameRegExp = "/^(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/";
    $passwordRegExp = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{0,}$/";
?>