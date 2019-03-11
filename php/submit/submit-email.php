<?php

include("../api/sql-api.php");

//--------IMPORTANT VARS--------//
$db_dir = "../../sql/pickfivefootball.db";
$page = "../../html/admin/admin-send-email.php";

$from = "Commissioner@pick5.club";
$subject = $_POST["subject"];
$text = wordwrap($_POST["email-text"], 70);

$_SESSION["info"]["text"] = $_POST["email-text"];


//--------SQL LOGIC--------//
$db = new SQLite3($db_dir);

if ($db)
    console_log("Connection established");
else 
    postErrorMessage("Connection with the database was not successful. Try again later.", $page);

//-------SEND EMAIL--------//
$result = $db->query("SELECT * FROM loginInformation WHERE name IS NOT NULL");

if (!$result)
    postErrorMessage("Error getting user data", $page);

$userNum = getNumOfRows($result);
//for ($i = 0; $i < $userNum; $i++) {
//    $user = $result->fetchArray(SQLITE3_ASSOC);
//    mail($user["email"], $subject, $text, "From: " . $from);
//}

mail("crcodel@gmail.com", $subject, $text, "From:" . $from);

postMessage("Email sent successfully.", $page);
?>