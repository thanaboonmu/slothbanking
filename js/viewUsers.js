$(document).ready(function() {
	// admin view all user account
	$.get("php/viewUsers.php?action=getUserAccount").done(function(data) {
		recordsObj = JSON.parse(data);
		userAccountTable = createTable(recordsObj.userAccount);
	}); // end getUserAccount
}); // end document.ready

// function for initializing a specific table
function createTable(userAccount) {
	var rows = []
	for (i = 0; i < userAccount.length; i++) {
		var record = userAccount[i];
		var row = [];

		if (record["companyName"] == null) {
			record["companyName_displayName"] = "-";
		}
		else {
			record["companyName_displayName"] = record["companyName"];
		}
		if (record["available"] == 1) {
			record["available_displayName"] = "Activated";
		}
		else if (record["available"] == 0) {
			record["available_displayName"] = "Suspended";
		}
		else {
			record["available_displayName"] = "Suspended";
		}
		row.push(record["username"]);
		row.push(record["accountNo"]);
		row.push(record["position"]);
		row.push(record["companyName_displayName"]);
		row.push(record["available_displayName"]);
		row.push( "<button type='submit' onclick='editRecord(\"" + record["username"] + "\",\"" + record["accountNo"] + "\",\"" + record["available"] + "\")' class='btn btn-primary fa fa-edit'></button>" + 
				 "<button type='submit' onclick='deleteRecord(\"" + record["username"] + "\",\"" + record["accountNo"] + "\")' class='btn btn-danger fa fa-trash'></button>");
		rows.push(row);
	}
	table = $('#allUserAccount').DataTable( {
        data: rows,
        columns: [
            { title: "Username" },
            { title: "Account No." },
            { title: "Position" },
            { title: "Company name"},
            { title: "Available"},
            { title: "Functions"}
        ]
    } );
    return table;
} // end createTable

function editRecord(username, accountNo, available) {
	bootbox.prompt({ 
  		size: "small",
  		title: "<i class='glyphicon glyphicon-pencil'></i> Editing [AVAILABLE] of Username: " + username + "<br>AccountNo: " + accountNo, 
  		value: available,
  		inputType: "select",
  		inputOptions: [
			{
			    text: 'Activated',
			    value: 1
			},
			{
			    text: 'Suspended',
			    value: 0
			}
		],
		buttons: {
			cancel: {
			    label: "CANCEL",
			    className: "btn-default"
			},
			confirm: {
			  	label: "EDIT",
			  	className: "btn-primary"
		  	}
		},
		callback: function(result) { /* result = String containing user input if OK clicked or null if Cancel clicked */
  			if (result != null) {
  				var postData = {"username":username, "accountNo":accountNo,"available": result};
				$.post("php/viewUsers.php?action=editRecord", postData).done(function(data) {
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
						});
					}
					else {
						bootbox.dialog({
							message: "Username: " + username + " AccountNo: " + accountNo + " is updated.",
							title: "Success",
							buttons: {
								success: {
									label: "OK"
								}
							}
						});
					}
			    	userAccountTable.destroy();									// delete old table to re draw table
					userAccountTable = createTable(recordsObj.userAccount);		// re draw table
			    }); // end post editRecord
  			}
  		} // end callback
	}) // end prompt
} // end editRecord

function deleteRecord(username, accountNo) {
	bootbox.dialog({
		message: "Are you sure to DELETE this record ? (Username: " + username + ",AccountNo: " +accountNo + ")",
		title: "<i class='glyphicon glyphicon-trash'></i> DELETE",
		buttons: {
			success: {
			  label: "CANCEL",
			  className: "btn-default",
			  callback: function() {
				 $('.bootbox').modal('hide');
			  } // end callback
			}, // end success
			danger: {
			  	label: "DELETE",
			  	className: "btn-danger",
			  	callback: function() {
			  		postData = { 'username':username, 'accountNo':accountNo};
					$.post('php/viewUsers.php?action=deleteRecord', postData).done(function(data){
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
							});
						}
						else {
							bootbox.dialog({
								message: "Username: " + username + " AccountNo: " + accountNo + " is deleted.",
								title: "Success",
								buttons: {
									success: {
										label: "OK"
									}
								}
							});
						}
						userAccountTable.destroy();									// delete old table to re draw table
						userAccountTable = createTable(recordsObj.userAccount);	    // re draw table
					}) // end post deleteReocrd
					.fail(function(){
						bootbox.alert('Something Went Wrog ....');
					})
			  	} // end callback
			} // end danger
		} // end button
	}); // end dialog
}	// end deleteRecord

function createPDF() {
	$.get("php/viewUsers.php?action=getAnalysisReport").done(function(data) {
		data = JSON.parse(data);
		if (data.analysis.response == true) {
			new PNotify({
	            title: 'Success',
	            text: data.analysis.message,
	            type: 'success',
	            styling: 'bootstrap3'
	        });
		}
		else {
			new PNotify({
	            title: 'Error',
	            text: data.analysis.message,
	            type: 'error',
	            styling: 'bootstrap3'
	        });
		}
		console.log(data);
		var doc = new jsPDF();
		doc.text('Analysis Report for ADMIN', 10, 10);
		doc.text('This file is generated from ANALYSIS REPORT FUNCTION' + '\n in ADMIN VIEW ALL USERS page using a Javascript library', 10, 20);
		doc.text('1. Total number of account in the system = '+data.analysis.numberOfAccount, 10, 50);
		doc.text('2. Number of Company in the system = '+data.analysis.numberOfCompany, 10, 60);
		doc.text('3. Number of Client in the system = '+data.analysis.numberOfClient, 10, 70);
		doc.text('4. Average balance of all account = '+data.analysis.averageBalance, 10, 80);
		doc.text('5. Highest balance of all account = '+data.analysis.maxBalance, 10, 90);
		doc.text('6. Lowest balance of all account = '+data.analysis.minBalance, 10, 100);
		doc.text('7. Account type with maximum interest rate = '+data.analysis.maxAccountType, 10, 110);
		doc.text('8. Highest amount of Transfer/Pay bill = '+data.analysis.maxTransferAmount, 10, 120);
		doc.text('9. Total number of money in this bank = '+data.analysis.sumOfBalance, 10, 130);
		doc.save('analysisReport.pdf')
	});
}