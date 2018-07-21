<?php
    session_start();

    if(isset($_SESSION["username"])){
        header("location: index.php");
    }

    function getUsername(){
        if(isset($_SESSION["inputedUsername"])){
            return htmlspecialchars($_SESSION["inputedUsername"]);
        }
        else{
            return "";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <!--
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    -->
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,shrink-to-fit=no" />
        <title>Register an account</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="css/styles_register.css" type="text/css" media="all" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="utils/scripts.js"></script>
        <script src="config/config.js"></script>
        <script src="js/scripts_register.js"></script>
    </head>
    <body>
        <div class="container">
            <h1>Create a new account</h1>
            <noscript class="text-danger">Please allow JavaScript on this page.</noscript>
            <form method="POST" action="utils/register.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input class="form-control" type="text" id="username" name="username" value='<?php echo getUsername();?>' required />
                    <small style="display: none;">Can't think of anything? How about <b id="usernameSuggestion"></b>?</small>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input class="form-control" type="password" id="password" name="password" minlength="8" required />
                    <label for="passwordAgain">Re-enter password:</label>
                    <input class="form-control" type="password" id="passwordAgain" required />
                </div>
                <div class="form-group text-danger" id="errorArea">

                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <a href="login.php">Already have an account?</a>
        </div>
    </body>
</html>