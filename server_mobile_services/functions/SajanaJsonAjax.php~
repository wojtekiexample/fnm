<?php
class SajanaJsonAjaxApi{
	//przyjmuje nazwe danej post zwraca zdekodowane jsona
	function odbierzSajanAjax($nazwa){
		if (isset($_POST[$nazwa])){
			return  json_decode($_POST[$nazwa],true);
		}
		
	}	
	//echuje dane sformatowane w json
	function wyslijSajanAjaxJakoArray($dane){
		$daneDoWyslania = array ();
		$numargs = func_num_args();
		$arg_list = func_get_args();
		for ($i = 0; $i < $numargs; $i++) {
			array_push($daneDoWyslania,$arg_list[$i]);
		}
		echo json_encode($daneDoWyslania);
	}
}
?>