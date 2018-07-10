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

    // Only usernames and passwords that match these regular expressions are accepted.
    // Do not modify these unless you know what you are doing.
    $usernameRegExp = "/^(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/";
    $passwordRegExp = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{0,}$/";

    // This should always be set to 'user'.
    // Set it to 'admin' to create your first admin account(s).
    // REMEMBER TO CHANGE IT BACK AFTEWARDS. Otherwise anyone can create an account with admin access. And that's probably very bad...
    $newAccountAccessLevel = "user";

    // Turning the debug mode ON disables database connection and only allows logging in with the username and password below.
    // Registering new accounts in debug mode is not allowed.
    // Using this in the production environment is a huge security risk. Don't do it, ever!
    // If you are sure you want to turn the debug mode ON, change $debugMode to 'IKnowWhatIAmDoing'.
    $debugMode = "no";
    $debugAdminUsername = "admin";
    $debugAdminPassword = "";
?>