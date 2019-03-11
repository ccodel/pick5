<?php

$db = new SQLite3("../../sql/pickfivefootball.db");

echo "<script>";
echo "console.log('Running init database. Connected');";
echo "</script>";

$db->query('CREATE TABLE IF NOT EXISTS loginInformation (name VARCHAR(255), email VARCHAR(255) NOT NULL PRIMARY KEY, pwd VARCHAR(255), accountType BIT NOT NULL)');
$db->query('CREATE TABLE IF NOT EXISTS sessions (sessionNum INTEGER NOT NULL, year INTEGER NOT NULL, sessionTitle VARCHAR(255) NOT NULL, dollarsPerPoint INTEGER NOT NULL, gamesToPlay INTEGER NOT NULL, gamesToPick INTEGER NOT NULL, masterKickoff DATETIME NOT NULL, perfectBonus BIT NOT NULL, steakDinner BIT NOT NULL)');
$db->query('CREATE TABLE IF NOT EXISTS games (sessionNum INTEGER NOT NULL, year INTEGER NOT NULL, home VARCHAR(255) NOT NULL, away VARCHAR(255) NOT NULL, spread REAL NOT NULL, kickoff DATETIME NOT NULL, winner BIT, defaultLoss BIT, defaultUnderdog BIT)');    
$db->query('CREATE TABLE IF NOT EXISTS picks (email VARCHAR(255) NOT NULL, sessionNum INTEGER NOT NULL, year INTEGER NOT NULL, pick1home VARCHAR(255) NOT NULL, pick1away VARCHAR(255) NOT NULL, pick1pick INTEGER NOT NULL, pick2home VARCHAR(255), pick2away VARCHAR(255), pick2pick BIT, pick3home VARCHAR(255), pick3away VARCHAR(255), pick3pick BIT, pick4home VARCHAR(255), pick4away VARCHAR(255), pick4pick BIT, pick5home VARCHAR(255), pick5away VARCHAR(255), pick5pick BIT)');    
$db->query('CREATE TABLE IF NOT EXISTS history (email VARCHAR(255) NOT NULL, sessionNum INTEGER NOT NULL, year INTEGER NOT NULL, wins INTEGER NOT NULL, losses INTEGER NOT NULL, points INTEGER NOT NULL, bonusPoints INTEGER NOT NULL, winnings INTEGER NOT NULL, ytdWins INTEGER NOT NULL, ytdLosses INTEGER NOT NULL, ytdPoints INTEGER NOT NULL, ytdBonusPoints INTEGER NOT NULL, ytdWinnings INTEGER NOT NULL, bonuses INTEGER NOT NULL, shares INTEGER NOT NULL)');

echo "<script>";
echo "console.log('Loading database with test data.');";
echo "</script>";

//--------Add Dad as admin--------//
$db->query('INSERT INTO loginInformation (email, accountType) VALUES ("frcodel@mchsi.com", 1)');

echo "<script>";
echo "console.log('All done loading the database with test data');";
echo "</script>";

$db->close();
echo "<script>";
echo "console.log('All done initializing the database');";
echo "</script>";

?>