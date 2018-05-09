<?php
/*
	此页仅仅允许 rank == 1的用户修改访问
*/
include 'config.php';
if(isset($_SESSION['rank']) && $_SESSION['rank']==1) {
	$rank  = $_SESSION['rank'];
}else{
	$rank = 0;
	header("location: ./error.php?txt="."权限不够.");
	exit();
}
$sql = "select * from `other_gzl`";
$result = mysql_query($sql);
if(!$result) {
	die("select error: ".mysql_error());
}
$list  = mysql_num_rows($result);
//$rows = mysql_fetch_array($result);
//print_r($rows);
?>
<?php
	include './header.php';
?>

	<!-- container -->
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="index.html">Home</a></li>
			<li class="active">About</li>
		</ol>

		<div class="row">
			
			<header class="page-header">
				<h1 class="page-title">现有其他工作量</h1>
			</header>
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<form action="insert_other_data_handle.php" method="post">
							<table class="table">
								<thead>
									<tr>
										<th >
											教师号
										</th>
										<th>
											姓名
										</th>
										<th>
											教分
										</th>
										<th>
											说明
										</th>
										<th>
											学期
										</th>
										<th>
											操作
										</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										for($i = 0; $i < $list; $i++) {
											$row = mysql_fetch_row($result);
											if($i%2==0) echo "<tr>";
											else echo "<tr class=\"success\">";
									?>
									
										<td>
											<?php echo $row[1]; ?>
										</td>
										<td>
											<?php echo $row[2]; ?>
										</td>
										<td>
											<?php echo $row[3]; ?>
										</td>
										<td>
											<?php echo $row[4]; ?>
										</td>
										<td>
											<?php echo $row[5]; ?>
											<input name="handle" type="hidden" value="2">
											<input name="id" type="hidden" value="<?php echo $row[0];?>">
										</td>
										<td>
											<button type="submit" class="btn btn-danger btn-xs">删除</button>
										</td>

									</tr>
									<?php } ?>
									

								</tbody>
							</table>
							

						</form>
					</div>
				</div>
			</div>

			<header class="page-header">
				<h1 class="page-title">添加工作量</h1>
			</header>
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<form action="insert_other_data_handle.php" method="post">
							<table class="table">
								<thead>
									<tr>
										<th>
											教师号
										</th>
										<th>
											姓名
										</th>
										<th>
											教分
										</th>
										<th>
											说明
										</th>
										<th>
											学期
										</th>
										<th>

										</th>
									</tr>
								</thead>
								<tbody>

									<tr>
										<td>
											<input name="id" style=\"width: auto;\" type=\"text\" class=\"form-control\" value="">
										</td>
										<td>
											<input name="name" style=\"width: auto;\" type=\"text\" class=\"form-control\" value="">
										</td>
										<td>
											<input name="jf" style=\"width: auto;\" type=\"text\" class=\"form-control\" value="">
										</td>
										<td>
											<input name="sm" style=\"width: auto;\" type=\"text\" class=\"form-control\" value="">
										</td>
										<td>
											<input name="xq" style=\"width: auto;\" type=\"text\" class=\"form-control\" value="">
											<input name="handle" type="hidden" value="1">
										</td>
										<td>
											<button type="submit" class="btn btn-success">提交</button>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
				</div>
			</div>



		</div>
	</div>	<!-- /container -->


	<?php include './footer.php'; ?>

</body>
</html>