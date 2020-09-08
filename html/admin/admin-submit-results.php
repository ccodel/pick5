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

        <?php include("../../php/api/header-message.php"); ?>

        <section>
            <h1>Input the results</h1>

            <hr>

            <form id="session-load-form" action="../../php/get/get-session-results-data.php" method="post">
		<h4>Select which session you'd like to input results for.</h4>

		<?php include("../../php/api/sql-api.php"); ?>
		<?php echo getSessionScrollHTML("session-dropdown"); ?>
                <input type="submit" name="session-submit" id="session-submit" value="Load data">
            </form>

            <hr>

            <form id="session-set-results-form" action="../../php/submit/submit-results.php" method="post">
                <input type="hidden" id="session" name="session" 
                       <?php if (isset($_SESSION["info"]["session"])) echo "value='" . $_SESSION["info"]["session"] . "'"; ?> >
                <input type="hidden" id="games-to-play" name="games-to-play"
                       <?php if (isset($_SESSION["info"]["games-to-play"])) echo "value='" . $_SESSION["info"]["games-to-play"] . "'"; ?> >

                <div>
                    <h4>Enter in the game information</h4>
                    <table id="game-information-table">
                        <tr>
                            <td class="left-team-box">Away team</td>
                            <td>Spread</td>
                            <td class="right-team-box">Home team</td>
                        </tr>

                        <?php if (isset($_SESSION["info"]["games"])) { for ($i = 0; $i < count($_SESSION["info"]["games"]); $i++) { ?>
                        <tr>
                            <td class="left-team-box">
                                <div <?php echo ('id="left-team-' . $i . '" name="left-team-' . $i . '"'); ?> >
                                    <label <?php echo "for='team-left-victor-" . $i . "'" ?> >
                                        <img <?php echo ('id="team-logo-left-' . $i . '" name="team-logo-left-' . $i . '" src="../../img/team-logos/' . $_SESSION["info"]["games"][$i]["away"] . '.png"'); ?> class="team-logo-left">
                                    </label>
                                    <input type="hidden" <?php echo "name='team-left-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["away"] . "'" ?>>
                                    <?php echo $teams[$_SESSION["info"]["games"][$i]["away"]]; ?>
                                    <label <?php echo "for='team-left-victor-" . $i . "'" ?> >Covered</label> 
                                    <input type="radio" <?php echo "name='team-victor-" . $i . "' id='team-left-victor-" . $i . "'"; ?> value="away"
                                           <?php if (isset($_SESSION["info"]["games"][$i]["victor"]) && $_SESSION["info"]["games"][$i]["victor"] === 0) echo "checked='checked'"; ?>>
                                </div>
                            </td>
                            <td>
                                <input type="hidden" <?php echo "name='game-spread-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["spread"] . "'" ?>>
                                <?php if ($_SESSION["info"]["games"][$i]["spread"] > 0) echo "+" . $_SESSION["info"]["games"][$i]["spread"]; else { echo $_SESSION["info"]["games"][$i]["spread"]; } ?>
                                <br>
                                <button type="button" <?php echo "id='clear-" . $i . "' class='clear-button'"; ?> >Clear choice</button>
                            </td>
                            <td class="right-team-box">
                                <div class="team-right-box" <?php echo ('id="right-team-' . $i . '" name="right-team-' . $i . '"'); ?> >
                                    <label <?php echo "for='team-right-victor-" . $i . "'" ?> >
                                        <img <?php echo ('id="team-logo-right-' . $i . '" name="team-logo-right-' . $i . '" src="../../img/team-logos/' . $_SESSION["info"]["games"][$i]["home"] . '.png"'); ?> class="team-logo-right">
                                    </label>
                                    <input type="hidden" <?php echo "name='team-right-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["home"] . "'" ?>>
                                    <?php echo $teams[$_SESSION["info"]["games"][$i]["home"]]; ?>
                                    <label <?php echo "for='team-right-victor-" . $i . "'" ?> >Covered</label>
                                    <input type="radio" <?php echo "name='team-victor-" . $i . "' id='team-right-victor-" . $i . "'"; ?> value="home"
                                           <?php if (isset($_SESSION["info"]["games"][$i]["victor"]) && $_SESSION["info"]["games"][$i]["victor"] === 1) echo "checked='checked'"; ?>>
                                </div>
                            </td>
                        </tr>
                        <?php } } ?>
                    </table>
                    <hr>
                </div>

                <div>
                    <h4>Submit your changes for this session.</h4>
                    <input type="submit" id="submit" name="submit" value="Submit">
                </div>

                <script src="../../js/results-create.js"></script>

                <?php if (isset($_SESSION["info"])) $_SESSION["info"] = null ?>
            </form>
        </section>
    </body>
</html>
