<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-see-picks.php";
$year = getYear();

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//---------FIND SESSION LOGIC--------//
//Identify the most recent session submitted
$_SESSION["info"]["session"] = $_POST["session"];
$session = $_POST["session"];

if ($session == "blank")
    postErrorMessage("Please select one of the sessions.", $page);

$sessionNum = substr($session, 7);

$usersResult = $db->query("SELECT * FROM loginInformation WHERE name IS NOT NULL ORDER BY name ASC");


$userNum = getNumOfRows($usersResult);
$_SESSION["info"]["user-num"] = $userNum;

for ($i = 0; $i < $userNum; $i++) {
    $user = $usersResult->fetchArray(SQLITE3_ASSOC);
    $pickResult = $db->query("SELECT * FROM picks WHERE email = '" . $user["email"] . "' AND sessionNum = " . $sessionNum . " AND year = " . $year);
    
    $_SESSION["info"]["users"][$i]["name"] = $user["name"];
    
    if (!$pickResult || getNumOfRows($pickResult) === 0)
        $_SESSION["info"]["users"][$i]["pick-status"] = false;
    else
        $_SESSION["info"]["users"][$i]["pick-status"] = true;
}

postMessage("Pick status loaded", $page);

?>