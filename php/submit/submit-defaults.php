<?php

include("../api/sql-api.php");

//--------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-submit-defaults.php";
$year = getYear();

//--------UPDATE LOADED DATA--------//
//Set some variables
$sessionNum = substr($_POST["session"], 7);
$gamesToPlay = $_POST["games-to-play"];
$gamesToPick = $_POST["games-to-pick"];

//Set the stored data in case not a valid form submission
$_SESSION["info"]["session"] = "session" . $sessionNum;
$_SESSION["info"]["games-to-play"] = $_POST["games-to-play"];
$_SESSION["info"]["games-to-pick"] = $_POST["games-to-pick"];
for ($i = 0; $i < $gamesToPlay; $i++) {
    $_SESSION["info"]["games"][$i]["away"] = $_POST["team-left-" . $i];
    $_SESSION["info"]["games"][$i]["home"] = $_POST["team-right-" . $i];
    $_SESSION["info"]["games"][$i]["spread"] = $_POST["game-spread-" . $i];
}

$homeDefaultLosses = array($gamesToPlay);
$awayDefaultLosses = array($gamesToPlay);
$homeDefaultUnderdogs = array($gamesToPlay);
$awayDefaultUnderdogs = array($gamesToPlay);

//Check that one game is marked as default loss
$counter = 0;
for ($i = 0; $i < $gamesToPlay; $i++) {
    if ($_POST["team-loss-" . $i] != null)
        $counter++;
}

if ($counter < 1)
    postErrorMessage("No team was marked as being the default loss team. Select one.", $page);
else if ($counter > 1)
    postErrorMessage("More than one team was marked as the default loss team. Select only one.", $page);

$counter = 0;
for ($i = 0; $i < $gamesToPlay; $i++) {
    if ($_POST["team-underdog-" . $i] != null)
        $counter++;
}

if ($counter < $gamesToPick - 1)
    postErrorMessage("Fewer than " . ($gamesToPick - 1) . " games were picked as default underdogs. Select more.", $page);
else if ($counter > $gamesToPick - 1)
    postErrorMessage("Greater than " . ($gamesToPick - 1) . " games were picked as default underdogs. Select fewer.", $page);

//Look now for if an underdog is selected that is not an underdog
for ($i = 0; $i < $gamesToPick; $i++) {
    if ($_POST["team-underdog-" . $i] == "away" && $_SESSION["games"][$i]["spread"] < 0)
        postErrorMessage("The selection for game " . ($i + 1) . " was for a favorite. Choose underdogs only.", $page);
    else if ($_POST["team-underdog-" . $i] == "home" && $_SESSION["games"][$i]["spread"] > 0)
        postErrorMessage("The selection for game " . ($i + 1) . " was for a favorite. Choose underdogs only.", $page);
}

for ($i = 0; $i < $gamesToPlay; $i++) {
    if ($_POST["team-loss-" . $i] == "away")
        $awayDefaultLosses[$i] = 1;
    else
        $awayDefaultLosses[$i] = 0;

    if ($_POST["team-loss-" . $i] == "home")
        $homeDefaultLosses[$i] = 1;
    else
        $homeDefaultLosses[$i] = 0;

    if ($_POST["team-underdog-" . $i] == "away")
        $awayDefaultUnderdogs[$i] = 1;
    else
        $awayDefaultUnderdogs[$i] = 0;

    if ($_POST["team-underdog-" . $i] == "home")
        $homeDefaultUnderdogs[$i] = 1;
    else
        $homeDefaultUnderdogs[$i] = 0;
}

//--------FORM LOGIC--------//
//Check for all variables, ensuring they aren't null

if ($sessionNum == null || $sessionNum == "blank")
    postErrorMessage("The session number wasn't found. Try again.", $page);

if ($gamesToPlay == null)
    postErrorMessage("The number of games for the session wasn't found or was null. Try again.", $page);


//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else 
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//For each game, update its data by team names and values entered
for ($i = 0; $i < $gamesToPlay; $i++) {
    $loss;
    $underdog;

    if ($awayDefaultLosses[$i] == 1)
        $loss = 0;
    else if ($homeDefaultLosses[$i] == 1)
        $loss = 1;
    else
        $loss = null;

    if ($awayDefaultUnderdogs[$i] == 1)
        $underdog = 0;
    else if ($homeDefaultUnderdogs[$i] == 1)
        $underdog = 1;
    else
        $underdog = null;

    $result = $db->query("UPDATE games SET "
                         . "defaultLoss = " . ($loss === null ? "NULL" : $loss) . ", "
                         . "defaultUnderdog = " . ($underdog === null ? "NULL" : $underdog) . " WHERE "
                         . "sessionNum = " . $sessionNum
                         . " AND year = " . $year
                         . " AND home = '" . $_POST["team-right-" . $i] . "'"
                         . " AND away = '" . $_POST["team-left-" . $i] . "'");

    if (!$result)
        postErrorMessage("Insertion of game " . ($i + 1) . " was not successful.", $page);
}

//--------SOME NOTES--------//
//Default picks may be given at any time
//But the assignation of picks only happens after master kickoff has passed
//Therefore, we check if the submission of defaults happens after the master kickoff
//If so, we assign defaults

$result = $db->query("SELECT * FROM sessions WHERE sessionNum = " . $sessionNum . " AND year = " . $year);
$session = $result->fetchArray(SQLITE3_ASSOC);

$index = strpos($session["masterKickoff"], " ");
$masterKickoffDate = substr($session["masterKickoff"], 0, $index);
$masterKickoffTime = substr($session["masterKickoff"], $index + 1);

if (cmpTimeToNow($masterKickoffDate, $masterKickoffTime) === 1)
    postMessage("Data saved correctly. No defaults assigned yet! You may leave the page safely.", $page);

//--------DEFAULT ASSIGNATION--------//
//Assign defaults to those without picks
//If no submission, give them default picks

var_dump("Hello world");

//Get default loss
$result = $db->query("SELECT * FROM games WHERE sessionNum = " . $sessionNum . " AND year = " . $year . " AND defaultLoss IS NOT NULL");

if (!$result || getNumOfRows($result) === 0)
    postErrorMessage("Unable to find default loss. Is one submitted?", $page);

$defaultLoss = $result->fetchArray(SQLITE3_ASSOC);

//Get default underdogs
$result = $db->query("SELECT * FROM games WHERE sessionNum = " . $sessionNum . " AND year = " . $year . " AND defaultUnderdog IS NOT NULL");

if (!$result || getNumOfRows($result) === 0)
    postErrorMessage("Unable to find default underdogs. Is one submitted?", $page);

$numOfUnderdogs = getNumOfRows($result);
$defaultUnderdogs = array($numOfUnderdogs);
for ($i = 0; $i < $numOfUnderdogs; $i++) 
    $defaultUnderdogs[$i] = $result->fetchArray(SQLITE3_ASSOC);

//Get login information
$result = $db->query("SELECT * FROM loginInformation WHERE name IS NOT NULL");

if (!$result)
    postErrorMessage("No login information found", $page);

$userNum = getNumOfRows($result);

for ($i = 0; $i < $userNum; $i++) {
    //Get user data
    $userData = $result->fetchArray(SQLITE3_ASSOC);
    $email = $userData["email"];
    $name = $userData["name"];

    //See if they made picks
    $picksResult = $db->query("SELECT * FROM picks WHERE email = '" . $email . "' AND sessionNum = " . $sessionNum . " AND year = " . $year);

    if (!$picksResult || getNumOfRows($picksResult) === 0) {
        $db->query("INSERT INTO picks (email, sessionNum, year, pick1home, pick1away, pick1pick) VALUES ("
                   . "'" . $email . "'" . ", "
                   . $sessionNum . ", "
                   . $year . ", "
                   . "'" . $defaultLoss["home"] . "'" . ", "
                   . "'" . $defaultLoss["away"] . "'" . ", "
                   . $defaultLoss["defaultLoss"] . ")");

        for ($j = 0; $j < count($defaultUnderdogs); $j++) {
            $db->query("UPDATE picks SET "
                       . "pick" . ($j + 2) . "home = '" . $defaultUnderdogs[$j]["home"] . "'" . ", "
                       . "pick" . ($j + 2) . "away = '" . $defaultUnderdogs[$j]["away"] . "'" . ", "
                       . "pick" . ($j + 2) . "pick = " . $defaultUnderdogs[$j]["defaultUnderdog"] . " WHERE "
                       . "sessionNum = " . $sessionNum
                       . " AND year = " . $year
                       . " AND email = '" . $email . "'");
        }       
    }
}

postMessage("Data saved correctly. Default picks assigned. You may leave the page safely.", $page);

?>