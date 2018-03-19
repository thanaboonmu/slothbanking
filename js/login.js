position =
$(document).ready(function() {
	$.get("php/login.php?action=checkStatus").done(function(data) {
      data = JSON.parse(data);
      if (data.response == false) { // found no session 
        if (window.location.pathname != "/bank/production/login.html") {
            window.location.href = "login.html";
        }
      }
      else { // found a session
        if (window.location.pathname == "/bank/production/login.html") {
          	window.location.href = "index.html";
    	}

        $(".getUsername").html(data.user.username);
        $(".getPosition").html(data.user.position);
        if (data.user.position != "admin") {
        	$("#viewUsers").hide();
        	$("#viewUserAccountInfo").hide();
        	$("#viewTransferHistory").hide();
        	if (window.location.pathname == "/bank/production/viewUsers.html" || 
        		window.location.pathname == "/bank/production/viewUserAccountInfo.html" ||
        		window.location.pathname == "/bank/production/viewTransferHistory.html") {
          		window.location.href = "index.html";
    		}
        } 
        if (data.user.position != "company") {
        	if (window.location.pathname == "/bank/production/index_addBill.html") {
        		window.location.href = "index.html";
        	}
        	$("#addBill").hide();
        }
        else {
        	if (window.location.pathname == "/bank/production/addAccount.html") {
        		window.location.href = "index.html";
        	}
        	$("#addAccount").hide();
        }
      } // end else found a session
    }); // end checkStatus

	$("#login_form").submit(function() {
		var username = $("#signin_username").val();
		var password = $("#signin_password").val();
		var postData = {"username": username, "password": password};
		$.post("php/login.php?action=signin", postData).done(function(data) {
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

  	$("#signup_form").submit(function() {
		var username = $("#signup_username").val();
		var password = $("#signup_password").val();
		var accountNo = $("#signup_accountNo").val();
		var atmNo = $("#signup_atmNo").val();
		var atmPassword = $("#signup_atmPassword").val();
		var firstName = $("#signup_firstName").val();
		var lastName = $("#signup_lastName").val();	 
		var position = $("input[type='radio'][name='position']:checked").val();
		var companyName = $("#signup_companyName").val();
		var identificationNo = $("#signup_identificationNo").val();
		var birthDate = $("#signup_birthDate").val();
		var postData = {"username": username, "password": password, "accountNo": accountNo, "atmNo": atmNo, "atmPassword": atmPassword, "firstName": firstName,
					 "lastName": lastName, "position": position, "companyName": companyName, "identificationNo": identificationNo, "birthDate":birthDate};

		$.post("php/login.php?action=signup", postData).done(function(data) {
			data = JSON.parse(data);
			if (data.response == true) {
    			new PNotify({
	                title: 'Success',
	                text: data.message,
	                type: 'success',
	                styling: 'bootstrap3'
	            });
	            setTimeout(function () {
			       window.location.href = "login.html"; 
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

  	$("#signout_button").click(function() {
		$.get("php/login.php?action=signout").done(function(data) {
			data = JSON.parse(data);
			if (data.response == true) {
    			new PNotify({
	                title: 'Success',
	                text: data.message,
	                type: 'success',
	                styling: 'bootstrap3'
	            });
	            setTimeout(function () {
			       window.location.href = "login.html"; 
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