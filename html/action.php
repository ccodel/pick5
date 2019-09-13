<?php

include("../php/api/check-login.php");
checkLogin();

if (!isset($_SESSION["info"])) {
    header("Location: ../php/get/get-action.php");
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

                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="admin/admin-home.php">Admin</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION['logged-in'])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="login.php">Login</a> </li>
                        <?php } ?>

                        <?php if (!isset($_SESSION['logged-in'])) { ?>
                        <li class="nav-item"> <a class="nav-link" href="register.php">Register</a> </li>
                        <?php } ?>

                        <?php if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == "true") { ?>
                        <li class="nav-item"> <a class="nav-link" href="logout.php">Logout</a> </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>

        <?php include("../php/api/header-message.php"); ?>
        <?php include("../php/api/team-names.php"); ?>

        <section>
            <div>
                <h1><?php if (isset($_SESSION["info"]["session-title"])) echo $_SESSION["info"]["session-title"]; ?></h1>
                <h2>This week's action.</h2>
            </div>

            <hr>

            <div>
                <?php if (isset($_SESSION["info"]["games"])) { ?>
                <p id="current-view">Currently viewing picks by team</p>
                <p>Toggle to see the picks</p>
                <button id="toggle">By player</button>

                <hr>

                <div id="tables">
                    <table id="team-table">
                        <tr>
                            <td class="left-column">Away</td>
                            <td class="right-column">Home</td>
                        </tr>
                        <?php for ($i = 0; $i < count($_SESSION["info"]["games"]); $i++) { ?>
                        <tr>
                            <td class="left-column">
                                <img <?php echo ('src="../../img/team-logos/' . $_SESSION["info"]["games"][$i]["away"] . '.png"'); ?> class="big-logo">
                                <?php echo getAbbr($_SESSION["info"]["games"][$i]["away"]); ?>
                                <?php if ($_SESSION["info"]["games"][$i]["spread"] < 0) { ?>
                                <?php echo "(" . $_SESSION["info"]["games"][$i]["spread"] . ")"; ?>
                                <?php } else { ?>
                                <?php echo "(+" . $_SESSION["info"]["games"][$i]["spread"] . ")"; ?>
                                <?php } ?>
                                <br>
                                <br>
                                <?php for ($j = 0; $j < count($_SESSION["info"]["picks"][$_SESSION["info"]["games"][$i]["away"]]); $j++) { ?>
                                <?php echo $_SESSION["info"]["picks"][$_SESSION["info"]["games"][$i]["away"]][$j]; ?>
                                <?php echo "<br>"; ?>
                                <?php } ?>
                                <br>
                            </td>
                            <td class="right-column">
                                <img <?php echo ('src="../../img/team-logos/' . $_SESSION["info"]["games"][$i]["home"] . '.png"'); ?> class="big-logo">
                                <?php echo getAbbr($_SESSION["info"]["games"][$i]["home"]); ?>
                                <?php if ($_SESSION["info"]["games"][$i]["spread"] < 0) { ?>
                                <?php echo "(+" . (-1 * $_SESSION["info"]["games"][$i]["spread"]) . ")"; ?>
                                <?php } else { ?>
                                <?php echo "(" . (-1 * $_SESSION["info"]["games"][$i]["spread"]) . ")"; ?>
                                <?php } ?>
                                <br>
                                <br>
                                <?php for ($j = 0; $j < count($_SESSION["info"]["picks"][$_SESSION["info"]["games"][$i]["home"]]); $j++) { ?>
                                <?php echo $_SESSION["info"]["picks"][$_SESSION["info"]["games"][$i]["home"]][$j]; ?>
                                <?php echo "<br>"; ?>
                                <?php } ?>
                                <br>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>

                    <br>
                    <br>
                    <br>
                    <hr>
                    <br>
                    <br>
                    <br>

                    <table id="player-table">
                        <tr>
                            <td class="left-column">Player</td>
                            <td class="right-column">Picks</td>
                        </tr>

                        <?php for ($i = 0; $i < $_SESSION["info"]["number-of-users"]; $i++) { ?>
                        <?php if (isset($_SESSION["info"]["users"][$i]["picks"]) && $_SESSION["info"]["users"][$i]["picks"] !== "none") { ?>
                        <tr>
                            <td class="left-column">
                                <?php echo $_SESSION["info"]["users"][$i]["name"]; ?>
                            </td>
                            <td class="right-column">
                                <?php for ($j = 0; $j < $_SESSION["info"]["users"][$i]["pickCounter"]; $j++) { ?>
                                <?php if ($_SESSION["info"]["users"][$i]["picks"][$j]["pick"] === 1) { ?>
                                <?php echo getAbbr($_SESSION["info"]["users"][$i]["picks"][$j]["home"]); ?>
                                <?php if (-1 * $_SESSION["info"]["users"][$i]["picks"][$j]["spread"] < 0) { ?>
                                <?php echo "(" . (-1 * $_SESSION["info"]["users"][$i]["picks"][$j]["spread"]) . ")"; ?>
                                <?php } else { ?>
                                <?php echo "(+" . (-1 * $_SESSION["info"]["users"][$i]["picks"][$j]["spread"]) . ")"; ?>
                                <?php } ?>
                                <?php } else { ?>
                                <?php echo getAbbr($_SESSION["info"]["users"][$i]["picks"][$j]["away"]); ?>
                                <?php if ($_SESSION["info"]["users"][$i]["picks"][$j]["spread"] < 0) { ?>
                                <?php echo "(" . $_SESSION["info"]["users"][$i]["picks"][$j]["spread"] . ")"; ?>
                                <?php } else { ?>
                                <?php echo "(+" . $_SESSION["info"]["users"][$i]["picks"][$j]["spread"] . ")"; ?>
                                <?php } ?>
                                <?php } ?>
                                <?php echo "<br>"; ?>
                                <?php } ?>
                            </td>
                        </tr>

                        <?php } } ?>

                    </table>
                </div>
                <?php } ?>
            </div>
            <script src="../js/action.js"></script>

            <?php if (isset($_SESSION["info"])) $_SESSION["info"] = null; ?>
        </section>
    </body>
</html>