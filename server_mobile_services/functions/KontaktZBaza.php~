<?php
class KontaktZBaza{
	private $baza;
	function __construct($baza) {
		$this->baza =$baza;
	}
	// ta funkcja przyjmuje sql query którego wynikiem !musi być jeden wiersz zwracany jest od jako tablica asocjacyjna
	function selectRowToAsoc($query){
		$wynik = $this->baza->query($query);
		$toReturn = $wynik->fetch_assoc();
		return $toReturn;
	}
	//gdy mamy mieć 1 lub więcej wierszy podtbale to przjmuje sqlquey i zwraca podtabela jako arraya indexowanego napełnionego arrayami asocjacyjnymi
	function selectRowsToArrayOfAsoc($query){
		$wynik = $this->baza->query($query);
		$ile_znalezionych = $wynik->num_rows;
		$toReturnArr = array();
		for ($i=0;$i<$ile_znalezionych;$i++){
				$wiersz = $wynik->fetch_assoc();
				array_push($toReturnArr,$wiersz);
		}
		return $toReturnArr;
	}
}
?>