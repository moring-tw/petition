
<?PHP
	//https://stackoverflow.com/questions/41969122/when-do-i-need-to-use-password-needs-rehash
	if(isset($_SESSION["UserInfo"])){
		Header("Location: "."/petition/");
		exit();
	}
	if(isset($_POST["Account"]) && isset($_POST["Password"])){
		global $moring_mysql;
		$query = "SELECT * FROM `petitio1_ntnu`.`ntnu_user` WHERE `user_acc` = :account ;";
		if(isset($_POST["Account"]))
			$result = $moring_mysql->Moring_MySQL_Normal_User_Query($query, Array('account' => $_POST["Account"]));
		else{
			echo "發生未知的錯誤";
			die();
		}
		if($result->rowCount() == 0){ // account doesn't exist
			//echo "<script>$(function(){changeInputTextToError(\"div[id='mo-div-acc']\")});</script>";
			echo "發生未知的錯誤";
			exit();
		}
		else if($result->rowCount() == 1){
			$row = $result->fetch();
			if(password_verify($_POST["Password"],$row[2])){
				$_SESSION["UserInfo"] = array(
					"id" => $row[0],
					"account" => $row[1],
					"activated" => $row[3],
					"permission" => $row[7]
				);
				if(password_needs_rehash($data['hash'], PASSWORD_DEFAULT, $options = ['cost' => 13])){
					$newhash = password_hash($_POST["Password"], PASSWORD_DEFAULT, $options);
					// store new hash in db.
					$query = "UPDATE `petitio1_ntnu`.`ntnu_user` SET `user_pwd` = :newhash WHERE `user_acc` = :account;";
					$moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("newhash"=>$newhash,"account"=> $_POST["Account"]));
				}
				$query = "UPDATE `petitio1_ntnu`.`ntnu_user` SET `last_ip` = :last_ip ,`last_login_time` = :datetime WHERE `user_acc` = :account;";
				date_default_timezone_set('Asia/Taipei');
				$moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("last_ip"=>$_SERVER['REMOTE_ADDR'], "datetime"=> date("Y/m/d H:i:s"), "account"=> $_POST["Account"]));
				
				Header("Location: "."/petition/");
				exit();
			}
			else{
				echo "密碼錯誤!";
				exit();
			}
		}
		else{
			echo "發生未知的錯誤";
			die();
		}
	}
?>