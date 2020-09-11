<?php

include_once("../php/api/check-login.php");

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Pick 5 Football Club</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/main.css?<?php echo date(); ?>">
        <link rel="icon" href="../img/icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>

    <?php include_once("../php/api/mobile-api.php"); ?>

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
                        <li class="nav-item"> <a class="nav-link" href="../index.php">Home</a> </li>
                        
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="admin/admin-home.php">Admin</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION['logged-in'])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="login.php">Login</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION['logged-in'])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="register.php">Register</a> </li>
                        <?php } ?>
                    </ul>
                </div>
            </div> 
        </nav>
        
         <?php include_once('../php/api/header-message.php'); ?>

        <div>
            <h2>
                You are currently signed in.
            </h2>

            <h3>
                Are you sure you would like to sign out?
            </h3>

            <br>

            <form action="../php/submit/submit-logout.php" method="post">
                <button type="submit" name="logout" id="logout">
                    Logout
                </button>
            </form>

        </div>
    </body>
</html>
