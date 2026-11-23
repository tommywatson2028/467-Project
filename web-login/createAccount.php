<html>
    <head>
        <title>Create Account</title>
        <link rel="stylesheet" type="text/css" href="../shared/css/styles.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/loginStyles.css">
    </head>
    <body class="login-page">
        <div class="login-card">
            <img class="logo" src="../shared/images/NEMT_LOGO.png" alt="NEMT Auto Parts Logo">
            <h2>Create Account</h2>
            <form method="POST" action="../backend/createEmployeeAccount.php" class="login-form"> 
                <div class="form-group">
					<label>First name*</label><br>
					<input type="text" name="First_Name" class="input-field"><br>
				</div>
                <div class="form-group">
					<label>Last name*</label><br>
					<input type="text" name="Last_Name" class="input-field"><br>
				</div>
				<div class="form-group">
					<label>Email*</label><br>
					<input type="text" name="Email" class="input-field"><br>
				</div>
				<div class="form-group">
					<label>Username*</label><br>
					<input type="text" name="Username" class="input-field"><br>
				</div>
                <div class="form-group">
					<label>Password*</label><br>
					<input type="password" name="Password" class="input-field"><br>
				</div>
				<input type="submit" value="Create" class="login-btn"><br>
			</form>
            <div class="form-footer">
				<a href="loginPage.php">↩ Back</a>
			</div>
        </div>
    </body>
</html> 