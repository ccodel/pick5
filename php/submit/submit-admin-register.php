<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-user-management.php";

//--------CONFIRM SUBMISSION--------//
$email = $_POST["email"];
$admin;
if (!empty($_POST["admin"]) && $_POST["admin"] == 1)
    $admin = 1;
else 
    $admin = 0;


//--------SQL LOGICS--------//
$db = new SQLite3($db_dir);

if ($db) 
    console_log("Connection established");
else 
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//Check for if someone exists with that email and user name
if (doesEntryExist($db, "loginInformation", "email", $email))
    postErrorMessage("A user exists with that email already. Try a new one.", $page);

//Insert information
$result = $db->query("INSERT INTO loginInformation (email, accountType) VALUES (" 
    . "'" . $email . "'" . ", "
    . $admin . ")");

if (!$result)
    postErrorMessage("Registration (database insertion) failed. Try again later.", $page);

postMessage("Registered that user successfully.", $page);

?>