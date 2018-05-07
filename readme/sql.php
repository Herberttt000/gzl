<?php
$con = mysql_connect("localhost","root","root");
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

//Create database
// if (mysql_query("CREATE DATABASE `gzl` DEFAULT CHARACTER 
// SET utf8 COLLATE utf8_general_ci;",$con))
// {
// 	echo "Database created";
// }
// else
// {
// 	echo "Error creating database: " . mysql_error();
// }

// Create table gzl database
mysql_select_db("gzl", $con);
//Create teachers table
// $sql = "CREATE TABLE teachers
// (
// 	teacher_id varchar(15) NOT NULL,
// 	PRIMARY KEY(teacher_id),
// 	teacher_name varchar(15),
// 	yikatong varchar(15),
// 	idcard varchar(20),
// 	gz_id varchar(25),
// 	zhicheng varchar(15),
// 	issjt tinyint(1),
// 	issd varchar(15),
// 	isxsgzry tinyint(1),
// 	xueyuan varchar(20),
// 	xi varchar(20),
// 	iswork tinyint(1)
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }


//Create zcxishu table
// $sql = "CREATE TABLE `zcxishu`
// (
// 	id int NOT NULL AUTO_INCREMENT,	
// 	PRIMARY KEY(id),
// 	name varchar(20),
// 	xishu float(11)
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

//Create lilun_temp table
// $sql = "CREATE TABLE `lilun_temp`
// (
// 	xueqi int,
//  	course_yuanxi varchar(30),
// 	course_id varchar(20),
// 	course_name varchar(30),
// 	course_index int,
// 	num_of_p int,
// 	xueshi float(11),
// 	xuankeshuxing varchar(10),
// 	heban varchar(255),
// 	issb tinyint(1),
// 	kcxz varchar(10),
// 	teacher_id varchar(15),
// 	teacher_name varchar(15),
// 	teacher_yuanxi varchar(50),
// 	teacher_zc varchar(20)
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

//Create lilun table
// $sql = "CREATE TABLE `lilun`
// (
// 	id int primary key not null auto_increment,
// 	xueqi int,
// 	INDEX (xueqi),
// 	course_id varchar(15),
// 	course_name varchar(30),
// 	course_index int,
// 	course_yuanxi varchar(30),
// 	teacher_yuanxi varchar(30),
// 	teacher_id varchar(15),
// 	teacher_name varchar(15),
// 	teacher_zc varchar(20),
// 	heban varchar(255),
//  	zhuanye varchar(30),
// 	issb tinyint(1),
// 	xueshi float(11),
// 	num_of_p int,
// 	xishu_people float(11),
// 	xishu_cfk float(11),
// 	xishu_zyk float(11),
// 	xishu_sb float(11),
// 	xishu_zl float(11),
// 	xishu_nd float(11),
// 	xishu_zc float(11),
// 	guocheng varchar(255),
// 	jiaofen float
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }


// //
// //Create shijian_temp table
// $sql = "CREATE TABLE `shijian_temp`
// (
// 	xueqi int,
// 	INDEX (xueqi),
// 	teacher_id varchar(15),
// 	INDEX (teacher_id),
// 	teacher_name varchar(15),
// 	shijian_name varchar(30),
// 	course_id varchar(15),
// 	course_index int,
// 	shijian_type varchar(15),
// 	zhoushu int,
// 	num_of_p int,
// 	banji varchar(255),
// 	banjishu int,
// 	didian varchar(20),
// 	teacher_xueyuan varchar(30),
// 	username varchar(30)	
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

// //Create shijian table
// $sql = "CREATE TABLE `shijian`
// (
// 	id int primary key not null auto_increment,
// 	xueqi int,
// 	INDEX (xueqi),
// 	xueyuan varchar(30),
// 	INDEX (xueyuan),
// 	teacher_id varchar(15),
// 	teacher_name varchar(15),
// 	teacher_zc varchar(15),
// 	shijian_name varchar(30),
// 	course_index int,
// 	course_id varchar(15),
// 	shijian_type varchar(15),
// 	zhichengxishu float,
// 	zhoushu int,
// 	num_of_p int,
// 	banji varchar(255),
// 	didian varchar(20),
//	 varchar(255),
// 	jiaofen float
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

// //Create jingsai table
// $sql = "CREATE TABLE `jingsai`
// (
// 	xueqi int,
// 	INDEX (xueqi),
// 	teacher_id varchar(15),
// 	INDEX (teacher_id),
// 	teacher_name varchar(15),
// 	yuanyin varchar(255),
// 	jiaofen float
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

// //Create jiaowu table
// $sql = "CREATE TABLE `jiaowu`
// (
// 	xueqi int,
// 	INDEX (xueqi),
// 	teacher_id varchar(15),
// 	INDEX (teacher_id),
// 	teacher_name varchar(15),
// 	yuanyin varchar(255),
// 	jiaofen float
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

// //Create qita table
// $sql = "CREATE TABLE `qita`
// (
// 	xueqi int,
// 	INDEX (xueqi),
// 	teacher_id varchar(15),
// 	INDEX (teacher_id),
// 	teacher_name varchar(15),
// 	yuanyin varchar(255),
// 	jiaofen float
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

// //Create yanjiusheng table
// $sql = "CREATE TABLE `yanjiusheng`
// (
// 	xueqi int,
// 	INDEX (xueqi),
// 	teacher_id varchar(15),
// 	INDEX (teacher_id),
// 	teacher_name varchar(15),
// 	yuanshi float,
// 	zhehe float,
// 	zhidao float,
// 	mubiao float
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

// //Create chengren table
// $sql = "CREATE TABLE `chengren`
// (
// 	xueqi int,
// 	INDEX (xueqi),
// 	teacher_id varchar(15),
// 	INDEX (teacher_id),
// 	teacher_name varchar(15),
// 	yuanshi float,
// 	zhehe float,
// 	shijianzhehe float
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

// //Create shiyan table
// $sql = "CREATE TABLE `shiyan`
// (
// 	xueqi int,
// 	INDEX (xueqi),
// 	teacher_id varchar(15),
// 	INDEX (teacher_id),
// 	teacher_name varchar(15),
// 	yuanshi float,
// 	zhehe float,
// 	jintie float
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

//Create users table
// $sql = "CREATE TABLE `users`
// (
// 	username varchar(30),
// 	INDEX (username),
// 	password varchar(20),
// 	name varchar(30),
// 	rank int,
// 	xueyuan varchar(30),
// 	import_jiaowu int(1),
// 	import_qita int(1),
// 	import_jingsai int(1),
// 	import_chengren int(1),
// 	import_yanjiusheng int(1),
// 	import_shiyan int(1),
// 	import_qiankao int(1),
// 	import_teachers int(1),
// 	import_banji int(1),
// 	import_lilun int(1),
// 	import_shijian int(1),
// 	import_something int(1),
// 	export_renshichu int(1),
// 	export_geren int(1),
// 	modify_jisuanxishu int(1),
// 	modify_zcxishu int(1),
// 	modify_data int(1),
// 	calc_lilun int(1),
// 	calc_shijian int(1),
// 	calc_tiyu int(1),
//  show_gzl int(1),
//	modify_users int(1)
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

//add admin to user table
// $sql = "INSERT INTO `users` (
// 	username,
// 	password,
// 	name,
// 	rank
// 	) VALUES (
// 	\"admin\",
// 	\"123123\",
// 	\"admin\",
// 	1
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

//Create jisuanxishu tablej
// $sql = "CREATE TABLE `jisuanxishu`
// (
// 	name varchar(30),
// 	INDEX (name),
// 	PRIMARY KEY(name),
// 	value float,
// 	disc varchar(255)
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

//Create class table
// $sql = "CREATE TABLE `class` 
// (
//  name varchar(20),
//  zhuanye varchar(30)
// )";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error : " . mysql_error());
// }

//汇总表
// $sql = "CREATE TABLE `huizong`
// (
// 	xueqi int,
// 	INDEX (xueqi),
// 	teacher_id varchar(15),
// 	INDEX (teacher_id),
// 	teacher_name varchar(15),
// 	teacher_zc varchar(30),
// 	teacher_issd varchar(15),
// 	teacher_sjt varchar(15),
// 	teacher_xueyuan varchar(15),
// 	teacher_xi varchar(15),
// 	teacher_idcard varchar(20),
// 	teacher_ykt varchar(20),
// 	teacher_gzk varchar(20),
// 	yjs_yuanshi float,
// 	yjs_zhehe float,
// 	yjs_zhidao float,
// 	yjs_mubiao float,
// 	cr_yuanshi float,
// 	cr_zhehe float,
// 	cr_shijianzhehe float,
// 	bk_lilunyuanshi float,
// 	bk_lilunzhehe float,
// 	bk_shijianyuanshi float,
// 	bk_shijianzhehe float,
// 	jingsai float,
// 	jiaowu float,
// 	qita float,
// 	sy_yuanshi float,
// 	sy_zhehe float,
// 	sy_jintie float,
// 	qiankao int,
// 	zhongji float
// 	)";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error:" . mysql_error());
// }

// Create qiankao table
// $sql = "CREATE TABLE `qiankao` 
// (
//  xueqi int,
//  teacher_id varchar(30),
//  teacher_name varchar(30),
//  jiaofen int
// )";
// $result = mysql_query($sql,$con);
// if(!$result) {
// 	die("mysql error : " . mysql_error());
// }



?>