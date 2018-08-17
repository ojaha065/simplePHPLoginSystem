"use strict";

/*
    Simple PHP registration and login system
    https://github.com/ojaha065/simplePHPLoginSystem
    (C) Jani Haiko, 2018
*/

$(document).ready(function(){
    if(!allowUsernameChange){
        $("#username").attr({
            disabled: "disabled",
            readonly: "readonly"
        });
        $("#usernameChangeDisallowedText").show();
    }

    $("#passwordCheckbox").change(function(){
        $("#changePasswordFieldset").slideToggle();
    });

    $("#saveChangesButton").click(function(){
        var problems = false;
        var oldPassword = "";
        var newPassword = -1;
        var newUsername = -1;
        if($("#passwordCheckbox").prop("checked")){
            oldPassword = $("#oldPassword").val();
            newPassword = $("#newPassword").val();
            if(newPassword !== $("#newPasswordAgain").val()){
                problems = true;
                $("#errorModalTitle").html("Passwords do not match");
                $("#errorModalMessage").html("The passwords do not match. Try again.");
                $("#errorModal").modal("show");
            }
        }
        if($("#username").val() != $("#oldUsername").val()){
            newUsername = $("#username").val();
        }
        if(!problems){
            modifyAccount(oldPassword,newPassword,newUsername);
        }
    });
});

function modifyAccount(oldPassword,newPassword,newUsername){
    $.ajax({
        method: "POST",
        url: "utils/modifyOwnAccount.php",
        data: {
            oldPassword: oldPassword,
            newPassword: newPassword,
            newUsername: newUsername
        },
        success: function(result){
            switch(result){
                case "accountModified":
                    $("#errorModalTitle").html("Saved!");
                    $("#errorModalMessage").html("Changes you made were successfully saved.");
                    $("#errorModal").modal("show");
                    break;
                case "securityError":
                    $("#errorModalTitle").html("Security error");
                    $("#errorModalMessage").html("There was security problem and that action is not permited right now.");
                    $("#errorModal").modal("show");
                    break;
                case "timeout": location.href = "login.php?returnCode=timeout";
                case "valuesNotSet":
                    $("#errorModalTitle").html("Error");
                    $("#errorModalMessage").html("Error: valuesNotSet");
                    $("#errorModal").modal("show");
                    break;
                case "wrongPassword":
                    $("#errorModalTitle").html("Wrong password");
                    $("#errorModalMessage").html("The old password you entered was incorrect. Please try again!");
                    $("#errorModal").modal("show");
                    break;
                case "passwordTooShort":
                    $("#errorModalTitle").html("Password too short");
                    $("#errorModalMessage").html("Your password is way too short. Try making it longer.");
                    $("#errorModal").modal("show");
                    break;
                case "passwordFailedRegExp":
                    $("#errorModalTitle").html("That password is not secure enough");
                    $("#errorModalMessage").html("Your password must follow these rules:<br />" + passwordRules);
                    $("#errorModal").modal("show");
                    break;
                case "usernameNotAvailable":
                    $("#errorModalTitle").html("That username is not available");
                    $("#errorModalMessage").html("Sorry, that username is already taken. Try something else.");
                    $("#errorModal").modal("show");
                    break;
                case "usernameTooShort":
                    $("#errorModalTitle").html("Username too short");
                    $("#errorModalMessage").html("Username needs to be at least " + usernameMinLength + "characters long.");
                    $("#errorModal").modal("show");
                    break;
                case "tooLongInput":
                    $("#errorModalTitle").html("Error: tooLongInput");
                    $("#errorModalMessage").html("Error: tooLongInput");
                    $("#errorModal").modal("show");
                    break;
                case "usernameFailedRegExp":
                    $("#errorModalTitle").html("That username is not allowed");
                    $("#errorModalMessage").html("Your username must follow these rules:<br />" + usernameRules);
                    $("#errorModal").modal("show");
                    break;
                case "errorOccurred":
                    $("#errorModalTitle").html("It's not you, it's us");
                    $("#errorModalMessage").html("Something went wrong on our side. Try again later.");
                    $("#errorModal").modal("show");
                    break;
                default:
                    $("#errorModalTitle").html("Error");
                    $("#errorModalMessage").html("Error: " + result);
                    $("#errorModal").modal("show");
            }

            $("#changePasswordFieldset").hide();
            $("#passwordCheckbox").prop("checked",false);
        },
        error: showAjaxError
    });
}

function showAjaxError(error){
    $("#errorModalTitle").html("Error");
    $("#errorModalMessage").html("Error " + error.status + ": " + error.statusText);
    $("#errorModal").modal("show");
}