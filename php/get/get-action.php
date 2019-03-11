<?php

include("../api/sql-api.php");

//--------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/action.php";
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
$sessionNum = 22;

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
$_SESSION["info"]["games-to-play"] = $session["gamesToPlay"];
$_SESSION["info"]["games-to-pick"] = $session["gamesToPick"];

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
    postErrorMessage("No games have passed their kickoff times. Check back later.", $page);

//Load in picks data
$result = $db->query("SELECT * FROM loginInformation WHERE name IS NOT NULL ORDER BY name ASC");
if (!$result)
    postErrorMessage("Get query (user data) was not successful. Try again later.", $page);

$userNum = getNumOfRows($result);
$_SESSION["info"]["number-of-users"] = $userNum;

if ($userNum === 0)
    postErrorMessage("No one has been registered yet. Try again later.", $page);

for ($i = 0; $i < $userNum; $i++) {
    //Get user info for each user
    $user = $result->fetchArray(SQLITE3_ASSOC);
    $_SESSION["info"]["users"][$i]["name"] = $user["name"];
    $_SESSION["info"]["users"][$i]["email"] = $user["email"];

    $picksResult = $db->query("SELECT * FROM picks WHERE email = '" . $user["email"] . "' AND sessionNum = " . $sessionNum . " AND year = " . $year);
    if (!$picksResult)
        postErrorMessage("Get query (picks data) was not successful. Try again later.", $page);

    if (getNumOfRows($picksResult) == 0)
        $_SESSION["info"]["users"][$i]["picks"] = "none";
    else {
        //Store pick under player and team to make toggle view easier
        $pick = $picksResult->fetchArray(SQLITE3_ASSOC);
        $pickCounter = 0;
        for ($j = 0; $j < $session["gamesToPick"]; $j++) {
            //Check if pick is not null
            if (isset($pick["pick" . ($j + 1) . "pick"])) {
                //Only store team data if the game has passed its kickoff time
                for ($k = 0; $k < $session["gamesToPlay"]; $k++) {
                    if ($_SESSION["info"]["games"][$k]["home"] == $pick["pick" . ($j + 1) . "home"]) {
                        $_SESSION["info"]["users"][$i]["picks"][$pickCounter]["home"] = $pick["pick" . ($j + 1) . "home"];
                        $_SESSION["info"]["users"][$i]["picks"][$pickCounter]["away"] = $pick["pick" . ($j + 1) . "away"];
                        $_SESSION["info"]["users"][$i]["picks"][$pickCounter]["pick"] = $pick["pick" . ($j + 1) . "pick"];
                        $_SESSION["info"]["users"][$i]["picks"][$pickCounter]["spread"] = $_SESSION["info"]["games"][$k]["spread"];

                        $pickCounter++;

                        if ($pick["pick" . ($j + 1) . "pick"] === 1) {
                            $nameCount = 0;
                            if (isset($_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "home"]]))
                                $nameCount = count($_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "home"]]);
                            $_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "home"]][$nameCount] = $user["name"];
                        } else {
                            $nameCount = 0;
                            if (isset($_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "away"]]))
                                $nameCount = count($_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "away"]]);
                            $_SESSION["info"]["picks"][$pick["pick" . ($j + 1) . "away"]][$nameCount] = $user["name"];
                        }
                    }
                }
            }
        }
        $_SESSION["info"]["users"][$i]["pickCounter"] = $pickCounter;
    }
}

postMessage("This week's action has been loaded.", $page);

?>