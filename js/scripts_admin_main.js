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
});