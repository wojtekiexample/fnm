<?php
echo("przed require");
require ('functions/SajanaJsonAjax.php');
require ('functions/KontaktZBaza.php');
echo("przed połączeniem");
//Łączenie z bazką
$baza = mysqli_connect('mariadb5.iq.pl', 'iexample_fnm', 'rcdky4y3zu', 'iexample_fnm');
// gdzies haslo wywalic importem z wyzszego katalogu moze

if (!$baza) {die("Connection to MySQL database failed " . mysqli_connect_error());}




//DROP DATABASE {mysql-database-name}


$sqlQuery = "CREATE TABLE users(";
$sqlQuery .=" id int(11) AUTO_INCREMENT PRIMARY KEY,";
$sqlQuery .=" createdTimestamp TIMESTAMP,";
$sqlQuery .=" actionsCount int(11),";
$sqlQuery .=" userActionsTableName text,";
$sqlQuery .=" eMail text,";
$sqlQuery .=" likes int(11),";
$sqlQuery .=" bans int(11),";
$sqlQuery .=" ignores int(11),";
$sqlQuery .=" login text,";
$sqlQuery .=" passwordHash text,";
$sqlQuery .=" influenceIndex int(11),";
$sqlQuery .=" asignedTransetter int(11)";
$sqlQuery .=");";
echo($sqlQuery);

$baza->query($sqlQuery);

$sqlQuery = "CREATE TABLE funnyContent(";
$sqlQuery .=" id int(11) AUTO_INCREMENT PRIMARY KEY,";
$sqlQuery .=" createdTimestamp TIMESTAMP,";
$sqlQuery .=" ownerId int(11),";
$sqlQuery .=" fcTitle text,";
$sqlQuery .=" fcContent text,";
$sqlQuery .=" likes int(11),";
$sqlQuery .=" bans int(11),";
$sqlQuery .=" ignores int(11)";
$sqlQuery .=");";

echo($sqlQuery);

$baza->query($sqlQuery);




?>