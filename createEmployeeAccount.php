	<?php
		// Read employee info from form submission
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$firstName = trim($_POST['First_Name'] ?? '');
			$lastName = trim($_POST['Last_Name'] ?? '');
			$email = trim($_POST['Email'] ?? '');
			$usernameInput = trim($_POST['Username'] ?? '');
			$passwordInput =($_POST['Password'] ?? '');

			// Validate required fields
    		if (
    			$firstName === '' ||
        		$lastName === '' ||
        		$email === '' ||
        		$usernameInput === '' ||
        		$passwordInput === ''
    		) {
        		$message = "All fields are required.";
    		
			}	
			else {
				//connect to database
				include 'set_connection_params.php';
				try {
					$pdo = new PDO($dsn, $username, $password);
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}
				catch(PDOException $e) {
					error_log("Connection failed: " . $e->getMessage());
					die("Unable to connect to database");
				}
		
				//check if username already exists
				$sql = "SELECT EmpID FROM EMPLOYEE WHERE Username = :Username OR Email = :Email";
				$result = $pdo->prepare($sql);
        		$result->execute([':Username' => $usernameInput,':Email' => $email]);
		

				if ($result->fetch()) {
					$message = "Username or email already exists.";
				}
				else {
					// Hash the employee's password
					$hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);
			

					// Insert the new employee into the database
					$sql = "INSERT INTO EMPLOYEE (First_Name, Last_Name, Email, Username, Password) 
							VALUES (:First_Name, :Last_Name, :Email, :Username, :Password)";

							$prepared = $pdo->prepare($sql);
							$prepared->execute([
								':First_Name' => $firstName,
								':Last_Name' => $lastName,
								':Email' => $email,
								':Username' => $usernameInput,
								':Password' => $hashedPassword
						]);

						$message = "Employee account created successfully!";
				
				}		
			}
		}	
?>