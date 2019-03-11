<?php

include("../api/sql-api.php");

//--------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/picks.php";
$year = getYear();

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//Identify the most recent session submitted
$session = null;
$sessionNum = 22;

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

//Check if this is before master kickoff time
//If so, redirect back to home page
$masterKickoff = $session["masterKickoff"];
$index = strpos($masterKickoff, " ");
$masterDate = substr($masterKickoff, 0, $index);
$masterTime = substr($masterKickoff, $index + 1);
if (cmpTimeToNow($masterDate, $masterTime) === -1)
    postErrorMessage("The master kickoff time has passed. You may not edit your picks.", "../../index.php");

$_SESSION["info"]["session"] = "session" . $sessionNum;
$_SESSION["info"]["session-title"] = $session["sessionTitle"];
$_SESSION["info"]["games-to-play"] = $session["gamesToPlay"];
$_SESSION["info"]["games-to-pick"] = $session["gamesToPick"];

//Get games data
$gameQuery = "SELECT * FROM games WHERE sessionNum = " . $sessionNum . " AND year = " . $year . " ORDER BY kickoff ASC";
$result = $db->query($gameQuery);

if (!$result)
    postErrorMessage("Get query (specific game data) was not successful. Try again later.", $page);

//Got good data, process
for ($i = 0; $i < $session["gamesToPlay"]; $i++) {
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $_SESSION["info"]["games"][$i]["home"] = $row["home"];
    $_SESSION["info"]["games"][$i]["away"] = $row["away"];
    $_SESSION["info"]["games"][$i]["spread"] = $row["spread"];

    $datetime = $row["kickoff"];
    $index = strpos($datetime, " ");
    $_SESSION["info"]["games"][$i]["date"] = substr($datetime, 0, $index);
    $_SESSION["info"]["games"][$i]["time"] = substr($datetime, $index + 1);
    
    //If the time is before now, then disable the pick
    if (cmpTimeToNow(substr($datetime, 0, $index), substr($datetime, $index + 1)) === -1)
        $_SESSION["info"]["games"][$i]["disabled"] = true;
    else
        $_SESSION["info"]["games"][$i]["disabled"] = false;
}

//Load in any data the user has
$result = $db->query("SELECT * FROM picks WHERE email = '" . $_SESSION["email"] . "' AND sessionNum = " . $sessionNum . " AND year = " . $year);

if (!$result || getNumOfRows($result) == 0)
    postMessage("No picks have been made for this session (yet!). Make your selection below.", $page);
else {
    $picks = $result->fetchArray(SQLITE3_ASSOC);
    for ($i = 0; $i < $session["gamesToPick"]; $i++) {
        $pickHome = $picks["pick" . ($i + 1) . "home"];
        $pickAway = $picks["pick" . ($i + 1) . "away"];
        $pickBit = $picks["pick" . ($i + 1) . "pick"];

        if ($pickHome != null && $pickAway != null)
            for ($j = 0; $j < $session["gamesToPlay"]; $j++)
                if ($_SESSION["info"]["games"][$j]["home"] == $pickHome && $_SESSION["info"]["games"][$j]["away"] == $pickAway)
                    $_SESSION["info"]["games"][$j]["pick"] = $pickBit;
    }

    postMessage("Your saved picks have been loaded. Good luck!", $page);
}

?>