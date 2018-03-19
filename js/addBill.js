$(document).ready(function() {
  	$("#addBill_form").submit(function() {
		var refNo = $("#referenceNo").val();
		var fName = $("#customerFirstName").val();
		var lName = $("#customerLastName").val();
		var amount = parseInt($("#amount").val());
		var date = $("#debtDate").val();
		var postData = {"referenceNo": refNo, "customerFirstName": fName,"customerLastName": lName, "amountDue": amount, "debtDateTime": date};
		$.post("php/addBill.php?action=addBill", postData).done(function(data) {
			data = JSON.parse(data);
			if (data.response == true) {
    			new PNotify({
	                title: 'Success',
	                text: data.message,
	                type: 'success',
	                styling: 'bootstrap3'
	            });
	            window.location.href = "index_addBill.html";
	    	}
	    	else {
	    		new PNotify({
	                title: 'Error',
	                text: data.message,
	                type: 'error',
	                styling: 'bootstrap3'
	            });
	    	}
	    });
	    return false;
  	});
  	$.get("php/addBill.php?action=getAllBillInfo").done(function(data) {
		recordsObj = JSON.parse(data);
		allBillTable = createTable(recordsObj.allBill);
	}); // end getUserAccountInfo
});
function createTable(allBill) {
	var rows = []
	for (i = 0; i < allBill.length; i++) {
		var record = allBill[i];
		var row = [];
		if (record["accountNo"] == null) {
			record["accountNo_display"] = "-";
		}
		else {
			record["accountNo_display"] = record["accountNo"]
		}
		if (record["paidDateTime"] == null) {
			record["paidDateTime_display"] = "Not paid yet";
		}
		else
		{
			record["paidDateTime_display"] = record["paidDateTime"];
		}
		row.push(record["referenceNo"]);
		row.push(record["companyName"]);
		row.push(record["customerFirstName"]);
		row.push(record["customerLastName"]);
		row.push(record["amountDue"]);
		row.push(record["debtDateTime"]);
		row.push(record["accountNo_display"]);
		row.push(record["paidDateTime_display"]);
		row.push( "<button type='submit' onclick='editRecord(\"" + record["referenceNo"] + "\",\"" + record["customerFirstName"] + "\",\"" + record["customerLastName"] +  "\",\""+ record["amountDue"] +"\")' class='btn btn-primary fa fa-edit'></button>" + 
				 "<button type='submit' onclick='deleteRecord(\"" + record["referenceNo"] + "\")' class='btn btn-danger fa fa-trash'></button>");
		rows.push(row);
	}
	table = $('#allBillInfo').DataTable( {
        data: rows,
        columns: [
            { title: "Reference No." },
            { title: "Company Name" },
            { title: "First name"},
            { title: "Last name"},
            { title: "Amount"},
            { title: "Date issue"},
            { title: "Paid by"},
            { title: "Paid Time"},
            { title: "Functions"}
        ]
    } );
    return table;
} // end createTable

function editRecord(referenceNo, customerFirstName, customerLastName,amountDue) {
	bootbox.confirm("<h2>You can EDIT Customer-Firstname-Lastname-Amount due.</h2><br>\
					 Reference No. :  <input type='text' name='referenceNo' value='"+referenceNo+"' disabled/><br/>\
    				 Customer Firstname :  <input type='text' name='first' value='"+customerFirstName+"' /><br>\
    				 Customer Lastname :  <input type='text' name='last' value='"+customerLastName+"' /><br>\
    				 Amount :  <input type='text' name='amountDue' value='"+amountDue+"' />\
    				 "
   	, function(result) {
        if(result) {
        	postLastName = $("input[name=referenceNo]").val();
        	postFirstName = $("input[name=first]").val();
        	postLastName = $("input[name=last]").val();
        	postAmount = $("input[name=amountDue]").val();
        	postData = {'referenceNo': referenceNo,'customerFirstName':postFirstName, 'customerLastName':postLastName, 'amountDue':postAmount};
        	$.post('php/addBill.php?action=editBillRecord', postData).done(function(data){
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
						message: "referenceNo: " + referenceNo + " is updated.",
						title: "Success",
						buttons: {
							success: {
								label: "OK"
							}
						}
					});
				}
				allBillTable.destroy();										// delete old table to re draw table
				allBillTable = createTable(recordsObj.allBill);	    // re draw table
			}) // end post editRecord
        } //end if result
	}); // end bootbox.confirm , function 
} // end editRecord

function deleteRecord(referenceNo) {
	bootbox.dialog({
		message: "Are you sure to DELETE this record ? (ReferenceNo: " +referenceNo + ")",
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
			  		postData = {'referenceNo':referenceNo};
					$.post('php/addBill.php?action=deleteBillRecord', postData).done(function(data){
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
								message: "Reference No: " + referenceNo + " is deleted.",
								title: "Success",
								buttons: {
									success: {
										label: "OK"
									}
								}
							});
						}
						allBillTable.destroy();									// delete old table to re draw table
						allBillTable = createTable(recordsObj.allBill);	// re draw table
					})
					.fail(function(){
						bootbox.alert('Something Went Wrog ....');
					})
			  	} // end callback
			} // end danger
		} // end button
	}); // end dialog
}

