<?php

include("../api/sql-api.php");

//--------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-user-management.php";

//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//--------GET USERS--------//
$result = $db->query("SELECT * FROM loginInformation ORDER BY name ASC");

if (!$result || getNumOfRows($result) == 0)
    postErrorMessage("No users were found. Try registering one.", $page);

$userNum = getNumOfRows($result);
$_SESSION["info"]["userNum"] = $userNum;
$_SESSION["info"]["users"] = array($userNum);
for ($i = 0; $i < $userNum; $i++) {
    $user = $result->fetchArray(SQLITE3_ASSOC);

    $_SESSION["info"]["users"][$i] = array(4);
    $_SESSION["info"]["users"][$i]["email"] = $user["email"];
    $_SESSION["info"]["users"][$i]["admin"] = $user["accountType"];

    if (isset($user["name"])) {
        $_SESSION["info"]["users"][$i]["name"] = $user["name"];
        $_SESSION["info"]["users"][$i]["registered"] = true;
    } else {
        $_SESSION["info"]["users"][$i]["name"] = "--";
        $_SESSION["info"]["users"][$i]["registered"] = false;
    }
}

postMessage("Users loaded.", $page);

?>