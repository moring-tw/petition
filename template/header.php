<?php
	if(!isset($_SESSION['UserInfo']) || $_SESSION['UserInfo']["permission"] !== 99){
		error_reporting(0);
		ini_set('display_errors', 0);
	}
	date_default_timezone_set('Asia/Taipei');
?>
<html>
	<head>
		<base href="/petition/" />
		<meta charset="utf-8">
		<script src="./js/jquery-3.6.1.min.js"></script>
		<script src="./js/prevent_post_again.js"></script>
		<script type="text/javascript" src="./js/jquery-te-1.4.0.min.js" charset="utf-8"></script>
		<link rel="stylesheet" href="./css/template.css">
		<link rel="stylesheet" href="./css/jquery-te-1.4.0.css">
		<link rel="stylesheet" href="./css/style.css?v=<?=time();?>">
		<title>學二舍</title>
	</head>
	<body>
		<div id="background"></div>
		<div id="header">
			<a href="./" class="home">
				<div class="home">學二舍提案系統</div>
			</a>
			<?php if(!isset($_SESSION["UserInfo"])){?>
			<a class="mo-login" href='./login/'>
				Login
			</a>
			<?php } else { ?>
			<a class="mo-login" href='./logout/'>
				Logout
			</a>
			<?php } ?>
		</div>
		<div id="content">
		