<?php
//Łączenie z bazką
$baza = mysqli_connect('mariadb5.iq.pl', 'iexample_fnm', 'rcdky4y3zu', 'iexample_fnm');
// gdzies haslo wywalic importem z wyzszego katalogu moze
if (!$baza) {echo("Connection to MySQL database failed " . mysqli_connect_error());}
?>