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
            $("#errorAlert").addClass("alert-success");
            $("#alertMessage").html("Your account was successfully created. Please log in below.");
            break;
        case "usernameNotFound":
            $("#errorAlert").addClass("alert-danger");
            $("#alertMessage").html("Your username or password was incorrect.");
            break;
        case "timeout":
            $("#errorAlert").addClass("alert-info");
            $("#alertMessage").html("You were logged out due to inactivity. Please log in again.");
            break;
        case "connectionError":
            $("#errorAlert").addClass("alert-danger");
            $("#alertMessage").html("Connection error occured. Try again later.");
            break;
        case "valuesNotSet": // This should never happen under normal circumstances.
            $("#errorAlert").addClass("alert-secondary");
            $("#alertMessage").html("Error: Values not set");
            break;
        default: $("#errorAlert").alert("close");
    }

    if(enableLoginMessage){
        $("#loginMessage").html(loginMessage);
    }
    else{
        $("#loginMessage").alert("close"); 
    }

    $("#rememberMe").change(function(){
        if($(this).prop("checked")){
            $("#rememberMeTimeFieldset").show();
        }
        else{
            $("#rememberMeTimeFieldset").hide();
        }
    });
});