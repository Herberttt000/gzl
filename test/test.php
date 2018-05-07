<?php
	// include './config.php';

	// $sql = "select count(*) from `shijian_temp` where `teacher_xueyuan`=\"计算机科学与技术学院\"";
	// $result = mysql_query($sql);
	// if(!$result) {
	// 	die("sql error: " . mysql_error());
	// }
	// //print_r(mysql_fetch_row($result));

	// 	$sql = "SELECT SUM(jiaofen),SUM(xueshi) FROM `lilun` WHERE `teacher_id`='0401361' AND xueqi='20141';";
	// 	$result = mysql_query($sql);
	// 	if(!$result) {
	// 		die("SQR ERROR : ". mysql_error());
	// 	}
	// 	$lilun_jiaofen = mysql_fetch_assoc($result);

	// 	//print_r($lilun_jiaofen);
	// 	//$lilun_jiaofen = $lilun_jiaofen['jiaofen'];
	// 	echo round($lilun_jiaofen['SUM(jiaofen)'],4);
	// 	echo round($lilun_jiaofen['SUM(xueshi)'],4);

	// add
	$str = '软件14-C2班';
	$str = '测通13-C1班';
	$str = '测通13-B1班';
	if (preg_match("/([^0-9]){1,8}\d\d-([a-zA-Z])\d班/", $str, $matchs) == 1) {
		var_dump($matchs);
	}else{
		echo "FUCK";
	}


?>
