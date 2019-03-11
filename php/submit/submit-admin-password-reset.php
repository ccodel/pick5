<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-user-management.php";

//--------CONFIRM SUBMISSION--------//
$email = $_POST["email"];

if (!isset($email) || $email == "")
    postErrorMessage("Please enter an email", $page);

//--------SQL LOGICS--------//
$db = new SQLite3($db_dir);

if ($db) 
    console_log("Connection established");
else 
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//Check for if someone exists with that email and user name
if (!doesEntryExist($db, "loginInformation", "email", $email))
    postErrorMessage("A user does not exist with that email. Try a new one.", $page);

//Reset password - set to null
$result = $db->query("UPDATE loginInformation SET pwd = NULL WHERE email = '" . $email . "'");

if (!$result)
    postErrorMessage("Password reset failed. Try again later.", $page);

postMessage("Reset password successfully.", $page);

?>