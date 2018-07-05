"use strict";

/*
    Simple PHP registration and login system
    https://github.com/ojaha065/simplePHPLoginSystem
    (C) Jani Haiko, 2018
*/

$(document).ready(function(){
    if(disableUserSelfRegistration){
        $("button").prop("disabled",true).html("Self-registration has been disabled by the admin");
    }

    switch(getUrlParameter("returnCode")){
        case "usernameNotAvailable":
            $("#errorArea").html("That username is not available. Try something else.");
            break;
        case "usernameTooShort":
            $("#errorArea").html("Your username is too short. It needs to be at least " + usernameMinLength + " characters long.");
            break;
        case "passwordTooShort":
            $("#errorArea").html("Your password is way too short. Try making it longer.");
            break;
        case "tooLongInput":
            $("#errorArea").html("Error: Too long input");
            break;
        case "notAllowed":
            $("#errorArea").html("Error: You are not allowed to register at this time.");
            break;
        case "valuesNotSet": $("#errorArea").html("Error: Values not set"); // This should never happen under normal circumstances.
    }

    $("#username").attr({
        minlength: usernameMinLength,
        maxlength: usernameMaxLength,
        placeholder: "At least " + usernameMinLength + " characters long"
    });

    $("form").submit(function(e){
        e.preventDefault();
        if($("#password").val() === $("#passwordAgain").val()){
            this.submit();
        }
        else{
            $("#errorArea").html("Passwords do not match.");
        }
    });
});