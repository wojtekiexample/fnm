<?php

require ('functions/SajanaJsonAjax.php');
require ('functions/KontaktZBaza.php');
require ('functions/connectToDB.php');

//WAGI REAKCJI







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
			$baza->query("CREATE TABLE $userActionsTableName (id int(11) AUTO_INCREMENT PRIMARY KEY, publicationId int(11), reaction int(11))");

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
			$fcContent = htmlspecialchars($fcContent, ENT_QUOTES);

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
		 * 		
		 * 		ban (dislike): 1
		 *		dont care: 2
		 *		like: 3
		 * 		
		 * 	
		 * 
		 * return status
		 * 
		 * */
		
		
		if (isset($requestData['userId']) && isset($requestData['fcObjectId'])&& isset($requestData['fcReaction'])){

			$userId = $requestData['userId'];
			$fcObjectId = $requestData['fcObjectId'];
			$fcReaction = $requestData['fcReaction'];
			
			//przypisanie influanceIndex
			$wagaReakcji=0;
			switch($fcReaction){
				case 1:
				//ban
				$wagaReakcji = 10;
				break;
				case 2:
				//dontCare
				$wagaReakcji = 5;
				case 3:
				//dontCare
				$wagaReakcji = 1;
			}
			
			
			
			$result = $kontaktZBaza->selectRowToAsoc("SELECT `influenceIndex` FROM `users` WHERE `id`='".$userId."';");
			$aktualnyInfluenceIndex = $result['influenceIndex'];
			$nowyInfluenceIndex = $aktualnyInfluenceIndex + $wagaReakcji;
			
			$query ='Update `users` set `influenceIndex`= '.$nowyInfluenceIndex.' WHERE `id`='.$userId.';';
			$baza->query($query);
			
			
			if ($baza->query('INSERT INTO `actions_user_'.$userId.'` set `reaction`='.$fcReaction.',`publicationId`='.$fcObjectId.';')){
				echo('{"status":"sukces"}');
			}else{
				echo('{"status":"obsrało się"}');
			}

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
	 * 1. pobierz id najnowszego - $n elementu z bazy
	 * 2. sprawdz, czy jest w tablicy reakcji usera 
	 * 	a) jeśli nie (tzn nie był oglądany) pobierz dane z elementu o tym id 
	 *  b) jeśli był oglądany dekrementuj id
	 * 
	 * */
			
				$userId = $requestData['userId'];
				
				$query = 'SELECT `id` FROM `funnyContent` ORDER BY `id` DESC LIMIT 1;';
				$wieraszAsoc = $kontaktZBaza->selectRowToAsoc($query);
				$currentId = $wieraszAsoc['id'];

					
				for($currentId ;  ; $currentId-- ){
				$query = 'SELECT `id` FROM `actions_user_'.$userId.'` WHERE `publicationId` = '.$currentId.';';
				$wieraszAsoc = $kontaktZBaza->selectRowToAsoc($query);
				$wasDisplayedAlredy = !empty($wieraszAsoc['id']);

				if(!$wasDisplayedAlredy){
					
					$query = 'SELECT `id`, `fcTitle`, `fcContent`  FROM `funnyContent` WHERE `id` = '.$currentId.';';
					$wieraszAsoc = $kontaktZBaza->selectRowToAsoc($query);
					
					$fcTitle = $wieraszAsoc['fcTitle'];
					$fcContent = $wieraszAsoc['fcContent'];
					
					echo '{"id":"'.$currentId.'","fcTitle":"'.$fcTitle.'","fcContent":"'.$fcContent.'"}';
					break;	
				}
				
			}
		}
		
		}
		
		
		
	
}else{
	echo('{"status":"błąd nie podana akcja"}');
}



?> 
