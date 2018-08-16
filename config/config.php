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
    // Using this in the production environment is a huge security risk. Don't do it, ever!
    // If you are sure you want to turn the debug mode ON, change $debugMode to 'IKnowWhatIAmDoing'.
    $debugMode = "no";
    $debugAdminUsername = "admin";
    $debugAdminPassword = "";
    $debugSkipInstall = true;

    // Date and time settings
    // Default settings saves dates in European format: 23.06.2018
    // If you live in some silly country that uses MM/DD/YYYY then change $mmddyyyy to 'true'.
    // Seperators can be almost anything.
    $dateSeperator = ".";
    $timeSeperator = ":";
    $mmddyyyy = false;

    // Users are required to login again after timeout.
    // Times are in seconds.
    // Use something very big (like 99999999) to disable timeout. (Might be a security risk!)
    $timeout = 900;
    $adminPanelTimeout = 450;

    // Supported values: default, verbose
    // This should always to be set to 'default' in production environment to prevent leaking any sensitive information (like database passwords) via the error messages.
    $errorMessages = "default";

    // Also use similar setting in config.php.
    $allowUsernameChange = true;

    // Forces users to access the page via secure connection (highly recommended).
    // Might cause issues on some hosts.
    $forceHTTPS = false;

    // Config options end here
    /////////////////////////

    // Do not change this (unless you know what you're doing)
    $nullToken = "notSet";

    function forceHTTPS(){
        if((!empty($_SERVER["HTTPS"]) && $_SERVER['HTTPS'] !== "off") || ((!empty($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") || (!empty($_SERVER["HTTP_X_FORWARDED_SSL"]) && $_SERVER["HTTP_X_FORWARDED_SSL"] == "on"))){
            $isHTTPS = true;
        }
        else{
            $isHTTPS = false;
        }

        if(!$isHTTPS){
            header("Strict-Transport-Security: max-age=1000");
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            die();
        }
    }
?>