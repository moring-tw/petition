<?php //include header
	include 'template/header.php';
?>

<?php
	$path = explode("/", $_SERVER['REQUEST_URI']);
	if(strcmp($path[1], "petition") === 0){
		if(strcmp($path[2], "login") === 0){
			include 'pages/login.php';
		} else if(strcmp($path[2], "signup") === 0){
			echo "<img src='img/FBM.jpg' id='fbm'>";
		} else if(strlen($path[2]) === 0){
			// home page
			include 'pages/petition_list.php';
		} else {
			include 'not-found.html';
		}
	}
?>

<?php // include footer
	include 'template/footer.php';
?>