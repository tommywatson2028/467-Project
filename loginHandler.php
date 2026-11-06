<?php
//start a session
session_start();

//login handler should only work if it was accessed by posting the form from the login page
//otherwise, send to login
if($_SERVER["REQUEST_METHOD"] == "POST"){
	//connect to database
	include 'set_connection_params.php';
	try { // if something goes wrong, an exception is thrown
		$pdo = new PDO($dsn, $username, $password);
	}
	catch(PDOException $e) { // handle that exception
		echo "Connection to database failed: " . $e->getMessage();
	}
	
	//read login info
	$usernameInput = $_POST['Username'];
	$passwordInput = $_POST['Password'];

	//check if login info is in the database
	//if exists, handle the login and send the user to the right page
	//if not, send user back to login
	// check if username exists in the database
    $sql = "SELECT * FROM Users WHERE USERNAME = :Username";

    $result = $pdo->prepare($sql);
    $result->execute([':Username' => $usernameInput]);

    // retrieve the matching user
    if ($row = $result->fetch(PDO::FETCH_ASSOC)) {

        // verify the submitted password
        if (password_verify($passwordInput, $row['Password'])) {

            // regenerate session ID after successful login
            session_regenerate_id(true);

            // store employee information in the session
            $_SESSION['EmpID'] = $row['EmpID'];
            $_SESSION['Is_Admin'] = (bool)$row['Is_Admin'];

            // send employee to dashboard
            header('Location: employeeDashboard.php');
            exit;

        } else {
            // incorrect password
            header('Location: loginPage.php?failed=1');
            exit;
        }

    } else {
        // username does not exist
        header('Location: loginPage.php?failed=1');
        exit;
    }
?>