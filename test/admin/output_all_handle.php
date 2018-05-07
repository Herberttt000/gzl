<?php

	/*导出人事处表格
	 * 按年导出
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
	if(!$_SESSION['export_renshichu']) {
		header("location: ./error.php?txt="."您没有导出人事处工作量表的权限.");
		exit();
	}

	if(!isset($_POST['year'])) {
		header("location: ./error.php?txt="."请输入需要导出的年份.");
		exit();
	}

	$year = $_POST['year'];
	//echo $year;
	//exit();
	# 数据准备


	#获取教师列表
	$sql = "SELECT * FROM `teachers`;";
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
	$objPHPExcel->getActiveSheet()->setCellValue('E1', '教师分类');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', '是否硕导');
	$objPHPExcel->getActiveSheet()->setCellValue('G1', '是否双肩挑');
	$objPHPExcel->getActiveSheet()->setCellValue('H1', '学院');
	$objPHPExcel->getActiveSheet()->setCellValue('I1', '系');
	$objPHPExcel->getActiveSheet()->setCellValue('J1', '身份证号');
	$objPHPExcel->getActiveSheet()->setCellValue('K1', '一卡通号');
	$objPHPExcel->getActiveSheet()->setCellValue('L1', '工资账号');
	$objPHPExcel->getActiveSheet()->setCellValue('M1', '研究生原始');
	$objPHPExcel->getActiveSheet()->setCellValue('N1', '研究生折合');
	$objPHPExcel->getActiveSheet()->setCellValue('O1', '研究生指导');
	$objPHPExcel->getActiveSheet()->setCellValue('P1', '研究生目标');
	$objPHPExcel->getActiveSheet()->setCellValue('Q1', '研究生指导竞赛');
	$objPHPExcel->getActiveSheet()->setCellValue('R1', '成人原始');
	$objPHPExcel->getActiveSheet()->setCellValue('S1', '成人折合');
	$objPHPExcel->getActiveSheet()->setCellValue('T1', '成人实践折合');
	$objPHPExcel->getActiveSheet()->setCellValue('U1', '本科理论原始');
	$objPHPExcel->getActiveSheet()->setCellValue('V1', '本科理论折合');
	$objPHPExcel->getActiveSheet()->setCellValue('W1', '本科实践原始');
	$objPHPExcel->getActiveSheet()->setCellValue('X1', '本科实践折合');
	$objPHPExcel->getActiveSheet()->setCellValue('Y1', '竞赛');
	$objPHPExcel->getActiveSheet()->setCellValue('Z1', '教务津贴');
	$objPHPExcel->getActiveSheet()->setCellValue('AA1', '其他');
	$objPHPExcel->getActiveSheet()->setCellValue('AB1', '实验原始');
	$objPHPExcel->getActiveSheet()->setCellValue('AC1', '实验折合');
	$objPHPExcel->getActiveSheet()->setCellValue('AD1', '实验津贴');
	$objPHPExcel->getActiveSheet()->setCellValue('AE1', '欠监考次数');
	$objPHPExcel->getActiveSheet()->setCellValue('AF1', '总计');


	$num = 2;

	# 遍历教师列表 写入Excel
	while($teacher_row = mysql_fetch_assoc($teachers_result)) {

		# 简化变量名
		$t_id = $teacher_row['teacher_id'];
		$t_name = $teacher_row['teacher_name'];
		$t_xueyuan = $teacher_row['xueyuan'];
		$t_xi = $teacher_row['xi'];
		$t_idcard = $teacher_row['idcard'];
		$t_ykt = $teacher_row['yikatong'];
		$t_zc = $teacher_row['zhicheng'];
		$t_cat = $teacher_row['teacher_cat'];
		$t_sd = $teacher_row['issd'];
		$t_sjt = $teacher_row['issjt'];
		$t_gzid = $teacher_row['gz_id'];



		# 提取理论表里的工作量
		$sql = "SELECT SUM(jiaofen),SUM(xueshi) FROM `lilun` WHERE `teacher_id`='".$t_id."' AND `xueqi`='".$year."';";
		//echo $sql;
		$result = mysql_query($sql);
		if(!$result) {
			die("SQR ERROR : ". mysql_error());
		}
		$lilun_jiaofen = mysql_fetch_assoc($result);

		$lilun_zhehe = round($lilun_jiaofen['SUM(jiaofen)'],4);
		$lilun_yuanshi = round($lilun_jiaofen['SUM(xueshi)'],4);

		# 实践课的工作量
		$sql = "SELECT SUM(jiaofen),SUM(zhoushu) FROM `shijian` WHERE `teacher_id`='".$t_id."' AND `xueqi`='".$year."';";
		//echo $sql;
		$result = mysql_query($sql);
		if(!$result) {
			die("SQL ERROR : ". mysql_error());
		}
		$shijian_jiaofen = mysql_fetch_assoc($result);
		
		$shijian_yuanshi = round($shijian_jiaofen['SUM(zhoushu)'],4);
		$shijian_zhehe = round($shijian_jiaofen['SUM(jiaofen)'],4);

		#实验
		$result_shiyan = mysql_query("select * from `shiyan` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."';");
		if(!$result_shiyan) {
			die("mysql select error:".mysql_error());
		}
		$result_shiyan = mysql_fetch_assoc($result_shiyan);
		$shiyan_yuanshi = round($result_shiyan['yuanshi'],4);
		$shiyan_zhehe = round($result_shiyan['zhehe'],4);
		$shiyan_jintie = round($result_shiyan['jintie'],4);

		
		#竞赛
		$result_jingsai = mysql_query("select SUM(jiaofen) from `jingsai` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."';");
		if(!$result_jingsai) {
			die("mysql select error:".mysql_error());
		}
		$result_jingsai = mysql_fetch_assoc($result_jingsai);
		$jingsai_jiaofen = $result_jingsai['SUM(jiaofen)'];

		#教务津贴
		$result_jiaowu = mysql_query("select SUM(jiaofen) from `jiaowu` where `teacher_id` = \"". $t_id ."\"  and `xueqi`='".$year."';");
		if(!$result_jiaowu) {
			die("mysql select error:".mysql_error());
		}
		$result_jiaowu = mysql_fetch_assoc($result_jiaowu);
		$jiaowu_jiaofen = $result_jiaowu['SUM(jiaofen)'];

		#其他
		$result_qita = mysql_query("select SUM(jiaofen) from `qita` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."';");
		if(!$result_qita) {
			die("mysql select error:".mysql_error());
		}
		$result_qita = mysql_fetch_assoc($result_qita);
		$qita_jiaofen = $result_qita['SUM(jiaofen)'];
	    
	    #欠考
		$result_qiankao = mysql_query("select SUM(jiaofen) from `qiankao` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."';");
		if(!$result_qiankao) {
			die("mysql select error:".mysql_error());
		}
		$result_qiankao = mysql_fetch_assoc($result_qiankao);
		$qiankao = $result_qiankao['SUM(jiaofen)'];


		#成人
		$result_chengren = mysql_query("select * from `chengren` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."';");
		if(!$result_chengren) {
			die("mysql select error:".mysql_error());
		}
		$result_chengren = mysql_fetch_assoc($result_chengren);
		$chengren_yuanshi = $result_chengren['yuanshi'];
		$chengren_zhehe = $result_chengren['zhehe'];
		$chengren_shijianzhehe = $result_chengren['shijianzhehe'];

		#研究生
		$result_yjs = mysql_query("select * from `yanjiusheng` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."';");
		if(!$result_yjs) {
			die("mysql select error:".mysql_error());
		}
		$result_yjs = mysql_fetch_assoc($result_yjs);
		$yjs_yuanshi = $result_yjs['yuanshi'];
		$yjs_zhehe = round($result_yjs['zhehe'],4);
		$yjs_zhidao = $result_yjs['zhidao'];
		$yjs_mubiao = round($result_yjs['mubiao'],4);
		$yjs_zdjs = round($result_yjs['zdjs'],4);
		$t_sd = $result_yjs['isshuodao']=="是"? "是" : "否(或未知)";



		# 汇总
		$sum_all = 0;
		$sum_all = $lilun_zhehe + $shijian_zhehe;
		$sum_all += $yjs_zhehe + $yjs_zhidao + $yjs_mubiao + $yjs_zdjs;
		$sum_all += $chengren_zhehe + $chengren_shijianzhehe;
		$sum_all += $shiyan_zhehe + $shiyan_jintie;
		$sum_all += $jingsai_jiaofen;
		$sum_all += $jiaowu_jiaofen;
		$sum_all += $qita_jiaofen;
		$sum_all += $qiankao;

		# 写入Excel类
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$num, (int)$year);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$num, (string)$t_id,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$num, (string)$t_name,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$num, (string)$teacher_row['zhicheng'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$num, (int)$teacher_row['teacher_cat']);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$num, (string)$t_sd,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$num, (string)$t_sjt,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$num, (string)$t_xueyuan,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$num, (string)$t_xi,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('J'.$num, (string)$t_idcard,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$num, (string)$t_ykt,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$num, (string)$t_gzid,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('M'.$num, (float)$yjs_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('N'.$num, (float)$yjs_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('O'.$num, (float)$yjs_zhidao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('P'.$num, (float)$yjs_mubiao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Q'.$num, (float)$yjs_zdjs,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('R'.$num, (float)$chengren_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('S'.$num, (float)$chengren_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('T'.$num, (float)$chengren_shijianzhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('U'.$num, (float)$lilun_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('V'.$num, (float)$lilun_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('W'.$num, (float)$shijian_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('X'.$num, (float)$shijian_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Y'.$num, (float)$jingsai_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('Z'.$num, (float)$jiaowu_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AA'.$num, (float)$qita_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AB'.$num, (float)$shiyan_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AC'.$num, (float)$shiyan_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AD'.$num, (float)$shiyan_jintie,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AE'.$num, (float)$qiankao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('AF'.$num, (float)$sum_all,PHPExcel_Cell_DataType::TYPE_NUMERIC);
	
		# 累加行数
		$num++;
}
	# 输出excel

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
