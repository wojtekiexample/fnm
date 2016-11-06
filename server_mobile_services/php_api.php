<?php
require ('functions/SajanaJsonAjax.php');
require ('functions/KontaktZBaza.php');

//Łączenie z bazką
$baza = mysqli_connect('mariadb5.iq.pl', 'iexample_fnm', 'rcdky4y3zu', 'iexample_fnm');
if (!$baza) {die("Connection to MySQL database failed " . mysqli_connect_error());
}

// tu zmieniam sobie insex testy żeby wiedzieć czy serwer nie cashuje poprzedniej wersji

$kontaktZBaza  = new KontaktZBaza($baza);
$jsonAjaxApi = new SajanaJsonAjaxApi();
	
	
	

$requestData = $jsonAjaxApi->odbierzSajanAjax('apiRequest');
if (isset($requestData['action'])){
	if ($requestData['action'] == 'addUser'){
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
		
		RETURN:jsonString - statuss
		 	komunikat czy sie zapisało na razie strigowy
		
		
		#############################*/
		if (isset($requestData['login']) && isset($requestData['password'])&& isset($requestData['eMail'])){
			$login = $requestData['login'];
			$password = $requestData['password'];
			$passordHash = md5($password,false);
			$eMail = $requestData['eMail'];
			$baza->query("INSERT INTO `users` set `login`='$login', `passwordHash`='$passordHash',`eMail`='$eMail';");
			//pobieranie aktualnego indexu tabeli users
			$idUsera = $baza->insert_id;
			//ustalamy nazwe dla tabeli akcji danego usera znajac juz jego id
			$userActionsTableName='actions_user_'.$idUsera;
			// dopisujemu mu nazwe jego wlasnej tabeli z akcjami
			$baza->query("UPDATE `users` set `userActionsTableName`='$userActionsTableName' WHERE `id` = '$idUsera';");
			//tworzymyTabeleUsera
			$baza->query("CREATE TABLE $userActionsTableName (id int, publicationId int, reaction int)");
			echo('{"status":"sukces"}');
		}
	}
	if ($requestData['action'] == 'addFunnyContent'){
        /*#################################
		
		addFunnyContent
		
		DESCRIPTION:
			dodajae treść
			
		PARAMETERS:
			ownerId:int - id usera który wrzucił
			fcTitle:string - tytuł
			fcContent:HTMLstrin - content
			
		RETURN:jsonString - status
		#############################*/
		if (isset($requestData['ownerId']) && isset($requestData['fcTitle'])&& isset($requestData['fcContent'])){
			$ownerId = $requestData['ownerId'];
			$fcTitle = $requestData['fcTitle'];
			$fcContent = $requestData['fcContent'];
			$baza->query("INSERT INTO `funnyContent` set `ownerId`='$ownerId', `fcTitle`='$fcTitle',`fcContent`='$fcContent';");
			echo('{"status":"sukces"}');
		}
	}
}else{
	echo('{"status":"błąd nie podana akcja"}');
}



?> 