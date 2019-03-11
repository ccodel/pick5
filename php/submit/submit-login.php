<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/login.php";

$email = $_POST['email'];
$password = $_POST['password'];

if ($email == null)
    postErrorMessage("Please enter a valid email", $page);

if ($password == null)
    postErrorMessage("Please enter a password", $page);

//--------SQL LOGICS--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//Check for email
if (!doesEntryExist($db, "loginInformation", "email", $email))
    postErrorMessage("A user with that email does not exist. Contact an administrator to get registered.", $page);

$result = $db->query("SELECT * FROM loginInformation WHERE email = '" . $email . "'");
if (!$result)
    postErrorMessage("Login (database query) failed. Try again later.", $page);

if (getNumOfRows($result) == 0)
    postErrorMessage("No user of that email was found. Try registering.", $page);

$firstRow = $result->fetchArray(SQLITE3_ASSOC);
//Check if they have registered yet
if (!isset($firstRow['pwd']))
    postErrorMessage("You have not registered yet.", $page);
else if ($firstRow['pwd'] != md5($password))
    postErrorMessage("Incorrect password.", $page);

$admin = $firstRow["accountType"];

$db->close();
login("Successfully logged in.", $email, $admin, "../../index.php");

?>