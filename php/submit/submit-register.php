<?php

include("../api/sql-api.php");

//-------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/register.php";

//--------CONFIRM SUBMISSION--------//
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmedPassword = $_POST['confirm-password'];

if ($name == null)
    postErrorMessage("The name field was empty. Enter your name to register.", $page);

if ($email == null)
    postErrorMessage("The email field was empty. Enter your email to register.", $page);

if ($password == null || $confirmedPassword == null)
    postErrorMessage("One of the password fields was empty. Enter a password to register.", $page);

if (strcmp($password, $confirmedPassword) != 0)
    postErrorMessage("Your passwords did not match. Try again.", $page);

//--------SQL LOGICS--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);


//Check for if someone exists with that email
if (!doesEntryExist($db, "loginInformation", "email", $email)) {
    postErrorMessage("A user with that email does not exist. Contact an administrator to get registered.", $page);
}

//Entry exists, scan for if already registered
$row = $db->query("SELECT * FROM loginInformation WHERE email = '" . $email . "'");
$rowArray = $row->fetchArray(SQLITE3_ASSOC);
if ($rowArray["pwd"] != NULL) {
    postErrorMessage("A user with that email has already been registered.", $page);
}

$admin = $rowArray["accountType"];

//All clear to insert, then
$result = $db->query("UPDATE loginInformation SET name = '" . $name . "', pwd = '" . md5($password) . "' WHERE email = '" . $email . "'");

if (!$result) {
    postErrorMessage("Registration (update) failed. Try again later.", $page);
}

//Registered
$db->close();
register("Successfully registered and logged in", $email, $admin, "../../index.php");
?>