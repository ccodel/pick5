<?php

include("../../php/api/check-login.php");
checkLogin();
checkAdmin();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Pick 5 Football Club</title>
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/main.css">
        <link rel="icon" href="../../img/icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>

    <?php include("../../php/api/mobile-api.php"); ?>

    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-brand">
                    <a id="home-link" href="../../index.php">
                        <img id="icon" src="../../img/icon.png" alt="Icon">
                        <?php if (!isMobile()) echo "Pick 5 Football Club"; else echo "Pick 5"; ?>
                    </a>
                </div>
                <div>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item"> <a class="nav-link" href="../../index.php">Home</a> </li>

                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="admin-home.php">Admin</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION['logged-in'])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="../login.php">Login</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION['logged-in'])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="../register.php">Register</a> </li>
                        <?php } ?>

                        <?php if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="../logout.php">Logout</a> </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>  
        </nav>

        <?php include('../../php/api/header-message.php'); ?>

        <section>
            <h1>Welcome, administrator</h1>

            <hr>

            <div>
                <h3>Create/edit the current session's games</h3>
                <button type="button" onclick="window.location.replace('admin-create-session.php');">Set up session</button>
            </div>

            <hr>

            <div>
                <h3>Set the default games for the current session</h3>
                <button type="button" onclick="window.location.replace('admin-submit-defaults.php');">Set default picks</button>
            </div>

            <hr>

            <div>
                <h3>Input the current session's results</h3>
                <button type="button" onclick="window.location.replace('admin-submit-results.php');">Input game results</button>
            </div>

            <hr>

            <div>
                <h3>View player status for the current session</h3>
                <button type="button" onclick="window.location.replace('admin-get-status.php');">View status</button>
            </div>

            <hr>
            
            <div>
                <h3>View pick status for the current session</h3>
                <button type="button" onclick="window.location.replace('admin-see-picks.php');">View picks</button>
            </div>
            
            <hr>
            
            <div>
                <h3>Manage users with registration, removal, and password reset</h3>
                <button type="button" onclick="window.location.replace('admin-user-management.php');">See the users</button>
            </div>
            
            <hr>
            
            <div>
                <h3>Send a mass email to registered users</h3>
                <button type="button" onclick="window.location.replace('admin-send-email.php');">Send an email</button>
            </div>
            
            <hr>

            <div>
                <h3>About the administrator role</h3>
                <button type="button" onclick="window.location.replace('admin-role.php')">About administrators</button>
            </div>

            <hr>
        </section>
    </body>
</html>