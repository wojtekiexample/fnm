<?php

/* 

$host = $daneDoMySQL->{'mariadb5.iq.pl'};
$user = $daneDoMySQL->{'iexample_fnm'};
$password = $daneDoMySQL->{'rcdky4y3zu'};
$db = $daneDoMySQL->{'iexample_fnm'};


//ustanawiamy polączenie
$bazaPortfolio = new mysqli($host,$user,$password,$db);//@ $nazwabazy = new nysqli ('host','uzytkownik','haslo','baza');
if (mysqli_connect_errno()){//mysqli_connect_errno() Return an error code from the last connection error, if any:
echo 'Wystąpił bląd nie udało się połączyć z bazą. ';	
}
**/ 
echo("test7: ");


$servername = "mariadb5.iq.pl";
$username = 'iexample_fnm';
$password = 'rcdky4y3zu';

// Create connection
$db = mysqli_connect($servername, $username, $password, $username);

// Check connection
if (!$db) {
    die("Connection to MySQL database failed " . mysqli_connect_error());
}


//  $db->close();
//disconnect



/*

function send_funny_content($user OR $trendsetter, chain1 position, chain2 position){

}

function react_to_content($objectID, $reaction){

}


**/





function addUser(){

}



?> 

