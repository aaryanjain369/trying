<?php 
    

    date_default_timezone_set("Asia/kolkata");
    include_once("config.php");


    $query = mysqli_query($con,"SELECT * FROM users where status=1");
    if(mysqli_num_rows($query))
    {
        while($row = mysqli_fetch_assoc($query))
        {
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
            $id = $row['id'];
            $email = $row['email']; 
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
            $body .= "<html><body><div> <strong>Hello New User,</strong><br>Here is your comics. </div><br><br><div style ='padding-top:8px;'> <img src=$filePath><br><br> You will get a comics in every 5 minutes. <a href='https://www.rambutcut.com/rtCampPHPAssignmentOne/unsubscribe.php?code=$id'> Click here </a> to unsubscribe. </div></body></html>" . $eol;
            
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
        }
    }

?>