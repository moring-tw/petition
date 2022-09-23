<?PHP
	//https://stackoverflow.com/questions/41969122/when-do-i-need-to-use-password-needs-rehash
	if(isset($_SESSION["UserInfo"])){
		Header("Location: "."/petition/");
		exit();
	}
	if(isset($_POST["Account"]) && isset($_POST["Password"])){
		global $moring_mysql;
		$query = "SELECT * FROM `moring`.`moring_user` WHERE `account` = :account ;";
		if(isset($_POST["Account"]))
			$result = $moring_mysql->Moring_MySQL_Query($query, Array('account' => $_POST["Account"]));
		else{
			echo "發生未知的錯誤";
			die();
		}
		if($result->rowCount() == 0){ // account doesn't exist
			echo "<script>$(function(){changeInputTextToError(\"div[id='mo-div-acc']\")});</script>";
			exit();
		}
		else if($result->rowCount() == 1){
			$row = $result->fetch();
			if(password_verify($_POST["Password"],$row[2])){
				$_SESSION["UserInfo"] = array(
					"account" => $row[1],
					"E_name" => $row[3],
					"C_name" => $row[4],
					"group" => $row[8],
					"level" => $row[9]
				);
				if(password_needs_rehash($data['hash'], PASSWORD_DEFAULT, $options = ['cost' => 13])){
					$newhash = password_hash($_POST["Password"], PASSWORD_DEFAULT, $options);
					// store new hash in db.
					$query = "UPDATE `moring`.`moring_user` SET `password` = :newhash WHERE `account` = :account;";
					$moring_mysql->Moring_MySQL_Query($query, Array("newhash"=>$newhash,"account"=> $_POST["Account"]));
				}
				Header("Location: "."/stock/");
				exit();
			}
			else
				echo "<script>$(function(){changeInputTextToError(\"div[id='mo-div-pwd']\")});</script>";
				exit();
		}
		else{
			echo "發生未知的錯誤";
			die();
		}
	}
?>