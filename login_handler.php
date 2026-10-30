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
	$username_input = $_POST['username'];
	$userpass = $_POST['userpass'];

	//check if login info is in the database
	//if exists, handle the login and send the user to the right page
	//if not, send user back to login
	// check if username exists in the database
    $sql = "SELECT * FROM Users WHERE USERNAME = :username";

    $result = $pdo->prepare($sql);
    $result->execute([':username' => $username_input]);

    // retrieve the matching user
    if ($row = $result->fetch(PDO::FETCH_ASSOC)) {

        // verify the submitted password
        if (password_verify($userpass, $row['USER_PASS'])) {

            // regenerate session ID after successful login
            session_regenerate_id(true);

            // store employee information in the session
            $_SESSION['useridx'] = $row['USER_ID'];
            $_SESSION['is_admin'] = (bool)$row['IS_ADMIN'];

            // send employee to dashboard
            header('Location: employee_dashboard.php');
            exit;

        } else {
            // incorrect password
            header('Location: login_page.php?failed=1');
            exit;
        }

    } else {
        // username does not exist
        header('Location: login_page.php?failed=1');
        exit;
    }
?>