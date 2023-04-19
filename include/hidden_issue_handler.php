<?php
	global $moring_mysql;
	if(isset($_POST["iid"])){
		$query = "SELECT `user_id` FROM `issue` WHERE `id` = :iid;";
		$result = $moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("iid" => $_POST["iid"]));
		
		if($result->rowCount() === 1){
			$row = $result->fetch();
			if(intval($row['user_id']) === intval($_SESSION["UserInfo"]["id"]) || intval($_SESSION["UserInfo"]["permission"]) === 99){
				$query = "UPDATE `petitio1_ntnu`.`issue` SET `hidden` = 1 WHERE `id` = :iid;";
				$moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("iid" => $_POST["iid"]));
			}
		}
	}

?>