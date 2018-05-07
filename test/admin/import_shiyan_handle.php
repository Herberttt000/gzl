<?php
	/* 
	*	upload file hanlde 
	*/
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';
	set_time_limit(0);
	
    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['import_shiyan']) {
        header("location: ./error.php?txt="."您没有导入实验信息的权限.");
        exit();
    }

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
    for($currentRow = 2;$currentRow<=$allRow;$currentRow++){ 
    	$output[$currentRow]['xueqi'] = (int)$currentSheet->getCell('A'.$currentRow)->getValue();
    	$output[$currentRow]['teacher_id'] = str_replace(' ','',(String)$currentSheet->getCell('B'.$currentRow)->getValue()); 
    	$output[$currentRow]['teacher_name'] = str_replace(' ','',(String)$currentSheet->getCell('C'.$currentRow)->getValue());  
    	$output[$currentRow]['yuanshi'] = (float)$currentSheet->getCell('D'.$currentRow)->getValue();
    	$output[$currentRow]['zhehe'] = (float)$currentSheet->getCell('E'.$currentRow)->getValue();
    	$output[$currentRow]['jintie'] = (float)$currentSheet->getCell('F'.$currentRow)->getValue();

    }

    
    $sql = "SELECT * from `teachers`;";
    $result = mysql_query($sql);
    if(!$result) {
        die("SQL ERROR : " . mysql_error());
    }

    $teacher2id = array();
    $id2teacher = array();
    while ( $row = mysql_fetch_assoc($result)) {
        $teacher2id[$row['xueqi']][$row['teacher_name']] = $row['teacher_id'];
        $id2teacher[$row['xueqi']][$row['teacher_id']] = $row['teacher_name'];
    }
    //header("Content-type:text/html;charset=utf-8");
    $error = "";
    echo "<br />===============================================<br />";
    echo "下面错误信息<br />若出现错误信息，根据提示认真检查您的Excel表格是否错误,并重新上传Excel<br />必要时请联系相关工作人员";

    echo "<br />===============================================<br />";
    $haserror = 0;
    for($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
        $xueqi_row = $output[$currentRow]['xueqi'];
        $teacherid = $output[$currentRow]['teacher_id'];
        $teachername = $output[$currentRow]['teacher_name'];
        $error = "";
        if(!isset($id2teacher[$xueqi_row][$teacherid])) {
            $error = "不存在教师号为“".$teacherid."”的教师.<br />";
            echo $error;
            flush();
            $haserror = 1;
        }
        if(!isset($teacher2id[$xueqi_row][$teachername])) {
            $error = "不存在姓名为“".$teachername."”的教师.<br />";
            echo $error;
            flush();
            $haserror = 1; 

        }
        if($teachername != $id2teacher[$xueqi_row][$teacherid]) {
          $error = "";
            if(isset($id2teacher[$xueqi_row][$teacherid])) $error .= "教师号为“".$teacher_id."”的教师,姓名应为“".$id2teacher[$xueqi_row][$teacherid]."”.<br />";
            if(isset($teacher2id[$xueqi_row][$teachername])) $error .= "姓名为“".$teachername."”的教师，教师号应为“".$teacher2id[$xueqi_row][$teachername]."”.<br />";
            echo $error;
            flush();
            $haserror = 1;
        }
    }
    
    if($haserror) {
        exit();
    }
	// #判断实践临时里是否已经有该教师信息
 //    $isset_in_temp = array();
 //    for($row = 2; $row <= $allRow; $row++) {
 //    	$sql = "select count(*) from `shiyan` where `teacher_id`=\"".$output[$row]['teacher_id']."\"
 //        and `xueqi`=".$output[2]['xueqi'].";";
 //    	$result = mysql_query($sql);
 //    	if(!$result) {
 //    		die("sql error: " . mysql_error());
 //    	}
 //    	$cunzai = mysql_fetch_row($result);
 //    	if($cunzai[0]==0) {
 //    		$isset_in_temp[$output[$row]['teacher_id']] = 0;
 //    	} else {
 //    		$isset_in_temp[$output[$row]['teacher_id']] = 1;
 //    	}
 //    }


    for($row = 2; $row <= $allRow; $row++) {
 
        $sql = "INSERT INTO `shiyan` (xueqi, teacher_id,
                 teacher_name,yuanshi,zhehe,jintie
                 ) VALUES (\"".
                 $output[$row]['xueqi']."\",\"".
                 $output[$row]['teacher_id']."\",\"".
                 $output[$row]['teacher_name']."\",\"".
                 $output[$row]['yuanshi']."\",\"".
                 $output[$row]['zhehe']."\",\"".
                 $output[$row]['jintie']."\"
                 );";

        $result = mysql_query($sql);
        if(!$result) {
           die("insert error:" . mysql_error());
        }
    }


echo "导入成功";
exit();



?>