"use strict";

/*
    Simple PHP registration and login system
    https://github.com/ojaha065/simplePHPLoginSystem
    (C) Jani Haiko, 2018
*/

$(document).ready(function(){
    var actionIcons = "<span class='fa fa-cog'></span><span class='fa fa-trash-alt'></span>";
    $(".actions").html(actionIcons);
    $(".fa-cog").attr("title","Modify").tooltip();
    $(".fa-trash-alt").attr("title","Delete account").tooltip();

    $(".fa-trash-alt").click(function(){
        var username = $(this).parent().siblings(".d-none").html();
        $("#confirmDeletionModalUsername").html(username);
        $("#confirmDeletionModal").modal("show");
    });
    $(".fa-cog").click(function(){
        var username = $(this).parent().siblings(".d-none").html();
        var accessLevel = $(this).parent().siblings(".accessLevel").html();
        var lastLogin = $(this).parent().siblings(".lastLogin").html();
        $("#modify_username").val(username);
        $("#modify_accessLevel").val(accessLevel);
        $("#modify_lastLogin").val(lastLogin.replace("<i>","").replace("</i>",""));
        $("#modifyModal").modal("show");
    });

    $("#confirmDeletionButton").click(function(){
        var username = $("#confirmDeletionModalUsername").html();
        removeAccount(username);
    });
    $("#saveChangesButton").click(function(){
        $("#saveChangesButton").prop("disabled",true);
        $("#saveChangesButton").html("Please wait...");
        var username = $("#modify_username").val();
        var accessLevel = $("#modify_accessLevel").val();
        var reset = false;
        if($("#modify_reset").prop("checked")){
            reset = true;
        }
        modifyAccount(username,accessLevel,reset);
    });

    $("#addAccountButton").click(function(){
        $("#addAccountUsername").removeClass("is-invalid");
        var username = $("#addAccountUsername").val();
        var accessLevel = $("#addAccountAccessLevel").val();
        if(username.length >= usernameMinLength){
            $("#addAccountButton").prop("disabled",true);
            $("#addAccountButton").html("Please wait...");
            addAccount(username,accessLevel);
        }
        else{
            $("#addAccountUsername").addClass("is-invalid");
        }
    });
    $("#generateUsername").click(function(){
        $.ajax({
            url: "../wordlist.txt",
            success: function(result){
                var wordlist = result.split(/\r?\n/g);
                var generatedUsername = wordlist[randomNumberBetween(0,wordlist.length - 1)]
                generatedUsername += "_" + wordlist[randomNumberBetween(0,wordlist.length - 1)];
                while(true){
                    if(generatedUsername.length < usernameMinLength){
                        generatedUsername += "_" + wordlist[randomNumberBetween(0,wordlist.length - 1)];
                    }
                    else{
                        break;
                    }
                }
                $("#addAccountUsername").val(generatedUsername);
            }
        });
    });
});

function removeAccount(username){
    $.ajax({
        method: "POST",
        url: "../utils/removeAccount.php",
        data: {
            username: username
        },
        success: function(result){
            if(result == "accountDeleted"){
                location.reload();
            }
            else if(result == "securityError"){
                $("#errorModalTitle").html("Security error");
                $("#errorModalMessage").html("There was security problem and that action is not permited right now.");
                $("#errorModal").modal("show");
            }
            else if(result == "accountActive"){
                $("#errorModalTitle").html("Cannot delete this account");
                $("#errorModalMessage").html("You cannot delete the account you are currently using.");
                $("#errorModal").modal("show");
            }
            else if(result == "timeout"){
                location.href = "../login.php?returnCode=timeout";
            }
            else if(result == "valuesNotSet"){
                $("#errorModalTitle").html("Error");
                $("#errorModalMessage").html("Error: valuesNotSet");
                $("#errorModal").modal("show");
            }
            else{
                $("#errorModalTitle").html("Error");
                $("#errorModalMessage").html("Error: " + result);
                $("#errorModal").modal("show");
            }
        },
        error: showAjaxError
    });
}

function modifyAccount(username,accessLevel,reset){
    $.ajax({
        method: "POST",
        url: "../utils/modifyAccount.php",
        data: {
            username: username,
            accessLevel: accessLevel,
            reset: reset
        },
        success: function(result){
            $("#modifyModal").modal("hide");
            switch(result){
                case "accountModified": location.reload();
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
                default:
                    $("#errorModalTitle").html("Error");
                    $("#errorModalMessage").html("Error: " + result);
                    $("#errorModal").modal("show");
            }
        },
        error: showAjaxError
    });
}

function addAccount(username,accessLevel){
    $.ajax({
        method: "POST",
        url: "../utils/addAccount.php",
        data: {
            username: username,
            accessLevel: accessLevel
        },
        success: function(result){
            $("#addAccountModal").modal("hide");
            $("#addAccountButton").prop("disabled",false);
            $("#addAccountButton").html("Create account");
            if(Number(result) >= 100000){
                $("#errorModalTitle").html("Account added");
                $("#errorModalMessage").html("The account <i>" + username + "</i> was created and password <b>" + result + "</b> was given to it.");
                $("#errorModal").modal("show");
            }
            else{
                switch(result){
                    case "securityError":
                        $("#errorModalTitle").html("Security error");
                        $("#errorModalMessage").html("There was a security problem and that action is not permited right now. Is the debug mode on?");
                        $("#errorModal").modal("show");
                        break;
                    case "usernameNotAvailable":
                        $("#errorModalTitle").html("Selected username not available");
                        $("#errorModalMessage").html("Sorry, that username is not available.");
                        $("#errorModal").modal("show");
                        break;
                    case "usernameTooShort":
                        $("#errorModalTitle").html("Username too short");
                        $("#errorModalMessage").html("Username needs to be at least " + usernameMinLength + " characters long.<br /><small>This can be changed in config.js.</small>");
                        $("#errorModal").modal("show");
                        break;
                    case "tooLongInput":
                        $("#errorModalTitle").html("Username too long");
                        $("#errorModalMessage").html("Username cannot be longer " + usernameMaxLength + " characters long.<br /><small>This can be changed in config.js.</small>");
                        $("#errorModal").modal("show");
                        break;
                    case "usernameFailedRegExp":
                        $("#errorModalTitle").html("Username not allowed");
                        $("#errorModalMessage").html(usernameRules);
                        $("#errorModal").modal("show");
                        break;
                    case "timeout":
                        location.href = "../login.php?returnCode=timeout";
                        break;
                    case "valuesNotSet":
                        $("#errorModalTitle").html("Error");
                        $("#errorModalMessage").html("Error: valuesNotSet");
                        $("#errorModal").modal("show");
                        break;
                    default:
                        $("#errorModalTitle").html("Error");
                        $("#errorModalMessage").html("Error: " + result);
                        $("#errorModal").modal("show");
                }
            }
        },
        error: function(error){
            $("#addAccountModal").modal("hide");
            $("#addAccountButton").prop("disabled",false);
            $("#addAccountButton").html("Create account");
            showAjaxError(error);
        }
    });
}

function showAjaxError(error){
    $("#errorModalTitle").html("Error");
    $("#errorModalMessage").html("Error " + error.status + ": " + error.statusText);
    $("#errorModal").modal("show");
}