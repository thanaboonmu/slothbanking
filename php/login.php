<?php
	session_start();
	include("connectDB.php");
	
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	}
	else {
		$response["response"] = false;
		$response["message"] = "Action not found";
	}
	if ($action == "signin") {
		$response = signin();
	}
	else if ($action == "signup") {
		$response = signup();
	}
	else if ($action == "signout") {
		$response = signout();
	}
	else if ($action == "checkStatus") {
		$response = checkstatus();
	}
	else {
		$response["response"] = false;
		$response["message"] = "Action not found";
	}
	echo json_encode($response);

	// SIGN IN
	function signin() {
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$username = mysqli_real_escape_string($con,$_POST['username']);
	    $password = mysqli_real_escape_string($con,$_POST['password']);
		$sql = "SELECT DISTINCT position,available FROM UserAccount WHERE username = '$username' AND password = '$password' ";
		if (!mysqli_query($con,$sql)) {
			$response["response"] = false;
			$response["message"] = "Error" . mysqli_error($con);
			mysqli_close($con);
			return $response;
		} 
		$result = mysqli_query($con,$sql);
		$count = mysqli_num_rows($result);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$available = $row['available'];
		if ($count >= 1 && $available == 1) {
			$position = $row['position'];
	    	mysqli_close($con);
	    	$_SESSION['login_username'] = $username;
			$_SESSION['login_position'] = $position;
			$response["position"] = $position;
			$response["response"] = true;
			$response["message"] = "Sign in successfully";
		}
		else if ($count >= 1 & $available == 0) {
			mysqli_close($con);
			$response["response"] = false;
			$response["message"] = "Your online account has been suspended";
		}
		else {
			mysqli_close($con);
			$response["response"] = false;
			$response["message"] = "Your username or password is invalid";
		}
		return $response;
	}

	// SIGN UP
	function signup() {
	 	$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$username = mysqli_real_escape_string($con,$_POST['username']);
		$password = mysqli_real_escape_string($con,$_POST['password']);
		$accountNo = mysqli_real_escape_string($con,$_POST['accountNo']);
		$atmNo = mysqli_real_escape_string($con,$_POST['atmNo']);
		$atmPassword = mysqli_real_escape_string($con,$_POST['atmPassword']);
		$firstName = mysqli_real_escape_string($con,$_POST['firstName']);
		$lastName = mysqli_real_escape_string($con,$_POST['lastName']);
		$position = mysqli_real_escape_string($con,$_POST['position']);
		$companyName = mysqli_real_escape_string($con,$_POST['companyName']);
		$identificationNo = mysqli_real_escape_string($con,$_POST['identificationNo']);
		$birthDate = mysqli_real_escape_string($con,$_POST['birthDate']);
		
		if ($username == "" || $password == "" || $accountNo == "" || $atmNo == "" || $atmPassword == "" ||
			$firstName == "" || $lastName == "" || $position == "" || $identificationNo == "" || $birthDate == "") {
			mysqli_close($con);
			$response["response"] = false;
			$response["message"] = "Some required field is missing! Please input every required field";
			return $response;
		}
		if ($position != 'company' && $companyName != "") {
			mysqli_close($con);
			$response["response"] = false;
			$response["message"] = "You aren't company ! Please don't input companyName";
			return $response;
		}
		if ($position == 'company' && $companyName == "") {
			mysqli_close($con);
			$response["response"] = false;
			$response["message"] = "You are company ! Please input companyName";
			return $response;
		}

		$sql ="SELECT username FROM UserAccount WHERE username = '$username' ";
		if (!mysqli_query($con,$sql)) {
			$response["response"] = false;
			$response["message"] = "Error" . mysqli_error($con);
			mysqli_close($con);
			return $response;
		}
		$result = mysqli_query($con,$sql);
		$count = mysqli_num_rows($result);
		if ($count != 0) {
			mysqli_close($con);
			$response["response"] = false;
			$response["message"] = "This username is already existed";
			return $response;
		}

		$sql = "SELECT accountNo FROM UserAccountInfo WHERE accountNo = '$accountNo' AND atmNo = '$atmNo' AND atmPassword = '$atmPassword'
		AND firstName = '$firstName' AND lastName = '$lastName' AND identificationNo = '$identificationNo' AND birthDate = '$birthDate' ";
		if (!mysqli_query($con,$sql)) {
			$response["response"] = false;
			$response["message"] = "Error" . mysqli_error($con);
			mysqli_close($con);
			return $response;
		}
		$result = mysqli_query($con,$sql);
		$count = mysqli_num_rows($result);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

		if ($count == 1) {
			$accountNo = $row['accountNo'];
			if ($companyName == "") {
				$companyName = null;
				$sql = "INSERT INTO UserAccount VALUES('$username','$password','$accountNo','$position',null,1)";
			} else {
				$sql = "INSERT INTO UserAccount VALUES('$username','$password','$accountNo','$position','$companyName',1)";
			}
			if (!mysqli_query($con,$sql)) {
				$response["response"] = false;
				$response["message"] = "Error" . mysqli_error($con);
				mysqli_close($con);
				return $response;
			} 
			$response["response"] = true;
			$response["message"] = "SUCCESS , YOUR ACCOUNT IS REGISTRATED";
		}
		else {
			$response["response"] = false;
			$response["message"] = "ERROR, YOUR INFORMATION DOESN'T MATCH ANY ENTIRED ACCOUNT";
		}
		mysqli_close($con);
		return $response;
	}
	
	// SIGN OUT
	function signout() {
		session_unset();
	    if(session_destroy()) {
	   		$response["response"] = true;
	   		$response["message"] = "You have signed out";
	    }
	    $reponseOfCheckStatus = checkStatus();
	    if ($reponseOfCheckStatus["response"] == true) {
	    	$response["response"] = false;
	   		$response["message"] = "Error, There's still a session";
	    }
	    return $response;
	}

	// CHECK STATUS 
	function checkStatus() {
		if (!isset($_SESSION['login_username'])) {
			$response["response"] = false;
      		$response["message"] = "No session found";
			return $response;
		}
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$check_username = $_SESSION['login_username'];
		$result = mysqli_query($con,"SELECT DISTINCT username FROM UserAccount WHERE username = '$check_username' ");
		$count = mysqli_num_rows($result);
		if($count == 1) {
      		$response["response"] = true;
      		$user["username"] = $_SESSION['login_username'];
			$user["position"] = $_SESSION['login_position'];
			$response["user"] = $user;
      		$response["message"] = "A session found";
   		}
   		else {
   			$response["response"] = false;
      		$response["message"] = "No session found";
   		}
   		return $response;
	}

?>


