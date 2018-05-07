<?PHP
/*
* 修改条目
*/
include "config.php";
$rank = 0;
if(!isset($_SESSION['rank'])) {
    header("location: ./error.php?txt="."请登录后再操作.");
    exit();
}else{
	if($_SESSION['rank']!=1) {
		header("location: ./error.php?txt="."你的权限不够.");
    exit();
	}else{
		$rank = $_SESSION['rank'];
	}
}

$modify_id = $_GET['modify_id'];
$modify_type = $_GET['modify_type'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="./favicon.ico">

	<title>Signin Template for Bootstrap</title>

	<!-- Bootstrap core CSS -->
	<link href="./css/bootstrap.css" rel="stylesheet">

	<!-- Custom styles for this template -->

	<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
	<!--[if lt IE 9]><script src="./assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
	<script src="./js/ie-emulation-modes-warning.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>

  <body>


  	<div class="container-fluid">
<?php
if($modify_type == 1) {
?>
  		<h2 class="sub-header">理论教学工作量明细</h2>
  		
  			<div class="span12">
  				<table class="table table-condensed">
  					<thead>
  						<tr>
  							<th>教师号</th>
  							<th>姓名</th>
  							<th>职称</th>
  							<th>课程号</th>
  							<th>课程名</th>
  							<th>合班</th>
  							<th>学时</th>
  							<th>人数</th>
  							<th>人数系数</th>
  							<th>重复课系数</th>
  							<th>专业课系数</th>
  							<th>三表系数</th>
  							<th>难度系数</th>
  							<th>职称系数</th>
  							<th>合班系数</th>
  							<th>计算过程</th>
  							<th>教分</th>
  							<th>学期</th>
  							<th>操作</th>


  						</tr>
  					</thead>
  					<tbody>
  						<?php
  					//echo "#";
  						for($i = 0; $i < 4; $i++) {
  							if($i%2==0) {
  								echo "<tr>";
  							}else{
  								echo "<tr style=\"background-color: #F2F2F2;\">";
  							}

  							for($k = 0; $k < 18; $k++) {
  								echo "<td>".$res2[$k]."</td>";
  							}
  							if($rank==1) {
  								echo "<td><a href=\"./modify.php?modify_id=". $modify_id ."\">修改</a></td>";
  							}else{
  								echo "<td>修改</td>";
  							}


  							echo "</tr>";
  						}
  						?>
  					</tbody>
  				</table>
  			</div>
<?php
}
if($modify_type==2) {
?>
  		<h2 class="sub-header">实践教学工作量明细</h2>
  			<div class="span12">
  				<table class="table table-condensed">
  				<thead>
  					<tr>
  						<th>教师号</th>
  						<th>姓名</th>
  						<th>职称</th>
  						<th>课程号</th>
  						<th>课程名</th>
  						<th>合班</th>
  						<th>实践类型</th>
  						<th>实践地点/方式</th>
  						<th>学时(周)</th>
  						<th>人数</th>
  						<th>职称系数</th>
  						<th>计算过程</th>
  						<th>教分</th>
  						<th>学期</th>


  						
  					</tr>
  				</thead>
  				<tbody>
  					<?php
  					for($i = 0; $i < $list; $i++) {
  						echo "<tr>";
  						for($i = 0; $i < 14; $i++) {
  							echo "<td>".$res[$i]."</td>";
  						}
  						echo "<td>修改</td>";

  						echo "</tr>";
  					}

  					?>
  				</tbody>
  			</table>
  		</div> 
<?php
}
?>
  	</div> <!-- /container -->


  	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  	<script src="./js/ie10-viewport-bug-workaround.js"></script>
  </body>
  </html>
