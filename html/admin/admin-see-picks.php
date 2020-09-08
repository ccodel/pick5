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
        <link rel="stylesheet" href="../../css/main.css?<?php echo date(); ?>">
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

                        <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="admin-home.php">Admin</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION["logged-in"])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="../login.php">Login</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION["logged-in"])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="../register.php">Register</a> </li>
                        <?php } ?>

                        <?php if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="../logout.php">Logout</a> </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>  
        </nav>

        <?php include('../../php/api/header-message.php'); ?>

        <section>
            <h1>See if players have made their picks.</h1>

            <form id="get-pick-status" action="../../php/get/get-admin-pick-status.php" method="post">
		<h4>Select which session you'd like to view.</h4>

		<?php include("../../php/api/sql-api.php"); ?>
		<?php echo getSessionScrollHTML("session-dropdown"); ?>
                <input type="submit" name="session-submit" id="session-submit" value="Load data">
            </form>

            <hr>

            <?php if (isset($_SESSION["info"])) { ?>
            <table>
                <tr>
                    <td>Name</td>
                    <td>Pick status</td>
                </tr>
                <?php for ($i = 0; $i < $_SESSION["info"]["user-num"]; $i++) { ?>
                <tr>
                    <td><?php echo $_SESSION["info"]["users"][$i]["name"]; ?></td>
                    <td><?php if ($_SESSION["info"]["users"][$i]["pick-status"]) echo "<img class='icon' src='../../img/check.png'"; else echo "--"; ?></td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>

            <?php if (isset($_SESSION["info"])) $_SESSION["info"] = null; ?>
        </section>
    </body>
</html>
