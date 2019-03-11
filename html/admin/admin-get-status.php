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
            <h1>See player status</h1>

            <form id="get-session-status" action="../../php/get/get-admin-player-status.php" method="post">
                <h4>Select which session you'd like to view the status of.</h4>
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
                    <option value="session17" <?php if ($_SESSION["info"]["session"] == "session17") echo "selected='selected'" ?>>Week 16</option>
                    <option value="session18" <?php if ($_SESSION["info"]["session"] == "session18") echo "selected='selected'" ?>>Week 17</option>
                    <option value="session19" <?php if ($_SESSION["info"]["session"] == "session19") echo "selected='selected'" ?>>Wild Card Playoffs</option>
                    <option value="session20" <?php if ($_SESSION["info"]["session"] == "session20") echo "selected='selected'" ?>>Divisional Playoffs</option>
                    <option value="session21" <?php if ($_SESSION["info"]["session"] == "session21") echo "selected='selected'" ?>>Conference Championship</option>
                    <option value="session22" <?php if ($_SESSION["info"]["session"] == "session22") echo "selected='selected'" ?>>Superbowl</option>
                </select>
                <input type="submit" name="session-submit" id="session-submit" value="Load data">
            </form>
            
            <hr>

            <?php if (isset($_SESSION["info"])) { ?>
            <div>
                <h3>Current session results</h3>
                
                <table id="user-status-table">
                    <tr>
                        <td>Name</td>
                        <td>Wins</td>
                        <td>Losses</td>
                        <td>Points</td>
                        <td>Bonus points</td>
                        <td>Total points</td>
                        <td>P&amp;L units</td>
                    </tr>
                    <?php for ($i = 0; $i < $_SESSION["info"]["userNum"]; $i++) { ?>
                    <tr>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["name"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-wins"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-losses"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-points"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-bonus-points"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-total-points"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-p-l"]; ?> </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total: <?php echo $_SESSION["info"]["total-session-p-l"]; ?> </td>
                    </tr>
                </table>

                <hr>

                <h3>YTD Results</h3>
                <table id="user-status-table">
                    <tr>
                        <td>Name</td>
                        <td>Wins</td>
                        <td>Losses</td>
                        <td>Win %</td>
                        <td>Points</td>
                        <td>Bonus points</td>
                        <td>Total points</td>
                        <td>P&amp;L units</td>
                        <td>Steak dinner shares</td>
                    </tr>
                    <?php for ($i = 0; $i < $_SESSION["info"]["userNum"]; $i++) { ?>
                    <tr>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["name"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["ytd-wins"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["ytd-losses"]; ?> </td>
                        <td> <?php if ($_SESSION["info"]["users"][$i]["ytd-wins"] + $_SESSION["info"]["users"][$i]["ytd-losses"] === 0) echo "--"; else echo substr(($_SESSION["info"]["users"][$i]["ytd-wins"] / ($_SESSION["info"]["users"][$i]["ytd-wins"] + $_SESSION["info"]["users"][$i]["ytd-losses"])) * 100, 0, 4) . "%"; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["ytd-points"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["ytd-bonus-points"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["ytd-total-points"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["ytd-p-l"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["steak-dinner-shares"]; ?> </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total: <?php echo $_SESSION["info"]["total-ytd-p-l"]; ?> </td>
                        <td>Total: <?php echo $_SESSION["info"]["total-steak-dinner-shares"]; ?> </td>
                    </tr>
                </table>
            </div>
            <?php } ?>

            <?php if (isset($_SESSION["info"])) $_SESSION["info"] = null; ?>
        </section>
    </body>
</html>