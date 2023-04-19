<?php
	function create_comment($iid, $uid, $comment){
		global $moring_mysql;
		$query = "INSERT INTO `issue_comment` (`iid`,`uid`,`comment`,`datetime`) VALUES (:iid, :uid, :comment, :datetime);";
		$moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("uid"=>$uid, "iid"=>$iid, "comment"=>$comment, "datetime"=>date("Y/m/d H:i:s")));
	}
?>