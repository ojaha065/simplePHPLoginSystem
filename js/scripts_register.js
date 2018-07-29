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
        case "usernameFailedRegExp":
            $("#errorArea").html("That username is not allowed. Your username must follow these rules:<br />" + usernameRules);
            break;
        case "passwordFailedRegExp":
            $("#errorArea").html("That password is not secure enough. Your password must follow these rules:<br />" + passwordRules);
            break;
        case "tooLongInput":
            $("#errorArea").html("Error: Too long input");
            break;
        case "notAllowed":
            $("#errorArea").html("Error: You are not allowed to register at this time.");
            break;
        case "securityError":
            $("#errorArea").html("Error: There was an security problem and this registration was aborted. Please contact the website's administrator.");
            break;
        case "valuesNotSet": $("#errorArea").html("Error: Values not set"); // This should never happen under normal circumstances.
    }

    $("#username").attr({
        minlength: usernameMinLength,
        maxlength: usernameMaxLength,
        placeholder: "At least " + usernameMinLength + " characters long"
    });
    $("#password").attr("minlength",passwordMinLength);

    if(enableUsernameSuggestions){
        var showUsernameTipsTimer;
        $("#username").one("focus",function(){
            showUsernameTipsTimer = setTimeout(function(){
                var wordlist = [];
                $.ajax({
                    url: "wordlist.txt",
                    success: function(result){
                        wordlist = result.split(/\r?\n/g);
                        var usernameSuggestion = wordlist[randomNumberBetween(0,wordlist.length - 1)];
                        usernameSuggestion += "_" + wordlist[randomNumberBetween(0,wordlist.length - 1)];
                        while(true){
                            if(usernameSuggestion.length < usernameMinLength){
                                usernameSuggestion += "_" + wordlist[randomNumberBetween(0,wordlist.length - 1)];
                            }
                            else{
                                break;
                            }
                        }
                        $("#usernameSuggestion").html(usernameSuggestion).parent().show();
                    },
                    error: function(error){
                        console.error(error.status + ": " + error.statusText);
                    }
                });
            },5000);
        });
        $("#usernameSuggestion").click(function(){
            $("#username").val($(this).html());
        });
        $("#username").blur(function(){
            clearTimeout(showUsernameTipsTimer);
            setTimeout(function(){
                $("#usernameSuggestion").parent().hide();
            },100);
        });
    }

    $("form").submit(function(e){
        e.preventDefault();
        $("button").prop("disabled",true).html("Please wait...");
        if($("#password").val() === $("#passwordAgain").val()){
            this.submit();
        }
        else{
            $("#errorArea").html("Passwords do not match.");
            $("button").prop("disabled",false).html("Submit");
        }
    });
});