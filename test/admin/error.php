<?php
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';


//接收错误说明
if(isset($_GET['txt'])) {
	$error_txt = $_GET['txt'];	
}else {
	$error_txt = "未知错误;";
}

?>
<?php include 'header.php'; ?>

	<!-- container -->
<div class="span9">
	<h3><?php echo $error_txt;?></h3>
	</div>	<!-- /container -->

	<?php include './footer.php'; ?>
</body>
</html>