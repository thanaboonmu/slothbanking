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
	
	if ($action == "getAccountNo") {
		$response = getAccountNo();
	}
	else if ($action == "getBalance") {
		$response = getBalance();
	}
	else if ($action == "getHistory") {
		$response = getHistory();
	}
	else {
		$response["response"] = false;
		$response["message"] = "Action not found";
	}
	
	echo json_encode($response);

	// Get accountNo
	function getAccountNo() {
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$response["accountNo"] = array();
		$user = $_SESSION['login_username'];
		$result = mysqli_query($con,"SELECT accountNo FROM UserAccount WHERE username = '$user' ");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["accountNo"][] = $row['accountNo'];

		}
		return $response;
	}

	// Get current balance
	function getBalance() {
		$selectedAccount = $_POST['selectedAccount'];
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$result = mysqli_query($con,"SELECT balance FROM UserAccountInfo WHERE accountNo = '$selectedAccount' ");
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$response["balance"] = $row['balance'];
		return $response;
	}

	// Get history
	function getHistory() {
		$selectedAccount = $_POST['selectedAccount'];
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$response["history"] = array();
		$result = mysqli_query($con,"SELECT * FROM TransferHistory WHERE accountNo = '$selectedAccount' LIMIT 30 ");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["history"][] = $row;
		}
		return $response;
	}

?>


