<?php
include 'config.php';
if(isset($_SESSION['rank'])) {
	$rank  = $_SESSION['rank'];
}else{
	$rank = 0;
}

//接收错误说明
if(isset($_GET['txt'])) {
	$error_txt = $_GET['txt'];	
}else {
	$error_txt = "未知错误;";
}

?>
<?php include 'header.php'; ?>

	<!-- container -->
	<div class="container">
		<div class="row">
			<!-- Article main content -->
			<article class="col-xs-12 maincontent">

				<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
					<div class="panel panel-default">
							<div class="panel-body">
							<h3 class="text-center"><?php echo $error_txt; ?></h3>
							<hr>
							</div>
					</div>
				</div>
			</article>
			<!-- /Article -->
		</div>
	</div>	<!-- /container -->

	<?php include './footer.php'; ?>
</body>
</html>