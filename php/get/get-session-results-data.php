<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-submit-results.php";
$year = getYear();

//-------ENSURE PROPER FORM SUBMISSION--------//
$sessionSelected = $_POST["session-dropdown"];
$_SESSION["info"]["session"] = $sessionSelected;

if ($sessionSelected == "blank") {
    postErrorMessage("Please select one of the sessions.", $page);
}

$sessionNum = substr($sessionSelected, 7);

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db) {
    console_log("Connection established");
} else {
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);
}

//Check if data has been saved yet
$result = $db->query("SELECT * FROM sessions WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

if (!$result)
    postErrorMessage("Get query did not succeed. Try again later.");

if (getNumOfRows($result) == 0)
    postErrorMessage("No saved session found.", $page);

//Load data - it's a bit confusing, but it's stored in $session, not $_SESSION
$session = $result->fetchArray(SQLITE3_ASSOC);
$_SESSION["info"]["games-to-play"] = $session["gamesToPlay"];

//Get games data
$gameQuery = "SELECT * FROM games WHERE sessionNum = " . $sessionNum . " AND year = " . $year;
$result = $db->query($gameQuery);

if (!$result)
    postErrorMessage("Get query (specific game data) was not successful. Try again later.", $page);

//Got good data
$_SESSION["info"]["games"] = array($session["gamesToPlay"]);
for ($i = 0; $i < $session["gamesToPlay"]; $i++) {
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $_SESSION["info"]["games"][$i] = array(6);
    $_SESSION["info"]["games"][$i]["home"] = $row["home"];
    $_SESSION["info"]["games"][$i]["away"] = $row["away"];
    $_SESSION["info"]["games"][$i]["spread"] = $row["spread"];
    
    $datetime = $row["kickoff"];
    $index = strpos($datetime, " ");
    $_SESSION["info"]["games"][$i]["date"] = substr($datetime, 0, $index);
    $_SESSION["info"]["games"][$i]["time"] = substr($datetime, $index + 1);
    
    if (isset($row["winner"]))
        $_SESSION["info"]["games"][$i]["victor"] = $row["winner"];
    else
        $_SESSION["info"]["games"][$i]["victor"] = null;
}

postMessage("Saved session data loaded.", $page);

?>