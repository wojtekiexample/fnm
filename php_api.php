<?php
$host = $daneDoMySQL->{'mariadb5.iq.pl'};
$user = $daneDoMySQL->{'iexample_fnm'};
$password = $daneDoMySQL->{'rcdky4y3zu'};
$db = $daneDoMySQL->{'iexample_fnm'};
//ustanawiamy polączenie
@ $bazaPortfolio = new mysqli($host,$user,$password,$db);//@ $nazwabazy = new nysqli ('host','uzytkownik','haslo','baza');
if (mysqli_connect_errno()){//mysqli_connect_errno() Return an error code from the last connection error, if any:
echo 'Wystąpił bląd nie udało się połączyć z bazą. ';	
}


/*

function send_funny_content($user OR $trendsetter, chain1 position, chain2 position){

}

function react_to_content($objectID, $reaction){

}


**/





function addUser(){

}



?> 

