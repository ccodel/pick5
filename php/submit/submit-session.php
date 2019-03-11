<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-create-session.php";
$year = getYear();

//--------UPDATE LOADED DATA--------//
$sessionNum = substr($_POST["session"], 7);
$sessionTitle = $_POST["session-title"];
$dollarsPerPoint = $_POST["dollars-per-point"];
$gamesToPlay = $_POST["games-to-play"];

if (strlen($_POST["master-kickoff-month"]) === 1)
    $_POST["master-kickoff-month"] = "0" . $_POST["master-kickoff-month"];

if (strlen($_POST["master-kickoff-day"]) === 1)
    $_POST["master-kickoff-day"] = "0" . $_POST["master-kickoff-month"];

if (strlen($_POST["master-kickoff-hour"]) === 1)
    $_POST["master-kickoff-hour"] = "0" . $_POST["master-kickoff-hour"];

if (strlen($_POST["master-kickoff-minute"]) === 1)
    $_POST["master-kickoff-minute"] = "0" . $_POST["master-kickoff-minute"];

$masterKickoffDate = $_POST["master-kickoff-year"] . "-" . $_POST["master-kickoff-month"] . "-" . $_POST["master-kickoff-day"];
$masterKickoffTime = $_POST["master-kickoff-hour"] . ":" . $_POST["master-kickoff-minute"];
$gamesToPick = $_POST["games-to-pick"];
$perfectBonus;
if ($_POST["perfect-bonus"] == "on") {
    $perfectBonus = 1;
} else {
    $perfectBonus = 0;
}
$steakDinner;
if ($_POST["steak-dinner"] == "on") {
    $steakDinner = 1;
} else {
    $steakDinner = 0;
}

$homeTeams = array($gamesToPlay);
$awayTeams = array($gamesToPlay);
$kickoffDates = array($gamesToPlay);
$kickoffTimes = array($gamesToPlay);
$pointSpreads = array($gamesToPlay);

for ($i = 0; $i < $gamesToPlay; $i++) {
    $awayTeams[$i] = $_POST["team-select-left-" . $i];
    $homeTeams[$i] = $_POST["team-select-right-" . $i];

    if (strlen($_POST["game-month-" . $i]) === 1)
        $_POST["game-month-" . $i] = "0" . $_POST["game-month-" . $i];

    if (strlen($_POST["game-day-" . $i]) === 1)
        $_POST["game-day-" . $i] = "0" . $_POST["game-day-" . $i];

    if (strlen($_POST["game-hour-" . $i]) === 1)
        $_POST["game-hour-" . $i] = "0" . $_POST["game-hour-" . $i];

    if (strlen($_POST["game-minute-" . $i]) === 1)
        $_POST["game-minute-" . $i] = "0" . $_POST["game-minute-" . $i];

    $kickoffDates[$i] = $_POST["game-year-" . $i] . "-" . $_POST["game-month-" . $i] . "-" . $_POST["game-day-" . $i];
    $kickoffTimes[$i] = $_POST["game-hour-" . $i] . ":" . $_POST["game-minute-" . $i];
    $pointSpreads[$i] = $_POST["game-spread-" . $i];
}

//Set the stored data in case not a valid form submission
$_SESSION["info"]["session"] = $_POST["session"];
$_SESSION["info"]["session-title"] = $_POST["session-title"];
$_SESSION["info"]["dollars-per-point"] = $_POST["dollars-per-point"];
$_SESSION["info"]["games-to-play"] = $_POST["games-to-play"];
$_SESSION["info"]["games-to-pick"] = $_POST["games-to-pick"];
$_SESSION["info"]["bonus"] = $perfectBonus;
$_SESSION["info"]["steak-dinner"] = $steakDinner;

$games = array($gamesToPlay);
for ($i = 0; $i < $gamesToPlay; $i++) {
    $games[$i] = array(5);
    $games[$i]["home"] = $homeTeams[$i];
    $games[$i]["away"] = $awayTeams[$i];
    $games[$i]["date"] = $kickoffDates[$i];
    $games[$i]["time"] = $kickoffTimes[$i];
    $games[$i]["spread"] = $pointSpreads[$i];
}
$_SESSION["info"]["games"] = $games;
$_SESSION["info"]["kickoff-time"] = $masterKickoffTime;
$_SESSION["info"]["kickoff-date"] = $masterKickoffDate;


//--------FORM LOGIC--------//
//Check for all variables, ensuring they aren't null
if ($sessionNum == null || $sessionNum == "blank")
    postErrorMessage("The session number wasn't found. Try again.", $page);

if ($sessionTitle == null)
    postErrorMessage("The session title wasn't found or was null. Try again.", $page);

if ($dollarsPerPoint == null)
    postErrorMessage("The number of dollars per point wasn't found or was null. Try again.", $page);

if ($gamesToPlay == null)
    postErrorMessage("The number of games for the session wasn't found or was null. Try again.", $page);

if (strlen($masterKickoffDate) !== 10)
    postErrorMessage("The master kickoff date wasn't found or was null. Try again.", $page);

if (strlen($masterKickoffTime) !== 5)
    postErrorMessage("The master kickoff time wasn't found or was null. Try again.", $page);

if ($gamesToPick == null)
    postErrorMessage("The number of picks for the session wasn't found or was null. Try again.", $page);

for ($i = 0; $i < $gamesToPlay; $i++) {
    if ($_POST["team-select-left-" . $i] == null || $_POST["team-select-left-" . $i] == "blank")
        postErrorMessage("Away team " . ($i + 1) . " was found to be null or blank.", $page);

    if ($_POST["team-select-right-" . $i] == null || $_POST["team-select-right-" . $i] == "blank")
        postErrorMessage("Home team " . ($i + 1) . " was found to be null or blank.", $page);

    if (strlen($games[$i]["date"]) !== 10)
        postErrorMessage("Kickoff date for game " . ($i + 1) . " was found to be null or blank.", $page);

    if (strlen($games[$i]["time"]) !== 5)
        postErrorMessage("Kickoff time for game " . ($i + 1) . " was found to be null or blank.", $page);

    if ($_POST["game-spread-" . $i] == null)
        postErrorMessage("Spread for game " . ($i + 1) . " was found to be null or blank.", $page);
}

$teamCount;
for ($i = 0; $i < $gamesToPlay; $i++) {
    if (isset($teamCount[$_POST["team-select-left-" . $i]]) && $teamCount[$_POST["team-select-left-" . $i]])
        postErrorMessage("The " . $_POST["team-select-left-" . $i] . " were selected twice. Try again", $page);
    else
        $teamCount[$_POST["team-select-left-" . $i]] = true;

    if (isset($teamCount[$_POST["team-select-right-" . $i]]) && $teamCount[$_POST["team-select-right-" . $i]] >= 1)
        postErrorMessage("The " . $_POST["team-select-right-" . $i] . " were selected twice. Try again", $page);
    else
        $teamCount[$_POST["team-select-right-" . $i]] = true;
}

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db) {
    console_log("Connection established");
} else {
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);
}

//See if field existed
$result = $db->query("SELECT * FROM sessions WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

if (!$result)
    postErrorMessage("Select query from the database failed. Try again later.", $page);

if (getNumOfRows($result) > 0) {
    //Update instead of insert
    $result = $db->query("UPDATE sessions SET " 
                         . "sessionTitle = '" . $sessionTitle . "', " 
                         . "dollarsPerPoint = " . $dollarsPerPoint . ", "
                         . "gamesToPlay = " . $gamesToPlay . ", "
                         . "gamesToPick = " . $gamesToPick . ", "
                         . "masterKickoff = '" . $masterKickoffDate . " " . $masterKickoffTime . "', " 
                         . "perfectBonus = " . $perfectBonus . ", "
                         . "steakDinner = " . $steakDinner . " WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

    if (!$result)
        postErrorMessage("Update of previous saved session data did not succeed.", $page);
} else {
    $result = $db->query("INSERT INTO sessions (sessionNum, year, sessionTitle, dollarsPerPoint, gamesToPlay, gamesToPick, masterKickoff, perfectBonus, steakDinner) VALUES ("
                         . $sessionNum . ", "
                         . $year . ", " 
                         . "'" . $sessionTitle . "'" . ", "
                         . $dollarsPerPoint . ", "
                         . $gamesToPlay . ", "
                         . $gamesToPick . ", "
                         . "'" . $masterKickoffDate . " " . $masterKickoffTime . "'" . ", "
                         . $perfectBonus . ", "
                         . $steakDinner . ")");

    if (!$result)
        //postErrorMessage("Insertion of session data did not succeed.", $page);
        exit();
}

//For each game, enter its data
//Delete the stored games for the session, though, in case the number of games changed
$result = $db->query("DELETE FROM games WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

if (!$result)
    postErrorMessage("Deletion of previously saved games failed.", $page);

for ($i = 0; $i < $gamesToPlay; $i++) {
    $result = $db->query("INSERT INTO games (sessionNum, year, home, away, spread, kickoff) VALUES ("
                         . $sessionNum . ", "
                         . $year . ", "
                         . "'" . $homeTeams[$i] . "'" . ", "
                         . "'" . $awayTeams[$i] . "'" . ", "
                         . $pointSpreads[$i] . ", "
                         . "'" . $kickoffDates[$i] . " " . $kickoffTimes[$i] . "'" . ")");

    if (!$result) {
        postErrorMessage("Insertion of game " . ($i + 1) . " was not successful.", $page);
    }
}

postMessage("Session data saved correctly. You may leave the page safely.", $page);

?>