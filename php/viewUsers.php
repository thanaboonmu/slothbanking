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
	
	if ($action == "getUserAccount") {
		$response = getUserAccount();
	}
	else if ($action == "editRecord") {
		$response = editRecord();
	}
	else if ($action == "deleteRecord") {
		$response = deleteRecord();
	}
	else if ($action == "getAnalysisReport") {
		$response = getAnalysisReport(); 
	}
	else {
		$response["response"] = false;
		$response["message"] = "Action not found";
	}
	
	echo json_encode($response);


	// Get userAccount
	function getUserAccount() {
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$response["userAccount"] = array();
		$result = mysqli_query($con,"SELECT username,accountNo,position,companyName,available FROM UserAccount");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["userAccount"][] = $row;
		}
		return $response;
	}

	// Update a record
	function editRecord() {
		$con = connectDB();
		$response["response"] = true;
		$response["message"] = "A row is updated";
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}

		$sqlUpdate = "UPDATE UserAccount SET available = '".$_POST['available']."' WHERE username= '".$_POST['username']."' AND accountNo= '".$_POST['accountNo']."'  ";
		
		if(!mysqli_query($con, $sqlUpdate)) {
			$response["response"] = false;
			$response["message"] = "Error" . mysqli_error($con);
		}
		$response["userAccount"] = array();
		$result = mysqli_query($con,"SELECT username,accountNo,position,companyName,available FROM UserAccount");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["userAccount"][] = $row;
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
		if(!mysqli_query($con,"DELETE FROM UserAccount WHERE username= '".$_POST['username']."' AND accountNo= '".$_POST['accountNo']."'  ")) {
			$response["response"] = false;
			$response["message"] = "Error " . mysqli_error($con);
		}
		$response["userAccount"] = array();
		$result = mysqli_query($con,"SELECT username,accountNo,position,companyName,available FROM UserAccount");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["userAccount"][] = $row;
		}
		return $response;
	}

	// QueryFunction is a function to reduce lines/replication of code. //
	function queryFunction($sql, $rowField, $responseField) {
		$response["response"] = true;
		$response["message"] = "Got an Analysis Report";
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
			return $response;
		}
		if(!mysqli_query($con, $sql)) {
			$response["response"] = false;
			$response["message"] = "Error " . mysqli_error($con);
			return $response;
		}
		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$response[$responseField] = $row[$rowField];
		return $response;
	}

	// Return data for Analysis report.
	function getAnalysisReport() {
		$response["analysis"] = queryFunction('SELECT COUNT(accountNo) AS countAccountNo FROM UserAccount', 'countAccountNo' , 'numberOfAccount');
		$response["analysis"] += queryFunction('SELECT COUNT(accountNo) AS countCompany FROM UserAccount WHERE position = "company"', 'countCompany' , 'numberOfCompany');
		$response["analysis"] += queryFunction('SELECT COUNT(accountNo) AS countClient FROM UserAccount WHERE position = "client"', 'countClient' , 'numberOfClient');
		$response["analysis"] += queryFunction('SELECT AVG(balance) AS average FROM UserAccountInfo', 'average' , 'averageBalance');
		$response["analysis"] += queryFunction('SELECT MAX(balance) AS max FROM UserAccountInfo', 'max' , 'maxBalance');
		$response["analysis"] += queryFunction('SELECT MIN(balance) AS min FROM UserAccountInfo', 'min' , 'minBalance');
		$response["analysis"] += queryFunction('SELECT accountType FROM AccountType WHERE interestRate = (SELECT MAX(interestRate) FROM AccountType)', 'accountType' , 'maxAccountType');
		$response["analysis"] += queryFunction('SELECT MAX(amount) AS maxTransfer FROM TransferHistory', 'maxTransfer' , 'maxTransferAmount');
		$response["analysis"] += queryFunction('SELECT SUM(balance) AS sumBalance FROM UserAccountInfo', 'sumBalance' , 'sumOfBalance');
		return $response;
	}

?>


