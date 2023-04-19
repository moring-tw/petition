<?php
	global $moring_mysql;
	$query = "SELECT * FROM `petitio1_ntnu`.`issue_types`;";
	$issue_types = $moring_mysql->Moring_MySQL_Normal_User_Query($query);

	if(isset($_POST['user_id']) && intval($_POST['user_id']) === intval($_SESSION['UserInfo']["id"]) ){
		if(isset($_POST['text1']) && strlen($_POST['text1']) !== 0){
			if(isset($_POST['title'])){
				if(isset($_POST['type']) && intval($_POST['type'])!==0){
					$query = "INSERT INTO `petitio1_ntnu`.`issue` (`title`, `content`, `date`, `user_id`, `type_id`) VALUES ( :title, :content, :date, :user_id, :type_id);";
					$moring_mysql->Moring_MySQL_Normal_User_Query($query, Array(
																				"title" => $_POST['title'], 
																				"content"=>$_POST['text1'], 
																				"date" => date("Y/m/d H:i:s"), 
																				"user_id"=>$_POST['user_id'], 
																				"type_id"=>$_POST['type']));
					$query = "SELECT `id` FROM `petitio1_ntnu`.`issue` WHERE `title` = :title AND `user_id` = :uid ORDER BY `id` DESC;";
					$result = $moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("title" => $_POST['title'], "uid" => $_POST['user_id']));
					$row = $result->fetch();
					Header("Location: "."/petition/".$row[0]."/");
				}
			}
		}
	} 
?>
