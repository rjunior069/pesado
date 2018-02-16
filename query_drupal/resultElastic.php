<?php

class ResultWiki{	

	public function Url($curl,$lienTest,$postfields){		
		curl_setopt($curl, CURLOPT_URL, $lienTest);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
		$connexionToken = curl_exec($curl);
		return $connexionToken;
	}

	public function getValue(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		if ($request->type == 'all') {
			$request->type = '1';
		} elseif ($request->type == 'wikiprf') {
			$request->type = '2';
		} elseif ($request->type == 'prfrespostas') {
			$request->type = '3';
		} elseif ($request->type == 'sei') {
			$request->type = '4';
		}

		//$lienTest = 'http://10.0.16.11/query_drupal/middleware.php?&queryNumber=1&quantity=8&page="+$postdata->page+"&text=';
		$lienTest = 'https://homologacao.prf.gov.br/query_drupal/middleware.php?queryNumber='.$request->type.'&quantity=8&page=';
		//$lienTest = 'http://localhost/query_drupal/middleware.php?&queryNumber=1&quantity=8&page=';
		$lienTest .= $request->page;
		$lienTest .= "&text=";
		$lienTest .= $request->value;

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

$bisuario = new ResultWiki();
$bisuario->getValue();
