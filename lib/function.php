<?php
function callApi($url,$return=false){
	if(isset($url)&&$url != ""){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = @curl_exec($ch);
		@curl_close($ch);
		if($return){
			return $response;
		}
		echo $response;
		exit();
	}
}
/**
 * Send mail
 * @param string $from 
 * @param string $fromName
 * @param string $to
 * @param string $subject
 * @param string $body  message or mail template
 * @return bool
 */
  function sendMail($from = '', $fromName = '', $to, $subject = '', $body) {
    require 'lib/class.phpmailer.php';
    $mail = new PHPMailer;
 
    $mail->isSMTP();                                      // Set mailer to use SMTP
    //$mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
    $mail->Host = SMTP_HOST; 
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    //$mail->Username = 'syphua2@gmail.com';                   // SMTP username
    $mail->Username =SMTP_USERNAME;  
    //$mail->Password = 'consokhong';               // SMTP password
    $mail->Password = SMTP_PASSWORD;  
    //$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
    if(SMTP_SECURE){
        $mail->SMTPSecure = SMTP_SECURE;
    }
    //$mail->Port = 587; 
    $mail->Port = SMTP_PORT;                                       //Set the SMTP port number - 587 for authenticated TLS
    $mail->setFrom($from, $fromName."(".$from.")");     //Set who the message is to be sent from
    //$mail->addReplyTo('labnol@gmail.com', 'First Last');  //Set an alternative reply-to address
    $mail->addAddress($to);  // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');
    $mail->WordWrap = 50;                                 // Set word wrap to 50 character
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $subject;
    $mail->Body    = $body;
    
    if($mail->send()){
        return true;
    }
    return false;
}

function downloadFileFromApi($downloadFile, $url) {
    $downloadFile = fopen($downloadFile, "w");
    $handle = curl_init($url);
// Tell cURL to write contents to the file.
    curl_setopt($handle, CURLOPT_FILE, $downloadFile);
// Follow redirects.
    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
// Do the request.
    curl_exec($handle);
// Clean up.
    curl_close($handle);
    fclose($downloadFile);
}

?> 