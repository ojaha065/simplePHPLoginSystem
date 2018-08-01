<?php
    require "../config/config.php";
    if($forceHTTPS){
        forceHTTPS();
    }
    include "../utils/credentials.php";
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
        <title>Simple PHP login system | Installation</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css" type="text/css" />
        <style>
            .card, #stepFourContinue{
                display: none;
            }
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="../utils/scripts.js"></script>
        <script src="scripts.js"></script>
    </head>
    <body>
        <div class="container">
            <h1>Let's set up your database</h1>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Step 1</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">First, let's try opening a connection to your database. Insert your database hostname, port, name and credentials into the corresponding fields below.</p>
                    <div class="table-responsive-sm">
                        <table class="table table-bordered table-hover table-sm">
                            <caption>Current settings</caption>
                            <thead class="thead-light">
                                <tr>
                                    <th>Setting</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Hostname</td>
                                    <td><input id="hostname" data-toggle="tooltip" title="e.g. 'localhost' or 'exampleSQLHost.com'" type="text" required value='<?php echo $host; ?>' /></td>
                                </tr>
                                <tr>
                                    <td>Port</td>
                                    <td><input id="port" data-toggle="tooltip" title="MySQL default: 3306" type="number" value='<?php echo $port; ?>' /></td>
                                </tr>
                                <tr>
                                    <td>Database name</td>
                                    <td><input id="databaseName" data-toggle="tooltip" title="Name of your MySQL database. Is case-sensitive!" type="text" required value='<?php echo $databaseName; ?>' /></td>
                                </tr>
                                <tr>
                                    <td>Database username</td>
                                    <td><input id="databaseUsername" data-toggle="tooltip" title="If you don't know, try 'root' (Using root might be a security risk!)" type="text" required value='<?php echo $databaseUsername; ?>' /></td>
                                </tr>
                                <tr>
                                    <td>Database password</td>
                                    <td><input id="databasePassword" data-toggle="tooltip" title="Please use secure passwords!" type="password" required value='<?php echo $databasePassword; ?>' /></td>
                                </tr>
                            </tbody>
                        </table>
                        <small><a href="index.php">Refresh table (Loads current saved values)</a></small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" id="stepOneContinue" class="btn btn-success">Save settings and test the database connection</button>
                    <p id="stepOneErrors" class="text-danger"></p>
                </div>
            </div>

            <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Step 2</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-success">Database connection: OK</p>
                        <p class="card-text">Next, we'll create a new table in your database.</p>
                        <a href="createTable.php" class="btn btn-primary">Create new table now</a>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="tableManualButton" class="btn btn-secondary">I'll create the table manually</button>
                    </div>
            </div>

            <div id="stepThree" class="card">
                    <div class="card-header">
                        <h4 class="card-title">Step 3</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Next, we'll create a new user account with admin rights.</p>
                        <p class="card-text">That account will get a random password. You will be able to change the password later.</p>
                        <a href="createAdmin.php" class="btn btn-primary">Create account</a>
                    </div>
            </div>

            <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Step 4</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Now you can test that everything in working correctly. Click to link below to open the login page and log in with the newly created account.</p>
                        <p>Remember to change the password of the account in the account management page.</p>
                        <p>Come back here after testing.</p>
                        <div class="border">
                            Username: admin
                            <br />
                            Password: <span id="adminPassword"></span>
                        </div>
                        <a href="../login.php?install=true" target="_blank" id="openLoginPage" class="card-link">Log in</a>
                    </div>
                    <div class="card-footer">
                        <button id="stepFourContinue" type="button" class="btn btn-success">Continue</button>
                    </div>
            </div>

            <div id="finalStep" class="card">
                    <div class="card-header">
                        <h4 class="card-title">Good to go!</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Everything is now set up. You must remove the <b>/install</b> folder before using this in live environment.</p>
                    </div>
                    <div class="card-footer">
                        <a href="../removeInstall.php" class="btn btn-primary">Remove the folder and go to the front page</a>
                    </div>
            </div>
        </div>
    </body>
</html>