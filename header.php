<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport"    content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author"      content="Sergey Pozhilov (GetTemplate.com)">
    
    <title>
        <?php 
            if(isset($title)) {
                echo $title;
            }else{
                echo "HRBUST";
            }
        ?>
    </title>

    <link rel="shortcut icon" href="assets/images/gt_favicon.png">
    
<!--     <link rel="stylesheet" media="screen" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
-->    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
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
                <a class="navbar-brand" href="index.php"><img src="assets/images/logo.png" alt="Progressus HTML5 template"></a>
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
                    <li class="active"><a href="index.php">Home</a></li>
                    
                        
                        <?php
                            if(isset($_SESSION['username'])) {
                                if($_SESSION['username']=="admin"){
                        ?>
                                <li class="active"><a href="admin">管理员入口</a></li>
                        <?php
                                }else{
                        ?>
                                <li class="active"><a href="admin">操作中心</a></li>

                        <?php
                                }
                            }
                        ?>

                        
                    <li> 
                        <?php
                        if(!$rank) {
                            ?>
                            <a class="btn active" href="signin.php">登录</a>
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
