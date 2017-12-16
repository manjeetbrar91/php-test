<?php
	function get_data($email,$apikey) 
	{
		$ch = curl_init();
		$timeout = 200;
		
		$url='https://api.fullcontact.com/v2/person.json?email='.$email.'&apiKey='.$apikey;

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		$data_array = json_decode($data, TRUE);
		$contactInfo=$data_array['contactInfo'];
		
		curl_close($ch);
		return $contactInfo;
	}

	function send_subcriber_email($mailTo,$mailSubject,$mailBody,$mailer_username,$mailer_password,$client_subscriber_id)
	{
		require 'PHPMailer/PHPMailerAutoload.php';
		date_default_timezone_set('Asia/Kolkata');
		$mail = new PHPMailer;
		$mail->isSMTP();
		// $mail->SMTPDebug = 1;
		$mail->Debugoutput = 'html';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Username = $mailer_username;
		$mail->Password = $mailer_password;
		// $mail->Username = "devops@age.team";
		// $mail->Password = "W1ne-0h!";
		$mail->setFrom($mailTo, 'Wineoh');
		$mail->addAddress($mailTo, 'Wineoh');
		$mail->addAddress($client_subscriber_id, 'Wineoh');
		$mail->Subject = $mailSubject;
		$mail->msgHTML($mailBody);
		$mail->AltBody = 'This is a plain-text message body';
		$mail->send();

	}


?>