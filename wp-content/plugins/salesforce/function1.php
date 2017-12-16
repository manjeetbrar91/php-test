<?php
function writeToLog($u ) 
	{
		$date_time=date("Y-m-d H:i:s");
		$date= date('Y-m-d');
		$path = dirname(__FILE__) . '/error_log/'.$date.'.txt';
		$agent = $_SERVER['HTTP_USER_AGENT'];
		if (($h = fopen($path, "a")) !== FALSE) {
			$mystring = $date_time.' '.$u . ' ' . $agent . PHP_EOL;
			fwrite( $h, $mystring );
			fclose($h);
			$mailTo='nishu.developtech@gmail.com';
			$mailSubject='Wineoh error';
			$mailBody=$mystring;
			send_email($mailTo,$mailSubject,$mailBody);
		}
		else
			die('Unable to open file!');
	}


function send_email($mailTo,$mailSubject,$mailBody)
{
	require_once 'PHPMailer/PHPMailerAutoload.php';
    date_default_timezone_set('Asia/Kolkata');
	
	$mail = new PHPMailer;
	$mail->isSMTP();
	// $mail->SMTPDebug = 1;
	$mail->Debugoutput = 'html';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = "devops@age.team";
	$mail->Password = "W1ne-0h!";
	$mail->setFrom($mailTo, 'Wineoh');
	$mail->addAddress($mailTo, 'Wineoh');
	$mail->Subject = $mailSubject;
	$mail->msgHTML($mailBody);
	$mail->AltBody = 'This is a plain-text message body';
	$mail->send();

}
	
	function get_company_data($domain,$apikey) 
	{
		$ch = curl_init();
		$timeout = 200;
		
		$url='https://api.fullcontact.com/v2/company/lookup.json?domain='.$domain.'&apiKey='.$apikey;

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$data_array=array();
		$data_array['organization']=array();
		
		
		if($statusCode == '200')
		{
			
			$data_array = json_decode($data, TRUE);
			if(!isset($data_array['socialProfiles']))
			{
				$data_array['socialProfiles']=array();
			}
			if(!isset($data_array['organization']))
			{
				$data_array['organization']=array();
			}
			$data_array=array("organization"=>$data_array['organization'],"socialProfiles"=>$data_array['socialProfiles']);
		}
		
		return $data_array;
	}
function get_person_data($email,$apikey) 
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
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		$data_array=array();
		$data_array['contactInfo']=array();
		$data_array['socialProfiles']=array();
		
		if($statusCode == '200')
		{
			
			$data_array = json_decode($data, TRUE);

			$data_array=array("contactInfo"=>$data_array['contactInfo'],"socialProfiles"=>$data_array['socialProfiles']);
		}
		
		
		
		
		return $data_array;
	}
/**********************************
Function for OKTA REST API CALL  
***********************************/
function Okta ($url, $method = "GET", $data = "") 
{
   
	$apiKey = "006K6-sJVQokG1yfgiWvGT7OIY_jx2OGuESxZieASP";
	$baseUrl = "https://dev-817806.oktapreview.com";

	$headers = array(
		'Authorization: SSWS ' . $apiKey,
		'Accept: application/json',
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


/**********************************
Function for ZENDESK REST API CALL  
***********************************/
function zendesk ($url,$method,$data) 
{
	$apiKey = "445302580d23ffc9c4f4d8c905b632f28cd94574163e71ecf4a4675a0322ca5a";
	//$apiKey = "e2784ddd417026dac58e28b7c3a1c35f94f8979fd0c575a44cbfd6c236b1e4b4";
	//$baseUrl = "https://agehelp.zendesk.com";
	$baseUrl = "https://wine-oh.zendesk.com";

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


function Zendesk_Api_generate_ticket($email)
{
 $ticket_data = array
    (
     "ticket" => array
         (
          "subject"=> "Forgot Password",
          "comment"=> array
              (
               "body"=>"Account is suspended."
              ),
          "requester"=> array
              (
               "email"=>$email
              ),
         )
    );

 $ticket_result = zendesk ("/api/v2/tickets.json","POST",$ticket_data);
 return $ticket_result;
}


function okta_reset_password ($user_id,$password) 
{
	$url="/api/v1/users/".$user_id;
	
	$apiKey = "SSWS 006K6-sJVQokG1yfgiWvGT7OIY_jx2OGuESxZieASP";
	$baseUrl = "https://dev-817806.oktapreview.com";
	$headers = array(
		'Authorization: ' . $apiKey,
		'Content-Type: application/json'      
	);

	$data = array
	(
		"credentials" => array
						(
							"password"=> $password							
						)
	);
	
	$curl_url = $baseUrl. $url;

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $curl_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
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


function get_lead_contact_data($domain,$apikey,$is_convert,$account_type,$leadId) 
{
	$companyinfo= get_company_data($domain,$apikey);
						
	$organization_name='';
	$organization_approxEmployees='';
	$organization_founded='';
	$organization_overview='';
	$organization_emailid='';
	$organization_contactno='';
	$organization_addressLine='';
	$organization_locality='';
	$organization_region='';
	$organization_country_name='';
	$organization_country_code='';
	$organization_postalCode='';
	$google_url='';
	$facebook_url='';
	$twitter_url='';
	$linkedin_url='';
	$youtube_url='';
	$pinterest_url='';
	$instagram_url='';
	$quora_url='';
	$angellist_url='';
	$google_username='';
	$facebook_username='';
	$twitter_username='';
	$linkedin_username='';
	$youtube_username='';
	$pinterest_username='';
	$instagram_username='';
	$quora_username='';
	$angellist_username='';
	$google_followers=0;
	$facebook_followers=0;
	$twitter_followers=0;
	$linkedin_followers=0;
	$youtube_followers=0;
	$pinterest_followers=0;
	$instagram_followers=0;
	$quora_followers=0;
	$angellist_followers=0;
	$google_bio='';
	$facebook_bio='';
	$twitter_bio='';
	$linkedin_bio='';
	$youtube_bio='';
	$pinterest_bio='';
	$instagram_bio='';
	$quora_bio='';
	$angellist_bio='';
	
	$addLead = new stdClass;

	if(isset($companyinfo) && count($companyinfo)>0)
	{
		if(isset($companyinfo['organization']['name']))
		{
			$organization_name= $companyinfo['organization']['name'];
		}
		if(isset($companyinfo['organization']['approxEmployees']))
		{
			$organization_approxEmployees= $companyinfo['organization']['approxEmployees'];
		}
		if(isset($companyinfo['organization']['founded']))
		{
			$organization_founded= $companyinfo['organization']['founded'];
		}
		if(isset($companyinfo['organization']['overview']))
		{
			$organization_overview= $companyinfo['organization']['overview'];
		}
		
		
		if(isset($companyinfo['organization']['contactInfo']))
		{
			foreach($companyinfo['organization']['contactInfo'] as $organization)
			{

				foreach($organization as $organizations){
					if(isset($organizations['value']))
					{
						$organization_emailid= $email_id= $organizations['value'];
					}
					if(isset($organizations['number']))
					{
						$organization_contactno= $contact_no= $organizations['number'];
					}
					if(isset($organizations['addressLine1']))
					{
						$organization_addressLine= $address= $organizations['addressLine1'];
					}
					if(isset($organizations['locality']))
					{
						$organization_locality= $locality= $organizations['locality'];
					}
					if(isset($organizations['region']['name']))
					{
						$organization_region= $region_name= $organizations['region']['name'];
					}
					if(isset($organizations['value']['name']))
					{
						$organization_country_name= $country_name= $organizations['country']['name'];
					}
					if(isset($organizations['country']['code']))
					{
						$organization_country_code= $country_code= $organizations['country']['code'];
					}
					if(isset($organizations['postalCode']))
					{
						$organization_postalCode= $postalcode= $organizations['postalCode'];
					}

				}
				
			}
		}
		
		if(isset($companyinfo['socialProfiles']))
		{
		
			foreach($companyinfo['socialProfiles'] as $socialProfiles)
			{
				if(isset($socialProfiles))
				{
					if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='google')
					{
						$google_url=$socialProfiles['url'];
						
						if(isset($socialProfiles['username']))
						{
							$google_username=$socialProfiles['username'];
						}
						if(isset($socialProfiles['followers']))
						{
							$google_followers=$socialProfiles['followers'];
						}
						if(isset($socialProfiles['bio']))
						{
							$google_bio=$socialProfiles['bio'];
						}
					}
					
					if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='facebook')
					{
						$facebook_url = $socialProfiles['url'];
					}
					if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='twitter')
					{
						$twitter_url = $socialProfiles['url'];
						
						if(isset($socialProfiles['username']))
						{
							$twitter_username=$socialProfiles['username'];
						}
						if(isset($socialProfiles['followers']))
						{
							$twitter_followers=$socialProfiles['followers'];
						}
						if(isset($socialProfiles['bio']))
						{
							$twitter_bio=$socialProfiles['bio'];
						}
					}
					if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='linkedincompany')
					{
						$linkedin_url = $socialProfiles['url'];
						
						if(isset($socialProfiles['username']))
						{
							$linkedin_username=$socialProfiles['username'];
						}
						if(isset($socialProfiles['followers']))
						{
							$linkedin_followers=$socialProfiles['followers'];
						}
						if(isset($socialProfiles['bio']))
						{
							$linkedin_bio=$socialProfiles['bio'];
						}
					}
					if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='youtube')
					{
						$youtube_url = $socialProfiles['url'];
					}
					if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='pinterest')
					{
						$pinterest_url = $socialProfiles['url'];
						
						if(isset($socialProfiles['username']))
						{
							$pinterest_username=$socialProfiles['username'];
						}
						if(isset($socialProfiles['followers']))
						{
							$pinterest_followers=$socialProfiles['followers'];
						}
						if(isset($socialProfiles['bio']))
						{
							$pinterest_bio=$socialProfiles['bio'];
						}
					}
					if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='instagram')
					{
						$instagram_url = $socialProfiles['url'];
						
						if(isset($socialProfiles['username']))
						{
							$instagram_username=$socialProfiles['username'];
						}
						if(isset($socialProfiles['followers']))
						{
							$instagram_followers=$socialProfiles['followers'];
						}
						if(isset($socialProfiles['bio']))
						{
							$instagram_bio=$socialProfiles['bio'];
						}
					}
					if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='quora')
					{
						$quora_url = $socialProfiles['url'];
					}
					if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='angellist')
					{
						$angellist_url = $socialProfiles['url'];
						
						if(isset($socialProfiles['username']))
						{
							$angellist_username=$socialProfiles['username'];
						}
						if(isset($socialProfiles['followers']))
						{
							$angellist_followers=$socialProfiles['followers'];
						}
						if(isset($socialProfiles['bio']))
						{
							$angellist_bio=$socialProfiles['bio'];
						}
					}
					
				}
				
			}
		}

	}
	
	if($is_convert == 0)
	{
		$addLead->convertedStatus='Converted';
		$addLead->Type='Partner';
		$addLead->doNotCreateOpportunity='true';		
		$addLead->leadId=$leadId;
		$addLead->overwriteLeadSource='true';
		$addLead->sendNotificationEmail='true';
	}
	
	$addLead->Facebook_A__c= $facebook_url;
	$addLead->YouTube_URL_A__c= $youtube_url;
	
	$addLead->angellist_A__c= $angellist_url;
	$addLead->AngelList_Username_A__c= $angellist_username;
	$addLead->AngelList_Bio_A__c= $angellist_bio;
	if($angellist_followers!=0)
	{
		$addLead->AngelList_Followers_A__c= $angellist_followers;
	}
	
	$addLead->GooglePlus__c= $google_url;
	$addLead->GooglePlus_Username_A__c= $google_username;
	$addLead->GooglePlus_Bio_A__c= $google_bio;
	if($google_followers!=0)
	{
		$addLead->googleplusfollowers__c= $google_followers;
	}	
	
	$addLead->Instagram_URL_A__c= $instagram_url;
	$addLead->Instagram_Username_A__c= $instagram_username;
	$addLead->Instagram_Bio_A__c= $instagram_bio;
	if($instagram_followers!=0)
	{
		$addLead->Instagram_Followers_A__c= $instagram_followers;
	}
	
	$addLead->LinkedInCompany__c= $linkedin_url;
	$addLead->LinkedIn_Username_A__c= $linkedin_username;
	$addLead->LinkedIn_Bio_A__c= $linkedin_bio;
	if($linkedin_followers!=0)
	{
		$addLead->LinkedIn_Followers_A__c= $linkedin_followers;
	}
	
	$addLead->Pinterest_URL_A__c= $pinterest_url;
	$addLead->Pinterest_Username_A__c= $pinterest_username;
	$addLead->Pinterest_Bio_A__c= $pinterest_bio;
	if($pinterest_followers!=0)
	{
		$addLead->Pinterest_Followers_A__c= $pinterest_followers;
	}
	
	$addLead->TwitterCompany__c= $twitter_url;
	$addLead->Twitter_Username_A__c= $twitter_username;
	$addLead->Twitter_Bio_A__c= $twitter_bio;
	if($twitter_followers!=0)
	{
		$addLead->Twitter_Followers_A__c= $twitter_followers;
	}
	
	$addLead->Youtube_Username_A__c= $youtube_username;
	$addLead->Youtube_Bio_A__c= $youtube_bio;
	if($youtube_followers!=0)
	{
		$addLead->Youtube_Followers_A__c= $youtube_followers;
	}
	
	$addLead->RecordTypeId = '01228000000SbE8';//account type - company
	$addLead->Account_Type__c = $account_type;
	
	return $addLead;
}

###########################################

function full_contact_api($email,$apikey,$contact_type,$account_id,$fname,$lname,$password)
{
		$contactInfo= get_person_data($email,$apikey);
					
		$c_last_name='';
		$c_fullName='';
		$c_first_name='';
		$c_company_website='';
		$c_google_url='';
		$c_google_username='';
		$c_google_followers='';
		$c_google_bio='';
		$c_facebook_url='';
		$c_twitter_url='';
		$c_twitter_username='';
		$c_twitter_followers='';
		$c_twitter_bio='';
		$c_facebook_url='';
		$c_linkedin_username='';
		$c_linkedin_url='';
		$c_linkedin_followers='';
		$c_linkedin_bio='';
		$c_youtube_url='';
		$c_pinterest_url='';
		$c_pinterest_username='';
		$c_pinterest_followers='';
		$c_pinterest_bio='';
		$c_instagram_url='';
		$c_instagram_username='';
		$c_instagram_followers='';
		$c_instagram_bio='';
		$c_quora_url='';
		$c_angellist_url='';
		$c_angellist_username='';
		$c_angellist_followers='';
		$c_angellist_bio='';

		if(isset($contactInfo) && count($contactInfo)>0)
		{
			
			if(isset($contactInfo['contactInfo']['familyName']))
			{
				$c_last_name= $contactInfo['contactInfo']['familyName'];
			}
			if(isset($contactInfo['contactInfo']['fullName']))
			{
				$c_fullName= $contactInfo['contactInfo']['fullName'];
			}
			if(isset($contactInfo['contactInfo']['givenName']))
			{
				$c_first_name= $contactInfo['contactInfo']['givenName'];
			}
			
			if(isset($contactInfo['contactInfo']['websites']))
			{
				
				$array=$contactInfo['contactInfo']['websites'];
				foreach($array as $data)
				{
					foreach($data as $company_website)
					{
						$c_company_website;
					}
				}


			}
			
			if(isset($contactInfo['socialProfiles']))
			{
			
				foreach($contactInfo['socialProfiles'] as $socialProfiles)
				{
					if(isset($socialProfiles))
					{
						if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='google')
						{
							$c_google_url=$socialProfiles['url'];
							
							if(isset($socialProfiles['username']))
							{
								$c_google_username=$socialProfiles['username'];
							}
							if(isset($socialProfiles['followers']))
							{
								$c_google_followers=$socialProfiles['followers'];
							}
							if(isset($socialProfiles['bio']))
							{
								$c_google_bio=$socialProfiles['bio'];
							}
						}
						
						if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='facebook')
						{
							$c_facebook_url = $socialProfiles['url'];
						}
						if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='twitter')
						{
							$c_twitter_url = $socialProfiles['url'];
							
							if(isset($socialProfiles['username']))
							{
								$c_twitter_username=$socialProfiles['username'];
							}
							if(isset($socialProfiles['followers']))
							{
								$c_twitter_followers=$socialProfiles['followers'];
							}
							if(isset($socialProfiles['bio']))
							{
								$c_twitter_bio=$socialProfiles['bio'];
							}
						}
						if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='linkedincompany')
						{
							$c_linkedin_url = $socialProfiles['url'];
							
							if(isset($socialProfiles['username']))
							{
								$c_linkedin_username=$socialProfiles['username'];
							}
							if(isset($socialProfiles['followers']))
							{
								$linkedin_followers=$socialProfiles['followers'];
							}
							if(isset($socialProfiles['bio']))
							{
								$c_linkedin_bio=$socialProfiles['bio'];
							}
						}
						if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='youtube')
						{
							$c_youtube_url = $socialProfiles['url'];
						}
						if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='pinterest')
						{
							$c_pinterest_url = $socialProfiles['url'];
							
							if(isset($socialProfiles['username']))
							{
								$c_pinterest_username=$socialProfiles['username'];
							}
							if(isset($socialProfiles['followers']))
							{
								$c_pinterest_followers=$socialProfiles['followers'];
							}
							if(isset($socialProfiles['bio']))
							{
								$c_pinterest_bio=$socialProfiles['bio'];
							}
						}
						if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='instagram')
						{
							$c_instagram_url = $socialProfiles['url'];
							
							if(isset($socialProfiles['username']))
							{
								$c_instagram_username=$socialProfiles['username'];
							}
							if(isset($socialProfiles['followers']))
							{
								$c_instagram_followers=$socialProfiles['followers'];
							}
							if(isset($socialProfiles['bio']))
							{
								$c_instagram_bio=$socialProfiles['bio'];
							}
						}
						if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='quora')
						{
							$c_quora_url = $socialProfiles['url'];
						}
						if(isset($socialProfiles['typeId']) && $socialProfiles['typeId']=='angellist')
						{
							$c_angellist_url = $socialProfiles['url'];
							
							if(isset($socialProfiles['username']))
							{
								$c_angellist_username=$socialProfiles['username'];
							}
							if(isset($socialProfiles['followers']))
							{
								$c_angellist_followers=$socialProfiles['followers'];
							}
							if(isset($socialProfiles['bio']))
							{
								$c_angellist_bio=$socialProfiles['bio'];
							}
						}
						
					}
					
				}
			}

		}
		
		//Insert all the values in the contact so as to save in the lead
		$add_new_contact = new stdClass(); 
		$add_new_contact->FirstName = $fname; 
		$add_new_contact->LastName = $lname;
		$add_new_contact->Email = $email;
		$add_new_contact->AccountId = $account_id;  // the account id to which the contact will be binded
		$add_new_contact->RecordTypeId=$contact_type; //Admin Record Type
		$add_new_contact->transitioningToStatus__c='DEPROVISIONED'; //Admin will be inactive by default
		$add_new_contact->Passcode__c=$password; //Password of the contact added
		
		$add_new_contact->Facebook_C__c= $c_facebook_url;
		$add_new_contact->YouTube_URL_C__c= $youtube_url;
		
		$add_new_contact->AngelList_C__c= $c_angellist_url;
		$add_new_contact->AngelList_Username_C__c= $c_angellist_username;
		$add_new_contact->AngelList_Bio_C__c= $c_angellist_bio;
		if($c_angellist_followers!=0)
		{
			$add_new_contact->AngelList_Followers_C__c= $c_angellist_followers;
		}
		
		$add_new_contact->GooglePlus_URL_C__c= $c_google_url;
		$add_new_contact->GooglePlus_Username_C__c= $c_google_username;
		$add_new_contact->GooglePlus_Bio_C__c= $c_google_bio;
		if($c_google_followers!=0)
		{
			$add_new_contact->GooglePlus_Followers_C__c= $c_google_followers;
		}	
		
		$add_new_contact->Instagram_URL_C__c= $c_instagram_url;
		$add_new_contact->Instagram_Username_C__c= $c_instagram_username;
		$add_new_contact->Instagram_Bio_C__c= $c_instagram_bio;
		if($c_instagram_followers!=0)
		{
			$add_new_contact->Instagram_Followers_C__c= $c_instagram_followers;
		}
		
		$add_new_contact->LinkedIn_URL_C__c= $c_linkedin_url;
		$add_new_contact->linkedin_username_C__c= $c_linkedin_username;
		$add_new_contact->LinkedIn_Bio_C__c= $c_linkedin_bio;
		if($c_linkedin_followers!=0)
		{
			$add_new_contact->linkedIn_followers_C__c= $c_linkedin_followers;
		}
		
		$add_new_contact->Pinterest_URL_C__c= $c_pinterest_url;
		$add_new_contact->Pinterest_username_C__c= $c_pinterest_username;
		$add_new_contact->Pinterest_Bio_C__c= $c_pinterest_bio;
		if($c_pinterest_followers!=0)
		{
			$add_new_contact->Pinterest_Followers_C__c= $c_pinterest_followers;
		}
		
		$add_new_contact->Twitter_URL_C__c= $c_twitter_url;
		$add_new_contact->Twitter_Username_C__c= $c_twitter_username;
		$add_new_contact->Twitter_Bio_C__c= $c_twitter_bio;
		if($c_twitter_followers!=0)
		{
			$add_new_contact->Twitter_Followers_C__c= $c_twitter_followers;
		}
		
		$add_new_contact->Youtube_Username_C__c= $c_youtube_username;
		$add_new_contact->Youtube_Bio_C__c= $c_youtube_bio;
		if($c_youtube_followers!=0)
		{
			$add_new_contact->Youtube_Followers_C__c= $c_youtube_followers;
		}
		return $add_new_contact;
}
function Okta_Api($first_name,$last_name,$email,$contacttype,$account_type,$password)
{
	
if($contacttype=='01228000000TLju') //admin
{
	if($account_type=='01228000000TJWy') //seller
	{
		$groupId=array
					(
						"00gbyikcvf04rzkyk0h7"
					);		
	}
	else
	{
		$groupId=array
					(
						"00gbyignmtxq4t14L0h7",
						"00gbyikcvf04rzkyk0h7"
					);		
	}
	
}
else if($contacttype=='01228000000TLjz') //associate
{
	if($account_type=='01228000000TJWy')//seller
	{
		$groupId=array
					(
						"00gcaj6jhwUkfNNtz0h7",
						"00gbyikcvf04rzkyk0h7"
					);		
	}
	else
	{
		$groupId=array
					(
						"00gcaj6jhwUkfNNtz0h7",
						"00gbyignmtxq4t14L0h7"
					);
	}
	
	
}
$add_user = array
				(
					"profile" => array
								(
									"firstName"=> $first_name,
									"lastName"=> $last_name,
									
									"email"=> $email,
									"login"=> $email,
								),
					"credentials" => array
										(
											"password" => array("value"=> $password)
										), 				
					"groupIds"=>$groupId
			);
	

	$add_user_result = Okta ("/api/v1/users?activate=false", "POST", $add_user);
	return $add_user_result;
}
function Okta_Login($email,$password)
{
	$user_login = array
				(
					"username"=> $email,
					"password"=> $password		
				);

	$add_user_result = Okta ("/api/v1/authn", "POST", $user_login);
	return $add_user_result;
}

function Okta_email_check($email)
{
	$add_user_result = Okta ("/api/v1/users?q=".$email."&limit=1", "GET", array());
	return $add_user_result;
}

function Zendesk_Api($first_name,$email)
{
	$user_data = array
				(
					"user" => array
							(
								"name"=> $first_name,
								"email"=> $email,
								"verified"=> true,
							)
				);

	$activate_result = zendesk ("/api/v2/users/create_or_update.json", "POST",$user_data);
	return $activate_result;
}

function set_okta_zendesk_account($email,$first_name,$last_name,$contact_type,$account_type,$password)
{
	$baseUrl = "https://dev-817806.oktapreview.com";
	
	$msg=array();
	$msg['error']="";
	$msg['id']="";
	
	/*OCTA Api Starts*/
	$add_user_result=Okta_Api($first_name,$last_name,$email,$contact_type,$account_type,$password);

	if(isset($add_user_result->errorCauses))
	{									
		$msg['error']=$add_user_result->errorCauses[0]->errorSummary;
		writeToLog($msg['error']);
	}
	else
	{
		/* Zendesk API Start*/
		$user_id=$add_user_result->id;
		$activate_result= Zendesk_Api($first_name,$email);

		if(isset($activate_result->error))
		{
			if(isset($activate_result->error->title) && isset($activate_result->error->message))
			{
				$msg['error']= $activate_result->error->title ."<br/>";
				$msg['error'].= $activate_result->error->message;
			}
			else
			{
				$msg['error']= $activate_result->error ."<br/>";
				$msg['error'].= $activate_result->description;
			}
			writeToLog($msg['error']);
		}
		else
		{
			$msg['id']=$add_user_result->id;	
			//assign_zendesk_app($email,$user_id);
		}
					
	}

	$msg_string=isset($msg['error'])?$msg['error']:'';
	if(isset($msg['id']))
	{
		if(trim($msg['id'])!=null)
		{
			//fire email
			$mailSubject="Wine-oh Confirmation";
			$mailBody='<!DOCTYPE html>
						<html>
						<body style="font-family:Verdana, Geneva, sans-serif; margin:0;">

						<div style="max-width:680px; width:100%; margin:auto; border:1px solid #333; padding:15px;    min-height: 150px;">
						<p style="margin-bottom:5px; color:#000;">
						Hello '.$first_name.' '.$last_name.',<br/><br/>

						Welcome to Wine-Oh!.Wine-Oh! manages its web applications through a single, secure home page and we have created a user account for you.
						<br/>
						Click the following <a href='.home_url().'/send-verification-email?tokenT='.$contact_type.'&tokenI='.$okta_msg['id'].'> link </a> to activate your account. This link expires in 7 days.<br/>
						<br/>

						Thanks,<br/>
						Team Wine-Oh 
						</p>
						</div>

						</body>
						</html>';
			// $link="<a href=".home_url()."/send-verification-email?tokenT=".$contact_type."&tokenI=".$okta_msg['id'].">click here to verify.</a>";
			send_email($email,$mailSubject,$mailBody);
			
			$msg_string="Your account has been registered successfully. Please check your registered email for confirmation.";
		}							
	}
	return $msg_string;
}


function send_associate_notification($email,$firstname,$lastname)
{
	$mailSubject="New Associate";
	$mailBody='<!DOCTYPE html>
				<html>
				<body style="font-family:Verdana, Geneva, sans-serif; margin:0;">

				<div style="max-width:680px; width:100%; margin:auto; border:1px solid #333; padding:15px;    min-height: 150px;">
				<p style="margin-bottom:5px; color:#000;">
				Hello Account Holder,<br/><br/>

				'.$firstname.' '.$lastname.' has joined as a new associate in your account. Visit your dashboard to activate the associate.
				<br/>
				<br/>

				Thanks,<br/>
				Team Wine-Oh 
				</p>
				</div>

				</body>
				</html>';
	
			send_email($email,$mailSubject,$mailBody);
}


function assign_zendesk_app($email,$user_id)
{
 $user_login = array
    (
      "id"=> $user_id,
       "scope"=> "USER",
       "credentials"=>
       array(
      "userName"=> $email
       )
    );

 $add_user_result = Okta ("/api/v1/apps/0oac9rfphgVYOgIWT0h7/users", "POST", $user_login);
 return $add_user_result;
}
function fraction_to_min_sec($coord)
{
  $isnorth = $coord>=0;
  $coord = abs($coord);
  $deg = floor($coord);
  $coord = ($coord-$deg)*60;
  $min = floor($coord);
  $sec = floor(($coord-$min)*60);
  //return array($deg, $min, $sec, $isnorth ? 'N' : 'S');
  // or if you want the string representation
  return sprintf("%d&deg;%d'%d\"%s", $deg, $min, $sec, $isnorth ? 'N' : 'S');
}
?>
