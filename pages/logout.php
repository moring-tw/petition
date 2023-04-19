<?PHP
	unset($_SESSION['UserInfo']);
	Header("Location: "."/petition/");
	exit();
	//not complete, see https://stackoverflow.com/questions/768431/how-do-i-make-a-redirect-in-php
?>