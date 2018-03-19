<?php
	function connectDB() {
		$con = mysqli_connect("127.0.0.1","root","","Bank");
		return $con;
	}	
?>