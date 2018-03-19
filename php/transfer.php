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
	if ($action == "getTargetAccountNo") {
		$response = getTargetAccountNo();
	}
	else if ($action == "getAccountOwner"){
		$response =getAllBillInfo();
	}
	else if ($action == "transferAction"){
		$response = editBillRecord();
	}
	else if ($action == "getAccountNo"){
		$response = getAccountNo();
	}
	else if ($action == "getNameFromAccount") {
		$response = getNameFromAccount();
	}
	else if ($action == "transferMoney") {
		$response = transferMoney();
	}
	else if ($action == "getOtherBankList"){
		$response = getOtherBankList();
	}
	else if ($action == "checkAccountNo"){
		$response = checkAccountNo();
	}
	echo json_encode($response);
	
	function checkAccountNo(){
		$response["response"] = true;
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$target = $_POST['target'];
		$bankName = $_POST['bankName'];
		if ($bankName == 'slot') {
			$sql = mysqli_query($con,"SELECT COUNT(accountNo) as myCount FROM UserAccountInfo WHERE accountNo = '$target'");	
			$row = mysqli_fetch_array($sql,MYSQLI_ASSOC);			
			$count = (int)$row['myCount'];
			if($count==0){
				$response["response"] = false;
				$response["message"] = "this account number doesn't exist!";
				return $response;
			}
		}
		else {
			$sql = mysqli_query($con,"SELECT COUNT(accountNo) as myCount FROM OtherBankAccount WHERE accountNo = '$target' AND bankName= '$bankName'");
			$row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
			$count= (int)$row['myCount'];
			if($count == 0)
			{
				$response["response"] = false;
				$response["message"] = "this account number in other bank doesn't exist";
				return $response;
			}
		}
		return $response;
	}

	function getOtherBankList(){
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$sql = mysqli_query($con,"SELECT bankName FROM BankInfo");
		while ($row = mysqli_fetch_array($sql,MYSQLI_ASSOC)) {
			$response["list"][] = $row['bankName'];
		}
		return $response;
	}
	function getAccountNo() {
		$con = connectDB();
		$response["response"] = true;
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
		$response["response"] = "pass";
		return $response;
	}
	
	function getTargetAccountNo(){
		$bankName = $_POST['bankName'];
		$target = $_POST['target'];
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$response["accountNo"] = array();
		$user = $_SESSION['login_username'];
		if ($bankName == 'slot') 
		{
		$result = mysqli_query($con,"SELECT accountNo FROM UserAccount WHERE accountNo = '$target'");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["accountNo"][] = $row['accountNo'];
		}
		}
		else{
			$result = mysqli_query($con,"SELECT accountNo FROM OtherBankAccount WHERE accountNo = '$target' AND bankName = '$bankName'");
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
			$response["accountNo"][] = $row['accountNo'];

		}
		return $response;
		}
	}
	function getNameFromAccount(){
		$bankName = $_POST['bankName'];
		$target = $_POST['target'];
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$response["name"] = array();
		$user = $_SESSION['login_username'];
		if ($bankName == 'slot') 
		{
		$result = mysqli_query($con,"SELECT firstName,lastName FROM UserAccountInfo WHERE accountNo  = (SELECT accountNo FROM UserAccount WHERE accountNo = '$target' LIMIT 1)");
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$firstName = $row['firstName'];
		$lastName = $row['lastName'];
		$firstName .= " ";
		$firstName .= $lastName;
		$response["name"] = $firstName;
		}
		else{
		$result = mysqli_query($con,"SELECT firstName,lastName FROM OtherBankAccount WHERE accountNo = '$target' AND bankName = '$bankName'");
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$firstName = $row['firstName'];
		$lastName = $row['lastName'];
		$firstName .= $lastName;
		$response["name"] = $firstName;
		}
		return $response;
	}

	function transferMoney(){
		$con = connectDB();
		if (mysqli_connect_errno()) {
			$response["response"] = false;
			$response["message"] = "Failed to connect to MYSQL:" .mysqli_connect_error();
		}
		$target = $_POST['target'];
		$userAcc = $_POST['accountNo'];
		$bankName = $_POST['bankName'];
		$money = $_POST['money'];
		if ($bankName == 'slot') 
		{
		$query = "SELECT transferNo FROM TransferHistory ORDER BY transferNo DESC LIMIT 1";
		$transferPrep = mysqli_query($con,$query);
		$transfer = mysqli_fetch_array($transferPrep,MYSQLI_ASSOC);
		$transferNo = (int)$transfer['transferNo'];
		$transferNo +=1;
		$type = 0;
		$balance = mysqli_query($con,"SELECT balance FROM UserAccountInfo WHERE accountNo = '$userAcc'");
		$amountB = mysqli_fetch_array($balance,MYSQLI_ASSOC);
		$userBalance = (int)$amountB['balance']; //money available for user
		if ($userBalance<$money) {
			$response["check"] = false;
			$response["message"] = "Sorry not enough money";
			return $response;
		}
		if ($userAcc == $target) {
			$response["check"] = false;
			$response["message"] = "Cannot doing transaction in your own account";
			return $response;
		}
		$result = mysqli_query($con,"INSERT INTO TransferHistory(transferNo,accountNo,bankName,type,amount) VALUES ('$transferNo','$userAcc','slot','$type','$money')");
		$type = 1;
		$result = mysqli_query($con,"INSERT INTO TransferHistory(transferNo,accountNo,bankName,type,amount) VALUES ('$transferNo','$target','slot','$type','$money')");
		$finalBalance = (float)$userBalance - (float)$money;
		if (!mysqli_query($con,"UPDATE UserAccountInfo SET balance = '$finalBalance' WHERE accountNo = '$userAcc'")) {
			$response["response"] = "Update Balance Error for user";
			return $response;
		}
		$balance2 = mysqli_query($con,"SELECT balance FROM UserAccountInfo WHERE accountNo = '$target'");
		$transfered = mysqli_fetch_array($balance2,MYSQLI_ASSOC);
		$userBalance2 = (int)$transfered['balance']; //money available for user
		$finalBalance = (float)$userBalance2 + (float)$money;
		if (!mysqli_query($con,"UPDATE UserAccountInfo SET balance = '$finalBalance' WHERE accountNo = '$target'")) {
			$response["response"] = "Update Balance Error for target";
			return $response;
		}
		$response["response"] = true;
		return $response;
		}
		else 
		{
			$query = "SELECT transferNo FROM TransferHistory ORDER BY transferNo DESC LIMIT 1";
			$transferPrep = mysqli_query($con,$query);
			$transfer = mysqli_fetch_array($transferPrep,MYSQLI_ASSOC);
			$transferNo = (int)$transfer['transferNo'];
			$transferNo += 1;
			$type = 0;
			$balance = mysqli_query($con,"SELECT balance FROM UserAccountInfo WHERE accountNo = '$userAcc'");
			$amountB = mysqli_fetch_array($balance,MYSQLI_ASSOC);
			$userBalance = (int)$amountB['balance']; //money available for user
			if ($userBalance<$money) {
				$response["check"] = false;
				$response["message"] = "Sorry you does not have sufficient amount";
				return $response;
			}
			$result = mysqli_query($con,"INSERT INTO TransferHistory(transferNo,accountNo,otherAccountNo,bankName,type,amount) VALUES ('$transferNo','$userAcc','$target','slot','$type','$money')");
			$type = 1;
			$result2 = mysqli_query($con,"INSERT INTO TransferHistory(transferNo,accountNo,otherAccountNo,bankName,type,amount) VALUES ('$transferNo','$userAcc','$target','$bankName','$type','$money')");
			$balance2 = mysqli_query($con,"SELECT balance FROM UserAccountInfo WHERE accountNo = '$userAcc'");
			$transfered = mysqli_fetch_array($balance2,MYSQLI_ASSOC);
			$userBalance2 = (int)$transfered['balance']; //money available for user
			$finalBalance = (float)$userBalance2 - (float)$money;
			if (!mysqli_query($con,"UPDATE UserAccountInfo SET balance = '$finalBalance' WHERE accountNo = '$userAcc'")) {
				$response["response"] = "Update Balance Error";
				return $response;
			}
			$response["response"] = true;
			$response["message"] = "Hooray";
			return $response;
		}
	}
?>