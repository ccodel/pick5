<?php

include("../../php/api/check-login.php");
checkLogin();
checkAdmin();

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

        <?php include("../../php/api/header-message.php"); ?>
        <?php include("../../php/api/team-names.php") ?>

        <section>
            <h1>Add default loss and underdogs to a session</h1>

            <hr>

            <form id="session-load-form" action="../../php/get/get-session-default-data.php" method="post">
                <h4>Select which session you'd like to add defaults to.</h4>
                <!-- Includes a dropdown menue with the weely sessions, named session-dropdown -->
                <?php include("../../php/api/echo-dropdown.php"); ?>
                <input type="submit" name="session-submit" id="session-submit" value="Load data">
            </form>

            <hr>

            <form id="session-set-defaults-form" action="../../php/submit/submit-defaults.php" method="post">
                <input type="hidden" id="session" name="session" 
                       <?php if (isset($_SESSION["info"]["session"])) echo "value='" . $_SESSION["info"]["session"] . "'"; ?> >
                <input type="hidden" id="games-to-play" name="games-to-play"
                       <?php if (isset($_SESSION["info"]["games-to-play"])) echo "value='" . $_SESSION["info"]["games-to-play"] . "'"; ?> >
                <input type="hidden" id="games-to-pick" name="games-to-pick"
                       <?php if (isset($_SESSION["info"]["games-to-pick"])) echo "value='" . $_SESSION["info"]["games-to-pick"] . "'"; ?> >

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
                            <td class="team-left-box">
                                <div <?php echo ('id="left-team-' . $i . '" name="left-team-' . $i . '"'); ?> >
                                    <img <?php echo ('id="team-logo-left-' . $i . '" name="team-logo-left-' . $i . '" src="../../img/team-logos/' . $_SESSION["info"]["games"][$i]["away"] . '.png"'); ?> class="team-logo-left">
                                    <input type="hidden" <?php echo "name='team-left-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["away"] . "'" ?>>
                                    <?php echo getAbbr($_SESSION["info"]["games"][$i]["away"]); ?>
                                    <br>
                                    <label <?php echo "for='team-left-loss-" . $i . "'"?>>Default loss</label>
                                    <input type="radio" <?php echo "name='team-loss-" . $i . "' id='team-left-loss-" . $i . "'"; ?> value="away"
                                           <?php if (isset($_SESSION["info"]["games"][$i]["loss"]) && $_SESSION["info"]["games"][$i]["loss"] === 0) echo "checked='checked'"; ?>> 
                                    <br>
                                    <label <?php echo "for='team-left-underdog-" . $i . "'"?>>Default Underdog</label>
                                    <input type="radio" <?php echo "name='team-underdog-" . $i . "' id='team-left-underdog-" . $i . "'"; ?> value="away"
                                           <?php if (isset($_SESSION["info"]["games"][$i]["underdog"]) && $_SESSION["info"]["games"][$i]["underdog"] === 0) echo "checked='checked'"; ?>>
                                </div>
                            </td>
                            <td>
                                <input type="hidden" <?php echo "name='game-spread-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["spread"] . "'" ?>>
                                <?php if ($_SESSION["info"]["games"][$i]["spread"] > 0) echo "+" . $_SESSION["info"]["games"][$i]["spread"]; else { echo $_SESSION["info"]["games"][$i]["spread"]; } ?>
                                <br>
                                <button type="button" <?php echo "id='clear-" . $i . "' class='clear-button'"; ?> >Clear choice</button>
                            </td>
                            <td class="team-right-box">
                                <div class="team-right-box" <?php echo ('id="right-team-' . $i . '" name="right-team-' . $i . '"'); ?> >
                                    <img <?php echo ('id="team-logo-right-' . $i . '" name="team-logo-right-' . $i . '" src="../../img/team-logos/' . $_SESSION["info"]["games"][$i]["home"] . '.png"'); ?> class="team-logo-right">
                                    <input type="hidden" <?php echo "name='team-right-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["home"] . "'" ?>>
                                    <?php echo getAbbr($_SESSION["info"]["games"][$i]["home"]); ?>
                                    <br>
                                    <label <?php echo "for='team-right-loss-" . $i . "'"?>>Default loss</label>
                                    <input type="radio" <?php echo "name='team-loss-" . $i . "' id='team-right-loss-" . $i . "'"; ?> value="home"
                                           <?php if (isset($_SESSION["info"]["games"][$i]["loss"]) && $_SESSION["info"]["games"][$i]["loss"] === 1) echo "checked='checked'"; ?>>
                                    <br>
                                    <label <?php echo "for='team-right-underdog-" . $i . "'"?>>Default underdog</label>
                                    <input type="radio" <?php echo "name='team-underdog-" . $i . "' id='team-right-underdog-" . $i . "'"; ?> value="home"
                                           <?php if (isset($_SESSION["info"]["games"][$i]["underdog"]) && $_SESSION["info"]["games"][$i]["underdog"] === 1) echo "checked='checked'"; ?>> 
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

                <script src="../../js/default-create.js"></script>

                <?php if (isset($_SESSION["info"])) $_SESSION["info"] = null; ?>
            </form>
        </section>
    </body>
</html>