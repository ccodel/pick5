<?php

include_once("../../php/api/check-login.php");
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

    <?php include_once("../../php/api/mobile-api.php"); ?>

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

        <?php include_once('../../php/api/header-message.php'); ?>

        <section>
            <h1>Send a mass email</h1>

            <h4>The email will be sent to the emails of everyone registered in the system.</h4>

            <hr>

            <form id="get-action-form" action="../../php/get/get-email-action.php" method="post">
                <p>Click the button to insert the action on games so far into the text box below.</p>
                <input type="hidden" id="action-hidden-text" name="action-hidden-text"
                       <?php if (isset($_SESSION["info"]["text"])) echo "value='" . $_SESSION["info"]["text"] . "'"; ?>>
                <input type="submit" name="submit" value="Insert action">
            </form>

            <hr>

            <form id="get-results-form" action="../../php/get/get-email-results.php" method="post">
                <p>Click the button to insert results so far for this session into the text box below.</p>
                <input type="hidden" id="results-hidden-text" name="results-hidden-text"
                       <?php if (isset($_SESSION["info"]["text"])) echo "value='" . $_SESSION["info"]["text"] . "'"; ?>>
                <input type="submit" name="submit" value="Insert results">
            </form>

            <hr>

            <form id="email-form" action="../../php/submit/submit-email.php" method="post">
                <p><strong>Subject</strong></p>
                <input type="text" name="subject"> <br> <br>
                <p><strong>Message body</strong></p>
                <textarea name="email-text" id="email-text" rows="8" cols="70"><?php if (isset($_SESSION["info"]["text"])) echo $_SESSION["info"]["text"]; ?></textarea> <br> <br>
                <!--<input type="submit" id="submit" name="submit" value="Send email">-->
            </form>

            <script src="../../js/email-create.js"></script>

            <?php if (isset($_SESSION["info"])) $_SESSION["info"] = null; ?>
        </section>
    </body>
</html>
