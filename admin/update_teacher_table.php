<?php
	#对应学院

	$sql = "SELECT * FROM `teachers`;";
	$teacher_result = mysql_query($sql);
	if(!$teacher_result){
		die("SQL ERROR : ".mysql_error());
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
		if(isset($xi2xueyuan[$teacher_row['xi']])){
			$sql = "UPDATE `teachers` SET `xueyuan` = '".$xi2xueyuan[$teacher_row['xi']]."' WHERE `teacher_id` = '".$teacher_row['teacher_id']."';";
			//$sql = "UPDATE `lilun` SET `teacher_xueyuan` = '".$xi2xueyuan[$teacher_row['xi']]."' WHERE `id` = '".$teacher_row['id']."';";
			$result_update = mysql_query($sql);
			if(!$result_update){
				die(mysql_error());
			}
		}else{
			$sql = "UPDATE `teachers` SET `xueyuan` = '".$teacher_row['xi']."' WHERE `teacher_id` = '".$teacher_row['teacher_id']."';";
			//$sql = "UPDATE `lilun` SET `teacher_xueyuan` = '".$teacher_row['xi']."' WHERE `id` = '".$teacher_row['id']."';";
			$result_update = mysql_query($sql);
			if(!$result_update){
				die(mysql_error());
			}
		}
	}
	//echo "update successfuly!";
?>