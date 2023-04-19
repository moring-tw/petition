<?php
function get_all_comments($iid){
	global $moring_mysql;
	$query = "CALL `get_comments_by_issue_id`(:iid)";
	$result = $moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("iid"=>$iid));
	return $result;
}

function get_user_reactions_in_an_issue_comments($uid, $iid){
	global $moring_mysql;
	$query = "SELECT * FROM `comment_react` WHERE `uid` = :uid AND `iid` = :iid;";
	$result = $moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("uid"=>$uid, "iid"=>$iid));
	$array = Array();
	while($row = $result->fetch()){
		$array[$row['cid']."agree"] = $row['agree'];
		$array[$row['cid']."oppose"] = $row['oppose'];
	}
	return $array;
}
?>