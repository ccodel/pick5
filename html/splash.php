<?php

include_once("../php/api/check-login.php");
checkLogin();

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

    <?php include_once("../php/api/mobile-api.php"); ?>

    <body>
        <p hidden id="destination"><?php echo $_SESSION["info"]["destination"]; ?></p>
        <p hidden id="picture"><?php echo $_SESSION["info"]["picture"]; ?></p>
        
    </body>
</html>

<?php echo "<script src='../js/splash.js?" . rand() . "'></script>"; ?>
