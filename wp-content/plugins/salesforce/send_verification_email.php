<?php 

if(isset($_GET['tokenI']))
{
	if(trim($_GET['tokenI'])!=null)
	{
		$user_id = $_GET['tokenI'];
	}
}
if(isset($_GET['tokenT']))
{
	if(trim($_GET['tokenT'])!=null)
	{
		$user_type = $_GET['tokenT'];
	}
}

if($user_type=="01228000000TLju")
{
	$url="/api/v1/users/".$user_id."/lifecycle/activate";
}
else
{
	$url="/api/v1/users/".$user_id."/lifecycle/deactivate";
}


	$apiKey = "SSWS 006K6-sJVQokG1yfgiWvGT7OIY_jx2OGuESxZieASP";
	$baseUrl = "https://dev-817806.oktapreview.com";

	$headers = array(
		'Authorization: ' . $apiKey,
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
	curl_setopt($curl, CURLOPT_POST, 1);
	if (($output = curl_exec($curl)) === FALSE) 
	{
		die("Curl Failed: " . curl_error($curl));
	}
	if(isset($add_user_result->errorCauses))
	{									
		$msg=$add_user_result->errorCauses[0]->errorSummary;
	}
	else
	{
		$msg="Your account has been registered successfully and will be activate by admin.";
		
	}
	
	curl_close($curl);

?>	

<script type="text/javascript">
	location.href="<?php echo home_url(); ?>/login?msg=<?php echo $msg;?>";
</script>