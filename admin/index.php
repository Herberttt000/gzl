<?php
	
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';

	# 判断是否登录
	if(!isset($_SESSION['rank'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

?>
<?php
	include './header.php'
?>

					<?php
					if(isset($_SESSION['username'])) {
						?>
						<h1 class="active"><a href="#">Hi ! <?php echo $_SESSION['name'];?></a></h1>
						<?php
					}
					?>
					<?php
					if (isset($_SESSION['modify_users'])) {
					?>
					<div class="row">
					<img src="./../files/img/liu.png">
					</div>

					<?php
					}
					?>

<?php include('./footer.php'); ?>
  </body>
</html>
