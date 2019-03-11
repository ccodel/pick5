<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION["logged-in"]) || $_SESSION["logged-in"] == "false") {
    header("Location: ../login.php");
    exit();
} else if (isset($_SESSION["last-activity"]) && time() - $_SESSION["last-activity"] < 3600) {
    $_SESSION["last-activity"] = time();
} else {
    $_SESSION["error"] = "Your session has expired.";
    $_SESSION["email"] = null;
    $_SESSION["admin"] = null;
    $_SESSION["logged-in"] = null;
    $_SESSION["last-activity"] = null;
    $_SESSION["info"] = null;
    header("Location: ../login.php");
}

if (!isset($_SESSION['admin'])) {
    $_SESSION['error'] = "You are not admin.";
    header("Location: ../../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html id="admin-home-page">
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
            <h1>About the administrator role</h1>
            
            <hr>
            
            <div>
                <h2>Abilities</h2>
                <p>
                    Administrators have the ability to register new users, create new sessions, assign default underdogs to certain games each session, and view the results before they are sent out to the players. Administrators set up the weekly sessions and make sure the website runs smoothly.
                </p>
            </div>
            
            <hr>
            
            <div>
                <h2>Creating sessions</h2>
                <p>
                    There are 22 sessions each regular NFL season. Prior to the games for each session, a virtual session must be created on the Pick 5 website. Creating a session is easy - go to the "create session" tab on the administration home. There, you may set the various fields for the session and mark the games and the spreads. Once submitted, the games will be open to pick by the users.
                </p>
            </div>
            
            <hr>
            
            <div>
                <h2>Creating and removing users</h2>
                <p>
                    The Pick 5 website only allows picks to be made by registered users. Users may only be registered once their email is in the system, added by administrators. From there, users register using that same email and set their name and password under the "registration" page. Once registered, users may make picks.
                    
                    Note, however, that the system assigns default underdogs and losses to registered players. If a player doesn't want to participate in a certain year, they must be removed from the system, else their email will continue to pop up all over the place!
                    
                    To create a new user, go to the "create user" page and enter the email the user will use. Then select if that user should have admin privileges.
                    
                    To remove a user, go to the "remove user" page and enter the email of the user you wish to remove. This will automatically delete all data about the user, including picks they've made this season (past seasons are saved).
                </p>
            </div>
            
            <hr>
            
            <div>
                <h2>Inputting the results</h2>
                <p>
                    Inputting the results of a session is simple - just have who covered the spread for each game ready, then choose the correct team. Note that when results are saved, no calculations are done, but they will be done on viewing the player status. These are temporary until sent out to the players, where they become more permanent.
                </p>
            </div>
            
            <hr>
            
            <div>
                <h2>Seeing the player status</h2>
                <p>
                    Each week, before the final results go out, administrators must review the results and okay them. If there is an issue, minor fixes may be made (such as adjusting who covered the spread and the default underdogs). For more major changes or bugs, contact Cayden Codel (email: crcodel@mchsi.com).
                </p>
            </div>
            
            <hr>
        </section>
    </body>
</html>