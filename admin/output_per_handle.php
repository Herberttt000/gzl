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
		header("location: ./error.php?txt="."请输入年份.");
		exit();
	}
	//echo $_POST['year'];

    $sql = "SELECT * FROM `lilun` WHERE `xueqi` LIKE \"".$_POST['year']."_\"";
	//echo $sql;
	$result_ll = mysql_query($sql);
	if(!$result_ll) {
		die("SQL ERROR : " . mysql_error());
	}
	$num_ll = mysql_num_rows($result_ll);
	if($num_ll==0) {
		header("location: ./error.php?txt="."没有“".$_POST['year']."”年的记录.");
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
$objPHPExcel->getActiveSheet()->setCellValue('C1', '课程号');
$objPHPExcel->getActiveSheet()->setCellValue('D1', '课程名');
$objPHPExcel->getActiveSheet()->setCellValue('E1', '序号');
$objPHPExcel->getActiveSheet()->setCellValue('F1', '开课学院');
$objPHPExcel->getActiveSheet()->setCellValue('G1', '教师所在院系');
$objPHPExcel->getActiveSheet()->setCellValue('H1', '教师号');
$objPHPExcel->getActiveSheet()->setCellValue('I1', '姓名');
$objPHPExcel->getActiveSheet()->setCellValue('J1', '职称');
$objPHPExcel->getActiveSheet()->setCellValue('K1', '合班');
$objPHPExcel->getActiveSheet()->setCellValue('L1', '学生专业');
$objPHPExcel->getActiveSheet()->setCellValue('M1', '是否3表');
$objPHPExcel->getActiveSheet()->setCellValue('N1', '学时');
$objPHPExcel->getActiveSheet()->setCellValue('O1', '人数');
$objPHPExcel->getActiveSheet()->setCellValue('P1', '人数系数');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', '重复课系数');
$objPHPExcel->getActiveSheet()->setCellValue('R1', '专业课系数');
$objPHPExcel->getActiveSheet()->setCellValue('S1', '三表系数');
$objPHPExcel->getActiveSheet()->setCellValue('T1', '质量系数');
$objPHPExcel->getActiveSheet()->setCellValue('U1', '难度系数');
$objPHPExcel->getActiveSheet()->setCellValue('V1', '职称系数');
$objPHPExcel->getActiveSheet()->setCellValue('W1', '计算过程');
$objPHPExcel->getActiveSheet()->setCellValue('X1', '教分');






$num = 2;
while ( $row = mysql_fetch_assoc($result_ll)) {
	
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$num, (int)$row['id']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$num, (int)$row['xueqi']);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$num, (string)$row['course_id'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$num, (string)$row['course_name'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$num, (int)$row['course_index']);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$num, (string)$row['course_yuanxi'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$num, (string)$row['teacher_yuanxi'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$num, (string)$row['teacher_id'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$num, (string)$row['teacher_name'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('J'.$num, (string)$row['teacher_zc'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$num, (string)$row['heban'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$num, (string)$row['zhuanye'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$num, (int)$row['issb']);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$num, (int)$row['xueshi']);
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$num, (int)$row['num_of_p']);
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$num, (float)$row['xishu_people']);
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$num, (float)$row['xishu_cfk']);
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$num, (float)$row['xishu_zyk']);
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$num, (float)$row['xishu_sb']);
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$num, (float)$row['xishu_zl']);
		$objPHPExcel->getActiveSheet()->setCellValue('U'.$num, (float)$row['xishu_nd']);
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$num, (float)$row['xishu_zc']);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('W'.$num, (string)$row['guocheng'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValue('X'.$num, (float)$row['jiaofen']);
	
	$num++;
}

$filename = "excel".$year.".xls";
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