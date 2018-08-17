<?php
    require_once "config/config.php";
    if($forceHTTPS){
        forceHTTPS();
    }

    if(is_dir("install") && !isset($_GET["install"]) && !$debugSkipInstall && !isset($_GET["skipInstall"])){
        header("location: install/index.php");
        die();
    }

    session_start();

    if(isset($_SESSION["username"])){
        header("location: index.php");
    }

    if(isset($_COOKIE["rememberMeUsername"]) && isset($_COOKIE["rememberMeToken"])){
        require_once "utils/databaseConnect.php";
        $query = $connection->prepare("SELECT * FROM users WHERE username = BINARY :username");
        $query->bindParam(":username",$_COOKIE["rememberMeUsername"]);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result !== NULL && $result["rememberMeToken"] != NULL && $result["rememberMeToken"] != $nullToken){
            if($_COOKIE["rememberMeToken"] === $result["rememberMeToken"]){
                $_SESSION["username"] = $_COOKIE["rememberMeUsername"];
                $_SESSION["lastActivity"] = time();
                header("location: index.php");
            }
            else{
                $query = $connection->prepare("UPDATE users SET rememberMeToken = :rememberMeToken WHERE username = BINARY :username");
                $query->bindParam(":username",$_COOKIE["rememberMeUsername"]);
                $query->bindParam(":rememberMeToken",$nullToken);
                $query->execute();

                setcookie("rememberMeUsername",null,time() - 2592000,"/");
                setcookie("rememberMeToken",null,time() - 2592000,"/");
            }
        }
        else{
            setcookie("rememberMeUsername",null,time() - 2592000,"/");
            setcookie("rememberMeToken",null,time() - 2592000,"/");
        }
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
        <title>Log In</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css" type="text/css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="utils/scripts.js"></script>
        <script src="config/config.js"></script>
        <script src="js/scripts_login.js"></script>
    </head>
    <body>
        <div class="container">
            <h1>Login to your account</h1>
            <div id="loginMessage" class="alert alert-primary show" role="alert"></div>
            <div id="errorAlert" class="alert alert-dismissible fade show" role="alert">
                <p id="alertMessage"></p>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <noscript class="text-danger">Please allow JavaScript on this page.</noscript>
            <form method="POST" action="utils/login.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input class="form-control" type="text" id="username" name="username" value='<?php echo getUsername();?>' required />
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input class="form-control" type="password" id="password" name="password" required />
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="rememberMe" value="rememberMe" />
                    <label class="form-check-label" for="rememberMe">Remember me</label> <!-- TODO -->
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <a id="selfRegistrationLink" href="register.php">Create account</a>
            <small class="fixed-bottom">Version Beta 0.7.3</small>
        </div>
    </body>
</html>