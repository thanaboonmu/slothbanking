$(document).ready(function() {

	// Dropdown accountNo
	$.get("php/transfer.php?action=getAccountNo").done(function(data) {
		data = JSON.parse(data);
		var items = "<option value='" + data.accountNo[0] + "' selected>" + data.accountNo[0] + "</option>";
		for (i = 1; i < data.accountNo.length; i++) {
			items += "<option value='" + data.accountNo[i] + "'>" + data.accountNo[i] + "</option>";   		
		}
	    $("#getAccountNo").append(items);
	
	}); // end getAccountNo

	$.get("php/transfer.php?action=getOtherBankList").done(function(data) {
		data = JSON.parse(data);
		var items = "<option value='" + data.list[2] + "' selected>" + data.list[2] + "</option>";
		for (i = 0; i < data.list.length-1; i++) {
			items += "<option value='" + data.list[i] + "'>" + data.list[i] + "</option>";   		
		}
	    $("#getOtherBankList").append(items);
	
	}); // end getOtherBankList

	$("#transfer_form").submit(function() {
		var userAccount = $("#getAccountNo").val();
		var bankName = $("#getOtherBankList").val();
		var target = $("#to_accountNo").val();
		var amount = $("#amount").val();
		var postData = {"accountNo": userAccount, "bankName": bankName,"target": target,"money": amount};
		var postForName = {"target":target,"bankName":bankName};
		$.post("php/transfer.php?action=getNameFromAccount", postForName).done(function(data) {
		 	data = JSON.parse(data);
		 	var nameClient = data.name;
		 	if (data.check != false) {
				$.post("php/transfer.php?action=checkAccountNo", postForName).done(function(data) {
					data = JSON.parse(data);
					if(data.response == false){
						bootbox.dialog({
						message: "accountNo: " + target + "from " + bankName +" is not exists.",
						title: "Fail",
						buttons: {
							success: {
								label: "OK"
							}
						}
						});
					}
					else{
						bootbox.confirm("<h2>Confirmation!.</h2><br>" + "From User Account No. : " + userAccount +"<br>" +"To Account No." + target +"<br>"+ "To Client Name: " + nameClient + "<br>" + "Amount: " + amount + "<br>"
    				 
   						, function(result) 
   						{
        					if(result) 
        					{
        						$.post('php/transfer.php?action=transferMoney', postData).done(function(data){
								recordsObj = JSON.parse(data);
								if (!recordsObj.response) {
									bootbox.dialog({
									message: recordsObj.message,
									title: "Error",
									buttons: {
										success: {
										label: "OK"
										}
									}
									}); //end bootbox
								}
								else 
								{
									PushBullet.APIKey = "o.U6tDoX91evKNQ5vGsF1GLBkUpCCqTzQg";
		 							var res = PushBullet.push("note", null, null, {title: "Transfer" , body:  " From: " +userAccount + "to " + target + "Amount: " + amount});
									bootbox.dialog({
									message: "accountNo: " + target + " is transfered.",
									title: "Success",
									buttons: {
										success: {
										label: "OK"
										}
									}
									}); //end bootbox
									setTimeout(function () {
		       							window.location.href = "index.html"; 
		    						}, 1000);
								}
							});
       		 				} //end if result
							}); // end bootbox.confirm , function
		 				}
		 	
		 			});
			}			
	    });
	    return false;
  	});

}); // end document.ready
