<div id="issue">
	<div class="light-blue a16px bold">	新增議題：</div>
	<form action="" method="POST" id="ci_form">
		<input type="text" name='title' id="in_title" placeholder="Enter title" value="<?php if(isset($_POST['title'])) echo $_POST['title'];?>" />
		<select name="type" id="in_select">
			<option value="0">請選擇議題類別</option>
			<?php
			 	while($type = $issue_types->fetch()){
					if(isset($_POST['type']) && intval($_POST['type']) === $type[0])
						echo "<option value='".$type[0]."' selected='selected'>".$type[1]."</option>";
					else
						echo "<option value='".$type[0]."'>".$type[1]."</option>";
				}
			?>
		</select>
		<textarea class="editor" name="text1" id="in_textarea" placeholder="Articles should be in here"><?php if(isset($_POST['text1'])) echo $_POST['text1']; ?></textarea>
		<?php
			echo "<input type='hidden' name='user_id' value='".$_SESSION["UserInfo"]["id"]."'>";
		?>
		<input type="submit" value="Post" id="in_submit" class="btn btn-primary">
	</form>

</div>

<script>
    $(".editor").jqte();
</script>