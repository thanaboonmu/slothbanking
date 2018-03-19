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
	
	if ($action == "getBillInfo") {
		$response = getBillInfo();
	}
	else if ($action == "getAmount"){
		$response = getAmount();
	}
	else if ($action == "getAccountNo") {
		$response = getAccountNo();
	}
	else if ($action == "getBalance"){
		$response = getBalance();
	}
	else if ($action == "payBillFinal"){
		$response = payBillFinal();
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
	function getBillInfo() {
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		
		$user = $_SESSION['login_username'];
		$sql = "SELECT firstName,lastName FROM UserAccountInfo WHERE accountNo  = (SELECT accountNo FROM UserAccount WHERE username = '$user' LIMIT 1)";
		$customerResult = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($customerResult,MYSQLI_ASSOC);
		$firstName = $row['firstName'];
		$lastName = $row['lastName'];

		$result = mysqli_query($con,"SELECT referenceNo FROM Bill_info WHERE customerFirstName = '$firstName' AND customerLastName='$lastName' AND accountNo IS null");
		$response["referenceNo"] = array();
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["referenceNo"][] = $row['referenceNo'];
		}		
		return $response;
	}
	function getAmount() {
		$selectedBill = $_POST['selectedBill'];
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$result = mysqli_query($con,"SELECT amountDue FROM Bill_info WHERE referenceNo = '$selectedBill'");
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$response["amountDue"] = $row['amountDue'];
		return $response;
	}
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

	function payBillFinal(){
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$refNo = mysqli_real_escape_string($con,$_POST['referenceNo']); //Reference num company
		$accNo = mysqli_real_escape_string($con,$_POST['AccountNo']); //Account for user
		$amountDue = mysqli_query($con,"SELECT amountDue FROM Bill_info WHERE referenceNo = '$refNo'");
		$amountA = mysqli_fetch_array($amountDue,MYSQLI_ASSOC);
		$debt = (int)$amountA['amountDue']; //debt for user
		$balance = mysqli_query($con,"SELECT balance FROM UserAccountInfo WHERE accountNo = '$accNo'");
		$amountB = mysqli_fetch_array($balance,MYSQLI_ASSOC);
		$userBalance = (int)$amountB['balance']; //money available for user
		$query = "SELECT transferNo FROM TransferHistory ORDER BY transferNo DESC LIMIT 1";
		$transferPrep = mysqli_query($con,$query);
		$transfer = mysqli_fetch_array($transferPrep,MYSQLI_ASSOC);
		$transferNo = (int)$transfer['transferNo'];
		$transferNo +=1;
		if($userBalance<$debt){
			$response["check"] = false;
			$response["response"] = "Sorry but your current balance is not enough";
			return $response;
		}
		$accountCompany = mysqli_query($con,"SELECT accountNo FROM UserAccount WHERE companyName = (SELECT companyName FROM Bill_info WHERE referenceNo = '$refNo')");
		$accNumCom = mysqli_fetch_array($accountCompany,MYSQLI_ASSOC);
		$accountNoForCompany = $accNumCom['accountNo'];
		if(!mysqli_query($con,"INSERT INTO TransferHistory(transferNo,accountNo,bankName,type,amount) VALUES ('$transferNo','$accountNoForCompany','slot',1,'$debt')")){
			$response["check"] = false;
			$response["response"] = "Insert into TransferHistory Error";
			return $response;
		}
		if (!mysqli_query($con,"INSERT INTO TransferHistory(transferNo,accountNo,bankName,type,amount) VALUES ('$transferNo','$accNo','slot',0,'$debt')")) {
			$response["check"] = false;
			$response["response"] = "Insert into TransferHistory Error";
			return $response;
		}
		$balanceFinal = (float)$userBalance-(float)$debt;
		if (!mysqli_query($con,"UPDATE UserAccountInfo SET balance = '$balanceFinal' WHERE accountNo = '$accNo'")) {
			$response["check"] = false;
			$response["response"] = "Update Balance Error";
			return $response;
		}
		if (!mysqli_query($con,"UPDATE UserAccountInfo SET balance = balance + '$debt' WHERE accountNo = '$accountNoForCompany'")) {
			$response["check"] = false;
			$response["response"] = "Update Balance Error";
			return $response;
		}
		if (!mysqli_query($con,"UPDATE Bill_info SET accountNo = '$accNo',paidDateTime = now() WHERE referenceNo = '$refNo'")) {
			$response["check"] = false;
			$response["response"] = "Update Bill status Error";
			return $response;
		}
		$response["response"] = "Success in everything";
		$response["check"] = true;
		return $response;
	}
?>


