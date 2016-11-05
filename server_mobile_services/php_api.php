<?php
//Łączenie z bazką
$baza = mysqli_connect('mariadb5.iq.pl', 'iexample_fnm', 'rcdky4y3zu', 'iexample_fnm');
if (!$baza) {die("Connection to MySQL database failed " . mysqli_connect_error());
}else{echo('polaczone z bazka<br/>');}


// tu zmieniam sobie insex testy żeby wiedzieć czy serwer nie cashuje poprzedniej wersji
echo("test31: <br/>");


/*
PHP API na razie do pierwszych testów na GET poem przepnie się na post i najlepiej na model json->php->json

główny parametr jaki przyjmuje API to action który odpowiada odpowiedniej funkcji API

*/

if (isset($_GET['action'])){
	if ($_GET['action'] == 'addUser'){
		/*#################################
		
		addUser 
		
		DESCRIPTION:
			Tworzy w bazie users wpis nowego usera
			Tworzy pustą tabele akcji usera o nazwie actions_user_[id usera z tabeli users];
		
		
		PARAMETERS:
			action: równe "addUser"
			login: string
			password: string
			eMail:eMail
		
		RETURN:string
		 	komunikat czy sie zapisało na razie strigowy
		
		
		#############################*/
		echo("akcja dodaj usera <br/>");
		if (isset($_GET['login']) && isset($_GET['password'])&& isset($_GET['eMail'])){
			echo("wszystko jest set<br/>");
			
			$login = $_GET['login'];
			$password = $_GET['password'];
			$passordHash = md5($password);
			$eMail = $_GET['eMail'];
			$sqlQueryString = "INSERT INTO `users` set `login`='$login', `passwordHash` = '$passwordHash',`eMail`='$eMail';";
			if ($baza->query("$sqlQueryString")){
				echo('gites powinno być w bazie');
			}else{
				echo('coś sie obsrało z wysłaniem do bazy');
			}
			//pobieranie aktualnego indexu tabeli users
			$idUsera = $baza->insert_id;
			//tworzymyTabeleUsera
			$baza->query("CREATE TABLE actions_user_".$idUsera."(id int, publicationId int, reaction int)");
			
		}
	}
}





















/*

function send_funny_content($user OR $trendsetter, chain1 position, chain2 position){

}

function react_to_content($objectID, $reaction){

}


**/




?> 

