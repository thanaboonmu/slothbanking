$(document).ready(function() {

$("#addAccount_form").submit(function() {
		var accountNo = $("#add_accountNo").val();
		var atmNo = $("#add_atmNo").val();
		var atmPassword = $("#add_atmPassword").val();
		var firstName = $("#add_firstName").val();
		var lastName = $("#add_lastName").val();
		var identificationNo = $("#add_identificationNo").val();
		var birthDate = $("#add_birthDate").val();
		birthDate = convertDate(birthDate);
		var postData = {"accountNo": accountNo, "atmNo": atmNo, "atmPassword": atmPassword, "firstName": firstName,
					 "lastName": lastName, "identificationNo": identificationNo, "birthDate":birthDate};
		
		$.post("php/addAccount.php", postData).done(function(data) {
			data = JSON.parse(data);
			if (data.response == true) {
    			new PNotify({
	                title: 'Success',
	                text: data.message,
	                type: 'success',
	                styling: 'bootstrap3'
	            });
	           setTimeout(function () {
			       window.location.href = "index.html"; 
			    }, 500);
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

});

function convertDate(birthDate) {
	birthDate = birthDate.split("/").reverse();
	var tmp = birthDate[2];
	birthDate[2] = birthDate[1];
	birthDate[1] = tmp;
	birthDate = birthDate.join("-");
	return birthDate;
}