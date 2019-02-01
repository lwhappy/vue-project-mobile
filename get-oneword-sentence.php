<?php
	//include("FileUtil.class.php");
	/*$query = array(
		"good","well","mail"
	);*/

	$value = $_REQUEST['word'];

	$sentences = array();
	  $path = "files/".$value.".txt";
	  $content = file_get_contents($path);
	  //$content='{"a":1,"b":2}';
	  $content=json_decode($content);
	  //$returnData = json_encode($returnData);
	  $sentence = 'null';//定义成null和空字符串时json_encode会有问题，导致前端拿不到返回数据，浏览器卡死
	  if(isset($content) && isset($content->sentence)){
	  	$sentences = $content->sentence;
	  	
	  }
	
	echo json_encode($sentences);
	
?>