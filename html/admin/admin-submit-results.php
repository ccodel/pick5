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

if (!isset($_SESSION["admin"])) {
    $_SESSION["error"] = "You are not admin.";
    header("Location: ../../index.php");
    exit();
}

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
            <h1>Input the results</h1>

            <hr>

            <form id="session-load-form" action="../../php/get/get-session-results-data.php" method="post">
                <h4>Select which session you'd like to input results for.</h4>
                <select id="session-dropdown" name="session-dropdown">
                    <option value="blank" <?php if (!isset($_SESSION["info"]["session"])) echo "selected='selected'" ?> >------</option>
                    <option value="session1" <?php if ($_SESSION["info"]["session"] == "session1") echo "selected='selected'" ?>>Session 1</option>
                    <option value="session2" <?php if ($_SESSION["info"]["session"] == "session2") echo "selected='selected'" ?>>Session 2</option>
                    <option value="session3" <?php if ($_SESSION["info"]["session"] == "session3") echo "selected='selected'" ?>>Session 3</option>
                    <option value="session4" <?php if ($_SESSION["info"]["session"] == "session4") echo "selected='selected'" ?>>Session 4</option>
                    <option value="session5" <?php if ($_SESSION["info"]["session"] == "session5") echo "selected='selected'" ?>>Session 5</option>
                    <option value="session6" <?php if ($_SESSION["info"]["session"] == "session6") echo "selected='selected'" ?>>Session 6</option>
                    <option value="session7" <?php if ($_SESSION["info"]["session"] == "session7") echo "selected='selected'" ?>>Session 7</option>
                    <option value="session8" <?php if ($_SESSION["info"]["session"] == "session8") echo "selected='selected'" ?>>Session 8</option>
                    <option value="session9" <?php if ($_SESSION["info"]["session"] == "session9") echo "selected='selected'" ?>>Session 9</option>
                    <option value="session10" <?php if ($_SESSION["info"]["session"] == "session10") echo "selected='selected'" ?>>Session 10</option>
                    <option value="session11" <?php if ($_SESSION["info"]["session"] == "session11") echo "selected='selected'" ?>>Session 11</option>
                    <option value="session12" <?php if ($_SESSION["info"]["session"] == "session12") echo "selected='selected'" ?>>Session 12</option>
                    <option value="session13" <?php if ($_SESSION["info"]["session"] == "session13") echo "selected='selected'" ?>>Session 13</option>
                    <option value="session14" <?php if ($_SESSION["info"]["session"] == "session14") echo "selected='selected'" ?>>Session 14</option>
                    <option value="session15" <?php if ($_SESSION["info"]["session"] == "session15") echo "selected='selected'" ?>>Session 15</option>
                    <option value="session16" <?php if ($_SESSION["info"]["session"] == "session16") echo "selected='selected'" ?>>Session 16</option>
                    <option value="session17" <?php if ($_SESSION["info"]["session"] == "session17") echo "selected='selected'" ?>>Session 17</option>
                    <option value="session18" <?php if ($_SESSION["info"]["session"] == "session18") echo "selected='selected'" ?>>Session 18</option>
                    <option value="session19" <?php if ($_SESSION["info"]["session"] == "session19") echo "selected='selected'" ?>>Session 19</option>
                    <option value="session20" <?php if ($_SESSION["info"]["session"] == "session20") echo "selected='selected'" ?>>Session 20</option>
                    <option value="session21" <?php if ($_SESSION["info"]["session"] == "session21") echo "selected='selected'" ?>>Session 21</option>
                    <option value="session22" <?php if ($_SESSION["info"]["session"] == "session22") echo "selected='selected'" ?>>Session 22</option>
                </select>
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