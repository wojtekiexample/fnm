<?php
echo("test4");
//laczymy z bazka
//@ $baza = new mysqli('mariadb5.iq.pl','iexample_fnm','rcdky4y3zu','iexample_fnm');






$baza = new mysqli("mariadb5.iq.pl","iexample_fnm","rcdky4y3zu","iexample_fnm");
if (mysqli_connect_errno()){
echo 'Wystąpił bląd nie udało się połączyć z bazą. ';	
}


/*

function send_funny_content($user OR $trendsetter, chain1 position, chain2 position){

}

function react_to_content($objectID, $reaction){

}

*/
if (isset($_GET['action'])){
	/*###### 	FUNKCJA  addUser	 #######*/
	if ($_GET['action'] == 'addUser'){
		if (isset($GET('login')) && isset($GET('password'))&& isset($GET('eMail'))){
			$login = $GET('login');
			$password = $GET('password');
			$passordHash = md5($password);
			$eMail = $GET('eMail');
			
			$sqlQueryString = "INSERT INTO `users` set `login`='$login', `passwordHash` = '$passwordHash',`eMail`='$eMail'";
			if (!$baza->query("$sqlQuery")){
				echo('coś sie obsrało z wysłaniem do bazy');
			}else{
				echo('gites powinno być w bazie');
			}
		}
	}
}





?>