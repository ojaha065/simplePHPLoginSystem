"use strict";

/*
    Simple PHP registration and login system
    https://github.com/ojaha065/simplePHPLoginSystem
    (C) Jani Haiko, 2018
*/

// This setting is client-side (browser) only.
// Also use the similar setting in config.php if you want fully disable registration for users.
var disableUserSelfRegistration = false;

// Important: Be careful modifying these as you can accidentally lock out previously created accounts.
var usernameMinLength = 3;
var usernameMaxLength = 30;