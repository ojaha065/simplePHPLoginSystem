"use strict";

/*
    Simple PHP registration and login system
    https://github.com/ojaha065/simplePHPLoginSystem
    (C) Jani Haiko, 2018
*/

$(document).ready(function(){
    if(disableUserSelfRegistration){
        $("#selfRegistrationLink").hide();
    }

    switch(getUrlParameter("returnCode")){
        case "accountCreated":
            $(".alert").addClass("alert-success");
            $("#alertMessage").html("Your account was successfully created. Please log in below.");
            break;
        case "usernameNotFound":
            $(".alert").addClass("alert-danger");
            $("#alertMessage").html("Your username or password was incorrect.");
            break;
        case "connectionError":
            $(".alert").addClass("alert-danger");
            $("#alertMessage").html("Connection error occured. Try again later.");
            break;
        case "valuesNotSet": // This should never happen under normal circumstances.
            $(".alert").addClass("alert-secondary");
            $("#alertMessage").html("Error: Values not set");
            break;
        default: $(".alert").alert("close");
    }

    $("#username").attr({
        minlength: usernameMinLength,
        maxlength: usernameMaxLength
    });
});