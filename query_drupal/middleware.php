<?php 
require_once("wiky.inc.php");


function dealData($data){
	global $number_of_posts, $format, $number_of_pages;
	$output = json_decode($data, TRUE);
	$hits = $output["hits"];
	$total = $hits["total"];
	$theData = array();
		
	if( $total-($number_of_posts*$number_of_pages) > $number_of_posts ){
		$totalIterable = $number_of_posts;
	}
	else{ $totalIterable = $total-($number_of_posts*$number_of_pages); }
	if( $totalIterable <= 0 ){	$theData = array('quantity' => 0); }
	else{
		$occurrence = $hits["hits"];
		if( $totalIterable >= $number_of_posts ){ $theData = array('quantity' => $number_of_posts);	}
		else{ $theData = array('quantity' => $totalIterable); }

		if ($totalIterable == 0) {
			$dataArray[] = array();
		} else {
			for ( $iterator = 0; $iterator < $totalIterable; $iterator++){
				$source = $occurrence[$iterator];			
				$systemText = "";
				$dataTitle = "";
				$dataText = "";
				$link = "";
				$id = "";
				$system = $source["_index"];

				if( $system == "dprfresposta"){
					$systemText = "B-PRFRespostas";
					$dataTitle = strip_tags($source["_source"]["prfresposta"]["post"]["title"]);
					$dataText = substr(strip_tags($source["_source"]["prfresposta"]["post"]["content"]),0,500);
					$dataText = strip_tags(str_replace('&nbsp;',' ',$dataText));
					if($source["_source"]["prfresposta"]["post"]["parentid"]==NULL){
						$id = $source["_source"]["prfresposta"]["post"]["id"];
					}
					else{
						$id = $source["_source"]["prfresposta"]["post"]["parentid"];
					}
					$link = "http://prf.gov.br/prfrespostas/index.php?qa=".$id;
				}
				elseif($system == "wikiprf"){
					$systemText = "A-WikiPRF";
					$dataTitle = strip_tags($source["_source"]["wikiprf"]["page"]["title"]);
					$dataTitle = str_replace('_',' ',$dataTitle);
					$wiky=new wiky;
					if( $wiky->parse($source["_source"]["wikiprf"]["page"]["text"]) ){
						$dataText = $source["_source"]["wikiprf"]["page"]["text"];
						$dataText = str_replace('{| style="','<a style="',$dataText);
						$dataText = str_replace(';" |','/>',$dataText);
						$dataText = preg_replace('/(\{\{\{)|(\[\[\{\{\{)/m','<a',$dataText);
						$dataText = preg_replace('/(\}\}\}\]\])|(\}\}\} \|\})|(\}\}\}\|)/m','/>',$dataText);
						$dataText = preg_replace('/(\{\|) ([a-z]*="[a-z]*")/m','<b>',$dataText);
						$dataText = preg_replace('/(\|-)|(\|)|(\{\{)(.*?)(\}\})|(\[\[[A-Z,a-z]*:)(.*)(\]\])|(&nbsp;)/m','',$dataText);
						$dataText = strip_tags($dataText);
						$dataText = str_replace('_',' ',$dataText);
						//$dataText = substr($dataText, 0, 500);
					}
					else{ $dataText = " "; }
					$link = "http://www.prf.gov.br/wikiprf/index.php?curid=".$source["_source"]["wikiprf"]["page"]["id"];
					$wiky = null;
					unset($wiky);
				}
				elseif( $system == "dseid"){
					$systemText = "C-SEI";
					$dataTitle = strip_tags($source["_source"]["dseid"]["page"]["title"]);
					$dataText = substr(strip_tags($source["_source"]["dseid"]["page"]["content"]),0,500);
					$dataText =str_replace('&nbsp;',' ',$dataText);
					$id = $source["_source"]["dseid"]["page"]["id"];
					$id = base64_encode($id."|$|".$dataTitle);
					$link = "https://homologacao.prf.gov.br/query_drupal/resultados_sei.php?id=".$id;
				}

				$dataArray[] = array(
					'sistema' => $systemText,
					'titulo' =>$dataTitle,
					'texto' =>$dataText,
					'link' =>$link
					);
			}
		}

		usort($dataArray, function($a, $b) {
		    return strcasecmp($a['sistema'], $b['sistema']);
		});

		$theData = $dataArray;
	}

	header('Content-type: application/json');
	return json_encode($theData, JSON_UNESCAPED_UNICODE);
}



function selectQuery($selector, $start_page, $number_of_posts, $param = NULL){		
	if($param == NULL || $param == ''){ $selector = 6;}
	
	$param = trim(addslashes($param));
	$param = str_replace('-',' ',$param);
	$url = '';
	$address = 'http://10.0.20.53:9200';//IP Elasticsearch PRODUCAO
	//$address = 'http://10.0.16.22:9200'; // IP DEV - SEM ELASTICSEARCH AINDA

	switch($selector){
		case "0":
			$url = $address.'/_all/_search?pretty&source=';
			$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{ "match_phrase": { "wikiprf.page.title": "'.$param.'" } },{ "match_phrase": { "prfresposta.post.title": "'.$param.'" } },{ "match_phrase": { "prfresposta.post.content": "'.$param.'"}},{ "match_phrase": { "wikiprf.page.text": "'.$param.'" } },{ "match": { "wikiprf.page.title": "'.$param.'" } },{ "match": { "prfresposta.post.title": "'.$param.'" } },{ "match": { "prfresposta.post.content": "'.$param.'"}},{ "match": { "wikiprf.page.text": "'.$param.'" } }]}}}';
			break;
		case "1":
			$paramPrin = '(*'.str_replace(' ','* AND *',$param).'*)';
			//$paramSecun = '(*'.str_replace(' ','* OR *',$param).'*)';

			$url = $address.'/_all/_search?pretty&source=';
			
			// ORDEM DE RELEVANCIA NA BUSCA(COMPLETA): 1. Primeiro busca no título, 2. palavras espaçadas no título, 3. palavras espaçadas no título/corpo   "*'.$param.'*^2"
			$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{"query_string":{"fields":["wikiprf.page.title^7"],"query":"'.$paramPrin.'"}},{"match_phrase":{"prfresposta.post.title":{"query":"'.$param.'","boost":6}}},{"match_phrase":{"dseid.page.title":{"query":"'.$param.'","boost":5}}},{"match":{"wikiprf.page.title":{"query":"'.$param.'","boost":4,"operator":"and"}}},{"match":{"prfresposta.post.title":{"query":"'.$param.'","boost":4,"operator":"and"}}},{"match":{"dseid.page.title":{"query":"'.$param.'","boost":4,"operator":"and"}}},{"bool":{"should":[{"match_phrase":{"wikiprf.page.title":{"query":"'.$param.'","boost":3}}},{"match_phrase":{"wikiprf.page.text":{"query":"'.$param.'","boost":3}}},{"match_phrase":{"prfresposta.post.title":{"query":"'.$param.'","boost":3}}},{"match_phrase":{"prfresposta.post.content":{"query":"'.$param.'","boost":3}}},{"match_phrase":{"dseid.page.title":{"query":"'.$param.'","boost":3}}},{"match_phrase":{"dseid.page.content":{"query":"'.$param.'","boost":3}}}]}}]}}}';

			// ORDEM DE RELEVANCIA NA BUSCA(OK): 2. palavras espaçadas no título, 3. palavras espaçadas no título/corpo
			//$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should": [{"match": {"prfresposta.post.title": {"query": "'.$param.'","boost": 5,"type": "phrase","operator": "and"}}},{"match": {"wikiprf.page.title": {"query": "'.$param.'","boost": 5,"type": "phrase","operator": "and"}}},{"match": {"dseid.page.title": {"query": "'.$param.'","boost": 5,"type": "phrase","operator": "and"}}},{"match": {"prfresposta.post.title": {"query": "'.$param.'","boost": 4,"type": "phrase"}}},{"match": {"wikiprf.page.title": {"query": "'.$param.'","boost": 4,"type": "phrase"}}},{"match": {"dseid.page.title": {"query": "'.$param.'","boost": 4,"type": "phrase"}}},{"match": {"prfresposta.post.title": {"query": "'.$param.'","boost": 3,"operator": "and"}}},{"match": {"prfresposta.post.content": {"query": "'.$param.'","boost": 3,"operator": "and"}}},{"match": {"wikiprf.page.title": {"query": "'.$param.'","boost": 3,"operator": "and"}}},{"match": {"wikiprf.page.text": {"query": "'.$param.'","boost": 3,"operator": "and"}}},{"match": {"dseid.page.title": {"query": "'.$param.'","boost": 3,"operator": "and"}}},{"match": {"dseid.page.content": {"query": "'.$param.'","boost": 3,"operator": "and"}}},{"match": {"prfresposta.post.title": {"query": "'.$param.'","boost": 2,"operator": "or"}}},{"match": {"prfresposta.post.content": {"query": "'.$param.'","boost": 2,"operator": "or"}}},{"match": {"wikiprf.page.title": {"query": "'.$param.'","boost": 2,"operator": "or"}}},{"match": {"wikiprf.page.text": {"query": "'.$param.'","boost": 2,"operator": "or"}}},{"match": {"dseid.page.title": {"query": "'.$param.'","boost": 2,"operator": "or"}}},{"match": {"dseid.page.content": {"query": "'.$param.'","boost": 2,"operator": "or"}}}]}}}';
			
			// OR: Para trazer mais resultados...
			//$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{ "match_phrase": { "prfresposta.post.title": { "query":"'.$param.'", "slop": 4}}},{ "match_phrase": { "prfresposta.post.content": { "query":"'.$param.'", "slop": 4}}},{ "match_phrase": { "wikiprf.page.title": { "query":"'.$param.'", "slop": 4}}},{"match_phrase":{"wikiprf.page.text": { "query":"'.$param.'", "slop": 4}}},{ "match_phrase": { "dseid.page.title": { "query":"'.$param.'", "slop": 4}}},{"match_phrase":{"dseid.page.content": { "query":"'.$param.'", "slop": 4}}}]}}}';

			// ORIGINAL: Sem o SEI e mais simples...
			//$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{ "match_phrase": {"prfresposta.post.title":"'.$param.'"}},{ "match_phrase": { "prfresposta.post.content": "'.$param.'"}},{ "match_phrase": { "wikiprf.page.title":"'.$param.'"}},{"match_phrase":{"wikiprf.page.text":"'.$param.'"}}]}}}';
			break;
		case "2":
			$paramPrin = '(*'.str_replace(' ','* AND *',$param).'*)';

			$url = $address.'/_all/_search?pretty&source=';

			// ORDEM DE RELEVANCIA NA BUSCA(SOMENTE NO WIKI): 1. Primeiro busca no título, 2. palavras espaçadas no título, 3. palavras espaçadas no título/corpo
			$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{"query_string":{"fields":["wikiprf.page.title^7"],"query":"'.$paramPrin.'"}},{"match":{"wikiprf.page.title":{"query":"'.$param.'","boost":4,"operator":"and"}}},{"bool":{"should":[{"match_phrase":{"wikiprf.page.title":{"query":"'.$param.'","boost":3}}},{"match_phrase":{"wikiprf.page.text":{"query":"'.$param.'","boost":3}}}]}}]}}}';
			//$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{ "match_phrase": { "wikiprf.page.title":"'.$param.'"}},{"match_phrase":{"wikiprf.page.text":"'.$param.'"}}]}}}';
			break;
		case "3":
			$paramPrin = '(*'.str_replace(' ','* AND *',$param).'*)';

			$url = $address.'/_all/_search?pretty&source=';

			// ORDEM DE RELEVANCIA NA BUSCA(SOMENTE NO PRF RESPOSTA): 1. Primeiro busca no título, 2. palavras espaçadas no título, 3. palavras espaçadas no título/corpo
			$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{"query_string":{"fields":["prfresposta.post.title^7"],"query":"'.$paramPrin.'"}},{"match":{"prfresposta.post.title":{"query":"'.$param.'","boost":4,"operator":"and"}}},{"bool":{"should":[{"match_phrase":{"prfresposta.post.title":{"query":"'.$param.'","boost":3}}},{"match_phrase":{"prfresposta.post.content":{"query":"'.$param.'","boost":3}}}]}}]}}}';
			//$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{ "match_phrase": {"prfresposta.post.title":"'.$param.'"}},{ "match_phrase": { "prfresposta.post.content": "'.$param.'"}}]}}}';
			break;
		case "4":
			$paramPrin = '(*'.str_replace(' ','* AND *',$param).'*)';

			$url = $address.'/_all/_search?pretty&source=';

			// ORDEM DE RELEVANCIA NA BUSCA(SOMENTE NO SEI): 1. Primeiro busca no título, 2. palavras espaçadas no título, 3. palavras espaçadas no título/corpo
			$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{"query_string":{"fields":["dseid.page.title^7"],"query":"'.$paramPrin.'"}},{"match":{"dseid.page.title":{"query":"'.$param.'","boost":4,"operator":"and"}}},{"bool":{"should":[{"match_phrase":{"dseid.page.title":{"query":"'.$param.'","boost":3}}},{"match_phrase":{"dseid.page.content":{"query":"'.$param.'","boost":3}}}]}}]}}}';
			//$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{ "match_phrase": { "dseid.page.title":"'.$param.'"}},{"match_phrase":{"dseid.page.content":"'.$param.'"}}]}}}';
			break;
		case "5":
			$url = $address.'/dprfresposta/_search?pretty&source=';
			$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{"match":{"prfresposta.post.title":"'.$param.'"}},{"match":{"prfresposta.post.content":"'.$param.'"}}]}}}';
			break;
		case "6":
			$url = $address.'/dprfresposta/_search?pretty&source=';
			$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query":{"bool":{"should":[{"match_phrase":{"prfresposta.post.title": "'.$param.'"}},{"match_phrase":{"prfresposta.post.content": "'.$param.'"}}]}}}';
			break;
		case "7":
			$url = $address.'/wikiprf/_search?pretty&source=';
			$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query": {"bool":{"should": [{ "match": { "wikiprf.page.text": "'.$param.'" } },{ "match": { "wikiprf.page.title": "'.$param.'"}}]}}}';
			break;
		 case "8":
			$url = $address.'/wikiprf/_search?pretty&source=';
			$url = $url.'{"from":'.( $start_page*$number_of_posts ).',"size":'.$number_of_posts.',"query": {"bool": {"should": [{ "match_phrase": { "wikiprf.page.text": "'.$param.'" } },{ "match_phrase": { "wikiprf.page.title": "'.$param.'"}}]}}}';
			break;
		default:
			$url = $address.'/_all/_search?pretty&size='.$number_of_posts;
	}
	$url = str_replace(" ","%20",$url);	
	#$url = urlencode($url);	
	return $url;
}


function getContent($start_page = 0, $param = NULL){
	global $number_of_posts, $query_number;
	$data = '';

	$url = selectQuery($query_number, $start_page, $number_of_posts, $param);

	if($ch = curl_init()){
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);		 
	}
	return $data;
}

/* soak in the passed variable or set our own */
$number_of_posts = isset($_GET['quantity']) ? intval($_GET['quantity']) : 4; //4 is the default quantity
// $number_of_posts = 20;
$query_number = isset($_GET['queryNumber']) ? intval($_GET['queryNumber']) : 9; //
$format = 'json';
$number_of_pages = isset($_GET['page']) ? intval($_GET['page']) : 0;//0 is the first page, so don't need to set the default
$text_to_search = isset($_GET['text']) ?  $_GET['text'] : '';//search without a text in elasticsearch or search a text 
$data=getContent($number_of_pages,$text_to_search);

if($data != ""){ 
	echo dealData($data); 
}
?>
