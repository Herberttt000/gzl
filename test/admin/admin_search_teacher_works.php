<!DOCTYPE html>
<?php
/*
*	查询修改某教师的工作量明细
* GET : $techer_id;
* 		$xueqi；
*/
include "./../config.php";
include "./../functions.php";

	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['find_gzl']) {
		header("location: ./error.php?txt="."您没有查询工作量的权限.");
		exit();
	}

	#获取当前学期
	$guessdata = 20141;
	if(date("m")>8){
		$guessdata = date("Y2");
	}else{
		$guessdata = date("Y1");
	}

	#获取用户所属学院的教师
	$user_xueyuan = $_SESSION['xueyuan'];
	if ($user_xueyuan == "无") {
		$sql = ";";
	}else if ($user_xueyuan == "全校") {
		$sql = "select * from `teachers` where xueqi='$guessdata';";
	}else {
		$sql = "select * from `teachers` where xueqi='$guessdata' and `xueyuan` = \"". $user_xueyuan ."\"";
	}
	$result = mysql_query($sql);
	if(!$result) {
		die("SQL ERROR : ".mysql_error());
	}

	//$rs = mysql_fetch_assoc($result);


include "./header.php";
?>
<script>

function showHint()
{

	var myButton = document.getElementById("myButton");
	myButton.innerHTML="请稍后...";

	var years = document.getElementById("id_years").value;
	var xmlhttp;
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
  	}
 	else
  	{// code for IE6, IE5
  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
 	}
  	xmlhttp.onreadystatechange=function()
  	{
  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
  		{
  			document.getElementById("t_list").innerHTML=xmlhttp.responseText;
  			//alert(xmlhttp.responseText);
  			myButton.innerHTML="查询";
  		}

  	}
  	xmlhttp.open("POST", "./ajax_get_gzl_by_xueyuan_and_years.php?t="+Math.random(), true);
  	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("years="+years);
}

function export_huizong(){
	var xueqi = document.getElementById("id_years").value;
	window.open("./admin_search_teacher_works_output_huizong.php?xueqi="+xueqi);
}
function export_lilun(){
	var xueqi = document.getElementById("id_years").value;
	window.open("./admin_search_teacher_works_show_lilun.php?xueqi="+xueqi);
}
function export_shijian(){
	var xueqi = document.getElementById("id_years").value;
	window.open("./admin_search_teacher_works_show_shijian.php?xueqi="+xueqi);
}
</script>
<div class="container-fluid">
	<div class="row">
		<h3>输入教师号</h3>
		<form class="navbar-form navbar-left" action="./admin_search_teacher_works_handle.php" method="get">
			<input type="text" name="userId" class="form-control" placeholder="请输入教师号">
			<span class="text-right"><button type="submit" class="btn btn-success">查询</button></span>
		</form>
	</div>
	<div class="row">
		<h3>输入年份或区间</h3>
			<form class="navbar-form navbar-left" method="get">

			<input type="text" name="userId" id="id_years" class="form-control" placeholder="请输入学期或区间">
			<span class="text-right"><button id="myButton" type="button" data-loading-text="bbbb" class="btn btn-success" onclick="showHint()">查询</button></span>
			<span class="text-right"><button id="total_Button" type="button" data-loading-text="bbbb" class="btn btn-success" onclick="export_huizong()">下载汇总表</button></span>
			<span class="text-right"><button id="lilun_Button" type="button" data-loading-text="bbbb" class="btn btn-success" onclick="export_lilun()">查询理论明细表</button></span>
			<span class="text-right"><button id="shijian_Button" type="button" data-loading-text="bbbb" class="btn btn-success" onclick="export_shijian()">查询实践明细表</button></span>
				</form>
	</div>
	<div class="row">
			<div class="alert alert-success" role="alert">查询小技巧: <br><ul><li><strong>20141</strong> 查询 2014春 的工作量</li><li><strong>20142</strong> 查询 2014秋 的工作量</li><li><strong>2013120142</strong> 查询 2013春 到 2014秋 的工作量</li></ul></div>
	</div>
	<div class="row">
		<div class="alert alert-info" role="alert">
			小技巧: 一般浏览器可以按<kbd><kbd>ctrl</kbd> + <kbd>f</kbd></kbd>查找页面内的文本,您可利用这个小技巧查找教师对应的教师号.
		</div>

	</div>
	<div class="row">
		<h4>教师列表<small><span class="label label-primary"><?php echo $user_xueyuan; ?></span></small></h4>
	</div>
	<div class="row" id="t_list">
		<table class="table table-hover table-striped table-condensed">	
			<thead>
				<tr>
					<th>#</th>
					<th>教师号</th>
					<th>姓名</th>
					<th>所属学院</th>
					<th>系</th>
				</tr>
			</thead>

					<?php
					# 循环输出教师
					$id = 1;
					while($rs = mysql_fetch_assoc($result)) {
						echo "<tr>";
						echo "<td>".$id++;
						echo "</td>";
						echo "<td>";
						echo $rs['teacher_id'];
						echo "</td>";
						echo "<td><a href=\"./admin_search_teacher_works_handle.php?userId=".$rs['teacher_id']."&xueqi=20151\">";
						echo $rs['teacher_name'];
						echo "</a></td>";
						echo "<td>";
						echo $rs['xueyuan'];
						echo "</td>";
						echo "<td>";
						echo $rs['xi'];
						echo "</td>";
						echo "</tr>";
					}

					?>

			<tbody>
			</tbody>
		</table>
	</div>

			

</div>	<!-- /container-fluid -->
<?php
include './footer.php'

?>
