<?php
require ('connectToDB.php');
require ('KontaktZBaza.php');
if (isset($_POST["zalogujId"])){
	echo("tu zalogujemy na usera ".$_POST["zalogujId"]." jeśli takowy istnieje");
	$kontaktZBaza  = new KontaktZBaza($baza);
	$zalogujId = $_POST["zalogujId"];
	$sqlQuery = 'SELECT * FROM `users` WHERE `id` = '.$zalogujId.';';
	$row = $kontaktZBaza->selectRowToAsoc($sqlQuery);
	if (isset($row["id"])){
		$_SESSION['fnmUserId']=$row["id"];
		$_SESSION['fnmUserLogin']=$row["login"];
		$_SESSION['fnmUserEmail'] = $row["eMail"];
	}else{
		echo("nie ma tekiego id w bazie nie możemy logować");
	}
	exit();
}


if (isset($_SESSION['fnmUserId'])){
	echo(
	'<div id="loginPanel" align="center">
	Jesteś zalogowany jako id: '.$_SESSION['fnmUserId'].' login: '.$_SESSION['fnmUserLogin'].' email: '.$_SESSION['fnmUserEmail'].' Przeloguj na: 
	<input type="number" id="zalogujId" />
	<button type="button" id="zalogujBtn">Zaloguj</button>
	</div>
	<script type="text/javascript">
		var zalogujBtn = document.getElementById("zalogujBtn");
		zalogujBtn.onclick=function(){
			var zalogujId = document.getElementById("zalogujId").value;
			var currentUrl = window.location.href;
			var formData = new FormData();
			formData.append("zalogujId", zalogujId);
			var reqest = new XMLHttpRequest();
			reqest.open("POST", currentUrl, true); 
			reqest.onreadystatechange = function (aEvt) {
			  if (reqest.readyState == 4) {
				 if(reqest.status == 200)
				  location.reload();
				 else
				  location.reload();
			  }
			};
			reqest.send(formData);
		}
	</script>
	');
}else{
	echo(
	'<div id="loginPanel"  align="center">
	Nie jesteś zalogowany zaloguj na: 
	<input type="number" id="zalogujId" />
	<button type="button" id="zalogujBtn">Zaloguj</button>
	</div>
	<script type="text/javascript">
		var zalogujBtn = document.getElementById("zalogujBtn");
		zalogujBtn.onclick=function(){
			var zalogujId = document.getElementById("zalogujId").value;
			var currentUrl = window.location.href;
			var formData = new FormData();
			formData.append("zalogujId", zalogujId);
			var reqest = new XMLHttpRequest();
			reqest.open("POST", currentUrl, true); 
			reqest.onreadystatechange = function (aEvt) {
			  if (reqest.readyState == 4) {
				 if(reqest.status == 200)
				  location.reload();
				 else
				  location.reload();
			  }
			};
			reqest.send(formData);
		}
	</script>
	');
}
?>