<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-get-status.php";
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

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

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
$bonus;
$steakDinner;

if ($session["perfectBonus"] == 1)
    $bonus = 1;
else
    $bonus = 0;

if ($session["steakDinner"] == 1)
    $steakDinner = 1;
else
    $steakDinner = 0;

$result = $db->query("SELECT * FROM history WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

if (!$result || getNumOfRows($result) === 0)
    postErrorMessage("No results have been submitted yet! Check back later.", $page);

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
    $_SESSION["info"]["users"][$i]["session-wins"] = $history["wins"];
    $_SESSION["info"]["users"][$i]["session-losses"] = $history["losses"];
    $_SESSION["info"]["users"][$i]["session-points"] = $history["points"];
    $_SESSION["info"]["users"][$i]["session-bonus-points"] = $history["bonusPoints"];
    $_SESSION["info"]["users"][$i]["session-total-points"] = $history["points"] + $history["bonusPoints"];
    $_SESSION["info"]["users"][$i]["session-p-l"] = $history["winnings"];
    $_SESSION["info"]["users"][$i]["ytd-wins"] = $history["ytdWins"];
    $_SESSION["info"]["users"][$i]["ytd-losses"] = $history["ytdLosses"];
    $_SESSION["info"]["users"][$i]["ytd-points"] = $history["ytdPoints"];
    $_SESSION["info"]["users"][$i]["ytd-bonus-points"] = $history["ytdBonusPoints"];
    $_SESSION["info"]["users"][$i]["ytd-total-points"] = $history["ytdPoints"] + $history["ytdBonusPoints"];
    $_SESSION["info"]["users"][$i]["ytd-p-l"] = $history["ytdWinnings"];
    $_SESSION["info"]["users"][$i]["steak-dinner-shares"] = $history["shares"];

    $totalSessionPL += $history["winnings"];
    $totalYTDPL += $history["ytdWinnings"];
    $totalShares += $history["shares"];
}

$_SESSION["info"]["total-session-p-l"] = $totalSessionPL;
$_SESSION["info"]["total-ytd-p-l"] = $totalYTDPL;
$_SESSION["info"]["total-steak-dinner-shares"] = $totalShares;

postMessage("Session results loaded.", $page);

?>