<?php
include 'config.php';
if(isset($_SESSION['rank'])) {
	$rank  = $_SESSION['rank'];
}else{
	$rank = 0;
	die("权限不够！");
}
$sql = "select * from `XSB`";
$result = mysql_query($sql);
$rows = mysql_fetch_array($result);
print_r($rows);
$list  = mysql_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport"    content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author"      content="Sergey Pozhilov (GetTemplate.com)">
	
	<title>About - Progressus Bootstrap template</title>

	<link rel="shortcut icon" href="assets/images/gt_favicon.png">
	
	<link rel="stylesheet" media="screen" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">

	<!-- Custom styles for our template -->
	<link rel="stylesheet" href="assets/css/bootstrap-theme.css" media="screen" >
	<link rel="stylesheet" href="assets/css/main.css">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="assets/js/html5shiv.js"></script>
	<script src="assets/js/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<!-- Fixed navbar -->
	<div class="navbar navbar-inverse navbar-fixed-top headroom" >
		<div class="container">
			<div class="navbar-header">
				<!-- Button for smallest screens -->
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand" href="index.html"><img src="assets/images/logo.png" alt="Progressus HTML5 template"></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav pull-right">
					<?php
					if(isset($_SESSION['username'])) {
						?>
						<li class="active"><a href="#">Hi ! <?php echo $_SESSION['username'];?></a></li>
						<?php
					}
					?>
					<li class="active"><a href="#">Home</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">操作 <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="active"><a href="show.php?">查询工作量</a></li>
							<li class="active"><a href="sidebar-right.html">修改系数</a></li>
						</ul>
					</li>
					<li> 
						<?php
						if(!$rank) {
							?>
							<a class="btn" href="signin.php">登录</a>
							<?php 
						}else{
							?>
							<a class="btn" href="loginout.php">退出</a>
							<?php
						}
						?> 
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div> 
	<!-- /.navbar -->

	<header id="head" class="secondary"></header>

	<!-- container -->
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="index.html">Home</a></li>
			<li class="active">About</li>
		</ol>

		<div class="row">
			
			
			<header class="page-header">
				<h1 class="page-title">About us</h1>
			</header>
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<table class="table">
							<thead>
								<tr>
									<th>
										编号
									</th>
									<th>
										系数
									</th>
									<th>
										系数说明
									</th>
									<th>
										状态
									</th>
								</tr>
							</thead>
							<tbody>
<?php
for($i = 0; $i < $list; $i++) {
								echo "<tr>";
									echo "<td>";
										echo $i + 1;
									echo "</td>";
									echo "<td>";
										echo $rows[$i];
									echo "</td>";
									echo "<td>";
										echo "01/04/2012";
									echo "</td>";
									echo "<td>";
										echo "Default";
									echo "</td>";
								echo "</tr>";
}
?>
								<tr class="success">
									<td>
										1
									</td>
									<td>
										TB - Monthly
									</td>
									<td>
										01/04/2012
									</td>
									<td>
										Approved
									</td>
								</tr>
								<tr class="error">
									<td>
										2
									</td>
									<td>
										TB - Monthly
									</td>
									<td>
										02/04/2012
									</td>
									<td>
										Declined
									</td>
								</tr>
								<tr class="warning">
									<td>
										3
									</td>
									<td>
										TB - Monthly
									</td>
									<td>
										03/04/2012
									</td>
									<td>
										Pending
									</td>
								</tr>
								<tr class="info">
									<td>
										4
									</td>
									<td>
										TB - Monthly
									</td>
									<td>
										04/04/2012
									</td>
									<td>
										Call in to confirm
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>



		</div>
	</div>	<!-- /container -->


	<footer id="footer" class="top-space">



		<div class="footer2">
			<div class="container">
				<div class="row">

					<div class="col-md-6 widget">
						<div class="widget-body">
							<p class="simplenav">
								<a href="#">Home</a> | 
								<a href="about.html">About</a> |
								<a href="sidebar-right.html">Sidebar</a> |
								<a href="contact.html">Contact</a> |
								<b><a href="signup.html">Sign up</a></b>
							</p>
						</div>
					</div>

					<div class="col-md-6 widget">
						<div class="widget-body">
							<p class="text-right">
								Copyright &copy; 2014, Your name. Designed by <a href="http://gettemplate.com/" rel="designer">gettemplate</a> 
							</p>
						</div>
					</div>

				</div> <!-- /row of widgets -->
			</div>
		</div>
	</footer>	





	<!-- JavaScript libs are placed at the end of the document so the pages load faster -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script src="assets/js/headroom.min.js"></script>
	<script src="assets/js/jQuery.headroom.min.js"></script>
	<script src="assets/js/template.js"></script>
</body>
</html>