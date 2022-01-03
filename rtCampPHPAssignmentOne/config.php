<?php 
	$servername = "localhost";
	$username = "Aaryan";
	$password = "G11j06m23@aj";
	$database = "salon_admin_panel";

	$con = mysqli_connect($servername,$username,$password,$database);

	if (!$con)
	{
		echo "Sorry failed to connect".mysqli_connect_error();	
	}
	else
	{
		// echo "Connection successful <br> ";
	}

?>