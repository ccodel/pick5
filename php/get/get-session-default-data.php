<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-submit-defaults.php";
$year = getYear();

//-------ENSURE PROPER FORM SUBMISSION--------//
$sessionSelected = $_POST["session-dropdown"];
$_SESSION["info"]["session"] = $sessionSelected;
$sessionNum = substr($sessionSelected, 7);

if ($sessionSelected == "blank") {
    postErrorMessage("Please select one of the sessions.", $page);
}

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);


//Check if data has been saved yet
$result = $db->query("SELECT * FROM sessions WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

if (!$result)
    postErrorMessage("Get query did not succeed. Try again later.");

if (getNumOfRows($result) == 0)
    postErrorMessage("No saved session found.", $page);

//Load data - it's a bit confusing, but it's stored in $session, not $_SESSION
$session = $result->fetchArray(SQLITE3_ASSOC);
$_SESSION["info"]["games-to-play"] = $session["gamesToPlay"];
$_SESSION["info"]["games-to-pick"] = $session["gamesToPick"];

//Get games data
$gameQuery = "SELECT * FROM games WHERE sessionNum = " . $sessionNum . " AND year = " . $year;
$result = $db->query($gameQuery);

if (!$result) {
    postErrorMessage("Get query (specific game data) was not successful. Try again later.", $page);
}

//Got good data
$_SESSION["info"]["games"] = array($session["gamesToPlay"]);
for ($i = 0; $i < $session["gamesToPlay"]; $i++) {
    $game = $result->fetchArray(SQLITE3_ASSOC);
    $_SESSION["info"]["games"][$i] = array(5);
    $_SESSION["info"]["games"][$i]["home"] = $game["home"];
    $_SESSION["info"]["games"][$i]["away"] = $game["away"];
    $_SESSION["info"]["games"][$i]["spread"] = $game["spread"];

    if ($game["defaultLoss"] === null)
        $_SESSION["info"]["games"][$i]["loss"] = null;
    else if ($game["defaultLoss"] !== null && $game["defaultLoss"] === 1)
        $_SESSION["info"]["games"][$i]["loss"] = 1;
    else
        $_SESSION["info"]["games"][$i]["loss"] = 0;

    if ($game["defaultUnderdog"] === null)
        $_SESSION["info"]["games"][$i]["underdog"] = null;
    else if ($game["defaultUnderdog"] !== null && $game["defaultUnderdog"] === 1)
        $_SESSION["info"]["games"][$i]["underdog"] = 1;
    else
        $_SESSION["info"]["games"][$i]["underdog"] = 0;
}

$db->close();
postMessage("Saved session data loaded.", $page);

?>