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
            error: function(error){
                $("#errorModalTitle").html("Error");
                $("#errorModalMessage").html("Error " + error.status + ": " + error.statusText);
                $("#errorModal").modal("show");
            }
        });
    });
});