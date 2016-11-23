<?php

require ('functions/SajanaJsonAjax.php');
require ('functions/KontaktZBaza.php');

//Łączenie z bazką
$baza = mysqli_connect('mariadb5.iq.pl', 'iexample_fnm', 'rcdky4y3zu', 'iexample_fnm');
// gdzies haslo wywalic importem z wyzszego katalogu moze

if (!$baza) {die("Connection to MySQL database failed " . mysqli_connect_error());
}


$kontaktZBaza  = new KontaktZBaza($baza);
$jsonAjaxApi = new SajanaJsonAjaxApi();
	
$requestData = $jsonAjaxApi->odbierzSajanAjax('apiRequest');

// case, switch, nie w petli if
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
		
		RETURN:jsonString - status
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
	
	if($requestData['action'] == 'sendReaction'){
		
		/*
		 * 
		 * sendReaction
		 * 
		 * desc:
		 * wysyla reakcje na tresc, zapisuje fakt obejrzenia tresci 
		 * 
		 * param:
		 * id usera
		 * id obrazka
		 * reakcja - reaction types:
		 * 		like: 1
		 * 		ban (dislike): -1
		 * 		dont care: 0
		 * 	
		 * 
		 * return status
		 * 
		 * */
		
		
		if (isset($requestData['userId']) && isset($requestData['fcObjectId'])&& isset($requestData['fcReaction'])){

			$userId = $requestData['userId'];
			$fcObjectId = $requestData['fcObjectId'];
			$fcReaction = $requestData['fcReaction'];

			$baza->query('INSERT INTO `action_user_'.$userId.'` set `id`='.$fcObjectId.', `reaction`='.$fcReaction.',`publicationId`='.$fcObjectId.';');
			
			// id 	publicationId 	reaction 
			
			
			
		echo('{"status":"sukces"}');
		}
		
		}
	
	
	
	
	if($requestData['action'] == 'getContent'){
		
		/*
		 * 
		 * getContent
		 * 
		 * desc:
		 * pobiera nastepna tresc
		 * 
		 * param:
		 * id usera
		 * 
		 * 
		 * return:
		 * id obrazka
		 * 
		 * */
		
		
		if (isset($requestData['userId']) ){


		
	/* 
	 * 
	 * na razie wysyla po kolei z glownej tresci
	 * 
	 * 1. pobierz najnowszy - $n element z bazy
	 * 2. sprawdz, czy jest w tablicy reakcji usera 
	 * 		a. nie -> zwroc ID
	 * 		b. tak -> zwiększ $n o 1, przejdź do 1.
	 * 
	 * */

			
				$userId = $requestData['userId'];
				
				$response = $baza->query('SELECT id FROM funnyContent ORDER BY id DESC LIMIT 1;');
				$response = mysqli_fetch_assoc($response);
				
				$currentId = $response['id'];
				
				
				for( ; ; ){
				
				$response2 = $baza->query('SELECT `id`, `publicationId`, `reaction` FROM `actions_user_40` WHERE `publicationId` = '.$currentId.'');
				$response2 = mysqli_fetch_assoc($response2);	
				$isEmpty = $response2['id'];
				
							
				if(empty($isEmpty))
				return $currentId;
				break;
				else
				$currentId --;
				
				
			}
			
			
		echo('{"status":"sukces"}');
		}
		
		}
		
		
		
	
}else{
	echo('{"status":"błąd nie podana akcja"}');
}



?> 
