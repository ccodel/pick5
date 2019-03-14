<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

// Check login credentials, admin status
if (empty($_SESSION["logged-in"]) || !isset($_SESSION["logged-in"]) || $_SESSION["logged-in"] == "false") {
    $_SESSION["error"] = "You are not logged in.";
    header("Location: ../../html/login.php");
    exit();
} else if (isset($_SESSION["last-activity"]) && time() - $_SESSION["last-activity"] < 3600) {
    $_SESSION["last-activity"] = time();
} else {
    $_SESSION["error"] = "Your session has expired.";
    $_SESSION["email"] = null;
    $_SESSION["admin"] = null;
    $_SESSION["logged-in"] = null;
    $_SESSION["last-activity"] = null;
    $_SESSION["info"] = null;
    header("Location: ../../html/login.php");
    exit();
}

// Called by page if pertaining to admin privileges
function checkAdmin() {
    if (!isset($_SESSION["admin"])) {
        $_SESSION["error"] = "You are not admin.";
        header("Location: ../../index.php");
        exit();
    }
}

?>