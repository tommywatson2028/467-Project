<?php
//start a session
session_start();

//login handler should only work if it was accessed by posting the form from the login page
//otherwise, send to login
if($_SERVER["REQUEST_METHOD"] == "POST") {
	//connect to database
	include '../shared/setConnectionParams.php';
	try { // if something goes wrong, an exception is thrown
		$pdo = new PDO($dsn, $username, $password);
	}
	catch(PDOException $e) { // handle that exception
		echo "Connection to database failed: " . $e->getMessage();
	}
} else {
    // if not accessed via POST, redirect to login page
    header('Location: ../web-login/loginPage.php');
    exit;
}

//read login info
$usernameInput = $_POST['Username'];
$passwordInput = $_POST['Password'];

	//check if login info is in the database
	//if exists, handle the login and send the user to the right page
	//if not, send user back to login
	// check if username exists in the database
    $sql = "SELECT * FROM EMPLOYEE WHERE Username = :Username";

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
	    $_SESSION['IsAdmin'] = (bool)$row['IsAdmin'];
	    $_SESSION['FirstName'] = $row['FirstName'];

            // send employee to dashboard
            header('Location: ../web-login/employeeDashboard.php');
            exit;

        } else {
            // incorrect password
            header('Location: ../web-login/loginPage.php?failed=1');
            exit;
        }

    } else {
        // username does not exist
        header('Location: ../web-login/loginPage.php?failed=1');
        exit;
    }
?>
