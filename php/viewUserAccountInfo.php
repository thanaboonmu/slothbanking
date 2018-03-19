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
	
	if ($action == "getUserAccountInfo") {
		$response = getUserAccountInfo();
	}
	else if ($action == "editRecord") {
		$response = editRecord();
	}
	else if ($action == "deleteRecord") {
		$response = deleteRecord();
	}
	else {
		$response["response"] = false;
		$response["message"] = "Action not found";
	}
	
	echo json_encode($response);


	// Get userAccount
	function getUserAccountInfo() {
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$response["userAccountInfo"] = array();
		$result = mysqli_query($con,"SELECT * FROM UserAccountInfo");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["userAccountInfo"][] = $row;
		}
		return $response;
	}

	// Update a record
	function editRecord() {
		$con = connectDB();
		$response["response"] = true;
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}

		$sqlUpdate = "UPDATE UserAccountInfo SET branchNo = '".$_POST['branchNo']."' , accountType = '".$_POST['accountType']."'
						WHERE accountNo= '".$_POST['accountNo']."'  ";
		
		if(!mysqli_query($con, $sqlUpdate)) {
			$response["response"] = false;
			$response["message"] = "Error" . mysqli_error($con);
		}
		$response["userAccountInfo"] = array();
		$result = mysqli_query($con,"SELECT * FROM UserAccountInfo");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["userAccountInfo"][] = $row;
		}
		return $response;
	}

	// Delete a record
	function deleteRecord() {
		$con = connectDB();
		$response["response"] = true;
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		if(!mysqli_query($con,"DELETE FROM UserAccountInfo WHERE accountNo= '".$_POST['accountNo']."'  ")) {
			$response["response"] = false;
			$response["message"] = "Error " . mysqli_error($con);
		}
		$response["userAccountInfo"] = array();
		$result = mysqli_query($con,"SELECT * FROM UserAccountInfo");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["userAccountInfo"][] = $row;
		}
		return $response;
	}

?>


