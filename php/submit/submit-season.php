<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-configure-season.php";
$year = getYear();

//--------UPDATE LOADED DATA--------//
$numberOfSessions = $_POST["number-of-sessions"];

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db) {
    console_log("Connection established");
} else {
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);
}

//See if field existed
$result = $db->query("SELECT * FROM seasons WHERE year = " . $year);

if (!$result)
    postErrorMessage("Select query failed. Try again later.", $page);

if (getNumOfRows($result) > 0) {
    // Update instead of insert
    $result = $db->query("UPDATE seasons SET sessions = " 
                        . $numberOfSessions 
                        . " WHERE year = " . $year);

    if (!$result)
      postErrorMessage("Upudate query failed.", $page);
} else {
    $result = $db->query("INSERT INTO seasons (year, sessions) VALUES ("
        . $year . ", "
        . $numberOfSessions . ")");

    if (!$result)
      postErrorMessage("Insertion failed.", $page);
}

postMessage("This year's season now has " . $numberOfSessions . " sessions. You may now leave the page safely.", $page);

?>
