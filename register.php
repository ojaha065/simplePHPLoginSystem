<?php
    session_start();

    if(isset($_SESSION["username"])){ // Onko tämä käyttäjä jo kirjautunut?
        header("location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
    <!--
        Simple PHP registration and login system
        (C) Jani Haiko, 2018
    -->
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,shrink-to-fit=no" />
        <title>Register an account</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css" type="text/css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="utils/scripts.js"></script>
        <script src="js/scripts_register.js"></script>
    </head>
    <body>
        <div class="container">
            <h1>Create a new account</h1>
            <noscript class="text-danger">Please allow JavaScript on this page.</noscript>
            <form method="POST" action="utils/register.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input class="form-control" type="text" id="username" name="username" placeholder="At least 3 characters long" minlength="3" maxlength="30" required /> <!-- You can modify min/maxlength to fit your needs. -->
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input class="form-control" type="password" id="password" name="password" minlength="8" maxlength="255" required />
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