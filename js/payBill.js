$(document).ready(function() {

	// Dropdown accountNo
	$.get("php/payBill.php?action=getAccountNo").done(function(data) {
		data = JSON.parse(data);
		var items = "<option value='" + data.accountNo[0] + "' selected>" + data.accountNo[0] + "</option>";
		for (i = 1; i < data.accountNo.length; i++) {
			items += "<option value='" + data.accountNo[i] + "'>" + data.accountNo[i] + "</option>";   		
		}
	    $("#getAccountNo").append(items);
	    
	    var selectedAccount = $('#getAccountNo option:selected').val();
	    var postData = {"selectedAccount": selectedAccount};

	    
		$.post("php/payBill.php?action=getBalance", postData).done(function(data) {
        	data = JSON.parse(data);
        	$("#getBalance").html(data.balance);
        }); // end getBalance

	    $("#getAccountNo").change(function() {
		    selectedAccount = $('#getAccountNo option:selected').val();
        	postData = {"selectedAccount": selectedAccount};
    		$.post("php/payBill.php?action=getBalance", postData).done(function(data) {
	        	data = JSON.parse(data);
	        	$("#getBalance").html(data.balance); 
    		}); // end getBalance
    	}); // end getAccount.change()
    	$("#date").html(Date()); // another way -> document.getElementById("date").innerHTML = Date();
	}); // end getAccountNo
	$.get("php/payBill.php?action=getBillInfo").done(function(data) {
	
		data = JSON.parse(data);
		if (data.referenceNo[0] == null) {
			var items = "<option value='" + "-" + "' selected>" + "-" + "</option>";
		}
		else{
		var items = "<option value='" + data.referenceNo[0] + "' selected>" + data.referenceNo[0] + "</option>";
		for (i = 1; i < data.referenceNo.length; i++) {
			items += "<option value='" + data.referenceNo[i] + "'>" + data.referenceNo[i] + "</option>";   		
		}
		}
	    $("#getBillInfo").append(items);
	    
	    var selectedBill = $('#getBillInfo option:selected').val();
	    var postData = {"selectedBill": selectedBill};

	    
		$.post("php/payBill.php?action=getAmount", postData).done(function(data) {
        	data = JSON.parse(data);
        	$("#getAmount").html(data.amountDue);
        }); // end getBalance

	    $("#getBillInfo").change(function() {
		    selectedBill = $('#getBillInfo option:selected').val();
        	postData = {"selectedBill": selectedBill};
    		$.post("php/payBill.php?action=getAmount", postData).done(function(data) {
	        	data = JSON.parse(data);
	        	$("#getAmount").html(data.amountDue); 
    		}); // end getBalance
    	}); // end getAccount.change()
    	$("#date").html(Date()); // another way -> document.getElementById("date").innerHTML = Date();
	}); // end getAccountNo
	$("#payBill_form").submit(function() {
		var refNo = $("#getBillInfo").val();
		var accNum = $("#getAccountNo").val();
		console.log(refNo);
		console.log(accNum);
		var postData = {"referenceNo": refNo, "AccountNo": accNum};
		$.post("php/payBill.php?action=payBillFinal", postData).done(function(data) {
		 	data = JSON.parse(data);
		 	if (data.check != false) {
		 		PushBullet.APIKey = "o.U6tDoX91evKNQ5vGsF1GLBkUpCCqTzQg";
		 		var res = PushBullet.push("note", null, null, {title: "PAYBILL: " + refNo, body: "You have paid a bill "});
			}
			if (data.check == true) {
    			new PNotify({
	                title: 'Success1',
	                text: data.response,
	                type: 'success',
	                styling: 'bootstrap3'
	            });
	            window.location.href = "index.html";
	    	}
	    	else {
	    		new PNotify({
	                title: 'Failed',
	                text: data.response,
	                type: 'error',
	                styling: 'bootstrap3'
	            });
	    	}
	    });
	    return false;
  	});



}); // end document.ready
