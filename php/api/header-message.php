<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["msg"])) {
    echo '<div style="border: 25px solid #00bf00; font-size: 20px;">' . $_SESSION["msg"] . '</div>';
    $_SESSION['msg'] = null;
}

if (isset($_SESSION["error"])) {
    echo '<div style="border: 25px solid red; font-size: 20px;">' . $_SESSION["error"] . '</div>';
    $_SESSION['error'] = null;
}

?>