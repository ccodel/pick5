<?php
var_dump($_POST);

include("../api/sql-api.php");

//--------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/picks.php";
$year = getYear();

//--------UPDATE LOADED DATA--------//
//Set some variables
$sessionNum = substr($_POST["session"], 7);
$gamesToPlay = $_POST["games-to-play"];
$gamesToPick = $_POST["games-to-pick"];

$winners = array($gamesToPlay);

for ($i = 0; $i < $gamesToPlay; $i++) {
    if ($_POST["team-victor-" . $i] == "away")
        $winners[$i] = 0;
    else
        $winners[$i] = 1;
}

//Set the stored data in case not a valid form submission
$_SESSION["info"]["session"] = $_POST["session"];
$_SESSION["info"]["games-to-play"] = $_POST["games-to-play"];
$_SESSION["info"]["games-to-pick"] = $_POST["games-to-pick"];


//Store game data and selections as well
for ($i = 0; $i < $gamesToPlay; $i++) {
    $_SESSION["info"]["games"][$i]["away"] = $_POST["left-" . $i];
    $_SESSION["info"]["games"][$i]["home"] = $_POST["right-" . $i];
    $_SESSION["info"]["games"][$i]["spread"] = $_POST["spread-" . $i];
    $_SESSION["info"]["games"][$i]["disabled"] = $_POST["disabled-" . $i];
    $_SESSION["info"]["games"][$i]["date"] = $_POST["date-" . $i];
    if (isset($_POST["team-victor-" . $i]) && $_POST["team-victor-" . $i] === "away")
        $_SESSION["info"]["games"][$i]["pick"] = 0;
    else if (isset($_POST["team-victor-" . $i]) && $_POST["team-victor-" . $i] === "home")
        $_SESSION["info"]["games"][$i]["pick"] = 1;
    else
        $_SESSION["info"]["games"][$i]["pick"] = null;
}

//--------FORM LOGIC--------//
//Check for all variables, ensuring they aren't null
if ($sessionNum == null || $sessionNum == "blank")
    postErrorMessage("The session number wasn't found. Try again.", $page);

if ($gamesToPlay == null)
    postErrorMessage("The number of games for the session wasn't found or was null. Try again.", $page);

$picks = array($gamesToPick);
$picksHome = array($gamesToPick);
$picksAway = array($gamesToPick);
$spreadChosen = array($gamesToPick);
$counter = 0;

//Store the picks now
$counter = 0;
for ($i = 0; $i < $gamesToPlay; $i++) {
    if (isset($_POST["team-victor-" . $i]) && $_POST["team-victor-" . $i] === "away") {
        $picks[$counter] = 0;
        $picksHome[$counter] = $_SESSION["info"]["games"][$i]["home"];
        $picksAway[$counter] = $_SESSION["info"]["games"][$i]["away"];
        $spreadChosen[$counter] = $_SESSION["info"]["games"][$i]["spread"];
        $counter++;
    }
    else if (isset($_POST["team-victor-" . $i]) && $_POST["team-victor-" . $i] === "home") {
        $picks[$counter] = 1;
        $picksHome[$counter] = $_SESSION["info"]["games"][$i]["home"];
        $picksAway[$counter] = $_SESSION["info"]["games"][$i]["away"];
        $spreadChosen[$counter] = -1 * $_SESSION["info"]["games"][$i]["spread"];
        $counter++;
    }
}


if ($counter < $gamesToPick)
    postErrorMessage("You have made fewer than " . $gamesToPick . " picks. Select more.", $page);
else if ($counter > $gamesToPick)
    postErrorMessage("You have made more than " . $gamesToPick . " picks. Select fewer.", $page);

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//Identify the most recent session submitted
// Get the session from the database corresponding to the sessionNum
$session = $db->query("SELECT * FROM sessions WHERE sessionNum = " . $sessionNum . " AND year = " . $year);
if ($session == null || !$session || getNumOfRows($session) == 0)
  postErrorMessage("The session for the week you've tried to submit doesn't exist. Contact admins.", $page);
else
  $session = $session->fetchArray(SQLITE3_ASSOC);

//Check if the current time is before master kickoff time
//If not, redirect back to home page
$masterKickoff = $session["masterKickoff"];
$index = strpos($masterKickoff, " ");
$masterDate = substr($masterKickoff, 0, $index);
$masterTime = substr($masterKickoff, $index + 1);
if (cmpTimeToNow($masterDate, $masterTime) === -1)
  postErrorMessage("The master kickoff time has passed. You may not edit your picks.", "../../index.php");

//See if previous picks have been made for the email
$result = $db->query("SELECT * FROM picks WHERE email = '" . $_SESSION["email"] . "' AND sessionNum = " . $sessionNum . " AND year = " . $year);

//Declare function real quick
function parrotPicks($picks, $away, $home, $spread) {
    $res = "You chose:\r\n";

    for ($i = 0; $i < $_POST["games-to-pick"]; $i++) {
        if ($picks[$i] == 1) {
            $spreadString = ($spread[$i] > 0) ? "+" . $spread[$i] : $spread[$i];
            $res .= getAbbr($home[$i]) . " (" . $spreadString . ")\r\n";
        }
        else {
            $spreadString = ($spread[$i] > 0) ? "+" . $spread[$i] : $spread[$i];
            $res .= getAbbr($away[$i]) . " (" . $spreadString . ")\r\n";
        }
    }

    return $res;
}

if (!$result || getNumOfRows($result) == 0) {
    $picksString = "";

    for ($i = 0; $i < $gamesToPick - 1; $i++)
        $picksString .= "pick" . ($i + 1) . "home, pick" . ($i + 1) . "away, pick" . ($i + 1) . "pick, ";

    $picksString .= " pick" . $gamesToPick . "home, pick" . $gamesToPick . "away, pick" . ($i + 1) . "pick";

    $query = "INSERT INTO picks (email, sessionNum, year, " . $picksString . ") VALUES ("
        . "'" . $_SESSION["email"] . "'" . ", "
        . $sessionNum . ", "
        . $year . ", ";

    for ($i = 0; $i < $gamesToPick - 1; $i++) {
        $query .= "'" . $picksHome[$i] . "'" . ", "
            . "'" . $picksAway[$i] . "'" . ", "
            . $picks[$i] . ", ";
    }

    $query .= "'" . $picksHome[$gamesToPick - 1] . "'" . ", "
        . "'" . $picksAway[$gamesToPick - 1] . "'" . ", "
        . $picks[$gamesToPick - 1] . ")";

    $result = $db->query($query);

    if (!$result || $result == null) {
        postErrorMessage("Insert of new picks did not succeed. Try again.", $page);
    } else {
        toSplash("../../html/splash.php", "../../index.php", NULL, nl2br("Picks saved. " . parrotPicks($picks, $picksAway, $picksHome, $spreadChosen)), NULL);
    }
} else {
    for ($i = 0; $i < $gamesToPick; $i++) {
        $result = $db->query("UPDATE picks SET " 
                             . "pick" . ($i + 1) . "home = '" . $picksHome[$i] . "'" . ", "
                             . "pick" . ($i + 1) . "away = '" . $picksAway[$i] . "'" . ", "
                             . "pick" . ($i + 1) . "pick = " . $picks[$i] . " WHERE "
                             . "sessionNum = " . $sessionNum
                             . " AND year = " . $year
                             . " AND email = '" . $_SESSION["email"] . "'");

        if (!$result || $result == null)
            postErrorMessage("Update query on pick " . ($i + 1) . " was not successful. Try again.", $page);
    }

    toSplash("../../html/splash.php", "../../index.php", NULL, nl2br("Picks saved. " . parrotPicks($picks, $picksAway, $picksHome, $spreadChosen)), NULL);
}

?>