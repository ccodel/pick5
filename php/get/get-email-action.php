<?php

include("../api/sql-api.php");

//--------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-send-email.php";
$year = getYear();

//--------GET TEXT--------//
if (!isset($_POST["action-hidden-text"]) || $_POST["action-hidden-text"] === "")
    $_SESSION["info"]["text"] = "";
else
    $_SESSION["info"]["text"] = $_POST["action-hidden-text"] . "\r\n";
$userString = "";
$teamString = "";

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//---------FIND SESSION LOGIC--------//
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

//Get games data
$gameQuery = "SELECT * FROM games WHERE sessionNum = " . $sessionNum . " AND year = " . $year;
$result = $db->query($gameQuery);

if (!$result)
    postErrorMessage("Get query (specific game data) was not successful. Try again later.", $page);

//Got good data, process
$gameCounter = 0;
for ($i = 0; $i < $session["gamesToPlay"]; $i++) {
    $game = $result->fetchArray(SQLITE3_ASSOC);

    $index = strpos($game["kickoff"], " ");
    $gameDate = substr($game["kickoff"], 0, $index);
    $gameTime = substr($game["kickoff"], $index + 1);
    if (cmpTimeToNow($gameDate, $gameTime) === -1) {
        //Add game
        $_SESSION["info"]["games"][$gameCounter]["home"] = $game["home"];
        $_SESSION["info"]["games"][$gameCounter]["away"] = $game["away"];
        $_SESSION["info"]["games"][$gameCounter]["spread"] = $game["spread"];
        $gameCounter++;
    }
}

//Check if any games are good to show
if ($gameCounter == 0)
    postErrorMessage("No games have passed their kickoff times. No text inserted.", $page);

//Load in picks data
$result = $db->query("SELECT * FROM loginInformation WHERE name IS NOT NULL");

if (!$result)
    postErrorMessage("Get query (user data) was not successful. Try again later.", $page);

$userNum = getNumOfRows($result);

if ($userNum === 0)
    postErrorMessage("No one has been registered yet. Try again later.", $page);

for ($i = 0; $i < $userNum; $i++) {
    //Get user info for each user
    $user = $result->fetchArray(SQLITE3_ASSOC);

    $picksResult = $db->query("SELECT * FROM picks WHERE email = '" . $user["email"] . "' AND sessionNum = " . $sessionNum . " AND year = " . $year);
    if (!$picksResult)
        postErrorMessage("Get query (picks data) was not successful. Try again later.", $page);

    if (getNumOfRows($picksResult) != 0) {
        $userString .= $user["name"] . ": ";

        $pick = $picksResult->fetchArray(SQLITE3_ASSOC);
        for ($j = 0; $j < $session["gamesToPick"]; $j++) {
            //Check if pick is not null
            if (isset($pick["pick" . ($j + 1) . "pick"])) {
                //Only store team data if the game has passed its kickoff time
                for ($k = 0; $k < $session["gamesToPlay"]; $k++) {
                    if ($_SESSION["info"]["games"][$k]["home"] == $pick["pick" . ($j + 1) . "home"]) {    
                        if ($pick["pick" . ($j + 1) . "pick"] === 1) {
                            $userString .= getAbbr($pick["pick" . ($j + 1) . "home"]) . " ";
                            $nameCount = 0;
                            if (isset($_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "home"]]))
                                $nameCount = count($_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "home"]]);
                            $_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "home"]][$nameCount] = $user["name"];
                        } else {
                            $userString .= getAbbr($pick["pick" . ($j + 1) . "away"]) . " ";
                            $nameCount = 0;
                            if (isset($_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "away"]]))
                                $nameCount = count($_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "away"]]);
                            $_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "away"]][$nameCount] = $user["name"];
                        }
                    }
                }
            }
        }
    } 
    $userString .= "\r\n";
}

//Create the game string
for ($i = 0; $i < $gameCounter; $i++) {
    $home = $_SESSION["info"]["games"][$i]["home"];
    $away = $_SESSION["info"]["games"][$i]["away"];
    $spread = $_SESSION["info"]["games"][$i]["spread"];

    //Home team
    if ($spread < 0)
        $teamString .= getAbbr($home) . " (+" . ($spread * -1) . "): ";
    else
        $teamString .= getAbbr($home) . " (" . ($spread * -1) . "): ";
    
    if (isset($_SESSION["info"]["picks"][$home]))
        for ($j = 0; $j < count($_SESSION["info"]["picks"][$home]); $j++)
            $teamString .= $_SESSION["info"]["picks"][$home][$j] . " ";
    $teamString .= "\r\n";

    if ($spread < 0)
        $teamString .= getAbbr($away) . " (" . $spread . "): ";
    else
        $teamString .= getAbbr($away) . " (+" . $spread . "): ";
    if (isset($_SESSION["info"]["picks"][$away]))
        for ($j = 0; $j < count($_SESSION["info"]["picks"][$away]); $j++)
            $teamString .= $_SESSION["info"]["picks"][$away][$j] . " ";
    $teamString .= "\r\n";
    $teamString .= "\r\n";
}

$temp = $_SESSION["info"]["text"];
$_SESSION["info"] = null;
$_SESSION["info"]["text"] = $temp;

$_SESSION["info"]["text"] .= $userString . "\r\n";
$_SESSION["info"]["text"] .= $teamString . "\r\n";
postMessage("The action has been loaded and inserted to the text box.", $page);

?>