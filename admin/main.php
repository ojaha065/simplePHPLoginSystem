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
                                echo "<tr>
                                    <td>",$usernames[$i][0],$extraInfo,"</td>
                                    <td><i>Set</i>
                                    <td>",$accessLevels[$i][0],"</td>
                                    <td>TODO</td>
                                    <td class='actions'></td>"; // The buttons don't do anything yet.
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