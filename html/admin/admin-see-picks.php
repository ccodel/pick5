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
            <h1>See if players have made their picks.</h1>

            <form id="get-pick-status" action="../../php/get/get-admin-pick-status.php" method="post">
                <h4>Select which session you'd like to view.</h4>
                <select id="session" name="session">
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