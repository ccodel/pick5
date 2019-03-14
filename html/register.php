<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

?>

<!DOCTYPE html>
<html id="register-page">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Pick 5 Football Club</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/main.css">
        <link rel="icon" href="../img/icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>

    <?php include("../php/api/mobile-api.php"); ?>

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

                        <?php if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="logout.php">Logout</a> </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>  
        </nav>

        <?php include('../php/api/header-message.php'); ?>

        <div>
            <h1>
                Register to become a Pick 5 member
            </h1>

            <hr>

            <form action="../php/submit/submit-register.php" method="post">
                <div class="input-area">
                    <label for="name"><u>Last name</u></label> <br>
                    <input type="text" name="name" id="name" value=""> <br>
                </div>
                <div class="input-area">
                    <label for="email"><u>Email</u></label> <br>
                    <input type="email" name="email" id="email" value=""> <br>
                </div>
                <div class="input-area">
                    <label for="password"><u>Password</u></label> <br>
                    <input type="password" name="password" id="password" value=""> <br>
                </div>
                <div class="input-area">
                    <label for="confirm-password"><u>Confirm password</u></label> <br>
                    <input type="password" name="confirm-password" id="confirm-password" value=""> <br>
                </div>
                <div class="input-area">
                    <input type="submit" name="submit" id="submit" value="Register">
                </div>
            </form>
        </div>

        <hr>
    </body>
</html>