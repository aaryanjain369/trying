<?php 

    $ch =curl_init();
    $comic_number= rand(1,2560);
    $url = "https://xkcd.com/$comic_number/info.0.json";
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


	date_default_timezone_set("Asia/Kolkata");
	include("config.php");

	if(!empty($_GET['code']) && ($_GET['code']))
	{
		$code = $_GET['code'];
		$query = mysqli_query($con,"select * from users where verification_code='$code'");
		$num = mysqli_fetch_array($query);

		if($num > 0)
		{
			$st = 0;
			$result = mysqli_query($con,"select id from users where verification_code='$code' and status='$st'");
			$result4 = mysqli_fetch_array($result);
            
            if($result4 > 0)
			{
				$st = 1;
				$result = mysqli_query($con, "update users set status='$st' where verification_code='$code'");
				
				$email = $num['email']; 
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
    		    $body .= "<html><body><div> <strong>Hello New User,</strong><br>Here is your comics. </div><br><br><div style ='padding-top:8px;'> <img src=$filePath><br><br> You will get a comics in every 5 minutes. <a href='https://www.rambutcut.com/rtCampPHPAssignmentOne/unsubscribe.php?code=$id'> Click here </a> to unsubscribe.</div></body></html>" . $eol;
    		    
    		    // attachment
    		    $body .= "--" . $separator . $eol;
    		    $body .= "Content-Type: application/octet-stream";
    		    $body .= "Content-Type: image/png; name=\"" . $filePath . "\"" . $eol;
    		    $body .= "Content-Transfer-Encoding: base64" . $eol;
    		    $body .= "Content-Disposition: attachment" . $eol;
    		    $body .= $content . $eol;
    		    $body .= "--" . $separator . "--";
    		    
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
		
				// echo "and your comics has been send to your registered email id" . $num['email'];
			
				$msg = "Your account is activated and your comics has been send to your registered email id". $num['email'];
			}
			else
			{
			    
				$msg = "Your account is already activated, no need to activate again.";
			}
		}
		else
	    {
	        echo "bye";
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
						<h1><?php echo htmlentities($msg); ?></h1>

						<button>Click here to Get your Comics now on email.</button>						
						
					</div>
					
				</div>
 				<div class="image" style=" width:50%; float:left;">
					<img style="width: 100%; height: 400px" src="images/subscribeimage.png">
				</div>
			
			
		</div>
	</body>
</html>