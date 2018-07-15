<?php
    /*
        Simple PHP registration and login system
        https://github.com/ojaha065/simplePHPLoginSystem
        (C) Jani Haiko, 2018
    */

    session_start();

    if(!isset($_SESSION["username"]) || !isset($_SESSION["accessLevel"])){
        header("location: ../login.php");
        die();
    }
    elseif($_SESSION["accessLevel"] === "admin"){
        require_once "../config/config.php";
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous"> <!-- Font Awesome -->
        <link rel="stylesheet" href="../css/styles_admin_main.css" type="text/css" media="all" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="../config/config.js"></script>
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
                                    <small>You can only edit your own password!</small>
                                </div>
                                <div class="form-group">
                                    <label for="modify_accessLevel">AccessLevel</label>
                                    <select class="form-control" id="modify_accessLevel" required>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="modify_lastLogin">LastLogin</label>
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
                                $string = $lastLogins[$i][0];
                                if($string == ""){
                                    $lastLogin = "<i>Never</i>";
                                }
                                else{
                                    $lastLogin = substr($string,0,2);
                                    $lastLogin .= $dateSeperator . substr($string,2,2);
                                    $lastLogin .= $dateSeperator . substr($string,4,4);
                                    $lastLogin .= " at " . substr($string,8,2);
                                    $lastLogin .= $timeSeperator . substr($string,10,2);
                                    $lastLogin .= " " . substr($string,12,2);
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
            <a href="../index.php" class="btn btn-secondary">Close admin panel</a>
        </div>
    </body>
</html>