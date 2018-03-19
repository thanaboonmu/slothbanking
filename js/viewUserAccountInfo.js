$(document).ready(function() {
	// admin view all user account info
	$.get("php/viewUserAccountInfo.php?action=getUserAccountInfo").done(function(data) {
		recordsObj = JSON.parse(data);
		userAccountInfoTable = createTable(recordsObj.userAccountInfo);
	}); // end getUserAccountInfo
}); // end document.ready

// function for initializing a specific table
function createTable(userAccountInfo) {
	var rows = []
	for (i = 0; i < userAccountInfo.length; i++) {
		var record = userAccountInfo[i];
		var row = [];
		row.push(record["accountNo"]);
		row.push(record["branchNo"]);
		row.push(record["accountType"]);
		row.push(record["firstName"]);
		row.push(record["lastName"]);
		row.push(record["identificationNo"]);
		row.push(record["address"]);
		row.push(record["email"]);
		row.push(record["phoneNum"]);
		row.push(record["birthDate"]);
		row.push(record["balance"]);
		row.push( "<button type='submit' onclick='editRecord(\"" + record["accountNo"] + "\",\"" + record["branchNo"] + "\",\"" + record["accountType"] + "\")' class='btn btn-primary fa fa-edit'></button>" + 
				 "<button type='submit' onclick='deleteRecord(\"" + record["accountNo"] + "\")' class='btn btn-danger fa fa-trash'></button>");
		rows.push(row);
	}
	table = $('#allUserAccountInfo').DataTable( {
        data: rows,
        columns: [
            { title: "Account No." },
            { title: "Branch No." },
            { title: "Account Type"},
            { title: "First name"},
            { title: "Last name"},
            { title: "Identification No."},
            { title: "Address"},
            { title: "Email"},
            { title: "Phone No."},
            { title: "Birth date"},
            { title: "Balance"},
            { title: "Functions"}
        ]
    } );
    return table;
} // end createTable

function editRecord(accountNo, branchNo, accountType) {
	bootbox.confirm("<h2>You can EDIT Branch No. and Account Type </h2><br>\
					 Account No. :  <input type='text' name='accountNo' value='"+accountNo+"' disabled/><br/>\
    				 Branch No. :  <input type='text' name='branchNo' value='"+branchNo+"' /><br>\
    				 Account Type. :  <input type='text' name='accountType' value='"+accountType+"' />\
    				 "
   	, function(result) {
        if(result) {
        	postAccountNo = $("input[name=accountNo]").val();
        	postBranchNo = $("input[name=branchNo]").val();
        	postAccountType = $("input[name=accountType]").val();
        	postData = {'accountNo':postAccountNo, 'branchNo':postBranchNo, 'accountType':postAccountType};
        	$.post('php/viewUserAccountInfo.php?action=editRecord', postData).done(function(data){
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
						message: "AccountNo: " + accountNo + " is updated.",
						title: "Success",
						buttons: {
							success: {
								label: "OK"
							}
						}
					});
				}
				userAccountInfoTable.destroy();										// delete old table to re draw table
				userAccountInfoTable = createTable(recordsObj.userAccountInfo);	    // re draw table
			}) // end post editRecord
        } //end if result
	}); // end bootbox.confirm , function 
} // end editRecord

function deleteRecord(accountNo) {
	bootbox.dialog({
		message: "Are you sure to DELETE this record ? (AccountNo: " +accountNo + ")",
		title: "<i class='glyphicon glyphicon-trash'></i> DELETE",
		buttons: {
			success: {
			  label: "NO",
			  className: "btn-default",
			  callback: function() {
				 $('.bootbox').modal('hide');
			  } // end callback
			}, // end success
			danger: {
			  	label: "DELETE",
			  	className: "btn-danger",
			  	callback: function() {
			  		postData = {'accountNo':accountNo};
					$.post('php/viewUserAccountInfo.php?action=deleteRecord', postData).done(function(data){
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
								message: "AccountNo: " + accountNo + " is deleted.",
								title: "Success",
								buttons: {
									success: {
										label: "OK"
									}
								}
							});
						}
						userAccountInfoTable.destroy();									// delete old table to re draw table
						userAccountInfoTable = createTable(recordsObj.userAccountInfo);	// re draw table
					})
					.fail(function(){
						bootbox.alert('Something Went Wrog ....');
					})
			  	} // end callback
			} // end danger
		} // end button
	}); // end dialog
}