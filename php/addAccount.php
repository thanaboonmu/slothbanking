<?php
	session_start();
	include("connectDB.php");

	$response = addAccount();
	echo json_encode($response);

	function addAccount() {
	 	$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}

		$username = $_SESSION['login_username'];
		$accountNo = mysqli_real_escape_string($con,$_POST['accountNo']);
		$atmNo = mysqli_real_escape_string($con,$_POST['atmNo']);
		$atmPassword = mysqli_real_escape_string($con,$_POST['atmPassword']);
		$firstName = mysqli_real_escape_string($con,$_POST['firstName']);
		$lastName = mysqli_real_escape_string($con,$_POST['lastName']);
		$identificationNo = mysqli_real_escape_string($con,$_POST['identificationNo']);
		$birthDate = mysqli_real_escape_string($con,$_POST['birthDate']);
		
		if ($accountNo == "" || $atmNo == "" || $atmPassword == "" || $firstName == "" ||
			 $lastName == "" || $identificationNo == "" || $birthDate == "") {
			mysqli_close($con);
			$response["response"] = false;
			$response["message"] = "Some required field is missing! Please input every required field";
			return $response;
		}

		$sql ="SELECT password,position,companyName FROM UserAccount WHERE username = '$username' ";
		if (!mysqli_query($con,$sql)) {
			$response["response"] = false;
			$response["message"] = "Error" . mysqli_error($con);
			mysqli_close($con);
			return $response;
		}
		$result = mysqli_query($con,$sql);
		$count = mysqli_num_rows($result);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$password = $row['password'];
		$position = $row['position'];
		$companyName = $row['companyName'];
		
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
			if ($companyName == "" || $companyName == "null") {
				$companyName == "null";
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
			$response["message"] = "SUCCESS , YOUR ACCOUNT HAS BEEN ADDED";
		}
		else {
			$response["response"] = false;
			$response["message"] = "ERROR, YOUR INFORMATION DOESN'T MATCH ANY ENTIRED ACCOUNT";
		}
		mysqli_close($con);
		return $response;
	}
?>


