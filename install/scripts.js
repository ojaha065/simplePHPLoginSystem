"use strict";

/*
    Simple PHP registration and login system
    https://github.com/ojaha065/simplePHPLoginSystem
    (C) Jani Haiko, 2018
*/

$(document).ready(function(){
    switch(getUrlParameter("returnCode")){
        case "stepTwoOK":
            $("#stepThree").show();
            break;
        case "stepThreeOK":
            $("#stepThree").hide().next().show();
            $("#adminPassword").html(getUrlParameter("password"));
            break;
        default: $(".card").first().show();
    }

    $("#stepOneContinue").click(function(){
        $("#stepOneContinue").parents(".card").hide().next().show();
    });
    $("#tableManualButton").click(function(){
        $("#stepThree").show().prev().hide();
    });
    $("#openLoginPage").click(function(){
        $("#stepFourContinue").show();
    });
    $("#stepFourContinue").click(function(){
        $("#finalStep").show().prev().hide();
    });
});