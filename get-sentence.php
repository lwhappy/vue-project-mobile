<?php
	//include("FileUtil.class.php");
	/*$query = array(
		"good","well","mail"
	);*/

	$words = $_REQUEST['words'];
	$num = $_REQUEST['num'];
	$query = json_decode($words);

	$sentences = array();
	foreach ($query as $value) {
	  $path = "files/".$value.".txt";
	  $content = file_get_contents($path);
	  //$content='{"a":1,"b":2}';
	  $content=json_decode($content);
	  //$returnData = json_encode($returnData);
	  $sentence = 'null';//定义成null和空字符串时json_encode会有问题，导致前端拿不到返回数据，浏览器卡死
	  if(isset($content) && isset($content->sentence)){
	  	$len = count($content->sentence);
	  	if($num <= $len){
	  		$index = $num - 1 ;
	  		//$index = rand(0,$len-1);
	  		if(isset($content->sentence[$index])){
	  			$sentence = $content->sentence[$index];
	  			
	  		}
	  	}
	  	
	  }
	  //print_r( $sentence);
	  //if($sentence != null){
	  	array_push($sentences,$sentence);
	  //}
	  
	  
	}
	//print_r($sentences);
	echo json_encode($sentences);
	
	//echo gettype($content);
?>