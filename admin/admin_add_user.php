<?php
	
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';

	if(!isset($_SESSION['rank'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}else if(!is_admin($_SESSION['rank'])) {
		header("location: ./error.php?txt="."你的权限不够.");
		exit();
	}else{
		$rank = 1;
	}
?>
<?php
	include './header.php'
?>
<?php


?>




<?php include('./footer.php'); ?>
  </body>
</html>
