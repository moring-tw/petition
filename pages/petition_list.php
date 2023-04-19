<?php
global $moring_mysql;
$query = "SELECT XC.*, IFNULL(reactions.agree_num, 0) AS agree_num, IFNULL(reactions.oppose_num, 0) AS oppose_num FROM ".
			"(SELECT OC.id, OC.title, OC.`date`, OC.user_id, OC.hidden, OC.abbreviate FROM ".
				"(SELECT * FROM `petitio1_ntnu`.`issue` AS A ".
			"INNER JOIN ".
				"`petitio1_ntnu`.`issue_types` AS B ".
			"ON A.type_id = B.issue_type_id ".
			") AS OC) AS XC ".
		"LEFT JOIN ".
			"(SELECT * FROM (".
				"(SELECT IFNULL(A.issue_id, O.issue_id) AS issue_id, IFNULL(A.agree_num, 0) AS agree_num, IFNULL(O.oppose_num, 0) as oppose_num FROM ".
					"((SELECT COUNT(*) AS agree_num, issue_id FROM `petitio1_ntnu`.`react` WHERE agree = 1 ". 
					"GROUP BY issue_id) AS A ".
				"LEFT JOIN ".
					"(SELECT COUNT(*) AS oppose_num, issue_id FROM `petitio1_ntnu`.`react` WHERE oppose = 1 ".
					"GROUP BY issue_id) AS O ".
				"ON A.`issue_id` = O.`issue_id`)) ".
			"UNION ALL ".
				"(SELECT IFNULL(A.issue_id, O.issue_id) AS issue_id, IFNULL(A.agree_num, 0) AS agree_num, IFNULL(O.oppose_num, 0) as oppose_num FROM ".
					"((SELECT COUNT(*) AS agree_num, issue_id FROM `petitio1_ntnu`.`react` WHERE agree = 1 ". 
					"GROUP BY issue_id) AS A ".
				"RIGHT JOIN ".
					"(SELECT COUNT(*) AS oppose_num, issue_id FROM `petitio1_ntnu`.`react` WHERE oppose = 1 ". 
					"GROUP BY issue_id) AS O ".
				"ON O.`issue_id` = A.`issue_id`) WHERE A.issue_id IS NULL)".
			") AS reactions) AS reactions ".
		"ON XC.id = reactions.issue_id ".
		"ORDER BY XC.id DESC". //change this line for sort
		";";
$result = $moring_mysql->Moring_MySQL_Normal_User_Query($query);
for($counter = 0; $counter < $result->rowCount(); ++$counter){
	$row = $result->fetch();
	if(intval($row['hidden']) === 0){
?>
<a href="./<?=$row['id']?>/" class="issue-link">
<div class="petition-option">
	<div class="issue-type"><?=$row['abbreviate']?></div>
	<div class="issue-abstract">
		<div class="issue-title">
			<div class="issue-title"><?=$row['title']?></div>
			<?PHP 
			if(intval($row['user_id']) === intval($_SESSION["UserInfo"]["id"]) || intval($_SESSION["UserInfo"]["permission"]) === 99){
			?>
				<div class='issue-option'>
					<form method="POST" action="">
						<input type="hidden" name="iid" value="<?=$row['id']?>" />
						<button type="button" class="hidden_btn">X</button>
					</form>
				</div>
			<?php
			}
			?>
		</div>
	<div class="issue-result">
		<div class="result">
			<div class="red-text">❤️</div><?=$row['agree_num']?><div class="lime-text">💔</div><?=$row['oppose_num']?>
		</div>
		<div class="upload-time"><?=$row['date']?></div>
		</div>
	</div>

</div>
</a>


<?php 
	}
}
?>
<script src="js/hidden_issue.js?v=<?=time();?>"></script>