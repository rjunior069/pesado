<?php
function getResultUSEI($data) {
  $output = json_decode($data, TRUE);
  $hits = $output["hits"];
  $occurrence = $hits["hits"];

  $source = $occurrence[0];     
  $dataTitle = "";
  $dataText = "";
  $system = $source["_index"];

  if($system == "dseid"){
    $dataTitle = strip_tags($source["_source"]["dseid"]["page"]["title"]);
    $dataText = $source["_source"]["dseid"]["page"]["content"];
    $dataText =str_replace('&nbsp;',' ',$dataText);
  }

  return $dataTitle."|$|".$dataText;
}



$dataResult = '';
$strRecIDTitle = (isset($_GET['id']) ? trim(base64_decode($_GET['id'])) : '');
$strRecIDTitle = explode('|$|', $strRecIDTitle); // id e titulo do resultado SEI
$numRecID = $strRecIDTitle[0];
$strRecTitle = $strRecIDTitle[1];

$strURLElasticSEI = 'http://10.0.20.53:9200/_all/_search?pretty&source={"from":0,"size":1,"query":{"bool":{"should":[{"match_phrase":{"dseid.page.title":"'.$strRecTitle.'"}},{"match_phrase":{"dseid.page.content":"'.$strRecTitle.'"}}]}}}​';
$strURLElasticSEI = str_replace(" ","%20",$strURLElasticSEI);

if ($ch = curl_init()) {
  curl_setopt($ch, CURLOPT_URL,$strURLElasticSEI);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $dataResult = curl_exec($ch);
  curl_close($ch);

  if ($dataResult != "") {
    $arrTitleContent = getResultUSEI($dataResult);
    $arrTitleContent = explode('|$|', $arrTitleContent);
    echo $arrTitleContent[1];
  }
}
?>