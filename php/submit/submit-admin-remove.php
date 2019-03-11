<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-user-management.php";
$year = getYear();

//--------CONFIRM SUBMISSION--------//
$email = $_POST['email'];

//--------SQL LOGICS--------//
$db = new SQLite3($db_dir);

if ($db) 
    console_log("Connection established");
else 
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//Check for if someone exists with that email and user name
if (!doesEntryExist($db, "loginInformation", "email", $email))
    postErrorMessage("A user does not exist with that email.", $page);

//Remove data from login table
$result = $db->query("DELETE FROM loginInformation WHERE email = '" . $email . "'");

if (!$result)
    postErrorMessage("Could not delete user from login information table.", $page);

//Remove from picks
$result = $db->query("DELETE FROM picks WHERE email = '" . $email . "' AND year = " . $year);

if (!$result)
    postErrorMessage("Could not delete picks from picks table.", $page);

//Remove from history
$result = $db->query("DELETE FROM history WHERE email = '" . $email . "' AND year = " . $year);

if (!$result)
    postErrorMessage("Could not delete history from history table.", $page);

postMessage("Removed the user successfully.", $page);

?>