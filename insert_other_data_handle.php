<?php

/*
* 其他工作量提交处理
*/

include 'config.php';

if(isset($_POST['handle'])){
	$handle = $_POST['handle'];
}else{
	header("location: ./error.php?txt="."错误的操作！");
	exit();
}

if($handle==1){//添加
	$year = $_POST['xq'];
	$teacher_id = $_POST['id'];
	$teacher_name = $_POST['name'];
	$disc = $_POST['sm'];
	$jf = $_POST['jf'];

	$sql = "insert into `other_gzl`(teacher_id,teacher_name,jf,disc,year) value(\"".$teacher_id."\",\"".$teacher_name."\",\"".$jf."\",\"".$disc."\",\"".$year."\");";
	$result = mysql_query($sql);
	if(!$result) {
			header("location: ./error.php?txt="."插入到数据库失败，错误信息：".mysql_error());
			exit();
	}else{
		header("location: ./insert_other_data.php");
	}
}
if($handle==2){
	$sql = "delete from `other_gzl` where `id` = ".$_POST['id'].";";
	$result = mysql_query($sql);
		if(!$result) {
			header("location: ./error.php?txt="."删除失败，错误信息：".mysql_error());
			exit();
	}else{
		header("location: ./insert_other_data.php");
	}

}


?>