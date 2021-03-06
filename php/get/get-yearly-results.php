<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/yearly-results.php";
$year = getYear();

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

//Load data in case not valid get request
$_SESSION["info"]["session"] = "session" . $sessionNum;
$_SESSION["info"]["session-title"] = $session["sessionTitle"];

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

$result = $db->query("SELECT * FROM history WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

if (!$result)
    postErrorMessage("Error getting stuff", $page);
else if (getNumOfRows($result) === 0 && $sessionNum > 1) {
    //Get last week's results
    $result = $db->query("SELECT * FROM history WHERE sessionNum = " . ($sessionNum - 1) . " AND year = " . $year);
    //Get all the player data
    $numOfPlayers = getNumOfRows($result);
    $_SESSION["info"]["userNum"] = $numOfPlayers;
    $totalSessionPL = 0;
    $totalYTDPL = 0;
    $totalShares = 0;
    for ($i = 0; $i < $numOfPlayers; $i++) {
        $history = $result->fetchArray(SQLITE3_ASSOC);
        //Get name from email
        $nameResult = $db->query("SELECT name FROM loginInformation WHERE email = '" . $history["email"] . "'");
        $_SESSION["info"]["users"][$i]["name"] = $nameResult->fetchArray(SQLITE3_ASSOC)["name"];
        $_SESSION["info"]["users"][$i]["email"] = $history["email"];
        $_SESSION["info"]["users"][$i]["ytd-wins"] = $history["ytdWins"];
        $_SESSION["info"]["users"][$i]["ytd-losses"] = $history["ytdLosses"];
        $_SESSION["info"]["users"][$i]["ytd-points"] = $history["ytdPoints"];
        $_SESSION["info"]["users"][$i]["ytd-bonus-points"] = $history["ytdBonusPoints"];
        $_SESSION["info"]["users"][$i]["ytd-total-points"] = $history["ytdPoints"] + $history["ytdBonusPoints"];
        $_SESSION["info"]["users"][$i]["ytd-p-l"] = $history["ytdWinnings"];
        $_SESSION["info"]["users"][$i]["steak-dinner-shares"] = $history["shares"];

        $totalYTDPL += $history["ytdWinnings"];
        $totalShares += $history["shares"];
    }

    $_SESSION["info"]["total-session-p-l"] = $totalSessionPL;
    $_SESSION["info"]["total-ytd-p-l"] = $totalYTDPL;
    $_SESSION["info"]["total-steak-dinner-shares"] = $totalShares;
} else {
    //Get all the player data
    $numOfPlayers = getNumOfRows($result);
    $_SESSION["info"]["userNum"] = $numOfPlayers;
    $totalSessionPL = 0;
    $totalYTDPL = 0;
    $totalShares = 0;
    for ($i = 0; $i < $numOfPlayers; $i++) {
        $history = $result->fetchArray(SQLITE3_ASSOC);
        //Get name from email
        $nameResult = $db->query("SELECT name FROM loginInformation WHERE email = '" . $history["email"] . "'");
        $_SESSION["info"]["users"][$i]["name"] = $nameResult->fetchArray(SQLITE3_ASSOC)["name"];
        $_SESSION["info"]["users"][$i]["email"] = $history["email"];
        $_SESSION["info"]["users"][$i]["ytd-wins"] = $history["ytdWins"];
        $_SESSION["info"]["users"][$i]["ytd-losses"] = $history["ytdLosses"];
        $_SESSION["info"]["users"][$i]["ytd-points"] = $history["ytdPoints"];
        $_SESSION["info"]["users"][$i]["ytd-bonus-points"] = $history["ytdBonusPoints"];
        $_SESSION["info"]["users"][$i]["ytd-total-points"] = $history["ytdPoints"] + $history["ytdBonusPoints"];
        $_SESSION["info"]["users"][$i]["ytd-p-l"] = $history["ytdWinnings"];
        $_SESSION["info"]["users"][$i]["steak-dinner-shares"] = $history["shares"];

        $totalYTDPL += $history["ytdWinnings"];
        $totalShares += $history["shares"];
    }

    $_SESSION["info"]["total-ytd-p-l"] = $totalYTDPL;
    $_SESSION["info"]["total-steak-dinner-shares"] = $totalShares;
}

postMessage("Session results loaded.", $page);

?>