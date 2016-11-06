<?php



/* 

start



**************** ---------------------------------------------  ***********************************
			changelog:
**************** ---------------------------------------------  ***********************************


05.11 db table users
05.11 php_api mockup




**************** ---------------------------------------------  ***********************************
			main todo:
**************** ---------------------------------------------  ***********************************


0. create database model
		a. users - DONE
		b. objects - DONE
	c. reactions

	//later 2. insert sample data

1. add basic methods 
	a. register ( --> autologin, autoregister on mobile)
	b. upload content (mock)
 	c. get_next_funny_content
	d. send_reaction

2. clickby or CRON script for setting/ calculate trendsetters and update chains
	
	a. set trendsetters
	b. update feeds
	c. update users-trendsetters connections

	procedure:
	i. get users table [id, reactions_count] // +reset counter
	ii. set trendsetters (sort users BY reactions DESC)
	iii. get master chain data (newest to earliest_trendsetter_reaction)
	iv. set trends (filter masterfeed by trendsetters reactions)
	v. add content indexes to feeds/ chains table 

	vi. create trendsetters patterns
	vii. get User IF reaction_cout > 100
	viii. compare user behavior to trendsetters(?trendfeeds?)
	ix. update User entry: set trensetterID, reset reaction_counter


	// 3. simple backup for testing purposes
		a. mass content upload
		b. adContent auto scripts/ robots
		c. 


3. frontend proof-of-concept JS app
	1. login/ regsister form
	2. list view (get_content, send_reaction)
4. MVP with phonegap



**************** ---------------------------------------------  ***********************************
**************** ---------------------------------------------  ***********************************


**/ 

echo '1';



/*


**************** ---------------------------------------------  ***********************************
				questions
**************** ---------------------------------------------  ***********************************

WS: jak weryfikacja, autentykacja komunikacji mobileapp <-> serwer (php api) ??



**************** ---------------------------------------------  ***********************************
				notes
**************** ---------------------------------------------  ***********************************


// mockup and descripton, documentation link\

catche?


założenia:
1. sa 2 podstawowe rodzaje chainów - pełny i trendsetterow
2. pierwszenstwo wyswietlania - najpierw grupy, potem pełny (nowe treści + ich indexowanie/ ocenianie)
3. każda dodana treść wpada do masterchaina



____________-



logika endless feeda bez powtórzeń:

a. mobile wysyła żądanie przesłania następnej partii treści (1 bądź więcej obiektów
b. indentyfikacja użytkownika
c. sprawdzenie przypisanego feeda

d. wybranie najnowszej treści z feeda
e. sprawdzenie, czy byla do niej reakcja (czy byla kiedys wyswietlona)
	e.1. jeśli tak - cofamy o jeden index na feedzie = bierzemy wcześniejszy element feeda
	e.2. jeśli nie - przygotowane do wysłania

f. w przypadku wyczerpania feeda trendsettera, przełączamy na główny feed
g. w przypadku wyczerpania master feeda -> komunikat + dalej obrazki losowo z bazy (?nadpisywanie reakcji?)



**/ 


?>
