<?php
	include "./../config.php";
	//mysql_real_escape_string
	$xy = mysql_real_escape_string($_POST['xy']);
	$xi = mysql_real_escape_string($_POST['xi']);

	#判断学期

	if(date("m")>8){
		$guessdata = date("Y2");	
	}else{
		$guessdata = date("Y1");
	}

	$sql = "SELECT * FROM `teachers` WHERE `xueyuan`=\"".$xy."\" AND `xi`=\"".$xi."\";";
	$result = mysql_query($sql);
	if(!$result) {
		die("SQL ERROR : ".mysql_error());
	}


	$response = "您查找的是：<b>".$xy."</b> 的 <b>".$xi."</b><br>";
	$response .= "<table class=\"table table-striped\">";
	$response .= "<tr><th>教师号</th><th>姓名</th><th>操作</th></tr>";

	while($row = mysql_fetch_assoc($result)){
		$response .= '<tr><td>'.$row["teacher_id"].'</td><td>'.$row["teacher_name"].'</td><td><a href="./modify_lilun_per.php?teacher_id='.$row['teacher_id'].'&xueqi='.$guessdata.'" target="_blank">修改</a></td></tr>';
	}
	$response .="</table>";
	echo $response;
?>
