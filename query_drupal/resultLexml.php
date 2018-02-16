<?php

class ResultLexml{	

	public function Url($curl,$lienTest,$postfields){		
		curl_setopt($curl, CURLOPT_URL, $lienTest);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
		$connexionToken = curl_exec($curl);
		return $connexionToken;
	}

	public function getStringWithoutA($strString) {
		return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$strString);
	}

	public function getValue(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$lienTest = 'http://lexmlwsprod.prf/LexmlWs/rest/lexml/buscar?param=';

		$lienTest .= $this->getStringWithoutA($request->value);
		//print_r($lienTest);

		$postfields = array(
			'format'=> 'json'
		);

		$curl = curl_init();
		$connexion = $this->Url($curl,$lienTest,$postfields);

		print_r($connexion);
		return $connexion;
	}
}
$bisuario = new ResultLexml();
$bisuario->getValue();