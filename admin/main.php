<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2019
    */

    require_once "../config/config.php";
    if($forceHTTPS){
        forceHTTPS();
    }
    session_start();

    if(!isset($_SESSION["username"]) || !isset($_SESSION["accessLevel"])){
        session_unset();
        session_destroy();
        header("location: ../login.php");
        die();
    }
    if(!isset($_SESSION["lastActivity"]) || time() - $_SESSION["lastActivity"] > $adminPanelTimeout){
        require_once "../utils/databaseConnect.php";
        $query = $connection->prepare("UPDATE users SET rememberMeToken = :rememberMeToken WHERE username = BINARY :username");
        $query->bindParam(":username",$_SESSION["username"]);
        $query->bindParam(":rememberMeToken",$nullToken);
        $query->execute();

        setcookie("rememberMeUsername",null,time() - 2592000,"/");
        setcookie("rememberMeToken",null,time() - 2592000,"/");

        session_unset();
        session_destroy();
        header("location: ../login.php?returnCode=timeout");
        die();
    }
    elseif($_SESSION["accessLevel"] === "admin"){
        $_SESSION["lastActivity"] = time();
        if($debugMode !== "IKnowWhatIAmDoing"){
            require_once "../utils/databaseConnect.php";
            $query = $connection->prepare("SELECT username FROM users ORDER BY AccessLevel");
            $query->execute();
            $usernames = $query->fetchAll();
            $query = $connection->prepare("SELECT accessLevel FROM users ORDER BY AccessLevel");
            $query->execute();
            $accessLevels = $query->fetchAll();
            $query = $connection->prepare("SELECT lastLogin FROM users ORDER BY AccessLevel");
            $query->execute();
            $lastLogins = $query->fetchAll();
        }
    }
    else{
        header("location: index.php");
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,shrink-to-fit=no" />
        <title>Admin panel</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"> <!-- Font Awesome -->
        <link rel="stylesheet" href="../css/styles_admin_main.css" type="text/css" media="all" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script src="../config/config.js"></script>
        <script src="../utils/scripts.js"></script>
        <script src="../js/scripts_admin_main.js"></script>
    </head>
    <body>
        <div class="container">
            <!-- Modals -->
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
            <div class="modal fade" id="confirmDeletionModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Are you sure?</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete account <span id="confirmDeletionModalUsername"></span> from the system?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            <button type="button" id="confirmDeletionButton" class="btn btn-danger" data-dismiss="modal">Yes, I'm sure</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modifyModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modify user</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="modify_username">Username</label>
                                    <input type="text" class="form-control" id="modify_username" readonly />
                                </div>
                                <div class="form-group">
                                    <label for="modify_password">Password</label>
                                    <input type="text" class="form-control" id="modify_password" value="SET" readonly />
                                    <small>You cannot edit or see passwords here!</small>
                                </div>
                                <div class="form-group">
                                    <label for="modify_accessLevel">Access Level</label>
                                    <select class="form-control" id="modify_accessLevel" required>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="modify_lastLogin">Last Login</label>
                                    <input type="text" class="form-control" id="modify_lastLogin" readonly />
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="modify_reset" />
                                        <label class="form-check-label" for="modify_reset">Reset</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Discard changes</button>
                            <button type="button" id="saveChangesButton" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add new account</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="addAccountUsername">Username</label>
                                    <input type="text" class="form-control" id="addAccountUsername" required />
                                    <div class="invalid-feedback">Please enter a valid username</div>
                                    <button id="generateUsername" type="button" class="btn btn-info btn-sm">Generate random</button>
                                </div>
                                <div class="form-group">
                                    <label for="addAccountPassword">Password</label>
                                    <input type="text" class="form-control" id="addAccountPassword" readonly disabled required />
                                    <small>Password will be randomly assigned.</small>
                                </div>
                                <div class="form-group">
                                    <label for="addAccountAccessLevel">AccessLevel</label>
                                    <select class="form-control" id="addAccountAccessLevel" required>
                                        <option value="admin">Admin</option>
                                        <option value="user" selected>User</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="addAccountButton" class="btn btn-primary">Create account</button>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-bordered table-hover table-responsive-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Access level</th>
                        <th>Last login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($debugMode !== "IKnowWhatIAmDoing"){
                            for($i = 0;$i < count($usernames);$i++){
                                $extraInfo = "";
                                if($usernames[$i][0] == $_SESSION["username"]){
                                    $extraInfo = " <i>(Current account)</i>";
                                }
                                $timestamp = $lastLogins[$i][0];
                                if($timestamp == ""){
                                    $lastLogin = -1;
                                }
                                else{
                                    $lastLogin = $timestamp;
                                }
             
                                echo "<tr>
                                    <td>",$usernames[$i][0],$extraInfo,"</td>
                                    <td><i>Set</i>
                                    <td class='accessLevel'>",$accessLevels[$i][0],"</td>
                                    <td class='lastLogin'>",$lastLogin,"</td>
                                    <td class='actions'></td>
                                    <td class='d-none'>",$usernames[$i][0],"</td></tr>";
                            }
                        }
                        else{
                            echo "<p class='text-danger'>No database connection because the debug mode is ON.</p>";
                        }
                    ?>
                </tbody>
            </table>

            <button data-toggle="modal" data-target="#addAccountModal" type="button" class="btn btn-info">Add new account</button>
            <a href="../index.php" class="btn btn-secondary">Close admin panel</a>

            <hr />
            <div id="theDangerZone" class="border p-2">
                <h3 class="text-danger">The Danger Zone</h3>
                <button id="openInvalidateModal" data-toggle="modal" data-target="#untrustTokensModal" type="button" class="btn btn-secondary hasTooltip" title="All users need to login again, even if they checked the 'Remember me' option.">Invalidate all login tokens</button>
            </div>

            <div class="modal fade" id="untrustTokensModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Are you sure?</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Do you really want to invalidate ALL (including your own) login tokens? They are used by the 'Remember me' checkbox. All users need to login again, even if they checked the checkbox.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="invalidateTokensButton" class="btn btn-danger">Invalidate tokens</button>
                            <button type="button" data-dismiss="modal" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>