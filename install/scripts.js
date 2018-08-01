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

    // Let's enable tooltips
    $("[data-toggle='tooltip']").tooltip({
        placement: "right"
    });

    $("#stepOneContinue").click(function(){
        $("#stepOneContinue").prop("disabled",true);
        $("#stepOneContinue").html("Please wait...");
        $.ajax({
            method: "POST",
            url: "writeCredentials.php",
            data: {
                hostname: $("#hostname").val(),
                port: $("#port").val(),
                databaseName: $("#databaseName").val(),
                databaseUsername: $("#databaseUsername").val(),
                databasePassword: $("#databasePassword").val()
            },
            success: function(result){
                if(result != ""){
                    $("#stepOneErrors").html(result);
                    $("#stepOneContinue").html("Try again");
                    $("#stepOneContinue").prop("disabled",false);
                }
                else{
                    $.ajax({
                        method: "GET",
                        url: "../utils/databaseConnect.php",
                        data: {
                            test: true
                        },
                        success: function(result){
                            if(result !== ""){
                                $("#stepOneErrors").html("Database connection failed. Detailed error info:<br />" + result);
                            }
                            else{
                                $(".card").first().hide().next().show();
                            }
                            $("#stepOneContinue").html("Try again");
                            $("#stepOneContinue").prop("disabled",false);
                        },
                        error: function(error){
                            $("stepOneError").html("AJAX failed. Detailed error info:<br />" + error.status + ": " + error.statusText);
                            $("#stepOneContinue").html("Try again");
                            $("#stepOneContinue").prop("disabled",false);
                        }
                    });
                }
            },
            error: function(error){
                $("stepOneError").html("AJAX failed. Detailed error info:<br />" + error.status + ": " + error.statusText);
                $("#stepOneContinue").html("Try again");
                $("#stepOneContinue").prop("disabled",false);
            }
        });
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