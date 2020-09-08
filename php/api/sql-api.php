<?php

//--------INITIALIZATION--------//
if (session_status() == PHP_SESSION_NONE)
    session_start();

include("database-init.php");
include("team-names.php");

//--------MESSAGE FUNCTIONS--------//
function login($msg, $email, $admin, $goto) {
    if ($admin == 1)
        $_SESSION["admin"] = "true";
    else
        $_SESSION["admin"] = "false";
    $_SESSION["msg"] = $msg;
    $_SESSION["email"] = $email;
    $_SESSION["logged-in"] = "true";
    $_SESSION["last-activity"] = time();
    header("Location: " . $goto);
    exit();
}

function logout($msg, $goto) {
    session_unset();
    $_SESSION["email"] = null;
    $_SESSION["admin"] = null;
    $_SESSION["msg"] = $msg;
    $_SESSION["error"] = null;
    $_SESSION["logged-in"] = null;
    $_SESSION["last-activity"] = null;
    $_SESSION["info"] = null;
    header("Location: " . $goto);
    exit();
}

function register($msg, $email, $admin, $goto) {
    login($msg, $email, $admin, $goto);
    exit();
}

function postErrorMessage($msg, $goto) {
    $_SESSION["error"] = $msg;
    header("Location: " . $goto);
    exit();
}

function postMessage($msg, $goto) {
    $_SESSION["msg"] = $msg;
    header("Location: " . $goto);
    exit();
}

function postMessageAndErrorMessage($msg, $error, $goto) {
    $_SESSION["msg"] = $msg;
    $_SESSION["error"] = $error;
    header("Location: " . $goto);
    exit();
}

function toSplash($splash, $destination, $picture, $msg, $error) {
    $_SESSION["msg"] = $msg;
    $_SESSION["error"] = $error;
    $_SESSION["info"] = array(2);
    $_SESSION["info"]["destination"] = $destination;
  
    // Generate random number to choose picture
    $randInt = rand(1, 100);
    if ($randInt == 1)
      $_SESSION["info"]["picture"] = "../img/mean.jpg";
    else if ($randInt == 2)
      $_SESSION["info"]["picture"] = "../img/middle-finger1.jpg";
    else if ($randInt == 3)
      $_SESSION["info"]["picture"] = "../img/rundall.jpg";
    else if ($picture != NULL)
      $_SESSION["info"]["picture"] = $picture;
    else if ($randInt % 2 == 0)
      $_SESSION["info"]["picture"] = "../img/cheerleaders.jpg";
    else
      $_SESSION["info"]["picture"] = "../img/bikini2.jpg";
  
    header("Location: " . $splash);
    exit();
}

//--------HELPER FUNCTIONS--------//
function console_log($msg) {
    echo "<script> console.log('" . $msg . "'); </script>";
}

function getYear() {
    $currentDate = date("y-m-d");
    $year = date('Y', strtotime($currentDate));
    $month = date('n', strtotime($currentDate));
    if ($month <= 3)
        $year -= 1;
    
    return $year;
}

function getMaxNumOfSessions() {
    // Manually return the number of sessions in one season
    return 100;
}

function cmpTimeToNow($passedDate, $passedTime) {
    date_default_timezone_set("America/Chicago");
    //Date is in format of y-m-d, and time of HH:MM (military)
    //Returns -1 if passed time is before now, 0 if same, and 1 if later than now
    $year = date("Y");
    $month = date("m");
    $day = date("d");
    $hour = date("H");
    $minute = date("i");

    $index = strpos($passedDate, "-");
    $passedYear = substr($passedDate, 0, $index);
    $trunc = substr($passedDate, $index + 1);

    $index = strpos($trunc, "-");
    $passedMonth = substr($trunc, 0, $index);
    $trunc = substr($trunc, $index + 1);

    $passedDay = $trunc;

    $index = strpos($passedTime, ":");
    $passedHour = substr($passedTime, 0, $index);
    $passedMinute = substr($passedTime, $index + 1);

    if ($passedYear < $year)
        return -1;
    else if ($passedYear > $year)
        return 1;

    if ($passedMonth < $month)
        return -1;
    else if ($passedMonth > $month)
        return 1;

    if ($passedDay < $day)
        return -1;
    else if ($passedDay > $day)
        return 1;

    if ($passedHour < $hour)
        return -1;
    else if ($passedHour > $hour)
        return 1;

    if ($passedMinute < $minute)
        return -1;
    else if ($passedMinute > $minute)
        return 1;

    return 0;
}

function getNumOfRows($result) {
    $nrows = 0;
    $result->reset();
    while ($result->fetchArray())
        $nrows++;
    $result->reset();
    return $nrows;
}

function doesEntryExist($database, $table, $field, $value) {
    $result = $database->query("SELECT * FROM " . $table . " WHERE " . $field . " = " . "'" . $value . "'");

    if (!$result)
        return false;

    if (getNumOfRows($result) > 0)
        return true;

    return false;
}

function getMostRecentSession() {
    // TODO fill in with most recent session logic
    // Figure out if PHP returns tuples
}

// For admin weekly session selection
function getSessionScrollHTML($scrollId) {
  $db = new SQLite3("../../sql/pickfivefootball.db");
  $year = getYear();
  $result = $db->query("SELECT * FROM seasons WHERE year = " . $year);
  if (!$result || getNumOfRows($result) == 0)
    return "<h4>Error getting number of seasons</h4>";

  $row = $result->fetchArray(SQLITE3_ASSOC);
  $numberOfSessions = $row["sessions"];
  $hasSessions = true;

  // Get active sessions
  $result = $db->query("SELECT sessionNum, title FROM sessions WHERE year = " . $year);
  if (!$result)
    $hasSessions = false;

  $sessions = $result->fetchArray(SQLITE3_ASSOC);
  $numActive = getNumOfRows($sessions);

  $scrollStr = "<select id='" . $scrollId . "' name='" . $scrollId . "'><option value='blank'";
  if (!isset($_SESSION["info"]["session"]))
    $scrollStr = $scrollStr . " selelected='selected'";
  $scrollStr = $scrollStr . ">";

  // Loop through sessions and pick out those names already defined
  for ($i = 1; $i <= $numberOfSessions; $i++) {
    $weekStr = "Week " . $i;
    if ($hasSessions) {
      for ($j = 0; $j < $numActive; $j++) {
        if ($sessions[$j]["sessionNum"] == $i) {
          $weekStr = $sessions[$j]["title"];
          break;
        }
      }

      $scrollStr = $scrollStr . "<option value='session" . $i . "'";
      if ($_SESSION["info"]["session"] == "session" . $i)
        $scrollStr = $scrollStr . " selected='selected'";
      $scrollStr = $scrollStr . ">" . $weekStr . "</option>";
    }
  }

  $scrollStr = $scrollStr . "</select>";
  return $scrollStr;
}

?>
