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
	b. objects
	c. reactions

	//later 2. insert sample data

1. add methods for register, get_next_funny_content, reaction

2. clickby or CRON script for setting/ calculate trendsetters and update chains
	a. set trendsetters
	b. update feeds

	procedure:
	i. get users table [id, reactions_count] // +reset counter
	ii. set trendsetters (sort users BY reactions DESC)
	iii. get master chain data (newest to earliest_trendsetter_reaction)
	iv. set trends (filter masterfeed by trendsetters reactions)
	v. add content indexes to feeds/ chains table 


3. simple backup for testing purposes

4. frontend proof-of-concept JS app
5. MVP with phonegap



**************** ---------------------------------------------  ***********************************
**************** ---------------------------------------------  ***********************************


**/ 

echo '1';



/*

**************** ---------------------------------------------  ***********************************
				notes
**************** ---------------------------------------------  ***********************************


// mockup and descripton, documentation link\

catche?


założenia:
1. sa 2 podstawowe rodzaje chainów - pełny i trendsetterow
2. pierwszenstwo wyswietlania - najpierw grupy, potem pełny (nowe treści + ich indexowanie/ ocenianie)
3. każda dodana treść wpada do masterchaina




backend services

0. send funny content (user, chain_position)

'cron' jobs
1. analyze users_likes table
2. set trendsetters
3. update chains



**/ 


?>
