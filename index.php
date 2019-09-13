<?php

include("php/api/check-login.php");
checkLogin();

//Since no additional info is required to load page, we clear it here
if (isset($_SESSION["info"]))
    $_SESSION["info"] = null;

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Pick 5 Football Club</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/main.css?<?php echo date(); ?>">
        <link rel="icon" href="img/icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>

    <?php include("php/api/mobile-api.php"); ?>

    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-brand">
                    <a id="home-link" href="../index.php">
                        <img id="icon" src="../img/icon.png" alt="Icon">
                        <?php if (!isMobile()) echo "Pick 5 Football Club"; else echo "Pick 5"; ?>
                    </a>
                </div>
                <div>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="html/admin/admin-home.php">Admin</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION["logged-in"])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="html/login.php">Login</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION["logged-in"])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="html/register.php">Register</a> </li>
                        <?php } ?>

                        <?php if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="html/logout.php">Logout</a> </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>  
        </nav>

        <?php include("php/api/header-message.php"); ?>

        <section>
            <div>
                <h1>Let the games begin!</h1>
            </div>

            <hr>

            <div>
                <h4>Ready to make your picks?</h4>
                <button type="button" onclick="window.location.replace('html/picks.php');">
                    Submit your picks
                </button>
            </div>

            <hr>

            <div>
                <h4>Want to see what other people have picked?</h4>
                <button type="button" onclick="window.location.replace('html/action.php');">
                    See the action
                </button>
            </div>

            <hr>

            <div>
                <h4>Want to see the results?</h4>
                <button type="button" onclick="window.location.replace('html/weekly-results.php');">
                    Weekly results
                </button>
                <button type="button" onclick="window.location.replace('html/yearly-results.php');">
                    Yearly results
                </button>
            </div>
        </section>

        <hr>
    </body>
</html>