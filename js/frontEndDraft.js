var dodajUseraBtn = document.getElementById("dodajUseraBtn");
var dodajUseraLoginInput = document.getElementById("dodajUseraLoginInput");
var dodajUseraPasswordInput = document.getElementById("dodajUseraPasswordInput");
var dodajUseraEMailInput = document.getElementById("dodajUseraEMailInput");




dodajUseraBtn.addEventListener("click",onClickDodajUseraBtn);
function onClickDodajUseraBtn(){
	var apiRequest = new Object();
	apiRequest.action = "addUser";
	apiRequest.login = dodajUseraLoginInput.value;
	apiRequest.eMail = dodajUseraEMailInput.value;
	apiRequest.password = dodajUseraPasswordInput.value;
	
	
	var jsonAjax = new SajanaAjax('/server_mobile_services/php_api.php',przetworzResponseTworzeniuUsera);
	jsonAjax.dodaj("apiRequest",apiRequest);
	jsonAjax.start();
}

function przetworzResponseTworzeniuUsera(response){
	console.log(response);
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