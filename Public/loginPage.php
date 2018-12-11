<?php session_start();
    $_SESSION['Login'] = NULL ;
    $_SESSION['adminAccess'] = false;
	require_once("../Private/functions.php");
	require_once("../Private/loginClass.php");
	require_once("../Private/memberClass.php");
	require_once("../Private/violationClass.php");
	restart();
	$updateBans = new Ban();
	$updateBans->updateBans();
	$violation = new Violation();
	$violation->clearViolations();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Login Page</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/design.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body id = "loginPage">
		<div class="container" id="loginForm">
			<h2 id = "title">Computer Gaming Society</h2>
			<img id = "logo" src="resources/joystick.png" alt ="controller" width="100" height="100"/>
			<?php tryLogin();?>
			<form method ="post" class="form-horizontal">
				<ul id="loginform">
					<li><input required type="email" id="email" style="border:none; height: 35px; padding-left:10px;"  placeholder="Enter email" name="email"></li>
					<li><input required type="password"id="pwd" style="border:none ;height: 35px; padding-left:10px;" placeholder="Enter password" name="pwd"></li>
					<li><input type="submit" id="login" class="btn btn-primary" style="border:none;" name="login" value="Login"></li>
					<li><a href="register.php" id="register" class="btn btn-primary" style="border:none;">Register</a></li>
				</ul>
			</form>
		</div>
	</body>
</html>




