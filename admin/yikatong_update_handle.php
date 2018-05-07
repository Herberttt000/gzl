<?php
	/* 
	*	upload file hanlde 
	*/
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';
	set_time_limit(0);

include "./header.php";


	if(!isset($_FILES["file"]["name"])) {
		header("location: ./error.php?txt="."你没有上传文件.");
		exit();
	}
	if (($_FILES["file"]["type"] == "application/vnd.ms-excel")
		&& ($_FILES["file"]["size"] < 2000000))
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Error: " . $_FILES["file"]["error"] . "<br />";
		}
		else
		{
			echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			echo "Type: " . $_FILES["file"]["type"] . "<br />";
			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			echo "Stored in: " . $_FILES["file"]["tmp_name"];

		}
	}
	else
	{
		echo "Invalid file";
		exit();
	}

	/*
	* 读取Excel
	*/
	require_once './../include/Classes/PHPExcel/IOFactory.php';
	
	$filePath = $_FILES["file"]["tmp_name"]; 
	
    $fileType = PHPExcel_IOFactory::identify($filePath); //文件名自动判断文件类型
    $objReader = PHPExcel_IOFactory::createReader($fileType);
    $objPHPExcel = $objReader->load($filePath);
    
    $currentSheet = $objPHPExcel->getSheet(0); //第一个工作簿
    $allRow = $currentSheet->getHighestRow(); //行数
    $output = array();
    $preType = '';
    
    //$qh = $currentSheet->getCell('A4')->getValue();
    //按照文件格式从第7行开始循环读取数据
    echo "<br />".$allRow;
    for($currentRow = 2;$currentRow<=$allRow;$currentRow++){ 
        $output[$currentRow]['bianhao'] = (int)$currentSheet->getCell('A'.$currentRow)->getValue();
        $output[$currentRow]['name'] = str_replace(' ','',(String)$currentSheet->getCell('B'.$currentRow)->getValue());  
    	$output[$currentRow]['idcard'] = str_replace(' ','',(String)$currentSheet->getCell('C'.$currentRow)->getValue()); 
        $output[$currentRow]['yikatong'] = str_replace(' ','',(String)$currentSheet->getCell('D'.$currentRow)->getValue());
        $output[$currentRow]['gzzh'] = str_replace(' ','',(String)$currentSheet->getCell('F'.$currentRow)->getValue());
        $output[$currentRow]['xy'] = str_replace(' ','',(String)$currentSheet->getCell('E'.$currentRow)->getValue());

    }

    $allnums = 0;
    $updated = array();
    for($row = 2; $row < $allRow; $row++) {
        $bianhao = $output[$row]['bianhao'];
        $idcard = $output[$row]['idcard'];
        $yikatong = $output[$row]['yikatong'];
        $gzzh = $output[$row]['gzzh'];
        $sql = "UPDATE `teachers` SET `yikatong`='".$yikatong."', `gz_id`='".$gzzh."' WHERE `idcard`='".$idcard."';";
        $result_update = mysql_query($sql);
        if(!$result_update) {
            die("UPDATE FAILD : ".mysql_error());
        }
        $affected_rows = mysql_affected_rows();
        if($affected_rows==0) {
            //echo "<br />".$bianhao;
            continue;
        }
        $updated[$row] = 1;
        $allnums ++;
    }

echo "第一次匹配完成<br />";
echo "共更新 ".$allnums." 教师的信息.<br />";
$allnums = 0;
    for($row = 2; $row < $allRow; $row++) {
        if($updated) continue;
        $bianhao = $output[$row]['bianhao'];
        $idcard = $output[$row]['idcard'];
        $yikatong = $output[$row]['yikatong'];
        $gzzh = $output[$row]['gzzh'];
        $name = $output[$row]['name'];
        $xy = $output[$row]['xy'];
        $sql = "UPDATE `teachers` SET `yikatong`='".$yikatong."', `gz_id`='".$gzzh."' WHERE `teacher_name`='".$name."' AND `xueyuan`='".$xy."';";
        $result_update = mysql_query($sql);
        if(!$result_update) {
            die("UPDATE FAILD : ".mysql_error());
        }
        $affected_rows = mysql_affected_rows();
        if($affected_rows==0) {
            //echo "<br />".$bianhao;
            continue;
        }
        $allnums ++;
    }

echo "第二次匹配完成<br />";
echo "共更新 ".$allnums." 教师的信息.";



?>