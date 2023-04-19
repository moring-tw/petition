<?php
	$path = explode("/", $_SERVER['REQUEST_URI']);
	if(isset($_SESSION["UserInfo"])){
		// if account is not actived yet.
		if($_SESSION["UserInfo"]["activated"] === 0){
			//if(strcmp($path[2], "active") !== 0){
				//Header("Location: "."/petition/active/");
			//}
		}
		if(strcmp($path[2], "create_account") === 0) {
			if(intval($_SESSION["UserInfo"]["permission"]) !== 99){
				Header("Location: "."/petition/login/");
				exit();
			}
		}
		// check permission for each page
	} else {
		if(strcmp($path[2], "login") !== 0){
			//Header("Location: "."/petition/login/");
			//exit();
		}
        if(strcmp($path[2], "create_issue") === 0){
            Header("Location: "."/petition/login/");
            exit();
        }
	}
?>