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
	
	if ($action == "addBill") {
		$response = addBill();
	}
	else if ($action == "getAllBillInfo"){
		$response =getAllBillInfo();
	}
	else if ($action == "editBillRecord"){
		$response = editBillRecord();
	}
	else if ($action == "deleteBillRecord") {
		$response = deleteBillRecord();
	}
	echo json_encode($response);

	// ADD BILL
	function addBill() {
		$position = $_SESSION['login_position'];
		if ($position != "company") {
			$response["response"] = false;
			$response["message"] = "You aren't company! Quitting";
			return $response;
		}
	 	$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$refNo = mysqli_real_escape_string($con,$_POST['referenceNo']);
		$fName = mysqli_real_escape_string($con,$_POST['customerFirstName']);
		$lName = mysqli_real_escape_string($con,$_POST['customerLastName']);
		$amount = mysqli_real_escape_string($con,$_POST['amountDue']);
		$debt = mysqli_real_escape_string($con,$_POST['debtDateTime']);
		if ($refNo == "" || $fName == "" || $lName == "" || $amount == "" || $debt == "" ) {
			mysqli_close($con);
			$response["response"] = false;
			$response["message"] = "Some required field is missing! Please input every required field";
			return $response;
		}	
		$sql = "SELECT referenceNo FROM Bill_info WHERE referenceNo = '$refNo'";
		$result = mysqli_query($con,$sql);
		$count = mysqli_num_rows($result);
		if ($count != 0) {
			mysqli_close($con);
			$response["response"] = false;
			$response["message"] = "This BillNo is already existed";
			return $response;
		}

		$user = $_SESSION['login_username'];
		$sql = "SELECT companyName FROM UserAccount WHERE username = '$user' ";
		if (!mysqli_query($con,$sql)) {
			$response["response"] = false;
			$response["message"] = "Error" . mysqli_error($con);
			mysqli_close($con);
			return $response;
		} 
		$result = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$company = $row['companyName'];
		$sql = "INSERT INTO Bill_info (referenceNo,companyName,customerFirstName,customerLastName,amountDue,debtDateTime,accountNo,paidDateTime) 
			VALUES('$refNo','$company','$fName','$lName', '$amount','$debt',null,null)";
		if (!mysqli_query($con,$sql)) {
			$response["response"] = false;
			$response["message"] = "Error" . mysqli_error($con);
			mysqli_close($con);
			return $response;
		} 
		$response["response"] = true;
		$response["message"] = "SUCCESS , YOUR BILL IS ADDED";
		mysqli_close($con);
		return $response;
	}
	function getAllBillInfo(){
		$con = connectDB();
		$response['response'] = true;
		//return $response;
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
			return $response;
		}
		$response["allBill"] = array();
		$user = $_SESSION['login_username'];
		$sql = "SELECT companyName FROM UserAccount WHERE username = '$user'";
		if (!mysqli_query($con,$sql)) {
			$response["response"] = false;
			$response["message"] = "No company";
			return $response;
		}
		$result = mysqli_query($con,$sql);
		$line = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$sql2 = $line['companyName'];
		if (!mysqli_query($con,"SELECT * FROM Bill_info WHERE companyName = '$sql2'")) {
			$response["response"] = false;
			$response["message"] = "Error " . mysqli_error($con);
			return $response;
		}
		$result = mysqli_query($con,"SELECT * FROM Bill_info WHERE companyName = '$sql2'");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["allBill"][] = $row;
		}
		return $response;
	}
	function editBillRecord(){
		$con = connectDB();
		$response["response"] = true;
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$user = $_SESSION['login_username'];
		$sql = "SELECT companyName FROM UserAccount WHERE username = '$user'";
		if (!mysqli_query($con,$sql)) {
			$response["response"] = false;
			$response["message"] = "No company";
			return $response;
		}
		$result = mysqli_query($con,$sql);
		$line = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$sql2 = $line['companyName'];
		$sqlUpdate = "UPDATE Bill_info SET customerFirstName = '".$_POST['customerFirstName']."' , customerLastName = '".$_POST['customerLastName']."', amountDue = '".$_POST['amountDue']."'
						WHERE referenceNo= '".$_POST['referenceNo']."'  ";
		
		if(!mysqli_query($con, $sqlUpdate)) {
			$response["response"] = false;
			$response["message"] = "Error" . mysqli_error($con);
		}
		$response["allBill"] = array();
		$result = mysqli_query($con,"SELECT * FROM Bill_info WHERE companyName = '$sql2'");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["allBill"][] = $row;
		}
		return $response;
	}
	function deleteBillRecord(){
		$con = connectDB();
		$refNo = $_POST['referenceNo'];
		$response["response"] = true;
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$user = $_SESSION['login_username'];
		$sql = "SELECT companyName FROM UserAccount WHERE username = '$user'";
		if (!mysqli_query($con,$sql)) {
			$response["response"] = false;
			$response["message"] = "No company";
			return $response;
		}
		$result = mysqli_query($con,$sql);
		$line = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$company = $line['companyName'];
		if(!mysqli_query($con,"DELETE FROM Bill_info WHERE referenceNo= '$refNo' AND companyName = '$company'")) {
			$response["response"] = false;
			$response["message"] = "Error " . mysqli_error($con);
		}
		$response["allBill"] = array();
		$result = mysqli_query($con,"SELECT * FROM Bill_info WHERE companyName = '$company'");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["allBill"][] = $row;
		}
		return $response;
	}
?>


