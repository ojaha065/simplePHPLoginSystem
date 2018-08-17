<?php
    require_once "config/config.php";
    if($forceHTTPS){
        forceHTTPS();
    }

    session_start();

    if(!isset($_SESSION["username"])){
        header("location: login.php");
        die();
    }
    if(!isset($_SESSION["lastActivity"]) || time() - $_SESSION["lastActivity"] > $timeout){
        require_once "utils/databaseConnect.php";
        $query = $connection->prepare("UPDATE users SET rememberMeToken = :rememberMeToken WHERE username = BINARY :username");
        $query->bindParam(":username",$_SESSION["username"]);
        $query->bindParam(":rememberMeToken",$nullToken);
        $query->execute();

        setcookie("rememberMeUsername",null,time() - 2592000,"/");
        setcookie("rememberMeToken",null,time() - 2592000,"/");
        
        session_unset();
        session_destroy();
        header("location: login.php?returnCode=timeout");
        die();
    }
    else{
        $_SESSION["lastActivity"] = time();
        require_once "utils/databaseConnect.php";
        $query = $connection->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindParam("username",$_SESSION["username"]);
        $query->execute();
        $result = $query->fetch();

        if($result == NULL){
            session_unset();
            session_destroy();
            header("location: login.php?returnCode=accountNoLongerExists");
            die();
        }
        else{
            $username = $result["username"];
            $accessLevel = $result["accessLevel"];
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
        <title>Account settings</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css" type="text/css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="config/config.js"></script>
        <script src="js/scripts_account.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="modal fade" id="errorModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="errorModalTitle"></h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p id="errorModalMessage"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <h1>My account</h1>
            <span>Your account has following rights: <b><?php echo $accessLevel; ?></b></span>
            <form>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input class="form-control" type="text" id="username" value='<?php echo $username; ?>' required />
                    <small id="usernameChangeDisallowedText" style="display: none;">You cannot change your username.</small>
                    <input class="form-control" type="hidden" id="oldUsername" value='<?php echo $username; ?>' readonly disabled />
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="passwordCheckbox" />
                    <label for="passwordCheckbox">I want to change my password</label>
                </div>
                <div id="changePasswordFieldset" class="form-group" style="display: none;">
                    <label for="oldPassword">Old password:</label>
                    <input class="form-control" type="password" id="oldPassword" required />
                    <label for="newPassword">New password:</label>
                    <input class="form-control" type="password" id="newPassword" required />
                    <label for="newPasswordAgain">New password again:</label>
                    <input class="form-control" type="password" id="newPasswordAgain" required />
                </div>
                    <br />
                    <button type="button" class="btn btn-primary" id="saveChangesButton">Save</button>
                    <a href="index.php" class="btn btn-secondary mt-1 d-block">Go back</a>
            </form>
        </div>
    </body>
</html>