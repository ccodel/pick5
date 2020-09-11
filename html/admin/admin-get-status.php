<?php

include_once("../../php/api/check-login.php");
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

        <?php include_once('../../php/api/header-message.php'); ?>

        <section>
            <h1>See player status</h1>

            <form id="get-session-status" action="../../php/get/get-admin-player-status.php" method="post">
                <h4>Select which session you'd like to view the status of.</h4>

		<?php include_once("../../php/api/sql-api.php"); ?>
		<?php echo getSessionScrollHTML("session-dropdown"); ?>
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
