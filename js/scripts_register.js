"use strict";

/*
    Simple PHP registration and login system
    (C) Jani Haiko, 2018
*/

$(document).ready(function(){
    switch(getUrlParameter("returnCode")){
        case "usernameNotAvailable":
            $("#errorArea").html("That username is not available. Try something else.");
            break;
        case "usernameTooShort":
            $("#errorArea").html("Your username is too short. It needs to be at least 3 characters long.");
            break;
        case "passwordTooShort":
            $("#errorArea").html("Your password is way too short. Try making it longer.");
            break;
        case "tooLongInput":
            $("#errorArea").html("Error: Too long input"); // This should never happen under normal circumstances.
            break;
        case "valuesNotSet": $("#errorArea").html("Error: Values not set"); // This should never happen under normal circumstances.
    }

    $("form").submit(function(e){
        e.preventDefault();
        //console.log("Prevented the browser from sending the form.");
        if($("#password").val() === $("#passwordAgain").val()){
            //console.log("Everything seems to be fine. Sending form...");
            this.submit();
        }
        else{
            $("#errorArea").html("Passwords do not match.");
        }
    });
});