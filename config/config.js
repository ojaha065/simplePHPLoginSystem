"use strict";

/*
    Simple PHP registration and login system
    https://github.com/ojaha065/simplePHPLoginSystem
    (C) Jani Haiko, 2018
*/

// This setting is client-side (browser) only.
// Also use the similar setting in config.php if you want fully disable registration for users.
var disableUserSelfRegistration = false;

var usernameMinLength = 3;
var usernameMaxLength = 30;
var passwordMinLength = 8;

// These are shown to the user if they try to register with non-allowed username or password
var usernameRules = "<ol>"
    +"<li>Username must be at least " + usernameMinLength + " characters long.</li>"
    +"<li>Username can only contain alphanumeric characters (A-Z, 0-9), underscore and dot.</li>"
    +"<li>Username cannot start or end with underscore or dot.</li>"
    +"<li>Underscore and dot cannot be next to each other.</li>"
    +"<li>Multiple underscores or dots cannot be next to each other</li>"
+"</ol";
var passwordRules = "<ol>"
    +"<li>Password must be at least " + passwordMinLength + " characters long.</li>"
    +"<li>Password must contain at least one upper case and one lower case letter (A-Z).</li>"
    +"<ul><li>Language special characters (like Å, Ä, Ö) are allowed but they do not count towards this.</li></ul>"
    +"<li>Password must contain at least one number</li>"
+"</ol";

var enableUsernameSuggestions = true;