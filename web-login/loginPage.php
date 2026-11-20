<?php
	// show error if login failed
	if(isset($_GET['failed'])){
		echo "<a class='error' href='loginPage.php'>Login attempt failed, please try again</a>";
	}
?>

<html>
	<head>
		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title> Login </title>
		<link rel="stylesheet" href="../shared/css/styles.css">
		<link rel="stylesheet" href="../shared/css/loginStyles.css">
	</head>
	
	<body class="login-page">
		
		<div class="login-card">
			<img class="logo" src="../shared/images/NEMT_LOGO.png" alt="NEMT Auto Parts Logo">
			<h2>Welcome back</h2>
			<form method="POST" action="../backend/loginHandler.php" class="login-form"> 
				<div class="form-group">
					<label>Username*</label><br>
					<input type="text" name="Username" class="input-field"><br>
				</div>

				<div class="form-group">
					<label>Password*</label><br>
					<input type="password" name="Password" class="input-field"><br>
				</div>

				<input type="submit" value="Login" class="login-btn"><br>
			</form>
			<div class="form-footer">
				<a href="createAccount.php">Create Account</a>
			</div>
		</div>
	</body>
</html>
