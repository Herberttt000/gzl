<?php
	#对应学院
include "./../config.php";
	set_time_limit(0);

	$sql = "SELECT * FROM `lilun`;";
	$teacher_result = mysql_query($sql);
	if(!$teacher_result){
		die("SQL ERROR : ".mysql_error());
	}

	if(!isset($_GET['xueqi'])) {
		echo "<h2>需要一个学期参数（xueqi）来确认更新</h2>";
		exit();
	}
	$sql = "SELECT * FROM `xue2xi`;";
	$result = mysql_query($sql);
	if(!$result){
		die(mysql_error());
	}

	$xi2xueyuan = array();
	while ($row = mysql_fetch_assoc($result)) {
		# code...
		$xi2xueyuan[$row['xi']] = $row['xueyuan'];
	}

	while($teacher_row = mysql_fetch_assoc($teacher_result)){
		if(isset($xi2xueyuan[$teacher_row['teacher_yuanxi']])){
			// $sql = "UPDATE `teachers` SET `xueyuan` = '".$xi2xueyuan[$teacher_row['xi']]."' WHERE `teacher_id` = '".$teacher_row['teacher_id']."';";
			$sql = "UPDATE `lilun` SET `teacher_xueyuan` = '".$xi2xueyuan[$teacher_row['teacher_yuanxi']]."' WHERE `id` = '".$teacher_row['id']."';";
			$result_update = mysql_query($sql);
			if(!$result_update){
				die(mysql_error());
			}
		}else{
			// $sql = "UPDATE `teachers` SET `xueyuan` = '".$teacher_row['xi']."' WHERE `teacher_id` = '".$teacher_row['teacher_id']."' AND `xueqi` = '".$_GET['xueqi']."';";
			$sql = "UPDATE `lilun` SET `teacher_xueyuan` = '".$teacher_row['teacher_yuanxi']."' WHERE `id` = '".$teacher_row['id']."';";
			$result_update = mysql_query($sql);
			if(!$result_update){
				die(mysql_error());
			}
		}
	}
	echo "update successfuly!";
?>