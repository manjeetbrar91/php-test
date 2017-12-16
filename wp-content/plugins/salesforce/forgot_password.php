<?php

function zendesk ($url,$method,$data) 
{
	
	$apiKey = "e2784ddd417026dac58e28b7c3a1c35f94f8979fd0c575a44cbfd6c236b1e4b4";
	$baseUrl = "https://agehelp.zendesk.com";

	$headers = array(
		'Authorization: Bearer ' . $apiKey,
		'Content-Type: application/json'      
	);

	$curl_url = $baseUrl. $url;

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $curl_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
   
	if ($method == "POST") 
	{      
		curl_setopt($curl, CURLOPT_POST, 1);
	}      
   
	if ($method == "GET") 
	{      
		curl_setopt($curl, CURLOPT_HTTPGET, 1);
	}
   
	if (!empty($data)) 
	{               
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	}
	
	if (($output = curl_exec($curl)) === FALSE) 
	{
		die("Curl Failed: " . curl_error($curl));
	}
	
	curl_close($curl);
	return json_decode($output);
}

if(isset($_POST['btnSubmit']))
{
	$ticket_data = array
				(
					"ticket" => array
									(
										"subject"=> "Forgot Password",
										"comment"=> array
														(
															"body"=>"abc"
														),
										"requester"=> array
														(
															"email"=>$_POST['txtEmail']
														),
									)
				);

	$activate_result = zendesk ("/api/v2/tickets.json","POST",$ticket_data);

	if(isset($activate_result->error))
	{
		if(isset($activate_result->error->title) && isset($activate_result->error->message))
		{
			echo $activate_result->error->title ."<br/>";
			echo $activate_result->error->message;
		}
		else
		{
			echo $activate_result->error ."<br/>";
			echo $activate_result->description;
		}
		
	}
	else
	{
		echo '<pre>';
		print_r($activate_result);
		echo '</pre>';
	}
}

?>
<!DOCTYPE html>
<html>
<body>

<form action="" method="POST">
 
  Email:<br>
  <input type="email" name="txtEmail" id="txtEmail"  value="">
  <br><br>
  <input type="submit" name="btnSubmit" id="btnSubmit" value="Submit">
</form> 


</body>
</html>
