<?php

	/*
	 * 学院导出汇总表
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
	if(!$_SESSION['find_gzl']) {
		header("location: ./error.php?txt="."您没有导出的权限.");
		exit();
	}
	# 处理学院
	$user_xueyuan = $_SESSION['xueyuan'];
	if($user_xueyuan == "无") {
		header("location: ./error.php?txt="."错误的操作,你的学院为'无'.");
		exit();
	} else if ($user_xueyuan == "全校") {
		$find_xueyuan = "1";
	} else {
		$find_xueyuan = "`xueyuan` = '".$user_xueyuan."'";
	}



	# 处理学期
	if(!isset($_GET['xueqi'])) {
		header("location: ./error.php?txt="."请输入需要导出的年份.");
		exit();
	}
	
	$get_xueqi   = mysql_real_escape_string($_GET['xueqi']);
	if (strlen($get_xueqi) == 10 || strlen($get_xueqi) == 5) {
		}else{echo("请检查你的输入格式是否正确.");
		exit();
	}
	$begin_xueqi = "";
	$end_xueqi   = $find_method = "";

	if(strlen($get_xueqi) > 5) { #区间查询
		$begin_xueqi = substr($get_xueqi, 0, 5);
		$end_xueqi   = substr($get_xueqi, 5, 5);
		$find_method = "`xueqi` 
						BETWEEN ".$begin_xueqi." 
						AND ".$end_xueqi." ";

	}else{ #单学期查询
		$find_method = "`xueqi` 
						BETWEEN ".$get_xueqi." 
						AND ".$get_xueqi." ";
	}


	# 数据准备


	#获取教师列表
	$sql = "SELECT * FROM `teachers` where ".$find_xueyuan.";";
	$teachers_result = mysql_query($sql);
	if(!$teachers_result) {
		die("SQL ERROR : ". mysql_error());
	}

	# Excel 的预处理

	// Excel开始
	// 准备EXCEL的包括文件
	// Error reporting 
	//error_reporting(0);
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
	$objPHPExcel->getActiveSheet()->setCellValue('A1', '学期');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', '教师号');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', '职称');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', '是否硕导');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', '学院');
	$objPHPExcel->getActiveSheet()->setCellValue('G1', '系');
	$objPHPExcel->getActiveSheet()->setCellValue('H1', '研究生原始');
	$objPHPExcel->getActiveSheet()->setCellValue('I1', '研究生折合');
	$objPHPExcel->getActiveSheet()->setCellValue('J1', '研究生指导');
	$objPHPExcel->getActiveSheet()->setCellValue('K1', '研究生目标');
	$objPHPExcel->getActiveSheet()->setCellValue('L1', '研究生指导竞赛');
	$objPHPExcel->getActiveSheet()->setCellValue('M1', '成人原始');
	$objPHPExcel->getActiveSheet()->setCellValue('N1', '成人折合');
	$objPHPExcel->getActiveSheet()->setCellValue('O1', '成人实践折合');
	$objPHPExcel->getActiveSheet()->setCellValue('P1', '本科理论原始');
	$objPHPExcel->getActiveSheet()->setCellValue('Q1', '本科理论折合');
	$objPHPExcel->getActiveSheet()->setCellValue('R1', '本科实践原始');
	$objPHPExcel->getActiveSheet()->setCellValue('S1', '本科实践折合');
	$objPHPExcel->getActiveSheet()->setCellValue('T1', '竞赛');
	$objPHPExcel->getActiveSheet()->setCellValue('U1', '教务津贴');
	$objPHPExcel->getActiveSheet()->setCellValue('V1', '其他');
	$objPHPExcel->getActiveSheet()->setCellValue('W1', '实验原始');
	$objPHPExcel->getActiveSheet()->setCellValue('X1', '实验折合');
	$objPHPExcel->getActiveSheet()->setCellValue('Y1', '实验津贴');
	$objPHPExcel->getActiveSheet()->setCellValue('Z1', '欠监考次数');
	$objPHPExcel->getActiveSheet()->setCellValue('AA1', '总计');


	$num = 2;

	# 遍历教师列表 写入Excel
	while($teacher_row = mysql_fetch_assoc($teachers_result)) {
		$sql = "select 
				sum(yjs_yuanshi),
				sum(yjs_zhehe),
				sum(yjs_zhidao),
				sum(yjs_mubiao),
				sum(yjs_zhidaojingsai),
				sum(cr_yuanshi),
				sum(cr_zhehe),
				sum(cr_shijianzhehe),
				sum(bk_lilunyuanshi),
				sum(bk_lilunzhehe),
				sum(bk_shijianyuanshi),
				sum(bk_shijianzhehe),
				sum(jingsai),
				sum(jiaowu),
				sum(qita),
				sum(sy_yuanshi),
				sum(sy_zhehe),
				sum(sy_jintie),
				sum(qiankao),
				sum(zhongji)
				from `huizong` where `teacher_id`='".$teacher_row['teacher_id']."' and ".$find_method.";";
		$result_sum = mysql_query($sql);
		if(!$result_sum) {
			die("SQL ERROR : ".mysql_error());
		}
		$sum_list = mysql_fetch_assoc($result_sum);
		# 写入Excel类
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$num, (int)$get_xueqi);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$num, (string)$teacher_row['teacher_id'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$num, (string)$teacher_row['teacher_name'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$num, (string)$teacher_row['zhicheng'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$num, (string)$teacher_row['issd'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$num, (string)$teacher_row['xueyuan'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$num, (string)$teacher_row['xi'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$num, (float)$sum_list['sum(yjs_yuanshi)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$num, (float)$sum_list['sum(yjs_zhehe)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('J'.$num, (float)$sum_list['sum(yjs_zhidao)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$num, (float)$sum_list['sum(yjs_mubiao)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$num, (float)$sum_list['sum(yjs_zhidaojingsai)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('M'.$num, (float)$sum_list['sum(cr_yuanshi)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('N'.$num, (float)$sum_list['sum(cr_zhehe)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('O'.$num, (float)$sum_list['sum(cr_shijianzhehe)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('P'.$num, (float)$sum_list['sum(bk_lilunyuanshi)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Q'.$num, (float)$sum_list['sum(bk_lilunzhehe)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('R'.$num, (float)$sum_list['sum(bk_shijianyuanshi)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('S'.$num, (float)$sum_list['sum(bk_shijianzhehe)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('T'.$num, (float)$sum_list['sum(jingsai)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('U'.$num, (float)$sum_list['sum(jiaowu)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('V'.$num, (float)$sum_list['sum(qita)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('W'.$num, (float)$sum_list['sum(sy_yuanshi)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('X'.$num, (float)$sum_list['sum(sy_zhehe)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Y'.$num, (float)$sum_list['sum(sy_jintie)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('X'.$num, (float)$sum_list['sum(qiankao)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AA'.$num, (float)$sum_list['sum(zhongji)'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
	
		# 累加行数
		$num++;
}
	# 输出excel

	$filename = "huizong_".$get_xueqi."_[".$user_xueyuan."].xls";
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
