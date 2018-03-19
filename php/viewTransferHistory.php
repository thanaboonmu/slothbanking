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
	
	if ($action == "getTransferHistory") {
		$response = gettransferHistory();
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
	function getTransferHistory() {
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$response["userAccountInfo"] = array();
		$result = mysqli_query($con,"SELECT * FROM TransferHistory");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["transferHistory"][] = $row;
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
		if(!mysqli_query($con,"DELETE FROM TransferHistory WHERE transferNo= '".$_POST['transferNo']."'  ")) {
			$response["response"] = false;
			$response["message"] = "Error " . mysqli_error($con);
		}
		$response["transferHistory"] = array();
		$result = mysqli_query($con,"SELECT * FROM TransferHistory");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["transferHistory"][] = $row;
		}
		return $response;
	}

?>


