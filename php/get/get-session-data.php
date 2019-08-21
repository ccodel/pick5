<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$year = getYear();
$page = "../../html/admin/admin-create-session.php";

//-------ENSURE PROPER FORM SUBMISSION--------//
$sessionSelected = $_POST["session-dropdown"];
$_SESSION["info"]["session"] = $sessionSelected;

if ($sessionSelected == "blank") {
    postErrorMessage("Please select one of the sessions.", $page);
}

$sessionNum = substr($sessionSelected, 7);

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

var_dump($_POST);
console_log("The session number is " . $sessionNum . " and the year is " . $year);

//Check if data has been saved yet
$result = $db->query("SELECT * FROM sessions WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

if (!$result)
    postErrorMessage("Get query did not succeed. Try again later.");

if (getNumOfRows($result) == 0)
    postMessage("No saved session found (no worries!). Generated blank template.", $page);

console_log("Number of rows is " . getNumOfRows($result));

//Load data - it's a bit confusing, but it's stored in $session, not $_SESSION
$session = $result->fetchArray(SQLITE3_ASSOC);
$_SESSION["info"]["session-num"] = $sessionNum;
$_SESSION["info"]["session-title"] = $session["sessionTitle"];
$_SESSION["info"]["dollars-per-point"] = $session["dollarsPerPoint"];
$_SESSION["info"]["games-to-play"] = $session["gamesToPlay"];
$_SESSION["info"]["games-to-pick"] = $session["gamesToPick"];

$index = strpos($session["masterKickoff"], " ");
$_SESSION["info"]["kickoff-date"] = substr($session["masterKickoff"], 0, $index);
$_SESSION["info"]["kickoff-time"] = substr($session["masterKickoff"], $index + 1);

if ($session["perfectBonus"] == 1) {
    $_SESSION["info"]["bonus"] = 1;
} else {
    $_SESSION["info"]["bonus"] = null;
}

if ($session["steakDinner"] == 1) {
    $_SESSION["info"]["steak-dinner"] = 1;
} else {
    $_SESSION["info"]["steak-dinner"] = null;
}

//Get games data
$gameQuery = "SELECT * FROM games WHERE sessionNum = " . $sessionNum . " AND year = " . $year;
$result = $db->query($gameQuery);

if (!$result) {
    postErrorMessage("Get query (specific game data) was not successful. Try again later.", $page);
}

//Got good data
$_SESSION["info"]["games"] = array($session["gamesToPlay"]);
for ($i = 0; $i < $session["gamesToPlay"]; $i++) {
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $_SESSION["info"]["games"][$i] = array(5);
    $_SESSION["info"]["games"][$i]["home"] = $row["home"];
    $_SESSION["info"]["games"][$i]["away"] = $row["away"];
    $_SESSION["info"]["games"][$i]["spread"] = $row["spread"];

    $datetime = $row["kickoff"];
    $index = strpos($datetime, " ");
    $_SESSION["info"]["games"][$i]["date"] = substr($datetime, 0, $index);
    $_SESSION["info"]["games"][$i]["time"] = substr($datetime, $index + 1);
}

$db->close();
postMessage("Saved session data loaded.", $page);

?>