<div class="center-content">
	<div class="center-table-cell">
		<div class="mo-login-form">
			<h4>
				Create<br>Account
			</h4>
			<form action="" method="POST">
				<div class="form-group" id="mo-div-pwd"><input id="pass1" type="text" name="new_account" placeholder="Enter new account" class="form-control mo-login-com"></div>
				<div class="form-group" id="mo-div-pwd"><input id="pass1" type="password" name="new_pwd" placeholder="Enter new password" class="form-control mo-login-com"></div>
				<div class="form-group" id="mo-div-pwd2"><input id="pass2" type="password" name="confirm_pwd" placeholder="Enter new password again" class="form-control mo-login-com"></div>
				
				<select name="permission">
					<option value="0">請選擇權限</option>
					<?php
						while($permission = $user_permissions->fetch()){
							if(isset($_POST['permission']) && intval($_POST['permission']) === $permission["value"])
								echo "<option value='".$permission["value"]."' selected='selected'>".$permission["name"]."</option>";
							else
								echo "<option value='".$permission["value"]."'>".$permission["name"]."</option>";
						}
					?>
				</select>
				<input type="submit" value="Active" class="btn btn-primary">
			</form>
		</div>
	</div>
</div>