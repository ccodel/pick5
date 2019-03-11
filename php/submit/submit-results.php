<?php

include("../api/sql-api.php");

//--------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-submit-results.php";
$year = getYear();

function getDefaultLoss($games) {
    for ($i = 0; $i < count($games); $i++) {
        if (isset($games[$i]["defaultLoss"]) && $games[$i]["defaultLoss"] == 0) {
            $res["home"] = $games[$i]["home"];
            $res["away"] = $games[$i]["away"];
            $res["pick"] = 0;
            return $res;
        } else if (isset($games["defaultLoss"]) && $games[$i]["defaultLoss"] == 1) {
            $res["home"] = $games[$i]["home"];
            $res["away"] = $games[$i]["away"];
            $res["pick"] = 1;
            return $res;
        }
    }

    return false;
}

function getDefaultUnderdogs($games) {
    $res;
    $counter = 0;
    for ($i = 0; $i < count($games); $i++) {
        if (isset($games[$i]["defaultUnderdog"]) && $games[$i]["defaultUnderdog"] === 0) {
            $res[$counter]["home"] = $games[$i]["home"];
            $res[$counter]["away"] = $games[$i]["away"];
            $res[$counter]["pick"] = 0;
            $counter++;
        } else if (isset($games[$i]["defaultUnderdog"]) && $games[$i]["defaultUnderdog"] === 1) {
            $res[$counter]["home"] = $games[$i]["home"];
            $res[$counter]["away"] = $games[$i]["away"];
            $res[$counter]["pick"] = 1;
            $counter++;
        }
    }
    return $res;
}

//--------UPDATE LOADED DATA--------//
$sessionNum = substr($_POST["session"], 7);
$gamesToPlay = $_POST["games-to-play"];

//Set the stored data in case not a valid form submission
$_SESSION["info"]["session"] = $_POST["session"];
$_SESSION["info"]["games-to-play"] = $_POST["games-to-play"];
$gameCounter = 0;
for ($i = 0; $i < $gamesToPlay; $i++) {
    if (isset($_POST["team-victor-" . $i])) {
        $_SESSION["info"]["games"][$gameCounter]["away"] = $_POST["team-left-" . $i];
        $_SESSION["info"]["games"][$gameCounter]["home"] = $_POST["team-right-" . $i];
        $_SESSION["info"]["games"][$gameCounter]["spread"] = $_POST["game-spread-" . $i];
        if ($_POST["team-victor-" . $i] === "home")
            $_SESSION["info"]["games"][$gameCounter]["victor"] = 1;
        else if ($_POST["team-victor-" . $i] === "away")
            $_SESSION["info"]["games"][$gameCounter]["victor"] = 0;
        $gameCounter++;
    }
}

//--------FORM LOGIC--------//
//Check for all variables, ensuring they aren't null
if ($sessionNum == null || $sessionNum == "blank")
    postErrorMessage("The session number wasn't found. Try again.", $page);

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//For each game, update its data by team names and values entered
for ($i = 0; $i < $gameCounter; $i++) {
    $result = $db->query("UPDATE games SET "
                         . "winner = " . $_SESSION["info"]["games"][$i]["victor"] . " WHERE "
                         . "sessionNum = " . $sessionNum
                         . " AND year = " . $year
                         . " AND home = '" . $_SESSION["info"]["games"][$i]["home"] . "'"
                         . " AND away = '" . $_SESSION["info"]["games"][$i]["away"] . "'");

    if (!$result)
        postErrorMessage("Insertion of game " . ($i + 1) . " was not successful.", $page);
}

//---------DEFAULT PICKS ASSIGNATION--------//
//If the time is past the master kickoff time, we assign defaults
//But only if they have been input yet
$result = $db->query("SELECT * FROM sessions WHERE sessionNum = " . $sessionNum . " AND year = " . $year);
$session = $result->fetchArray(SQLITE3_ASSOC);

$index = strpos($session["masterKickoff"], " ");
$masterKickoffDate = substr($session["masterKickoff"], 0, $index);
$masterKickoffTime = substr($session["masterKickoff"], $index + 1);

$flag = false;
if (cmpTimeToNow($masterKickoffDate, $masterKickoffTime) === -1) {
    $flag = true;
    $result = $db->query("SELECT * FROM games WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

    if (!$result || getNumOfRows($result) == 0)
        postErrorMessage("No games found or query failed", $page);

    $gameNum = getNumOfRows($result);
    $games;
    for ($i = 0; $i < $gameNum; $i++)
        $games[$i] = $result->fetchArray(SQLITE3_ASSOC);

    //--------PROCESS USER DATA--------//
    //Get all users, ensure they made submission
    //If no submission, give them default picks
    $result = $db->query("SELECT * FROM loginInformation WHERE name IS NOT NULL");

    if (!$result)
        postErrorMessage($db, "No login information found");

    $userNum = getNumOfRows($result);

    for ($i = 0; $i < $userNum; $i++) {
        //Get user data
        $userData = $result->fetchArray(SQLITE3_ASSOC);
        $email = $userData["email"];
        $name = $userData["name"];

        //See if they made picks
        $picksResult = $db->query("SELECT * FROM picks WHERE email = '" . $email . "' AND sessionNum = " . $sessionNum . " AND year = " . $year);

        if (!$picksResult || getNumOfRows($picksResult) == 0) {
            $defaultLoss = getDefaultLoss($games);
            $defaultUnderdogs = getDefaultUnderdogs($games);

            $db->query("INSERT INTO picks (email, sessionNum, year, pick1home, pick1away, pick1pick) VALUES ("
                       . "'" . $email . "'" . ", "
                       . $sessionNum . ", "
                       . $year . ", "
                       . "'" . $defaultLoss["home"] . "'" . ", "
                       . "'" . $defaultLoss["away"] . "'" . ", "
                       . $defaultLoss["pick"] . ")");

            for ($j = 0; $j < count($defaultUnderdogs); $j++) {
                $db->query("UPDATE picks SET "
                           . "pick" . ($j + 2) . "home = '" . $defaultUnderdogs[$j]["home"] . "'" . ", "
                           . "pick" . ($j + 2) . "away = '" . $defaultUnderdogs[$j]["away"] . "'" . ", "
                           . "pick" . ($j + 2) . "pick = " . $defaultUnderdogs[$j]["pick"] . " WHERE "
                           . "sessionNum = " . $sessionNum
                           . " AND year = " . $year
                           . " AND email = '" . $email . "'");
            }

            console_log("User with email " . $email . " didn't have submission, was given default picks");   
        }
    }
}

//--------CALCULATE RESULTS LOGIC--------//
$dollarsPerPoint = $session["dollarsPerPoint"];
$gamesToPick = $session["gamesToPick"];

//Get all games, store in array
$result = $db->query("SELECT * FROM games WHERE sessionNum = " . $sessionNum . " AND year = " . $year);

if (!$result || getNumOfRows($result) == 0)
    postErrorMessage("No games found or query failed", $page);

$gameNum = getNumOfRows($result);
$games;
for ($i = 0; $i < $gameNum; $i++) {
    $games[$i] = $result->fetchArray(SQLITE3_ASSOC);
}

//--------PROCESS USER DATA--------//
//Get all users picks data, process their records and winnings
$result = $db->query("SELECT * FROM loginInformation WHERE name IS NOT NULL");

if (!$result)
    postErrorMessage("No login information found", $page);

$userNum = getNumOfRows($result);

$userPicks;
$userHistory;

for ($i = 0; $i < $userNum; $i++) {
    //Get user data
    $userData = $result->fetchArray(SQLITE3_ASSOC);
    $email = $userData["email"];
    $name = $userData["name"];

    //Get user picks
    $picksResult = $db->query("SELECT * FROM picks WHERE email = '" . $email . "' AND sessionNum = " . $sessionNum . " AND year = " . $year);

    $wins = 0;
    $losses = 0;
    if (getNumOfRows($picksResult) > 0) {
        $userPicks[$i] = $picksResult->fetchArray(SQLITE3_ASSOC);

        //Calculate their record
        $wins = 0;
        $losses = 0;

        for ($j = 0; $j < $gamesToPick; $j++) {
            $game = null;
            for ($k = 0; $k < $gameCounter; $k++) {
                if ($games[$k]["home"] === $userPicks[$i]["pick" . ($j + 1) . "home"]) {
                    $game = $games[$k];
                    $k = $gameCounter;
                }
            }

            if (isset($game["winner"]) && $userPicks[$i]["pick" . ($j + 1) . "pick"] === $game["winner"])
                $wins++;
            else if (isset($game["winner"]) && $userPicks[$i]["pick" . ($j + 1) . "pick"] !== $game["winner"])
                $losses++;
        }
    }

    //Calculate points, store in history array
    $userHistory[$i]["email"] = $email;
    $userHistory[$i]["sessionNum"] = $sessionNum;
    $userHistory[$i]["year"] = $year;
    $userHistory[$i]["wins"] = $wins;
    $userHistory[$i]["losses"] = $losses;
    $userHistory[$i]["points"] = (5 * $wins) - (3 * $losses);

    if ($session["perfectBonus"] == 1 && $wins == $gamesToPick)
        $userHistory[$i]["bonusPoints"] = 5;
    else
        $userHistory[$i]["bonusPoints"] = 0;

    if ($session["steakDinner"] == 1 && $losses == $gamesToPick)
        $userHistory[$i]["steakDinner"] = 1;
    else
        $userHistory[$i]["steakDinner"] = 0;

}

//All records calculated
//Calculate winnings for each player
for ($i = 0; $i < count($userHistory); $i++) {
    $points = $userHistory[$i]["points"] + $userHistory[$i]["bonusPoints"];    
    $userHistory[$i]["winnings"] = 0;
    for ($j = 0; $j < $userNum; $j++) {
        if ($userHistory[$j]["points"] + $userHistory[$j]["bonusPoints"] > $userHistory[$i]["points"] + $userHistory[$i]["bonusPoints"])
            $userHistory[$i]["winnings"] -= $dollarsPerPoint * ($userHistory[$j]["points"] + $userHistory[$j]["bonusPoints"] - $points);
        else if ($userHistory[$j]["points"] + $userHistory[$j]["bonusPoints"] < $userHistory[$i]["points"] + $userHistory[$i]["bonusPoints"])
            $userHistory[$i]["winnings"] += $dollarsPerPoint * ($points - $userHistory[$j]["points"] - $userHistory[$j]["bonusPoints"]);
    }

    //Pull from previous week, if exists
    $ytdWins = $ytdLosses = $ytdPoints = $ytdBonusPoints = $ytdWinnings = $ytdBonuses = $steakDinnerShares = 0;
    if ($sessionNum != 1) {
        $previousSessionResult = $db->query("SELECT * FROM history WHERE email = '" . $userHistory[$i]["email"] . "' AND sessionNum = " . ($sessionNum - 1) . " AND year = " . $year);

        if (getNumOfRows($previousSessionResult) > 0) {
            $previousSession = $previousSessionResult->fetchArray(SQLITE3_ASSOC);
            $ytdWins = $previousSession["ytdWins"];
            $ytdLosses = $previousSession["ytdLosses"];
            $ytdPoints = $previousSession["ytdPoints"];
            $ytdBonusPoints = $previousSession["ytdBonusPoints"];
            $ytdWinnings = $previousSession["ytdWinnings"];
            $ytdBonuses = $previousSession["bonuses"];
            $steakDinnerShares = $previousSession["shares"];
        }
    }

    $savedHistory = $db->query("SELECT * FROM history WHERE email = '" . $userHistory[$i]["email"] . "' AND sessionNum = " . $sessionNum . " AND year = " . $year);

    //If no history, insert, otherwise update
    if (getNumOfRows($savedHistory) == 0) {
        $result = $db->query("INSERT INTO history (email, sessionNum, year, wins, losses, points, bonusPoints, winnings, ytdWins, ytdLosses, ytdPoints, ytdBonusPoints, ytdWinnings, bonuses, shares) VALUES ("
                             . "'" . $userHistory[$i]["email"] . "'" . ", "
                             . $sessionNum . ", "
                             . $year . ", "
                             . $userHistory[$i]["wins"] . ", "
                             . $userHistory[$i]["losses"] . ", "
                             . $userHistory[$i]["points"] . ", "
                             . $userHistory[$i]["bonusPoints"] . ", "
                             . $userHistory[$i]["winnings"] . ", "
                             . ($userHistory[$i]["wins"] + $ytdWins) . ", "
                             . ($userHistory[$i]["losses"] + $ytdLosses) . ", "
                             . ($userHistory[$i]["points"] + $ytdPoints) . ", "
                             . ($userHistory[$i]["bonusPoints"] + $ytdBonusPoints) . ", "
                             . ($userHistory[$i]["winnings"] + $ytdWinnings) . ", "
                             . (($userHistory[$i]["bonusPoints"] > 0 ? 1 : 0) + $ytdBonuses) . ", "
                             . ($userHistory[$i]["steakDinner"] + $steakDinnerShares) . ")");

        if (!$result)
            postErrorMessage("Insertion of user history with email " . $userHistory[$i]["email"] . " failed", $page);
    } else {       
        $result = $db->query("UPDATE history SET "
                             . "wins = " . $userHistory[$i]["wins"] . ", "
                             . "losses = " . $userHistory[$i]["losses"] . ", "
                             . "points = " . $userHistory[$i]["points"] . ", "
                             . "bonusPoints = " . $userHistory[$i]["bonusPoints"] . ", "
                             . "winnings = " . $userHistory[$i]["winnings"] . ", "
                             . "ytdWins = " . ($userHistory[$i]["wins"] + $ytdWins) . ", "
                             . "ytdLosses = " . ($userHistory[$i]["losses"] + $ytdLosses) . ", "
                             . "ytdPoints = " . ($userHistory[$i]["points"] + $ytdPoints) . ", "
                             . "ytdBonusPoints = " . ($userHistory[$i]["bonusPoints"] + $ytdBonusPoints) . ", "
                             . "ytdWinnings = " . ($userHistory[$i]["winnings"] + $ytdWinnings) . ", "
                             . "bonuses = " . (($userHistory[$i]["bonusPoints"] > 0 ? 1 : 0) + $ytdBonuses) . ", "
                             . "shares = " . ($userHistory[$i]["steakDinner"] + $steakDinnerShares) . " WHERE "
                             . "email = '" . $userHistory[$i]["email"] . "' AND sessionNum = " . $sessionNum . " AND year = " . $year);

        if (!$result)
            postErrorMessage("Update of history for user with email " . $userHistory[$i]["email"] . " failed", $page);
    }
}

if ($flag)
    postMessage("Session results calculated and stored. Default picks assigned to players.", $page);
else
    postMessage("Session results calculated and stored. No default picks assigned to players (yet!)", $page);

?>