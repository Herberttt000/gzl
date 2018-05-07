/*
//add data to jisuanxishu table
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"NOP1\", 30, \"一个自然班的人数\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"L1\", 90, \"自然班到第一个人数分级上限\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"L2\", 120, \"第二个分级上限\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"L1K\", 0.0175, \"第1个分级,每增加5人,K增加的值\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"L2K\", 0.0116, \"第2个分级,每增加5人,K增加的值\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"CFK12\", 1.0, \"教师在同一学期讲授同一门课程，前两次授课C的值\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"CFK3\", 0.85, \"教师在同一学期讲授同一门课程，第三次授课C的值\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"CFK4\", 0.7, \"教师在同一学期讲授同一门课程，第四次授课C的值\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"ZYK\", 0.2, \"专业课K增加的值\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"SB\", 0.2, \"为二表B，三表学生授课，K增加的值\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"HHB\", 0.05, \"为混合班授课，K增加的值\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"TYK\", 0.65, \"体育课系数\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"KCSJ\", 0.27, \"课程设计公式的系数\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"SCSX\", 0.34, \"生产实习公式的系数\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"SCSX2\", 1.2, \"生产实习公式的系数（室外系数）\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"BYSJ\", 0.56, \"毕业设计公式的系数\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"JGSX\", 1.7, \"金工实习公式的系数\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}
$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"ZHXSJYXL\", 0.33, \"综合性设计与训练公式的系数\");";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}

$sql = "INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"FSXSXYSJ\", 0.15, \"分散性实习与实践公式的系数\");";
$result = mysql_query($sql);
if(!$result) {
	die("mysql error:" . mysql_error());
}

/*	
$sql = "
	
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"M1K\", 1, \"1次授课班级数为1时，K的值\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"L1\", 90, \"自然班到第一个人数分级上限\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"L2\", 120, \"第二个分级上限\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"L1K\", 0.0175, \"第1个分级,每增加5人,K增加的值\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"L2K\", 0.0116, \"第2个分级,每增加5人,K增加的值\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"CFK12\", 1.0, \"教师在同一学期讲授同一门课程，前两次授课C的值\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"CFK3\", 0.85, \"教师在同一学期讲授同一门课程，第三次授课C的值\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"CFK4\", 0.7, \"教师在同一学期讲授同一门课程，第四次授课C的值\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"ZYK\", 0.2, \"专业课K增加的值\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"SB\", 0.2, \"为二表B，三表学生授课，K增加的值\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"HHB\", 0.05, \"为混合班授课，K增加的值\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"TYK\", 0.65, \"体育课系数\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"KCSJ\", 0.27, \"课程设计公式的系数\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"SCSX\", 0.34, \"生产实习公式的系数\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"SCSX\", 1.2, \"生产实习公式的系数（室外系数）\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"BYSJ\", 0.56, \"毕业设计公式的系数\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"JGSX\", 1.7, \"金工实习公式的系数\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"ZHXSJYXL\", 0.33, \"综合性设计与训练公式的系数\");
	INSERT INTO `jisuanxishu` (	name,value,disc) 
	VALUES ( \"FSXSXYSJ\", 0.15, \"分散性实习与实践公式的系数\");	
";
$result = mysql_query($sql,$con);
if(!$result) {
	die("mysql error:" . mysql_error());
}

*/