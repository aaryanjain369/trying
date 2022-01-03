<?php 

	$ch =curl_init();

	$url = "https://xkcd.com/info.0.json ";

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$resp = curl_exec($ch);

	if($e = curl_error($ch))
	{
		echo $e;
	}
	else
	{
		$decoded =json_decode($resp,true);
	}

	curl_close($ch);


	date_default_timezone_set("Asia/kolkata");
	include_once("config.php");

	if(isset($_POST['submit']))
	{
		$email = $_POST['email']; 
		$activationCode = md5($email.time()) ;
		$status = 0;

		$insertQuery = "insert into users(email,verification_code,status) values('$email','$activationCode','$status')";
		$query = mysqli_query($con,$insertQuery);

		if($query)
		{
		    $filePath = $decoded['img'];
			$fileName = $decoded['safe_title'];
		    $subject ="XKCD Comics";
		    $fromname ="Aaryan Jain | Full Stack Developer";
		    $fromemail = 'aaryanmanishjain@gmail.com';  
		    $mailto = $email; 
		    $content = file_get_contents($filePath);
		    $content = chunk_split(base64_encode($content));
		    $separator = md5(time());
		    $eol = "\r\n";
		    
		    // main header (multipart mandatory)
		    $headers = "From: ".$fromname." <".$fromemail.">" . $eol;
		    $headers .= "MIME-Version: 1.0" . $eol;
		    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
		    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
		    $headers .= "This is a MIME encoded message." . $eol;
		    
		    // message
		    $headers .= "--" . $separator . $eol;
		    $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
		    $headers .= "Content-Transfer-Encoding: 8bit" . $eol;
		    
		    $body = "";
		    $body .= "<html><body><div>Hello New User, </div><br><br><div style ='padding-top:8px;'> Please click the below button to verify your email and get the XKCD comic.</div><a href='https://www.rambutcut.com/rtCampPHPAssignmentOne/email_verification.php?code=$activationCode'><strong> Verify Email</strong></a></body></html>" . $eol;
		    
		    //SEND Mail
		    if (mail($mailto, $subject, $body, $headers))
		    {
		        echo "mail send ... OK"; // do what you want after sending the email;
		    }
		    else
		    {
		        echo "mail send ... ERROR!";
		        print_r( error_get_last() );
		    }
			header("Location: index.php");
		}
		else
		{
			echo "fail";
		}	
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
						<h1>Subscribe to news letter</h1>
						<form action="" method="post">
							<input type="email" name="email"> 
							<input type="submit" value="SUBSCRIBE" name="submit">
						</form>
					</div>
					
				</div>
 				<div class="image" style=" width:50%; float:left;">
					<img style="width: 100%; height: 400px" src="images/subscribeimage.png">
				</div>


			
			
		</div>
	</body>
</html>