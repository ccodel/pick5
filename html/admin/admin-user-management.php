<?php

include("../../php/api/check-login.php");
checkLogin();
checkAdmin();

if (!isset($_SESSION["info"])) {
    header("Location: ../../php/get/get-users.php");
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
            <h1>User management</h1>
            <h4>Current users are listed below.</h4>
            <h4>To register new ones, remove old ones, and reset passwords, go to the bottom of the page.</h4>
        </section>

        <hr>

        <section>
            <h2>Users</h2>

            <?php if (isset($_SESSION["info"])) { ?>
            <div>                
                <table id="user-table">
                    <tr>
                        <td>Email</td>
                        <td>Name</td>
                        <td>Admin</td>
                        <td>Registered</td>
                    </tr>
                    <?php for ($i = 0; $i < $_SESSION["info"]["userNum"]; $i++) { ?>
                    <tr>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["email"]; ?> </td>
                        <td> <?php echo $_SESSION["info"]["users"][$i]["name"]; ?> </td>
                        <td> <?php if ($_SESSION["info"]["users"][$i]["admin"] === 1) echo "<img class='icon' src='../../img/check.png'>"; ?> </td>
                        <td> <?php if ($_SESSION["info"]["users"][$i]["registered"]) echo "<img class='icon' src='../../img/check.png'>"; ?> </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <?php } ?>

            <?php if (isset($_SESSION["info"])) $_SESSION["info"] = null; ?>
        </section>

        <hr>

        <section>
            <h2>Register a new user</h2>

            <p>Specify the email the new user will access the account from.</p>
            <p>Check the bottom box if they should have admin privileges.</p>

            <form action="../../php/submit/submit-admin-register.php" method="post">
                <div class="input-area">
                    <strong><u>Email</u></strong> <br>
                    <input type="email" name="email" id="email" value=""> <br>
                    <strong><u>Admin privileges</u></strong> <br>
                    <input type="checkbox" name="admin" id="admin" value="1"> <br> <br>
                    <input type="submit" name="submit" id="submit" value="Register">
                </div>
            </form>
        </section>

        <hr>

        <section>
            <h2>Remove a user</h2>

            <p>Specify the email of the user to remove.</p> <br>
            <p>Year to date history for this year only will be removed.</p>
            <p>This includes picks, winnings, points, and steak dinner shares.</p>

            <form action="../../php/submit/submit-admin-remove.php" method="post">
                <div class="input-area">
                    <strong><u>Email</u></strong> <br>
                    <input type="email" name="email" id="email" value=""> <br> <br>
                    <input type="submit" name="submit" id="submit" value="Remove">
                </div>
            </form>
        </section>
        
        <hr>

        <section>
            <h2>Reset a user's password</h2>

            <p>Specifiy the email of the user whose password should be reset.</p>
            <p>The next time the user logs in, they must re-register.</p>
            <p>No history data or picks will be affected.</p>

            <form action="../../php/submit/submit-admin-password-reset.php" method="post">
                <div class="input-area">
                    <strong><u>Email</u></strong> <br>
                    <input type="email" name="email" id="email" value=""> <br> <br>
                    <input type="submit" name="submit" id="submit" value="Reset">
                </div>
            </form>
        </section>
    </body>
</html>