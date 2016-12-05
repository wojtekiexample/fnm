<?php
session_start();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Stream</title>

<link rel="stylesheet" type="text/css" href="styles/styles.css" />

</head>

<body>
<? require ('server_mobile_services/functions/login.php'); ?>


<div align="center">
	<h1 id="fcTitleH1"></h1>
	<div id="fcContentDiv"></div>
    <button type="button" id="banBtn">Ban</button><button type="button" id="dontCareBtn">I don't care</button><button type="button" id="likeBtn">Like</button>
</div>





<script type="text/javascript">
var fcTitleH1 = document.getElementById("fcTitleH1");
var fcContentDiv = document.getElementById("fcContentDiv");


var currentContentId;

//guziory
var banBtn = document.getElementById("banBtn");
var dontCareBtn = document.getElementById("dontCareBtn");
var likeBtn = document.getElementById("likeBtn");
var userId =<?php if (isset($_SESSION['fnmUserId'])){echo($_SESSION['fnmUserId']);} ?>;





//listenery
banBtn.addEventListener("click",banBtnOnClick);
dontCareBtn.addEventListener("click",dontCareBtnOnClick);
likeBtn.addEventListener("click",likeBtnOnClick);

//funkcje na klik
function banBtnOnClick(){
	sendReaction(userId,currentContentId,1);
}
function dontCareBtnOnClick(){
	sendReaction(userId,currentContentId,2);
}
function likeBtnOnClick(){
	sendReaction(userId,currentContentId,3);
}

function sendReaction(userId,fcObjectId,fcReaction){
	var jsonAjaxBan = new SajanaAjax('/server_mobile_services/php_api.php',gdyWyslanaReakcja);
	var reactionApiRequest = new Object();
	reactionApiRequest.action = "sendReaction";
	reactionApiRequest.userId = userId;
	reactionApiRequest.fcObjectId = fcObjectId;
	reactionApiRequest.fcReaction = fcReaction;
	jsonAjaxBan.dodaj("apiRequest",reactionApiRequest);
	jsonAjaxBan.start();
	
}

function gdyWyslanaReakcja(response){
	console.log("gdyWyslanaReakcja"+response.status);
	if (response.status == "sukces"){
		location.reload();
	}
}



var apiRequest = new Object();
apiRequest.action = "getContent";
apiRequest.userId = userId;

var jsonAjax = new SajanaAjax('/server_mobile_services/php_api.php',przetworzResponseCntentu);
jsonAjax.dodaj("apiRequest",apiRequest);
jsonAjax.start();







function przetworzResponseCntentu(response){
	if (response){
		console.log(response);
		var fcContentHTML = decodeHTMLEntities (response.fcContent);
		var fcTitle = response.fcTitle;
		currentContentId = 	response.id;
		
		fcContentDiv.innerHTML = fcContentHTML;	
		fcTitleH1.innerHTML = fcTitle;
		
	}
}







/*
"Klasa"/prototyp do zybkiego ajaxowaia
przyjmuje url phpa i funkcje callbackową której zwraca respone od serwera

Metody:

dodaj(varname,varval); //podajemy dane do wyslania na serwer klucz/nazwa dla php post data i wartosc
!!zalca się wysyłanie wszystkich danych w ednym obiekcie i wywołanie tej metody raz wtedy dowolna struktura danych idzie jako json jako jedna parametr post
 choć można dodaać wiele obiektów oddzielnie wtedy każdy idzie w oddzielnym json stringu w oddzielnym parametrze post
start(); //uruchamia żadanie

*/
function SajanaAjax (url,callBackFunction){
	this.url = url; //url do php
	this.formData = new FormData(); //form data do wysłania
	this.request = new XMLHttpRequest (); //request
	this.request.open('POST',url,true); //otwieranie połaczenia
	this.request.addEventListener("load",onload); // nasłuch odbioru
	this.request.onreadystatechange = function (e) {
	  if (e.currentTarget.readyState == 4) {
		 if(e.currentTarget.status == 200){// jak się uda odebrać response
			 var odebranyJsonString = e.currentTarget.responseText; // odbieramy z php jsonString z responsem
			 var odebranyObiekt = JSON.parse(odebranyJsonString); // rozkodowujemy
			 callBackFunction(odebranyObiekt);// przekazujemy do funkcji callbeckowej do przetworzenia
		 }else{
		  console.log("obsrałosię");//log błedu
		 }
	  }
	};

	this.dodaj = function(varname,varval){//dodawanie obiektów/wartości do wysłania
		varval = JSON.stringify(varval); // kodowanie obiektu/wartości do jsonStringa
		this.formData.append(varname,varval);//dodanie do FormData

	}
	this.start = function(){ //wysyłanie requestu
		this.request.send(this.formData);
	}
}









function decodeHTMLEntities(text) {
    var entities = [
        ['amp', '&'],
        ['apos', '\''],
        ['#x27', '\''],
        ['#x2F', '/'],
        ['#39', '\''],
        ['#47', '/'],
        ['lt', '<'],
        ['gt', '>'],
        ['nbsp', ' '],
        ['quot', '"']
    ];

    for (var i = 0, max = entities.length; i < max; ++i) 
        text = text.replace(new RegExp('&'+entities[i][0]+';', 'g'), entities[i][1]);

    return text;
}

</script>


</body>
</html>
