<?php

include("../php/api/check-login.php");
checkLogin();

if (!isset($_SESSION["info"])) {
    header("Location: ../php/get/get-picks.php");
    exit();
}

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

                        <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="admin/admin-home.php">Admin</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION["logged-in"])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="login.php">Login</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION["logged-in"])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="register.php">Register</a> </li>
                        <?php } ?>

                        <?php if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="logout.php">Logout</a> </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>

        <?php if (isset($_SESSION["error"])) $_SESSION["msg"] = null; ?>
        <?php include("../php/api/header-message.php"); ?>
        <?php include("../php/api/team-names.php"); ?>

        <?php if (isset($_SESSION["info"])) { ?>
        <section>
            <div>
                <h1><?php if (isset($_SESSION["info"]["session-title"])) echo $_SESSION["info"]["session-title"]; ?></h1>
                <h2>Make your picks below.</h2>
                <h4><?php if (isset($_SESSION["info"]["games-to-pick"])) echo "You must pick " . $_SESSION["info"]["games-to-pick"] . " games."; ?></h4>
            </div>

            <div id="picks-form">
                <hr>

                <h4>Toggle the game view.</h4>
                <button id="toggle" type="button">View by favored/underdog</button> <br> <br>

                <form id="get-picks-form" action="../php/submit/submit-picks.php" method="post">
                    <input type="hidden" id="session" name="session" 
                           <?php if (isset($_SESSION["info"]["session"])) echo "value='" . $_SESSION["info"]["session"] . "'"; ?> >
                    <input type="hidden" id="games-to-play" name="games-to-play"
                           <?php if (isset($_SESSION["info"]["games-to-play"])) echo "value='" . $_SESSION["info"]["games-to-play"] . "'"; ?> >
                    <input type="hidden" id="games-to-pick" name="games-to-pick"
                           <?php if (isset($_SESSION["info"]["games-to-pick"])) echo "value='" . $_SESSION["info"]["games-to-pick"] . "'"; ?> >

                    <?php if (isset($_SESSION["info"]["games"])) { for ($i = 0; $i < count($_SESSION["info"]["games"]); $i++) { ?>
                    <?php if ($i === 0 || $_SESSION["info"]["games"][$i]["date"] != $_SESSION["info"]["games"][$i - 1]["date"]) { ?>
                    <?php echo "<h4><strong>" . date("l", strtotime($_SESSION["info"]["games"][$i]["date"])) . "</strong></h4>"; ?>
                    <table>
                        <?php } ?>
                        
                        <?php if ($i === 0) { ?>
                        <tr>
                            <td class="left-team-box" id="left-header">Away</td>
                            <td></td>
                            <td class="right-team-box" id="right-header">Home</td>
                        </tr>
                        <?php } ?>

                        <tr>
                            <td class="left-team-box" <?php if (isset($_SESSION["info"]["games"][$i]["disabled"]) && $_SESSION["info"]["games"][$i]["disabled"]) echo "style='background-image: linear-gradient(#aaa, #ccc);'"?>>
                                <div <?php echo 'id="left-team-' . $i . '"'; ?> >
                                    <input type="hidden" <?php echo "id='left-" . $i . "' name='left-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["away"] . "'"; ?>>
                                    <input type="hidden" <?php echo "name='left-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["away"] . "'"; ?>>
                                    <label <?php echo "for='team-left-victor-" . $i . "'"?> >
                                        <img <?php echo ('id="team-logo-left-' . $i . '" src="../../img/team-logos/' . $_SESSION["info"]["games"][$i]["away"] . '.png"'); ?> class="big-logo">
                                        <strong> <?php echo getAbbr($_SESSION["info"]["games"][$i]["away"]); ?> </strong>
                                        <strong <?php echo "id='left-spread-" . $i . "'"; ?>>
                                            <?php if ($_SESSION["info"]["games"][$i]["spread"] > 0) echo "(+" . $_SESSION["info"]["games"][$i]["spread"] . ")"; else echo "(" . $_SESSION["info"]["games"][$i]["spread"] . ")"; ?>
                                        </strong>
                                    </label>
                                    <input type="radio" <?php echo "name='team-victor-" . $i . "' id='team-left-victor-" . $i . "'"; ?> value="away"
                                           <?php if (isset($_SESSION["info"]["games"][$i]["pick"]) && $_SESSION["info"]["games"][$i]["pick"] === 0) echo "checked='checked'"; else if (isset($_SESSION["info"]["games"][$i]["disabled"]) && $_SESSION["info"]["games"][$i]["disabled"]) echo "disabled='disabled'"; ?> >
                                    <br>
                                    <br>
                                </div>
                            </td>
                            <td <?php if (isset($_SESSION["info"]["games"][$i]["disabled"]) && $_SESSION["info"]["games"][$i]["disabled"]) echo "style='background-image: linear-gradient(#aaa, #ccc);'"?>>
                                <input type="hidden" <?php echo "name='spread-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["spread"] . "'"; ?>>
                                <input type="hidden" <?php echo "name='disabled-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["disabled"] . "'"; ?>>
                                <input type="hidden" <?php echo "name='date-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["date"] . "'"; ?>>
                                <?php if (!$_SESSION["info"]["games"][$i]["disabled"]) { ?>
                                <button type="button" <?php echo "id='clear-" . $i . "'"; ?>>Clear pick</button>
                                <?php } ?>
                            </td>
                            <td class="right-team-box" <?php if (isset($_SESSION["info"]["games"][$i]["disabled"]) && $_SESSION["info"]["games"][$i]["disabled"]) echo "style='background-image: linear-gradient(#aaa, #ccc);'"?>>
                                <div <?php echo 'id="right-team-' . $i . '"'; ?> >
                                    <input type="hidden" <?php echo "id='right-" . $i . "' name='right-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["home"] . "'"; ?>>
                                    <input type="hidden" <?php echo "name='right-" . $i . "' value='" . $_SESSION["info"]["games"][$i]["home"] . "'"; ?>>
                                    <label <?php echo "for='team-right-victor-" . $i . "'"?> >
                                        <img <?php echo ('id="team-logo-right-' . $i . '" src="../../img/team-logos/' . $_SESSION["info"]["games"][$i]["home"] . '.png"'); ?> class="big-logo">
                                        <strong> <?php echo strtoupper(getAbbr($_SESSION["info"]["games"][$i]["home"])); ?> </strong>
                                        <strong <?php echo "id='right-spread-'" . $i . "'"; ?>>
                                            <?php if ($_SESSION["info"]["games"][$i]["spread"] < 0) echo "(+" . (-1 * $_SESSION["info"]["games"][$i]["spread"]) . ")"; else echo "(" . (-1 * $_SESSION["info"]["games"][$i]["spread"]) . ")"; ?>
                                        </strong>
                                    </label>
                                    <input type="radio" <?php echo "name='team-victor-" . $i . "' id='team-right-victor-" . $i . "'"; ?> value="home"
                                           <?php if (isset($_SESSION["info"]["games"][$i]["pick"]) && $_SESSION["info"]["games"][$i]["pick"] === 1) echo "checked='checked'"; else if (isset($_SESSION["info"]["games"][$i]["disabled"]) && $_SESSION["info"]["games"][$i]["disabled"]) echo "disabled='disabled'"; ?> >
                                    <br>
                                    <br>
                                </div>
                            </td>
                        </tr>
                        <?php if ($i === count($_SESSION["info"]["games"]) - 1 || $_SESSION["info"]["games"][$i]["date"] != $_SESSION["info"]["games"][$i + 1]["date"]) { ?>
                    </table>
                    <br>
                    <br>
                    <?php } ?>
                    <?php } } ?>
                    <div class="input-area">
                        <input type="submit" id="submit" name="submit" value="Submit your picks">
                    </div>
                </form>
            </div>

            <script src="../js/picks.js"></script>

            <?php if (isset($_SESSION["info"])) $_SESSION["info"] = null; ?>
        </section>
        <?php } ?>
    </body>
</html>