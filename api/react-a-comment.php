<?php
	include "../include/moring_mysql_connect.php";
	$moring_mysql = new Moring_MySQL();
	$moring_mysql->Moring_Petition_Normal_User();
	if(isset($_POST["cid"])){
		if(isset($_POST["iid"])){
			if(isset($_POST["uid"])){
				if(isset($_POST["type"])){
					if(strcmp($_POST["type"], "agree") === 0) //agree
						$query = "INSERT INTO `comment_react` (`iid`, `uid`, `cid`, `agree`, `oppose`) VALUES (:iid, :uid, :cid, 1, 0) ON DUPLICATE KEY UPDATE `agree` = 1, `oppose` = 0;";
					else if(strcmp($_POST["type"], "oppose") === 0) // oppose`
						$query = "INSERT INTO `comment_react` (`iid`, `uid`, `cid`, `agree`, `oppose`) VALUES (:iid, :uid, :cid, 0, 1) ON DUPLICATE KEY UPDATE `agree` = 0, `oppose` = 1;";
					else
						$query = "INSERT INTO `comment_react` (`iid`, `uid`, `cid`, `agree`, `oppose`) VALUES (:iid, :uid, :cid, 0, 0) ON DUPLICATE KEY UPDATE `agree` = 0, `oppose` = 0;";
					$moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("uid"=>$_POST["uid"], "iid"=>$_POST["iid"], "cid"=>$_POST["cid"]));
					
					$query = "CALL `get_a_comment_reactions`(:cid, :iid);";
					$result = $moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("cid"=>$_POST["cid"],"iid"=>$_POST["iid"]));
					$row = $result->fetch();
					echo intval($row['agree_num']).",".intval($row['oppose_num']);
				}
			}
		}
	}
?>