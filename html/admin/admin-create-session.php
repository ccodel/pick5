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
            <h1>Create/edit a session</h1>

            <hr>
            <form id="session-load-form" action="../../php/get/get-session-data.php" method="post">
                <h4>Select which session you'd like to edit.</h4>
                <select id="session-dropdown" name="session-dropdown">
                    <option value="blank" <?php if (!isset($_SESSION["info"]["session"])) echo "selected=\'selected\'" ?> >------</option>
                    <option value="session1" <?php if ($_SESSION["info"]["session"] == "session1") echo "selected=\'selected\'" ?>>1 - Kickoff Game</option>
                    <option value="session2" <?php if ($_SESSION["info"]["session"] == "session2") echo "selected=\'selected\'" ?>>2 - Week 1</option>
                    <option value="session3" <?php if ($_SESSION["info"]["session"] == "session3") echo "selected=\'selected\'" ?>>3 - Week 2</option>
                    <option value="session4" <?php if ($_SESSION["info"]["session"] == "session4") echo "selected=\'selected\'" ?>>4 - Week 3</option>
                    <option value="session5" <?php if ($_SESSION["info"]["session"] == "session5") echo "selected=\'selected\'" ?>>5 - Week 4</option>
                    <option value="session6" <?php if ($_SESSION["info"]["session"] == "session6") echo "selected=\'selected\'" ?>>6 - Week 5</option>
                    <option value="session7" <?php if ($_SESSION["info"]["session"] == "session7") echo "selected=\'selected\'" ?>>7 - Week 6</option>
                    <option value="session8" <?php if ($_SESSION["info"]["session"] == "session8") echo "selected=\'selected\'" ?>>8 - Week 7</option>
                    <option value="session9" <?php if ($_SESSION["info"]["session"] == "session9") echo "selected=\'selected\'" ?>>9 - Week 8</option>
                    <option value="session10" <?php if ($_SESSION["info"]["session"] == "session10") echo "selected=\'selected\'" ?>>10 - Week 9</option>
                    <option value="session11" <?php if ($_SESSION["info"]["session"] == "session11") echo "selected=\'selected\'" ?>>11 - Week 10</option>
                    <option value="session12" <?php if ($_SESSION["info"]["session"] == "session12") echo "selected=\'selected\'" ?>>12 - Week 11</option>
                    <option value="session13" <?php if ($_SESSION["info"]["session"] == "session13") echo "selected=\'selected\'" ?>>13 - Week 12</option>
                    <option value="session14" <?php if ($_SESSION["info"]["session"] == "session14") echo "selected=\'selected\'" ?>>14 - Thanksgiving Day</option>
                    <option value="session15" <?php if ($_SESSION["info"]["session"] == "session15") echo "selected=\'selected\'" ?>>15 - Week 13</option>
                    <option value="session16" <?php if ($_SESSION["info"]["session"] == "session16") echo "selected=\'selected\'" ?>>16 - Week 14</option>
                    <option value="session17" <?php if ($_SESSION["info"]["session"] == "session17") echo "selected=\'selected\'" ?>>17 - Week 15</option>
                    <option value="session18" <?php if ($_SESSION["info"]["session"] == "session18") echo "selected=\'selected\'" ?>>18 - Week 16</option>
                    <option value="session19" <?php if ($_SESSION["info"]["session"] == "session19") echo "selected=\'selected\'" ?>>19 - Week 17</option>
                    <option value="session20" <?php if ($_SESSION["info"]["session"] == "session20") echo "selected=\'selected\'" ?>>20 - Wild Card Playoffs</option>
                    <option value="session21" <?php if ($_SESSION["info"]["session"] == "session21") echo "selected=\'selected\'" ?>>21 - Divisional Playoffs</option>
                    <option value="session22" <?php if ($_SESSION["info"]["session"] == "session22") echo "selected=\'selected\'" ?>>22 - Conference Championships</option>
                    <option value="session23" <?php if ($_SESSION["info"]["session"] == "session23") echo "selected=\'selected\'" ?>>23 - Superbowl</option>
                </select>
                <input type="submit" name="session-submit" id="session-submit" value="Create/load data">
            </form>

            <hr>

            <?php if (isset($_SESSION["info"])) { ?>
            <form id="session-create-form" action="../../php/submit/submit-session.php" method="post">
                <input type="hidden" id="session" name="session" 
                       <?php if (isset($_SESSION["info"]["session"])) echo "value='" . $_SESSION["info"]["session"] . "'"; ?> >

                <div>
                    <h4>Select the title of the game session.<br>
                        This will be displayed to all players when making their picks.</h4>                
                    <input type="text" id="session-title" name="session-title" 
                           <?php if (isset($_SESSION["info"]["session-title"])) echo ('value="' . $_SESSION["info"]["session-title"] . '"'); ?> >
                    <hr>
                </div>

                <div>
                    <h4>Select dollars per point for the session.</h4>
                    <input type="number" id="dollars-per-point" name="dollars-per-point" min="1" max="15"
                           <?php if (isset($_SESSION["info"]["dollars-per-point"])) echo ('value="' . $_SESSION["info"]["dollars-per-point"] . '"'); ?> >
                    <hr>
                </div>

                <div>
                    <h4>Select the number of games for the session.</h4>
                    <input type="number" id="games-to-play" name="games-to-play" min="1" max="16" 
                           <?php if (isset($_SESSION["info"]["games-to-play"])) echo ('value="' . $_SESSION["info"]["games-to-play"] . '"'); ?> >
                    <hr>
                </div>

                <div>
                    <h4>Choose the master kickoff time.<br>
                        This is point where the pick submission portal closes.</h4>
                    <p><strong>Please note that this time should be in CST.</strong></p>
                    <label for="master-kickoff-month">Month</label>
                    <input type="number" id="master-kickoff-month" name="master-kickoff-month" min="1" max="12"
                           <?php if (isset($_SESSION["info"]["kickoff-date"])) echo "value='" . substr($_SESSION["info"]["kickoff-date"], 5, 2) . "'"; ?>>
                    <label for="master-kickoff-day">Day</label>
                    <input type="number" id="master-kickoff-day" name="master-kickoff-day" min="1" max="31"
                           <?php if (isset($_SESSION["info"]["kickoff-date"])) echo "value='" . substr($_SESSION["info"]["kickoff-date"], 8, 2) . "'"; ?>>
                    <label for="master-kickoff-year">Year</label>
                    <input type="number" id="master-kickoff-year" name="master-kickoff-year" min="2018" max="9999"
                           <?php if (isset($_SESSION["info"]["kickoff-date"])) echo "value='" . substr($_SESSION["info"]["kickoff-date"], 0, 4) . "'"; ?>>
                    <br>
                    <label for="master-kickoff-hour">Hour (24 hr)</label>
                    <input type="number" id="master-kickoff-hour" name="master-kickoff-hour" min="0" max="23"
                           <?php if (isset($_SESSION["info"]["kickoff-time"])) echo "value='" . substr($_SESSION["info"]["kickoff-time"], 0, 2) . "'"; ?>>
                    <label for="master-kickoff-minute">Minute</label>
                    <input type="number" id="master-kickoff-minute" name="master-kickoff-minute" min="0" max="60"
                           <?php if (isset($_SESSION["info"]["kickoff-time"])) echo "value='" . substr($_SESSION["info"]["kickoff-time"], 3, 2) . "'"; ?>>
                    <hr>
                </div>

                <div>
                    <h4>Generate the game table.<br>
                        Once you're satisfied with the above information, click the button below.<br>
                        The button will create a blank table for the games.</h4>
                    <button type="button" id="enter-games">Enter games</button>
                    <hr>
                </div>
                <div>
                    <h4>Enter in the game information.</h4>
                    <strong>Please note that times should be in CST.</strong>
                    <table id="game-information-table">
                        <tr>
                            <td class="left-team-box">Away team</td>
                            <td>Game information</td>
                            <td class="right-team-box">Home team</td>
                        </tr>

                        <?php if (isset($_SESSION["info"]["games"])) { for ($i = 0; $i < count($_SESSION["info"]["games"]); $i++) { ?>
                        <tr>
                            <td class="team-left-box">
                                <div <?php echo ('id="left-team-' . $i . '" name="left-team-' . $i . '"'); ?> >
                                    <img <?php echo ('id="team-logo-left-' . $i . '" name="team-logo-left-' . $i . '" src="../../img/team-logos/' . $_SESSION["info"]["games"][$i]["away"] . '.png"'); ?> class="team-logo-left">
                                    <select <?php echo ('id="team-select-left-' . $i . '" name="team-select-left-' . $i . '" value="' . $_SESSION["info"]["games"][$i]["away"] . '"'); ?> >
                                        <option value="blank">------</option>
                                        <option value="49ers" <?php if ($_SESSION["info"]["games"][$i]["away"] == "49ers") echo "selected='selected'" ?>>49ers</option>
                                        <option value="bears" <?php if ($_SESSION["info"]["games"][$i]["away"] == "bears") echo "selected='selected'" ?>>Bears</option>
                                        <option value="bengals" <?php if ($_SESSION["info"]["games"][$i]["away"] == "bengals") echo "selected='selected'" ?>>Bengals</option>
                                        <option value="bills" <?php if ($_SESSION["info"]["games"][$i]["away"] == "bills") echo "selected='selected'" ?>>Bills</option>
                                        <option value="broncos" <?php if ($_SESSION["info"]["games"][$i]["away"] == "broncos") echo "selected='selected'" ?>>Broncos</option>
                                        <option value="browns" <?php if ($_SESSION["info"]["games"][$i]["away"] == "browns") echo "selected='selected'" ?>>Browns</option>
                                        <option value="buccaneers" <?php if ($_SESSION["info"]["games"][$i]["away"] == "buccaneers") echo "selected='selected'" ?>>Buccaneers</option>
                                        <option value="cardinals" <?php if ($_SESSION["info"]["games"][$i]["away"] == "cardinals") echo "selected='selected'" ?>>Cardinals</option>
                                        <option value="chargers" <?php if ($_SESSION["info"]["games"][$i]["away"] == "chargers") echo "selected='selected'" ?>>Chargers</option>
                                        <option value="chiefs" <?php if ($_SESSION["info"]["games"][$i]["away"] == "chiefs") echo "selected='selected'" ?>>Chiefs</option>
                                        <option value="colts" <?php if ($_SESSION["info"]["games"][$i]["away"] == "colts") echo "selected='selected'" ?>>Colts</option>
                                        <option value="cowboys" <?php if ($_SESSION["info"]["games"][$i]["away"] == "cowboys") echo "selected='selected'" ?>>Cowboys</option>
                                        <option value="dolphins" <?php if ($_SESSION["info"]["games"][$i]["away"] == "dolphins") echo "selected='selected'" ?>>Dolphins</option>
                                        <option value="eagles" <?php if ($_SESSION["info"]["games"][$i]["away"] == "eagles") echo "selected='selected'" ?>>Eagles</option>
                                        <option value="falcons" <?php if ($_SESSION["info"]["games"][$i]["away"] == "falcons") echo "selected='selected'" ?>>Falcons</option>
                                        <option value="giants" <?php if ($_SESSION["info"]["games"][$i]["away"] == "giants") echo "selected='selected'" ?>>Giants</option>
                                        <option value="jaguars" <?php if ($_SESSION["info"]["games"][$i]["away"] == "jaguars") echo "selected='selected'" ?>>Jaguars</option>
                                        <option value="jets" <?php if ($_SESSION["info"]["games"][$i]["away"] == "jets") echo "selected='selected'" ?>>Jets</option>
                                        <option value="lions" <?php if ($_SESSION["info"]["games"][$i]["away"] == "lions") echo "selected='selected'" ?>>Lions</option>
                                        <option value="packers" <?php if ($_SESSION["info"]["games"][$i]["away"] == "packers") echo "selected='selected'" ?>>Packers</option>
                                        <option value="panthers" <?php if ($_SESSION["info"]["games"][$i]["away"] == "panthers") echo "selected='selected'" ?>>Panthers</option>
                                        <option value="patriots" <?php if ($_SESSION["info"]["games"][$i]["away"] == "patriots") echo "selected='selected'" ?>>Patriots</option>
                                        <option value="raiders" <?php if ($_SESSION["info"]["games"][$i]["away"] == "raiders") echo "selected='selected'" ?>>Raiders</option>
                                        <option value="rams" <?php if ($_SESSION["info"]["games"][$i]["away"] == "rams") echo "selected='selected'" ?>>Rams</option>
                                        <option value="ravens" <?php if ($_SESSION["info"]["games"][$i]["away"] == "ravens") echo "selected='selected'" ?>>Ravens</option>
                                        <option value="redskins" <?php if ($_SESSION["info"]["games"][$i]["away"] == "redskins") echo "selected='selected'" ?>>Redskins</option>
                                        <option value="saints" <?php if ($_SESSION["info"]["games"][$i]["away"] == "saints") echo "selected='selected'" ?>>Saints</option>
                                        <option value="seahawks" <?php if ($_SESSION["info"]["games"][$i]["away"] == "seahawks") echo "selected='selected'" ?>>Seahawks</option>
                                        <option value="steelers" <?php if ($_SESSION["info"]["games"][$i]["away"] == "steelers") echo "selected='selected'" ?>>Steelers</option>
                                        <option value="texans" <?php if ($_SESSION["info"]["games"][$i]["away"] == "texans") echo "selected='selected'" ?>>Texans</option>
                                        <option value="titans" <?php if ($_SESSION["info"]["games"][$i]["away"] == "titans") echo "selected='selected'" ?>>Titans</option>
                                        <option value="vikings" <?php if ($_SESSION["info"]["games"][$i]["away"] == "vikings") echo "selected='selected'" ?>>Vikings</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <p><strong>Kickoff (CST)</strong></p>
                                <label <?php echo "for='game-month-" . $i . "'"; ?>>Month </label>
                                <input type="number" <?php echo "id='game-month-" . $i . "' name='game-month-" . $i . "'"; ?> min="1" max="12"
                                       <?php echo "value='" . substr($_SESSION["info"]["games"][$i]["date"], 5, 2) . "'"; ?>>
                                <br>
                                <label <?php echo "for='game-day-" . $i . "'"; ?>>Day </label>
                                <input type="number" <?php echo "id='game-day-" . $i . "' name='game-day-" . $i . "'"; ?> min="1" max="31"
                                       <?php echo "value='" . substr($_SESSION["info"]["games"][$i]["date"], 8, 2) . "'"; ?>>
                                <br>
                                <label <?php echo "for='game-year-" . $i . "'"; ?>>Year </label>
                                <input type="number" <?php echo "id='game-year-" . $i . "' name='game-year-" . $i . "'"; ?> min="2018" max="9999"
                                       <?php echo "value='" . substr($_SESSION["info"]["games"][$i]["date"], 0, 4) . "'"; ?>>
                                <br>
                                <label <?php echo "for='game-hour-" . $i . "'"; ?>>Hour (24hr) </label>
                                <input type="number" <?php echo "id='game-hour-" . $i . "' name='game-hour-" . $i . "'"; ?> min="0" max="23"
                                       <?php echo "value='" . substr($_SESSION["info"]["games"][$i]["time"], 0, 2) . "'"; ?>>
                                <br>
                                <label <?php echo "for='game-minute-" . $i . "'"; ?>>Minute </label>
                                <input type="number" <?php echo "id='game-minute-" . $i . "' name='game-minute-" . $i . "'"; ?> min="0" max="59"
                                       <?php echo "value='" . substr($_SESSION["info"]["games"][$i]["time"], 3, 2) . "'"; ?>>
                                <br>
                                <br>
                                <label <?php echo "for='spread-" . $i . "'"; ?>>Spread </label>
                                <input type="number" min="-28.5" max="28.5" step="1" <?php echo 'id="game-spread-' . $i . '" name="game-spread-' . $i . '" value="' . $_SESSION["info"]["games"][$i]["spread"] . '"'; ?> >
                            </td>
                            <td class="team-right-box">
                                <div class="team-right-box" <?php echo ('id="right-team-' . $i . '" name="right-team-' . $i . '"'); ?> >
                                    <img <?php echo ('id="team-logo-right-' . $i . '" name="team-logo-right-' . $i . '" src="../../img/team-logos/' . $_SESSION["info"]["games"][$i]["home"] . '.png"'); ?> class="team-logo-right">
                                    <select <?php echo ('id="team-select-right-' . $i . '" name="team-select-right-' . $i . '" value="' . $_SESSION["info"]["games"][$i]["home"] . '"'); ?> >
                                        <option value="blank">------</option>
                                        <option value="49ers" <?php if ($_SESSION["info"]["games"][$i]["home"] == "49ers") echo "selected='selected'" ?>>49ers</option>
                                        <option value="bears" <?php if ($_SESSION["info"]["games"][$i]["home"] == "bears") echo "selected='selected'" ?>>Bears</option>
                                        <option value="bengals" <?php if ($_SESSION["info"]["games"][$i]["home"] == "bengals") echo "selected='selected'" ?>>Bengals</option>
                                        <option value="bills" <?php if ($_SESSION["info"]["games"][$i]["home"] == "bills") echo "selected='selected'" ?>>Bills</option>
                                        <option value="broncos" <?php if ($_SESSION["info"]["games"][$i]["home"] == "broncos") echo "selected='selected'" ?>>Broncos</option>
                                        <option value="browns" <?php if ($_SESSION["info"]["games"][$i]["home"] == "browns") echo "selected='selected'" ?>>Browns</option>
                                        <option value="buccaneers" <?php if ($_SESSION["info"]["games"][$i]["home"] == "buccaneers") echo "selected='selected'" ?>>Buccaneers</option>
                                        <option value="cardinals" <?php if ($_SESSION["info"]["games"][$i]["home"] == "cardinals") echo "selected='selected'" ?>>Cardinals</option>
                                        <option value="chargers" <?php if ($_SESSION["info"]["games"][$i]["home"] == "chargers") echo "selected='selected'" ?>>Chargers</option>
                                        <option value="chiefs" <?php if ($_SESSION["info"]["games"][$i]["home"] == "chiefs") echo "selected='selected'" ?>>Chiefs</option>
                                        <option value="colts" <?php if ($_SESSION["info"]["games"][$i]["home"] == "colts") echo "selected='selected'" ?>>Colts</option>
                                        <option value="cowboys" <?php if ($_SESSION["info"]["games"][$i]["home"] == "cowboys") echo "selected='selected'" ?>>Cowboys</option>
                                        <option value="dolphins" <?php if ($_SESSION["info"]["games"][$i]["home"] == "dolphins") echo "selected='selected'" ?>>Dolphins</option>
                                        <option value="eagles" <?php if ($_SESSION["info"]["games"][$i]["home"] == "eagles") echo "selected='selected'" ?>>Eagles</option>
                                        <option value="falcons" <?php if ($_SESSION["info"]["games"][$i]["home"] == "falcons") echo "selected='selected'" ?>>Falcons</option>
                                        <option value="giants" <?php if ($_SESSION["info"]["games"][$i]["home"] == "giants") echo "selected='selected'" ?>>Giants</option>
                                        <option value="jaguars" <?php if ($_SESSION["info"]["games"][$i]["home"] == "jaguars") echo "selected='selected'" ?>>Jaguars</option>
                                        <option value="jets" <?php if ($_SESSION["info"]["games"][$i]["home"] == "jets") echo "selected='selected'" ?>>Jets</option>
                                        <option value="lions" <?php if ($_SESSION["info"]["games"][$i]["home"] == "lions") echo "selected='selected'" ?>>Lions</option>
                                        <option value="packers" <?php if ($_SESSION["info"]["games"][$i]["home"] == "packers") echo "selected='selected'" ?>>Packers</option>
                                        <option value="panthers" <?php if ($_SESSION["info"]["games"][$i]["home"] == "panthers") echo "selected='selected'" ?>>Panthers</option>
                                        <option value="patriots" <?php if ($_SESSION["info"]["games"][$i]["home"] == "patriots") echo "selected='selected'" ?>>Patriots</option>
                                        <option value="raiders" <?php if ($_SESSION["info"]["games"][$i]["home"] == "raiders") echo "selected='selected'" ?>>Raiders</option>
                                        <option value="rams" <?php if ($_SESSION["info"]["games"][$i]["home"] == "rams") echo "selected='selected'" ?>>Rams</option>
                                        <option value="ravens" <?php if ($_SESSION["info"]["games"][$i]["home"] == "ravens") echo "selected='selected'" ?>>Ravens</option>
                                        <option value="redskins" <?php if ($_SESSION["info"]["games"][$i]["home"] == "redskins") echo "selected='selected'" ?>>Redskins</option>
                                        <option value="saints" <?php if ($_SESSION["info"]["games"][$i]["home"] == "saints") echo "selected='selected'" ?>>Saints</option>
                                        <option value="seahawks" <?php if ($_SESSION["info"]["games"][$i]["home"] == "seahawks") echo "selected='selected'" ?>>Seahawks</option>
                                        <option value="steelers" <?php if ($_SESSION["info"]["games"][$i]["home"] == "steelers") echo "selected='selected'" ?>>Steelers</option>
                                        <option value="texans" <?php if ($_SESSION["info"]["games"][$i]["home"] == "texans") echo "selected='selected'" ?>>Texans</option>
                                        <option value="titans" <?php if ($_SESSION["info"]["games"][$i]["home"] == "titans") echo "selected='selected'" ?>>Titans</option>
                                        <option value="vikings" <?php if ($_SESSION["info"]["games"][$i]["home"] == "vikings") echo "selected='selected'" ?>>Vikings</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <?php } } ?>

                    </table>
                    <hr>
                </div>

                <div>
                    <h4>Select the number of picks players are required to submit.<br>
                        Pick submissions that contain fewer than this number of teams will not be accepted.</h4>
                    <input type="number" id="games-to-pick" name="games-to-pick" min="1" max="5"
                           <?php if (isset($_SESSION["info"]["games-to-pick"])) { echo ('value="' . $_SESSION["info"]["games-to-pick"] . '"'); } ?> >
                    <hr>
                </div>

                <div>
                    <h4>Check the box if players should receive a bonus when they go 5-0.</h4>
                    <input type="checkbox" id="perfect-bonus" name="perfect-bonus" 
                           <?php if (isset($_SESSION["info"]["bonus"]) && $_SESSION["info"]["bonus"] == 1) echo ('checked="true"'); ?> >
                    <hr>
                </div>

                <div>
                    <h4>Check the box if 0-5 players get additional shares of the steak dinner.</h4>
                    <input type="checkbox" id="steak-dinner" name="steak-dinner" 
                           <?php if (isset($_SESSION["info"]["steak-dinner"]) && $_SESSION["info"]["steak-dinner"] == 1) echo ('checked="true"'); ?> >
                    <hr>
                </div>

                <div>
                    <h4>Submit your changes for this session.</h4>
                    <input type="submit" id="submit" name="submit" value="Submit">
                </div>

                <script src="../../js/session-create.js"></script>

                <?php if (isset($_SESSION["info"])) $_SESSION["info"] = null; ?>
            </form>
            <?php } ?>
        </section>
    </body>
</html>