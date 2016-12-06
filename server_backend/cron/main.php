<?php 

require ('../../server_mobile_services/functions/SajanaJsonAjax.php');
require ('../../server_mobile_services/functions/KontaktZBaza.php');
require ('../../server_mobile_services/functions/connectToDB.php');

$kontaktZBaza  = new KontaktZBaza($baza);
$jsonAjaxApi = new SajanaJsonAjaxApi();
$requestData = $jsonAjaxApi->odbierzSajanAjax('apiRequest');

/*
 * General idea:
 * 
 * 	A. data:
 * 1. get user table and save as array - query + fetch
 * 2. get user_reactions tables and save as array
 * 3. get chains data, save array
 * 
 * 	B. calculation:
 * 1. set trendsetters based on overall activity, included reaction wages - check influence_index in user entry
 * 2. check trendsetter to feed similarity - find and save best match 
 * 2. generate trendsetters new content arrays - remove banned content
 * 
 * 	C. update:
 * 1. add new content (array) to chains 
 * 2. check user to trendsetter similarity - find and save best match
 *  
 * 
 * 
 * Procedura obliczania:
 * 1. na podstawie sumy użytkowników określ ilość trendsetterów
 * 2. wyznacz trendsetterów na podstawie influence_index
 * 3. pobierz dane: tablice reakcji trenderów, tablice streamów, określ ich sumy (długość)
 * 4. sprawdź najwyższą zbieżność między tablicami feedu i trenderów, przyjmując wagi: dislike -5, dontcare -1, like +5
 * 5. przypisz trenderów do feedów
 * 6. na podstawie reakcji trenderów znajdź unikalne (niewystępujące w feedzie) treści, dodaj je do streamów (grup)
 * 7. pobierz tablice reakcji użytkowników i znajdź najwyższą zbieżność z tablicami trenderów, przyjmując wagi (dla jednakowych reakcji): 
 * 
 *  
*/
		
	//wyznacz 10% (docelowo 1%) userow z najwyzszym influence index, zapisz po ID
	
	
			//sprawdz ile userow
					
			$query = 'SELECT * FROM `users` ORDER BY `id` DESC LIMIT 1;';
			$wieraszAsoc = $kontaktZBaza->selectRowToAsoc($query);
			
			$usercount = $wieraszAsoc['id'];
			
			echo 'users:: '.$usercount;


	//oblicz ilość i wyznacz trendsetterów sortując po influence_index
	
			$trendsetterscount = floor($usercount*0.1);
			
			echo '<br><br>trendsetters count:: '.$trendsetterscount;
									
			$query = 'SELECT * FROM `users` ORDER BY `influenceIndex` DESC LIMIT '.$trendsetterscount.'; ';
			$trendsetter = $kontaktZBaza->selectRowsToArrayOfAsoc($query);
					
			echo '<br><br><strong>trendsetters array:: </strong>';
			print_r ($trendsetter);
		
	
	// !!!
	//TODO: zwiększanie influence_index przy akcjach użytkowników
	//TODO: reset infuence_index
	// !!!
	
	
	
	// pobierz tablice reakcji trendsetterow
			
		for($t=0;$t<$trendsetterscount;$t++){
			
				$query = 'SELECT * FROM `actions_user_'.$trendsetter[$t]['id'].'` ; ';
				$reactionstrendsetter[$t] = $kontaktZBaza-> selectRowsToArrayOfAsoc($query);
					echo "<strong><br><br>reactions trendsetter ".$t.":: </strong>";
					print_r ($reactionstrendsetter[$t]);
		
}


	// pobierz tablice streamów

		for($t=1; $t<11 ; $t++){
			
				$query = 'SELECT * FROM `stream'.$t.'` ; ';
				$stream[$t] = $kontaktZBaza-> selectRowsToArrayOfAsoc($query);
					echo "<br><strong><br>stream ".$t.":: </strong>";
					print_r ($stream[$t]);
				
	
				}
	
	
	//pobierz tablice funnyContent
		
				$query = 'SELECT * FROM `funnyContent` ; ';
				$mainstream = $kontaktZBaza-> selectRowsToArrayOfAsoc($query);
					echo "<br><strong><br>główny stream treści :: </strong>";
					print_r ($mainstream);
				
				

	echo "<br><br>";				
	/* 
	 *
	 * znajdz sume reakcji usera, znajdz sume feeda
	 * 
	 * wez ostatnia -n reakcje usera, poszukaj w feedzie
	 * 	jesli jest -> dodaj/odejmij punkty do pomocniczej zmiennej, w zaleznosci od reakcji
	 * 	jesli nie -> dekrementuj n, powtorz
	 * 
	 * */ 



		// wyznacz sume reakcji trenderów
			
	for($t=0;$t<$trendsetterscount;$t++){
			
		$query = 'SELECT `id` FROM `actions_user_'.$trendsetter[$t]['id'].'` ORDER BY `id` DESC LIMIT 1; ';
		$trendsetterreactioncount[$t] =  $kontaktZBaza-> selectRowToAsoc($query);

		echo "<br>trendsetter ".$t." reaction count:: ".$trendsetterreactioncount[$t]['id'];
		}
	
	
	echo "<br><br>";
	
	
		// wyznacz sume streamów
		
	for($t2=1; $t2<11 ; $t2++){
		
		$query = 'SELECT `id` FROM `stream'.$t2.'` ORDER BY `id` DESC LIMIT 1; ';
		$streamcount[$t2] =  $kontaktZBaza-> selectRowToAsoc($query);

		echo "<br>stream ".$t2." count:: ".$streamcount[$t2]['id']." ";
	
	}



	// złożoność obliczeniowa? -> docelowo do dodania limity na wywołaniach SQL, ogólnie skrypt może się liczyć spokojnie 3-12 godzin
	
	// przyporządkuj trenderów do feedów
	/*
	 * znajdź treści na które zareagował user, zmień zmienną pomocniczą (licznik podobieństwa) w zależności od reakcji
	 * 
	 * pętla (taka sama zastosowana w obliczaniu zbieżności użytkownik-trender):
	 * 1. dla każdego trendera
	 * 2. dla każdej reakcji trendera
	 * 3. dla każdego feeda
	 * 4. dla każdego obrazka feeda
	 * 		sprawdź, czy się pokrywają:
	 * 		tak -> zmień wskaźnik podobieństwa
	 * 5. znajdź najwyższy wskaźnik podobieństwa, zapisz jako powiązanie w bazie (trender->feed)
	 */
	 
	 
for($l=0 ; $l<$trendsetterscount ; $l++){
	//echo "<br><strong>for1 userow</strong>";
	//petla trenderow
	
	
	for($p=1;$p<=10;$p++){
		//echo "<br>for2 streamow";
		//petla streamow
		
		for($o=0 ; $o<$trendsetterreactioncount[$l]['id'] ; $o++){
			//echo "<br>for3 reakcji";
			//petla reakcji
		

			for($q=$streamcount[$p]['id']-1 ; $q>=0 ; $q--){
			//echo "for4 dlugosci streamu  :::   userow l=".$l." streamów p=".$p." reakcji o=".$o." stremu q=".$q." ";
				//petla tresci streama
				
					// czy jest match
					if($stream[$p][$q]['contentID'] == $reactionstrendsetter[$l][$o]['publicationId']){
						
					//echo "::: !!!! match - reakcja :: ".$reactionstrendsetter[$l][$o]['reaction'];

					//zapisz match jako wskaznik podobienstwa do danego feeda
						switch($reactionstrendsetter[$l][$o]['reaction']){
							case 1:
								//echo "dislike -5 ";
								$usertofeed[$l][$p] = $usertofeed[$l][$p]-5;
								break;
								
							case 2:
								//echo "dontcare  -1 ";
								$usertofeed[$l][$p] = $usertofeed[$l][$p]-1;
								break;
								
							case 3:
								//echo "like  +5 ";
								$usertofeed[$l][$p] = $usertofeed[$l][$p]+5;
								break;
						
						break;	// w przypadku matcha wyjdz z petli
						}		
								
					}
				}
			}
		}
	}




	
	echo "<br><br>";
	
	//wydrukuj podobienstwa do feedow
	
	for($l=0 ; $l<$trendsetterscount ; $l++){
		
		for($a=1; $a<=10; $a++){
			echo "<br> trender:".$l." compared to feed:".$a." with result: ".$usertofeed[$l][$a];
		}	
	
	}
		




	echo "<br><br>";
	
	//wyznacz feed o najwyzszej zbieznosci
	
for($l=0 ; $l<$trendsetterscount ; $l++){
	
	$highestfeed[$l] = max($usertofeed[$l]);
	$matchedfeed[$l] = array_search($highestfeed[$l], $usertofeed[$l]);
	
	//$value = max($array);	
	//$key = array_search($value, $array);


	echo "<br> matched feed :: trender=".$l." feed=".$matchedfeed[$l];
	
	}	








// wez tablice reakcji trendera, znajdz unikalne pozycje, w zależności od reakcji dodaj do streama
	
	// petle for,
	// sprawdz, reakcje - wez liki
				// sprawdz czy jest ID w streamie
					// jesli nie dodaj wpis do tablicy
					
for($l=0 ; $l<$trendsetterscount ; $l++){
	// petla trendsetterow
	
	$addtostream[$l] = array();

	for($o=0 ; $o<$trendsetterreactioncount[$l]['id'] ; $o++){
		// petla reakcji trenderow
	
		if($reactionstrendsetter[$l][$o]['reaction'] == 3){
		
		//echo '<br>reakcja trendera'.$l.' :: like';
		
			for($p=0; $p < $streamcount[$matchedfeed[$l]]['id'] ; $p++){
				// petla feeda - szukamy unikalnej tresci do dodania
				
					if($reactionstrendsetter[$l][$o]['publicationId'] == $stream[$matchedfeed[$l]][$p]['contentID']){
					//echo ' jest w feedzie ';
					$uniquecounter[$l][$o]++;
					}
				}
			
			//echo " uniquecounter :: ".$uniquecounter[$l][$o];
			
			if(empty($uniquecounter[$l][$o])){
			$uniquecontent[$l]++;

			array_push($addtostream[$l], $reactionstrendsetter[$l][$o]['publicationId']);
			//TODO: add to feed, not only array !!
			
			}
		
		}
			
	}



}	

	// wydrukuj tablice elementow do dodania
	
echo "<br><br> <strong>unikalne ID elementow do dodania do streamow:</strong><br>";

for($l=0 ; $l<$trendsetterscount ; $l++){
echo "trender ".$l.":</strong>";
print_r ( $addtostream[$l]);
echo "<br>";
}




	//TODO: utwórz w bazie trendsetterom przypisanie jako trenderzy danego feeda
	


		
	//wyznacz najwyższą zbieżność tablic usera do trendera
	//przypisz każdemu użytkownikowi trendchain i trendsettera, na podstawie przypisania trendsettera do newsfeeda

	/*
	 for dla kazdego usera
			 wez tablice reakcji
				 porownaj, znajdz najwyzsza zbieznosc -> selekcja negatywna
					 zapisz odniesienie do trendsettera
		
		
		
for(userscount
	for (userreactioncout
		for(trender
			for(trendserreactions
				$zbieznosc1,2

*/


	// coby nie bylo nieskonczonej petli dla sumy reakcji = null

for($a=1 ; $a <= $usercount ; $a++){
	$userreactioncount[$a] = 0;
	}		
	
	
	

for($a=1 ; $a <= $usercount ; $a++){
	//for userow
	//echo "<br> for1 userow";
				
	$query = 'SELECT `id` FROM `actions_user_'.$a.'` ORDER BY `id` DESC LIMIT 1; ';
	$userreactioncount[$a] =  $kontaktZBaza-> selectRowToAsoc($query);
	
	$query = 'SELECT * FROM `actions_user_'.$a.'` ORDER BY `id` DESC ; ';
	$userreactions[$a] =  $kontaktZBaza-> selectRowsToArrayOfAsoc($query);

	//print_r ( $userreactions[$a]);

		for($b=0 ; $b < $userreactioncount[$a]['id'] ; $b++){
			//for reakcji userow
			//echo "<br>for2 reakcji userów";
			//print_r ($userreactioncount[$a]);
			
			for($c=0 ; $c < $trendsetterscount ; $c++) {
				//for trendsetterow
				//echo "<br>for3 trenderow";
				
				
				for($d=0 ; $d < $trendsetterreactioncount[$c]['id'] ; $d++){
					
					//echo "<br>for4 reakcji trenderow";
						//for reakcji trenderow
					
					// sprawdzenie
					// jesli reakcja usera == reakcja trendera --> zwieksz licznik podobienstwa // switch 
					
					if($userreactions[$a][$b]['publicationId'] == $reactionstrendsetter[$c][$d]['publicationId']){
						//echo "<br><br> MATCH !!!! ~~~~~ <br>";
							//ten if pokrywa tylko IDiki reakcji, nie ich rodzaj!!!
						
						
						//TODO: switch rodzajow reakcji
						
						$usertotrender[$a][$c]++;
					
						}
					}
				
				}
			
			
			
			}
	
	}


echo "<br><br>";



/*
// wydrukuj poziomy zbieznosci

for($a=1 ; $a <= $usercount ; $a++){
			
	for($c=0 ; $c < $trendsetterscount ; $c++) {

	echo "<br> zbieznosc user:: ".$a." to trender :: ".$c." with result :: ".$usertotrender[$a][$c];
				
	}
}
* 
*/


//wyznacz i wydrukuj najwyzsze zbieznosci dla userow 

for($a=1 ; $a <= $usercount ; $a++){
	
	$usertrender2[$a] = max($usertotrender[$a]);
	$usertrender[$a] = array_search($usertrender2[$a], $usertotrender[$a]);
	
	echo "<br> matched user ::".$a." trender=".$usertrender[$a];
	
	}	
	
	


echo "<br><br>";


// zapisz powiazanie user -> trender

// zapisz powiazanie trender -> feed


	
echo "<br>end<br>";


?>
