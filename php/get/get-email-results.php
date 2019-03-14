<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-send-email.php";
$year = getYear();

if (!isset($_POST["results-hidden-text"]) || $_POST["results-hidden-text"] === "")
    $_SESSION["info"]["text"] = "";
else
    $_SESSION["info"]["text"] = $_POST["results-hidden-text"] . "\r\n";

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//---------FIND SESSION LOGIC--------//
//Identify the most recent session submitted
$session = null;
$sessionNum = getMaxNumOfSessions();

while ($session == null && $sessionNum > 0) {
    $result = $db->query("SELECT * FROM sessions WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

    //If not submitted yet
    if ($result == null || !$result || getNumOfRows($result) == 0)
        $sessionNum--;
    else
        $session = $result->fetchArray(SQLITE3_ASSOC);
}

if ($sessionNum == 0)
    postErrorMessage("No sessions have been submitted yet. Check back later.", "../../index.php");

//Check if data has been saved yet
$result = $db->query("SELECT * FROM sessions WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

if (!$result)
    postErrorMessage("Get query did not succeed. Try again later.", $page);

if (getNumOfRows($result) == 0)
    postErrorMessage("No session found. Try a different session.", $page);

//Load data - it's a bit confusing, but it's stored in $session, not $_SESSION
$session = $result->fetchArray(SQLITE3_ASSOC);
$dollarsPerPoint = $session["dollarsPerPoint"];
$gamesToPlay = $session["gamesToPlay"];
$gamesToPick = $session["gamesToPick"];

$result = $db->query("SELECT * FROM history WHERE sessionNum = " . $sessionNum . " AND year = " . $year . " ORDER BY winnings DESC");


if (!$result)
    postErrorMessage("Error getting stuff", $page);
else if (getNumOfRows($result) === 0 && $sessionNum > 1) {
    //Get last week's results
    $result = $db->query("SELECT * FROM history WHERE sessionNum = " . ($sessionNum - 1) . " AND year = " . $year . " ORDER BY winnings DESC");
    //Get all the player data
    $numOfPlayers = getNumOfRows($result);
    for ($i = 0; $i < $numOfPlayers; $i++) {
        $history = $result->fetchArray(SQLITE3_ASSOC);
        //Get name from email
        $nameResult = $db->query("SELECT name FROM loginInformation WHERE email = '" . $history["email"] . "'");
        $name = $nameResult->fetchArray(SQLITE3_ASSOC)["name"];
        $_SESSION["info"]["text"] .= $name . ": ";
        $_SESSION["info"]["text"] .= $history["wins"] . "-";
        $_SESSION["info"]["text"] .= $history["losses"] . ", ";
        $_SESSION["info"]["text"] .= $history["points"] + $history["bonusPoints"] . " pts ";
        $_SESSION["info"]["text"] .= $history["winnings"] . " units\r\n";
    }
} else {
     //Get all the player data
    $numOfPlayers = getNumOfRows($result);
    for ($i = 0; $i < $numOfPlayers; $i++) {
        $history = $result->fetchArray(SQLITE3_ASSOC);
        //Get name from email
        $nameResult = $db->query("SELECT name FROM loginInformation WHERE email = '" . $history["email"] . "'");
        $name = $nameResult->fetchArray(SQLITE3_ASSOC)["name"];
        $_SESSION["info"]["text"] .= $name . ": ";
        $_SESSION["info"]["text"] .= $history["wins"] . "-";
        $_SESSION["info"]["text"] .= $history["losses"] . ", ";
        $_SESSION["info"]["text"] .= $history["points"] + $history["bonusPoints"] . " pts ";
        $_SESSION["info"]["text"] .= $history["winnings"] . " units\r\n";
    }
}

//Get the ytd now
$result = $db->query("SELECT * FROM history WHERE sessionNum = " . $sessionNum . " AND year = " . $year . " ORDER BY ytdWinnings DESC");
$_SESSION["info"]["text"] .= "\r\nYTD Results\r\n";

if (!$result)
    postErrorMessage("Error getting stuff", $page);
else if (getNumOfRows($result) === 0 && $sessionNum > 1) {
    //Get last week's results
    $result = $db->query("SELECT * FROM history WHERE sessionNum = " . ($sessionNum - 1) . " AND year = " . $year . " ORDER BY ytdWinnings DESC");
    //Get all the player data
    $numOfPlayers = getNumOfRows($result);
    for ($i = 0; $i < $numOfPlayers; $i++) {
        $history = $result->fetchArray(SQLITE3_ASSOC);
        //Get name from email
        $nameResult = $db->query("SELECT name FROM loginInformation WHERE email = '" . $history["email"] . "'");
        $name = $nameResult->fetchArray(SQLITE3_ASSOC)["name"];
        $_SESSION["info"]["text"] .= $name . ": ";
        $_SESSION["info"]["text"] .= $history["ytdWins"] . "-";
        $_SESSION["info"]["text"] .= $history["ytdLosses"] . ", ";
        $_SESSION["info"]["text"] .= $history["ytdPoints"] + $history["ytdBonusPoints"] . " pts ";
        $_SESSION["info"]["text"] .= $history["ytdWinnings"] . " units\r\n";
    }
} else {
    //Get all the player data
    $numOfPlayers = getNumOfRows($result);
    for ($i = 0; $i < $numOfPlayers; $i++) {
        $history = $result->fetchArray(SQLITE3_ASSOC);
        //Get name from email
        $nameResult = $db->query("SELECT name FROM loginInformation WHERE email = '" . $history["email"] . "'");
        $name = $nameResult->fetchArray(SQLITE3_ASSOC)["name"];
        $_SESSION["info"]["text"] .= $name . ": ";
        $_SESSION["info"]["text"] .= $history["ytdWins"] . "-";
        $_SESSION["info"]["text"] .= $history["ytdLosses"] . ", ";
        $_SESSION["info"]["text"] .= $history["ytdPoints"] + $history["ytdBonusPoints"] . " pts ";
        $_SESSION["info"]["text"] .= $history["ytdWinnings"] . " units\r\n";
    }
}

postMessage("Results loaded and inserted into the text box.", $page);

?>