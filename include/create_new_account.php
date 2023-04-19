<?php
	global $moring_mysql;
    if(isset($_POST["new_account"])){
      $query = "SELECT * FROM `petitio1_ntnu`.`ntnu_user` WHERE `user_acc` = :acc;";
      $result = $moring_mysql->Moring_MySQL_Query($query, Array("acc"=>$_POST["new_account"]));
      if($result->rowCount() === 0){
        if(isset($_POST["new_pwd"])){
          if(isset($_POST["confirm_pwd"])){
            if($_POST["new_pwd"] === $_POST["confirm_pwd"]){ //
              $options = ['cost' => 13];
              $newhash = password_hash($_POST["new_pwd"], PASSWORD_DEFAULT, $options);
              $query = "INSERT INTO `petitio1_ntnu`.`ntnu_user` (`user_acc`, `user_pwd`,`permission`) VALUES (:acc, :pwd, :permission);";
              $moring_mysql->Moring_MySQL_Query($query, Array("acc"=>$_POST["new_account"], "pwd"=>$newhash,"permission"=>$_POST["permission"]));
            }
          }
        }
      }
    }
	$query = "SELECT * FROM `petitio1_ntnu`.`user_permission`;";
	$user_permissions = $moring_mysql->Moring_MySQL_Query($query);
?>