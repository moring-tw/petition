<?PHP 
	if(isset($_POST["Account"]) && isset($_POST["Password1"]) && isset($_POST["Password2"]) && isset($_POST['old_pwd'])){
      	if($_POST["Password1"] != ""){
          if(strcmp($_POST["Password1"], $_POST["Password2"])===0){
            $options = ['cost' => 13];
            $newhash = password_hash($_POST["Password1"], PASSWORD_DEFAULT, $options);
          }
          else {
            echo "<script> alert('密碼不一致。');</script>";
            header("Refresh:0");
            die();
          }
        } else {
          echo "<script> alert('密碼不得為空值。');</script>";
            header("Refresh:0");
          die();
        }
      
      
		if($_POST["Account"] === $_SESSION["UserInfo"]["account"]){
			global $moring_mysql;
          	$query = "SELECT * FROM `petitio1_ntnu`.`ntnu_user` WHERE `user_acc` = :account ;";
			$result = $moring_mysql->Moring_MySQL_Normal_User_Query($query, Array('account' => $_POST["Account"]));
          	if($result->rowCount() == 0){ // account doesn't exist
              //echo "<script>$(function(){changeInputTextToError(\"div[id='mo-div-acc']\")});</script>";
              echo "<script>alert('發生未知的錯誤');</script>";
            	header("Refresh:0");
              die();
			} else if($result->rowCount() == 1){
              	$row = $result->fetch();
				if(password_verify($_POST["old_pwd"],$row[2])){
                  $query = "UPDATE `petitio1_ntnu`.`ntnu_user` SET `user_pwd` = :password,`activate_ip` = :activate_ip ,`activated` = 1 WHERE `user_acc` = :account;";
                  $moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("password"=> $newhash, "activate_ip"=>$_SERVER['REMOTE_ADDR'], "account"=> $_POST["Account"]));

                  $_SESSION["UserInfo"]["activated"] = 1;
                  session_destroy();
                  Header("Location: "."/petition/login/");
                  exit();
                } else {
                  echo "<script> alert('舊密碼輸入錯誤。');</script>";
            header("Refresh:0");
                  die();
                }
            }
		}
	}
?>