<?php

	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';
	set_time_limit(0);

	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['export_geren']) {
		header("location: ./error.php?txt="."您没有导出个人工作量表的权限.");
		exit();
	}

	if(!isset($_POST['year'])) {
		header("location: ./error.php?txt="."请输入需要导出的学期.");
		exit();
	}
	//echo $_POST['year'];
	$sql = "SELECT * FROM `shijian` WHERE `xueqi` LIKE \"".$_POST['year']."\"";
	//echo $sql;
	$result_sj = mysql_query($sql);
	if(!$result_sj) {
		die("SQL ERROR : " . mysql_error());
	}
	$num_sj = mysql_num_rows($result_sj);
	if($num_sj==0) {
		header("location: ./error.php?txt="."没有“".$_POST['year']."”学期的记录.");
		exit();	
	}
// Excel开始
// 准备EXCEL的包括文件
// Error reporting 
error_reporting(0);
// PHPExcel 
require_once dirname(__FILE__) . './../include/Classes/PHPExcel.php';
// 生成新的excel对象
$objPHPExcel = new PHPExcel();
// 设置excel文档的属性
$objPHPExcel->getProperties()->setCreator("Sam.c")->setLastModifiedBy("Sam.c Test")->setTitle("Microsoft Office Excel Document")->setSubject("Test")->setDescription("Test")->setKeywords("Test")->setCategory("Test result file");
// 开始操作excel表
// 操作第一个工作表
$objPHPExcel->setActiveSheetIndex(0);
// 设置工作薄名称
$objPHPExcel->getActiveSheet()->setTitle(iconv('gbk', 'utf-8', 'daocuo'));
// 设置默认字体和大小
$objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', '宋体'));
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
$m_exportType = "excel";
$objPHPExcel->getActiveSheet()->setCellValue('A1', '编号');
$objPHPExcel->getActiveSheet()->setCellValue('B1', '学期');
$objPHPExcel->getActiveSheet()->setCellValue('C1', '教师号');
$objPHPExcel->getActiveSheet()->setCellValue('D1', '姓名');
$objPHPExcel->getActiveSheet()->setCellValue('E1', '职称');
$objPHPExcel->getActiveSheet()->setCellValue('F1', '实践名称');
$objPHPExcel->getActiveSheet()->setCellValue('G1', '序号');
$objPHPExcel->getActiveSheet()->setCellValue('H1', '课程号');
$objPHPExcel->getActiveSheet()->setCellValue('I1', '实践类型');
$objPHPExcel->getActiveSheet()->setCellValue('J1', '职称系数');
$objPHPExcel->getActiveSheet()->setCellValue('K1', '周数');
$objPHPExcel->getActiveSheet()->setCellValue('L1', '人数');
$objPHPExcel->getActiveSheet()->setCellValue('M1', '班级');
$objPHPExcel->getActiveSheet()->setCellValue('N1', '地点');
$objPHPExcel->getActiveSheet()->setCellValue('O1', '计算过程');
$objPHPExcel->getActiveSheet()->setCellValue('P1', '折合教分');





$num = 2;
while ( $row = mysql_fetch_assoc($result_sj)) {
	
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$num, (int)$row['id']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$num, (int)$row['xueqi']);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$num, (string)$row['teacher_id'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$num, (string)$row['teacher_name'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$num, (string)$row['teacher_zc'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$num, (string)$row['shijian_name'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$num, (int)$row['course_index']);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$num, (string)$row['course_id'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$num, (string)$row['shijian_type'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('J'.$num, (float)$row['zhichengxishu'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$num, (float)$row['zhoushu'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$num, (int)$row['num_of_p'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$num, (string)$row['banji'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$num, (string)$row['didian'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$num, (string)$row['guocheng'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$num, (float)$row['jiaofen']);
	$num++;
}

$filename = "excel".date("Y_m_d",time())."_".(time()%100000).".xls";
// 如果需要输出EXCEL格式
if($m_exportType=="excel"){
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	// 从浏览器直接输出$filename
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	// header("Content-Type:application/force-download");
	header("Content-Type: application/vnd.ms-excel;");
	// header("Content-Type:application/octet-stream");
	// header("Content-Type:application/download");
	header("Content-Disposition:attachment;filename=".$filename);
	header("Content-Transfer-Encoding:binary");
	$objWriter->save("php://output"); 
}
// 如果需要输出PDF格式
if($m_exportType=="pdf"){
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
	$objWriter->setSheetIndex(0);
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	header("Content-Type:application/force-download");
	header("Content-Type: application/pdf");
	header("Content-Type:application/octet-stream");
	header("Content-Type:application/download");
	header("Content-Disposition:attachment;filename=".$m_strOutputPdfFileName);
	header("Content-Transfer-Encoding:binary");
	$objWriter->save("php://output"); 
}



?>
