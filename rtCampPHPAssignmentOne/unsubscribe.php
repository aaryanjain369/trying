<?php

	date_default_timezone_set("Asia/Kolkata");
	include("config.php");

	if(!empty($_GET['code']) && ($_GET['code']))
	{
	    echo "hey";
	    $code = $_GET['code'];
	    $st = 0;
		$result = mysqli_query($con, "update users set status='$st' where id='$code'");
	    $msg = "You won't get any email's from us now. Sorry to see you go.";
	}
	else
    {
        $msg = "Failed to unsuscribe. Can't identify the user.";
    }


?>

<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="rtCamp">
		<meta name="keywords" content="rtCamp">
		<meta name="description" content="Trusted helping hand for your business growth.">
		
		<title>rtCampPHPAssignmentOne</title>		
		<link rel="stylesheet" href="style.css">
	</head>
		
	<body>
		<div style=" overflow: hidden;background-color:#ebb402; height: 400px;">
			
				<div class="" style=" width:50%; float:left;">
					<div class="form">
						<h1><?php echo htmlentities($msg); ?></h1>

						
						
					</div>
					
				</div>
 				<div class="image" style=" width:50%; float:left;">
					<img style="width: 100%; height: 400px" src="images/subscribeimage.png">
				</div>
			
			
		</div>
	</body>
</html>