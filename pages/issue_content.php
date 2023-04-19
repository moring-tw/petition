<?php 
	global $moring_mysql;
	$query = "SELECT XC.*, IFNULL(reactions.agree_num, 0) AS agree_num, IFNULL(reactions.oppose_num, 0) AS oppose_num FROM ".
				"(SELECT OC.id, OC.title, OC.content, OC.`manager_reply`, OC.`date`, OC.user_id, OC.hidden, OC.abbreviate FROM ".
					"(SELECT * FROM `petitio1_ntnu`.`issue` AS A ".
				"INNER JOIN ".
					"`petitio1_ntnu`.`issue_types` AS B ".
				"ON A.type_id = B.issue_type_id ".
				"WHERE A.`id` = :iid". //remove this line to query all issues
				") AS OC) AS XC ".
			"LEFT JOIN ".
				"(SELECT * FROM (".
					"(SELECT IFNULL(A.issue_id, O.issue_id) AS issue_id, IFNULL(A.agree_num, 0) AS agree_num, IFNULL(O.oppose_num, 0) as oppose_num FROM ".
						"((SELECT COUNT(*) AS agree_num, issue_id FROM `petitio1_ntnu`.`react` WHERE agree = 1 ". 
						"AND `issue_id` = :iid ". //remove this line to query all issues
						"GROUP BY issue_id) AS A ".
					"LEFT JOIN ".
						"(SELECT COUNT(*) AS oppose_num, issue_id FROM `petitio1_ntnu`.`react` WHERE oppose = 1 ".
						"AND `issue_id` = :iid ". //remove this line to query all issues
						"GROUP BY issue_id) AS O ".
					"ON A.`issue_id` = O.`issue_id`)) ".
				"UNION ALL ".
					"(SELECT IFNULL(A.issue_id, O.issue_id) AS issue_id, IFNULL(A.agree_num, 0) AS agree_num, IFNULL(O.oppose_num, 0) as oppose_num FROM ".
						"((SELECT COUNT(*) AS agree_num, issue_id FROM `petitio1_ntnu`.`react` WHERE agree = 1 ". 
						"AND `issue_id` = :iid ". //remove this line to query all issues
						"GROUP BY issue_id) AS A ".
					"RIGHT JOIN ".
						"(SELECT COUNT(*) AS oppose_num, issue_id FROM `petitio1_ntnu`.`react` WHERE oppose = 1 ". 
						"AND `issue_id` = :iid ". //remove this line to query all issues
						"GROUP BY issue_id) AS O ".
					"ON O.`issue_id` = A.`issue_id`) WHERE A.issue_id IS NULL)".
				") AS reactions) AS reactions ".
			"ON XC.id = reactions.issue_id".
			";";
	$result = $moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("iid"=>intval($path[2])));
	if($result->rowCount() == 1){
		$issue_data = $result->fetch();
		if(intval($issue_data['hidden']) === 1){
			Header("Location: "."/petition/noissue/");
			exit();
		}
	} else {
		Header("Location: "."/petition/noissue/");
		exit();
	}
	
	if(isset($_POST['reaction'])){ // normal user react to this article
		if(strcmp($_POST['reaction'], "agree") === 0){
			$query = "INSERT INTO `petitio1_ntnu`.`react` (`issue_id`, `user_id`, `agree`, `oppose`) VALUES (:iid, :uid, 1, 0) ON DUPLICATE KEY UPDATE `agree` = 1, `oppose` = 0;";
		} else if(strcmp($_POST['reaction'], "disagree") === 0 || strcmp($_POST['reaction'], "disoppose") === 0){
			$query = "INSERT INTO `petitio1_ntnu`.`react` (`issue_id`, `user_id`, `agree`, `oppose`) VALUES (:iid, :uid, 0, 0) ON DUPLICATE KEY UPDATE `agree` = 0, `oppose` = 0;";
		} else if(strcmp($_POST['reaction'], "oppose") === 0){
			$query = "INSERT INTO `petitio1_ntnu`.`react` (`issue_id`, `user_id`, `agree`, `oppose`) VALUES (:iid, :uid, 0, 1) ON DUPLICATE KEY UPDATE `agree` = 0, `oppose` = 1;";
		}
		$moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("iid"=>intval($path[2]), "uid"=>$_SESSION["UserInfo"]["id"]));
		header("Refresh:0");
	}
	$agree = 0;
	$oppose = 0;
	$query = "SELECT `agree`, `oppose` FROM `petitio1_ntnu`.`react` WHERE `issue_id` = :iid AND `user_id` = :uid ;";
	$result = $moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("iid"=>intval($path[2]), "uid"=>$_SESSION["UserInfo"]["id"]));
	if($result->rowCount() == 1){
		$row = $result->fetch();
		$agree = $row["agree"];
		$oppose = $row["oppose"];
	}
	
	if(isset($_POST['manager_reply']) && isset($_SESSION["UserInfo"]["permission"]) && $_SESSION["UserInfo"]["permission"] = 1){
		$query = "UPDATE `petitio1_ntnu`.`issue` SET `manager_reply` = :manager_reply, `manager_id` = :mid WHERE `id` = :iid;";
		$moring_mysql->Moring_MySQL_Normal_User_Query($query, Array("manager_reply"=>$_POST["manager_reply"], "iid"=>$_POST["issue_id"], "mid"=>$_SESSION["UserInfo"]["id"]));
		header("Refresh:0");
	}
	
	if(isset($_POST['student_reply'])){
		create_comment($path[2], $_SESSION['UserInfo']['id'], $_POST['student_reply']);
	}
?>
<div id="issue">
	<div class="petition-option">
		<div class="issue-type"><?=$issue_data['abbreviate']?></div>
		<div class="issue-abstract">
			<div class="issue-title"><?=$issue_data['title']?></div>
			<div class="issue-result">
				<div class="result">
					<div class="red-text">❤️</div><?=$issue_data['agree_num']?><div class="lime-text">💔</div><?=$issue_data['oppose_num']?>
				</div>
				<div class="upload-time"><?=$issue_data['date']?></div>
			</div>
		</div>
	</div>
	<div class="margin-bar"></div>
	<div id="issue-content">
		<div class="light-blue a16px bold">提案內容：</div>
		<div class="margin-bar"></div>
		<?=$issue_data['content']?>
		
		<?php if(isset($issue_data['manager_reply']) && strlen($issue_data['manager_reply']) !== 0) { ?>
			<div class="light-blue a16px bold">經理回覆：</div>
			<div class="margin-bar"></div>
			<?=$issue_data['manager_reply']?>
		<?php } ?>
		
	</div>
	<?php if(isset($_SESSION["UserInfo"]) && $_SESSION["UserInfo"]["permission"] !== 1){ // 1 means manager?> 
		<div id="issue-vote">
			<div class="vbtn-container">
				<form action="" method="POST">
					<input type="submit" value="我贊成" class="vbtn btn btn-primary <?=$agree?"issue-reacted":""?>">
					<input type="hidden" name="reaction" value="<?=$agree?"dis":""?>agree" />
				</form>
			</div>
			<div class="vbtn-container">
				<form action="" method="POST">
					<input type="submit" value="我反對" class="vbtn btn btn-primary <?=$oppose?"issue-reacted":""?>">
					<input type="hidden" name="reaction" value="<?=$oppose?"dis":""?>oppose" />
				</form>
			</div>
		</div>
	<?php } elseif(isset($_SESSION["UserInfo"]) && $_SESSION["UserInfo"]["permission"] === 1) { ?>
		<div id="manger-reply">
		經理回應區：
			<form action="" method="POST">
				<input type="hidden" name="issue_id" value="<?=$path[2] ?>" />
				<textarea class="editor" name="manager_reply" id="in_textarea">
              		<?php 
  						if(isset($issue_data['manager_reply']) && strlen($issue_data['manager_reply']) !== 0) { 
                  			echo $issue_data['manager_reply'];
                        }
                  	?>
              	</textarea>
				<?php
					echo "<input type='hidden' name='user_id' value='".$_SESSION["UserInfo"]["id"]."'>";
				?>
				<input type="submit" value="Post" id="in_submit" class="btn btn-primary">
			</form>
		</div>
		<script>
			$(".editor").jqte();
		</script>
	<?php } ?>
	
</div>

<div id="student-reply">
  <?php if(isset($_SESSION["UserInfo"]) && $_SESSION["UserInfo"]["permission"] !== 1){ // 1 means manager?> 
	<form action="" method="POST">
		<input type="hidden" name="issue_id" value="<?=$path[2] ?>" />
		<textarea class="comment-editor" name="student_reply" id="comment_textarea" placeholder="留個言吧，leave a message"></textarea>
		<?php
			echo "<input type='hidden' name='user_id' value='".$_SESSION["UserInfo"]["id"]."'>";
		?>
		<input type="submit" value="Post" id="comment_submit" class="btn btn-primary btn-fleft">
	</form>
	<?php 
    }
	$result = get_all_comments(intval($path[2]));
	$user_reaction_array = get_user_reactions_in_an_issue_comments($_SESSION['UserInfo']['id'], $path[2]);
	while($row = $result->fetch()){
		if(intval($row['hidden']) === 0){
	?>
	<div class="reply">
		<div class="comment-data light-blue">
		
			<span class="user_name"><?=$row['user_acc']?></span>
			<span class="date"><?=$row['datetime']?></span>
			
			<form action="" method="POST" class="inline-form" id="oppose_c<?=$row['cid']?>">
			
				<span class="comment-react <?=isset($user_reaction_array[$row['cid']."oppose"]) && $user_reaction_array[$row['cid']."oppose"] === 1 ?"comment-reacted":""?>">
				👎<?=isset($row['oppose_num'])?$row['oppose_num']:"0"?>
				</span>
				<input type="hidden" name="cid" value="<?=$row['cid']?>" />
				<input type="hidden" name="comment-react" value="<?=isset($user_reaction_array[$row['cid']."oppose"]) && $user_reaction_array[$row['cid']."oppose"] === 1 ?"dis":""?>oppose" />
			</form>
				
			<form action="" method="POST" class="inline-form" id="agree_c<?=$row['cid']?>">
				<span class="comment-react <?=isset($user_reaction_array[$row['cid']."agree"]) && $user_reaction_array[$row['cid']."agree"] === 1 ?"comment-reacted":""?>">
				👍<?=isset($row['agree_num'])?$row['agree_num']:"0"?></span>
				
				<input type="hidden" name="cid" value="<?=$row['cid']?>" />
				<input type="hidden" name="comment-react" value="<?=isset($user_reaction_array[$row['cid']."agree"]) && $user_reaction_array[$row['cid']."agree"] === 1 ?"dis":""?>agree" />
			</form>
		</div>
		<div class="comment_content"><?=$row['comment']?></div>
	</div>
	
	<?php
		}
	}
	$result->closeCursor();
	?>
</div>
	
	
<script>
	// set div#issue height equal to content
	$(document).ready(function(){
		$('#issue').height($('#content').height()+$('#footer').height());
	});
	$(document).ready(function(){
		$(".comment-react").each(function(){
			$(this).on("click", function(){
				var t = this;
				var cid = $(this).siblings("input[name='cid']").first().val();
				var type = $(this).siblings("input[name='comment-react']").first().val();
				console.log(t);
				$.ajax({
					method: "POST",
					url:'api/react-a-comment.php',
					data: {
						cid:cid,
						iid:"<?=$path[2]?>",
						uid:"<?=$_SESSION['UserInfo']['id']?>", 
						type:type
						}
				}).done(function(msg, textStatus, jqXHR){;
					const res = msg.split(',');
					console.log(msg);
					console.log($("#agree_c"+cid+" span").first().html());
					$("#agree_c"+cid+" span").first().html("👍"+res[0]);
					$("#oppose_c"+cid+" span").first().html("👎"+res[1]);
					if(type == "agree"){
						$("#agree_c"+cid+" input[name='comment-react']").first().val("disagree");
						$("#agree_c"+cid+" span").first().addClass("comment-reacted");
						$("#oppose_c"+cid+" input[name='comment-react']").first().val("oppose");
						$("#oppose_c"+cid+" span").first().removeClass("comment-reacted");
					} else if(type == "oppose"){
						$("#oppose_c"+cid+" input[name='comment-react']").first().val("disoppose");
						$("#oppose_c"+cid+" span").first().addClass("comment-reacted");
						$("#agree_c"+cid+" input[name='comment-react']").first().val("agree");
						$("#agree_c"+cid+" span").first().removeClass("comment-reacted");
					} else if(type == "disagree"){
						$("#agree_c"+cid+" span").first().removeClass("comment-reacted");
						$("#agree_c"+cid+" input[name='comment-react']").first().val("agree");
						$("#oppose_c"+cid+" input[name='comment-react']").first().val("oppose");
					} else if(type == "disoppose"){
						$("#oppose_c"+cid+" span").first().removeClass("comment-reacted");
						$("#oppose_c"+cid+" input[name='comment-react']").first().val("oppose");
						$("#agree_c"+cid+" input[name='comment-react']").first().val("agree");
					}
				});
			});
		});
	});
</script>



















