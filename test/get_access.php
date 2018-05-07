<?php
/*
* GET: $userId 
*/

include "./config.php";
include "./functions.php";

$teacher_id = $_GET['userId'];
$_SESSION['teacher_id'] = $teacher_id;

function get_token($teacher_id){
	$str = $teacher_id . date("d");
	return md5($str);
}

$_SESSION['token'] = get_token($teacher_id);

$guessdata = 20141;
if(date("m")>8){
	$guessdata = date("Y2");
}else{
	$guessdata = date("Y1");
}

if (isset($_GET['xueqi']) && $_GET['xueqi'] != "") {
	$guessdata = $_GET['xueqi'];
}

$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];


header("location: ./get_info_pro.php?xueqi=".$guessdata."");


?>