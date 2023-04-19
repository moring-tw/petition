<div id="center-content" class="center-content">
	<div class="center-table-cell">
		<div class="mo-login-form">
			<h4>
				Active<br>Account
			</h4>
			<form action="" method="POST">
				<input type="hidden" name="Account" value="<?=$_SESSION["UserInfo"]["account"]?>">
				<div class="form-group" id="mo-div-oldpwd"><input id="oldpass" type="password" name="old_pwd" placeholder="Enter old password" class="form-control mo-login-com"></div>
				<div class="form-group" id="mo-div-pwd"><input id="pass1" type="password" name="Password1" placeholder="Enter new password" class="form-control mo-login-com"></div>
				<div class="form-group" id="mo-div-pwd2"><input id="pass2" type="password" name="Password2" placeholder="Enter new password again" class="form-control mo-login-com"></div>
				<input type="submit" value="Active" class="btn btn-primary">
			</form>
		</div>
	</div>
</div>