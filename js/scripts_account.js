"use strict";

/*
    Simple PHP registration and login system
    https://github.com/ojaha065/simplePHPLoginSystem
    (C) Jani Haiko, 2018
*/

$(document).ready(function(){
    $("#passwordCheckbox").change(function(){
        $("#changePasswordFieldset").slideToggle();
    });

    $("#saveChangesButton").click(function(){
        var problems = false;
        var oldPassword = "";
        var newPassword = "noChange";
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
        if(!problems){
            modifyAccount(oldPassword,newPassword);
        }
    });
});

function modifyAccount(oldPassword,newPassword){
    $.ajax({
        method: "POST",
        url: "utils/modifyOwnAccount.php",
        data: {
            oldPassword: oldPassword,
            newPassword: newPassword
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
                case "timeout": location.href = "../login.php?returnCode=timeout";
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
                default:
                    $("#errorModalTitle").html("Error");
                    $("#errorModalMessage").html("Error: " + result);
                    $("#errorModal").modal("show");
            }
        },
        error: showAjaxError
    });
}

function showAjaxError(error){
    $("#errorModalTitle").html("Error");
    $("#errorModalMessage").html("Error " + error.status + ": " + error.statusText);
    $("#errorModal").modal("show");
}