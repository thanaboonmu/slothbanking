$(document).ready(function() {

	// Dropdown accountNo
	$.get("php/index.php?action=getAccountNo").done(function(data) {
		data = JSON.parse(data);
		var items = "<option value='" + data.accountNo[0] + "' selected>" + data.accountNo[0] + "</option>";
		for (i = 1; i < data.accountNo.length; i++) {
			items += "<option value='" + data.accountNo[i] + "'>" + data.accountNo[i] + "</option>";   		
		}
	    $("#getAccountNo").append(items);
	    
	    var selectedAccount = $('#getAccountNo option:selected').val();
	    var postData = {"selectedAccount": selectedAccount};

	    
		$.post("php/index.php?action=getBalance", postData).done(function(data) {
        	data = JSON.parse(data);
        	$("#getBalance").html(data.balance);
        }); // end getBalance

		$.post("php/index.php?action=getHistory", postData).done(function(data) {
			recordsObj = JSON.parse(data);
			historyTable = createTable(recordsObj.history);
		}); // end getHistory 

	    $("#getAccountNo").change(function() {
		    selectedAccount = $('#getAccountNo option:selected').val();
        	postData = {"selectedAccount": selectedAccount};
    		$.post("php/index.php?action=getBalance", postData).done(function(data) {
	        	data = JSON.parse(data);
	        	$("#getBalance").html(data.balance); 
    		}); // end getBalance
    		$.post("php/index.php?action=getHistory", postData).done(function(data) {
				recordsObj = JSON.parse(data);
				historyTable.destroy();
				historyTable = createTable(recordsObj.history);
			}); // end getHistory 
    	}); // end getAccount.change()
    	$("#date").html(Date()); // another way -> document.getElementById("date").innerHTML = Date();
	}); // end getAccountNo
}); // end document.ready

// function for initializing a specific table
function createTable(history) {
	var rows = []
	for (i = 0; i < history.length; i++) {
		var record = history[i];
		var row = [];
		row.push(record["transferNo"]);
		row.push(record["accountNo"]);
		if (record["type"] == 1) {
			record["amount"] = "+ " + record["amount"];
			
		}
		else
			record["amount"] = "- " + record["amount"];
		row.push(record["amount"]);
		row.push(record["dateTime"]);
		rows.push(row);
	}
	// dataSet example
	// var dataSet = [
	//     [ "Tiger Nixon", "System Architect", "Edinburgh", "5421", "2011/04/25", "$320,800" ],
	//     [ "Garrett Winters", "Accountant", "Tokyo", "8422", "2011/07/25", "$170,750" ],
	//     [ "Ashton Cox", "Junior Technical Author", "San Francisco", "1562", "2009/01/12", "$86,000" ],
	//     [ "Unity Butler", "Marketing Designer", "San Francisco", "5384", "2009/12/09", "$85,675" ]
	// ];
	table = $('#transferHistory').DataTable( {
        data: rows,
        columns: [
            { title: "Transfer No." },
            { title: "Account No." },
            { title: "Amount" },
            { title: "Date-Time"}
        ]
    } );
    return table;
} // end createTable