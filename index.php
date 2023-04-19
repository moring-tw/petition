<?php			
	session_start();
	include("include/permission_check.php");
	include("include/moring_mysql_connect.php");
	$moring_mysql = new Moring_MySQL();
	$moring_mysql->Moring_MySQL_Connect();
	$moring_mysql->Moring_Petition_Normal_User();
?>

<?php //include header
	include 'template/header.php';
?>

<?php
	$path = explode("/", $_SERVER['REQUEST_URI']);
	if(strcmp($path[1], "petition") === 0){
		if(strcmp($path[2], "login") === 0){
			include 'pages/login.php';
			include 'include/login_handler.php';
		} else if(strcmp($path[2], "logout") === 0){
			include 'pages/logout.php';
		} else if(strcmp($path[2], "active") === 0){
			include 'pages/active_acc.php';
			include 'include/active_handler.php';
		} else if(strcmp($path[2], "signup") === 0){
			echo "<img src='img/FBM.jpg' id='fbm'>";
		} else if(strcmp($path[2], "noissue") === 0){
			echo "<img src='img/FBM.jpg' id='fbm'>";
		} else if(strcmp($path[2], "create_issue") === 0){
          	include 'include/create_issue_handler.php';
			include 'pages/create_issue.php';
		} else if(strcmp($path[2], "create_account") === 0){
			include 'include/create_new_account.php';
			include 'pages/create_account.php';
		} else if(strlen($path[2]) === 0){
			// home page
			include 'include/hidden_issue_handler.php';
			include 'pages/petition_list.php';
		} else if(intval($path[2]) !== 0){
			include 'include/create_new_comment.php';
			include 'include/get_issue_comment.php';
			include 'pages/issue_content.php';
		} else {
			include 'not-found.html';
		}
	}
?>

<?php // include footer
	include 'template/footer.php';
?>
