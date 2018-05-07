<?php


/*
* 判断是否是管理员
*/
function is_admin($rank) {
	if($rank == 1) {
		return 1;
	}
	return 0;
}

/*
* 判断职称
*/
function get_zc($x) {
	switch ($x) {
		case 1:
			return "未定";
		case 2:
			return "助教";
		case 3:
			return "讲师";
		case 4:
			return "副教授";
		case 5:
			return "教授";
		default:
			return "null";
			break;
	}
	return "null";
}

/*
* 获取学期
*/
function get_xq($x) {
	if($x[4]=='1')
		return $x[0].$x[1].$x[2].$x[3]."春";
	else
		return $x[0].$x[1].$x[2].$x[3]."秋";
}

/*
* 更新某个教师的信息
*/

function update_teacher($teacher_id, $xueqi){
	$sql = "SELECT * FROM `lilun`
	WHERE `teacher_id` = '".$teacher_id."'
	AND `xueqi` = '".$xueqi."'
	ORDER BY `num_of_p` DESC";
	$result = mysql_query($sql);
	if(!$result) {
		die("SQL ERROR : ". mysql_error());
	}

	//while()

	$sql = "UPDATE `lilun` SET
	Address = 'Zhongshan 23',
	City = 'Nanjing'
	WHERE `teacher_id` = '".$teacher_id."'
	AND `xueqi` = '".$xueqi."';";
}

# 显示admin界面的左导航
function show_left_nav(){
	if($_SESSION['find_gzl'] || $_SESSION['modify_users'] || $_SESSION['modify_xitong']) {
		echo "<div class=\"list-group\">";
       	if($_SESSION['find_gzl'])
        	echo "    <a class=\"list-group-item\" href=\"./admin_search_teacher_works.php\">查询工作量</a>";
       	if($_SESSION['modify_users'])
        	echo "    <a class=\"list-group-item\" href=\"./admin_users.php\">网站用户管理</a>";
       	if($_SESSION['modify_xitong'])
        	echo "    <a class=\"list-group-item\" href=\"./admin_xitong.php\">网站系统管理</a>";
        echo "</div>";
	}
	if($_SESSION['import_teachers'] || $_SESSION['import_banji']
		|| $_SESSION['modify_zcxishu'] || $_SESSION['moidfy_jisuanxishu']
		|| $_SESSION['import_lilun'] || $_SESSION['import_shijian']) {
		echo "<div class=\"list-group\">";
        if($_SESSION['import_teachers'])
        	echo "  	<a class=\"list-group-item\" href=\"./add_teacher_data.php\">导入教师数据</a>";
        if($_SESSION['import_banji'])
        	echo "    <a class=\"list-group-item\" href=\"./admin_add_class_data.php\">导入班级信息</a>";
        if($_SESSION['modify_zcxishu'])
        	echo "    <a class=\"list-group-item\" href=\"./modify_zcxishu.php\">修改添加职称系数</a>";
        if($_SESSION['modify_jisuanxishu'])
        	echo "    <a class=\"list-group-item\" href=\"./modify_xishu.php\">修改系数</a>";
        if($_SESSION['import_lilun'])
        	echo "    <a class=\"list-group-item\" href=\"./admin_upload_lilun_data.php\">导入理论授课数据(Excel)</a>";
        if($_SESSION['import_shijian'])
        	echo "    <a class=\"list-group-item\" href=\"./shijian_import.php\">导入实践环节数据(Excel)</a>";
        echo "</div>";
	}
	if($_SESSION['import_jiaowu'] || $_SESSION['import_qita']
		|| $_SESSION['import_qiankao'] || $_SESSION['import_shiyan']
		|| $_SESSION['import_yanjiusheng'] || $_SESSION['import_jingsai']
		|| $_SESSION['import_chengren']) {
		echo "<div class=\"list-group\">";
        if($_SESSION['import_jingsai'])
        	echo "	<a class=\"list-group-item\" href=\"./import_jingsai.php\">竞赛数据导入</a>";
		if($_SESSION['import_jiaowu'])
			echo "	<a class=\"list-group-item\" href=\"./import_jiaowu.php\">教务津贴数据导入</a>";
		if($_SESSION['import_qita'])
			echo "	<a class=\"list-group-item\" href=\"./import_qita.php\">其他数据导入</a>";
		if($_SESSION['import_yanjiusheng'])
			echo "	<a class=\"list-group-item\" href=\"./import_yjs.php\">研究生数据导入</a>";
		if($_SESSION['import_chengren'])
			echo "	<a class=\"list-group-item\" href=\"./import_chengren.php\">成人数据导入</a>";
		if($_SESSION['import_shiyan'])
			echo "	<a class=\"list-group-item\" href=\"./import_shiyan.php\">实验数据导入</a>";
		if($_SESSION['import_qiankao'])
			echo "	<a class=\"list-group-item\" href=\"./import_qiankao.php\">欠考数据导入</a>";
        echo "</div>";
	}
	if($_SESSION['calc_lilun'] || $_SESSION['calc_shijian']
		|| $_SESSION['calc_tiyu'] || $_SESSION['modify_data']){
        echo "<div class=\"list-group\">";
        if($_SESSION['calc_lilun'])
        	echo "	<a class=\"list-group-item\" href=\"./calc_lilun.php\">#计算理论临时表</a>";
        if($_SESSION['calc_tiyu'])
        	echo "  <a class=\"list-group-item\" href=\"./calc_tiyu.php\">#计算计算体育</a>";
        if($_SESSION['calc_shijian'])
        	echo "  <a class=\"list-group-item\" href=\"./calc_shijian.php\">#计算全校实践</a>";
        if($_SESSION['calc_lilun'])
        	echo "  <a class=\"list-group-item\" href=\"./modify_lilun_per.php\">修改数据</a>";

        //add by houbaron 20161108 start
        // add by houbaron 20170517 start
        // 政策又变了，不再需要这种算法了
        //if ($_SESSION['modify_data'])
        //    echo " <a class='list-group-item' href='./modify_zzptk.php'>修改专业平台课</a>";//没有定义新的权限，用了一个已经存在的权限
        // add by houbaron 20170517 end
        //add by houbaron 20161108 end

        if($_SESSION['calc_lilun'])
        	echo "  <a class=\"list-group-item\" href=\"./admin_generate_total_table.php\">生成个人汇总表(测试)</a>";
        echo "</div>";
	}
	if($_SESSION['export_renshichu'] || $_SESSION['export_geren']){
        echo "<div class=\"list-group\">";
        if($_SESSION['export_renshichu']) {
        	echo "<a class=\"list-group-item\" href=\"./output_all.php\">导出人事部表格</a>";
            // add by houbaron 20170517 start
            echo "<a class=\"list-group-item\" href=\"./output_summary.php\">导出汇总（测试！！！）</a>";
            // add by houbaron 20170517 end
        }
        if($_SESSION['export_geren'])
        	echo "<a class=\"list-group-item\" href=\"./output_per.php\">导出全校个人明细表格</a>";
        echo "</div>";
        //print_r($_SESSION['export_geren']);
	}
}


?>
