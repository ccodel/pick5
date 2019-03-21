<?php

include("../php/api/check-login.php");
checkLogin();

if (!isset($_SESSION["info"])) {
    header("Location: ../php/get/get-weekly-results.php");
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
        <link rel="stylesheet" href="../css/main.css">
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

        <?php include("../php/api/header-message.php"); ?>

        <section>
            <?php if (isset($_SESSION["info"])) { ?>
            <div>
                <h3>Current session results</h3>

                <button type="button" id="alphabetize-button">Sort by name</button>
                <button type="button" id="pl-button">Sort by P&amp;L units</button>

                <p hidden id="user-num"><?php echo $_SESSION["info"]["userNum"]; ?></p>

                <br>
                <br>

                <table id="session-table">
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
                    <tr <?php echo "id='row-" . $i . "'"; ?>>
                        <td <?php echo "id='name-" . $i . "'"; ?>> <?php echo $_SESSION["info"]["users"][$i]["name"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-wins"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-losses"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-points"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-bonus-points"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["session-total-points"]; ?> </td>
                        <td <?php echo "id='pl-" . $i . "'"; ?>> <?php echo $_SESSION["info"]["users"][$i]["session-p-l"]; ?> </td>
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
            </div>
            <?php } ?>

            <?php echo "<script src='../js/weekly-results.js?" . rand() . "'></script>"; ?>

            <?php if (isset($_SESSION["info"])) $_SESSION["info"] = null; ?>
        </section>
    </body>
</html>