$(document).ready(function() {
	// admin view all transfer history
	$.get("php/viewTransferHistory.php?action=getTransferHistory").done(function(data) {
		recordsObj = JSON.parse(data);
		transferHistoryTable= createTable(recordsObj.transferHistory);
	}); // end getTransferHistory
}); // end document.ready

// function for initializing a specific table
function createTable(transferHistory) {
	var rows = []
	for (i = 0; i < transferHistory.length; i++) {
		var record = transferHistory[i];
		var row = [];

		if (record["accountNo"] == null) {
			record["accountNo_displayName"] = "-";
		}
		else {
			record["accountNo_displayName"] = record["accountNo"];
		}
		if (record["otherAccountNo"] == null) {
			record["otherAccountNo_displayName"] = "-";
		}
		else {
			record["otherAccountNo_displayName"] = record["otherAccountNo"];
		}
		if (record["type"] == 1) {
			record["amount"] = "+ " + record["amount"];
		}
		else {
			record["amount"] = "- " + record["amount"];
		}

		row.push(record["transferNo"]);
		row.push(record["accountNo_displayName"]);
		row.push(record["otherAccountNo_displayName"]);
		row.push(record["bankName"]);
		row.push(record["amount"]);
		row.push(record["dateTime"]);
		row.push("<button type='submit' onclick='deleteRecord(\"" + record["transferNo"] + "\")' class='btn btn-danger fa fa-trash'></button>");
		rows.push(row);
	}
	table = $('#allTransferHistory').DataTable( {
        data: rows,
        columns: [
            { title: "Transfer No." },
            { title: "Account No." },
            { title: "Other-Bank Account No."},
            { title: "Bank name"},
            { title: "Amount"},
            { title: "Date Time"},
            { title: "Functions"}
        ]
    } );
    return table;
} // end createTable

function deleteRecord(transferNo) {
	bootbox.dialog({
		message: "Are you sure to DELETE this record ? (TransferNo: " + transferNo + ")",
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
			  		postData = {'transferNo':transferNo};
					$.post('php/viewTransferHistory.php?action=deleteRecord', postData).done(function(data){
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
								message: "TransferNo: " + transferNo + " is deleted.",
								title: "Success",
								buttons: {
									success: {
										label: "OK"
									}
								}
							});
						}
						transferHistoryTable.destroy();									// delete old table to re draw table
						transferHistoryTable = createTable(recordsObj.transferHistory);	    // re draw table
					})
					.fail(function(){
						bootbox.alert('Something Went Wrog ....');
					})
			  	} // end callback
			} // end danger
		} // end button
	}); // end dialog
}