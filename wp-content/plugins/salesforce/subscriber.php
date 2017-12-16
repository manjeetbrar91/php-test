<?php
include('keys.php');	
include('subscriber_function.php');
	
	$fields_string='';

	if(isset($_POST['submit_newsletter']))
	{
		
		$email=$_POST['email'];
		$contactInfo= get_data($email,$apikey);
		
		$name='';
		$first_name='';
		$last_name='';
		
		if(trim($email)!=null)
		{
			if(count($contactInfo)>0)
			{
				$name=$contactInfo['fullName'];
				$first_name=$contactInfo['givenName'];
				$last_name=$contactInfo['familyName'];
			}
			$path= plugin_dir_url( __FILE__ ).'soapclient';	
			require_once ('soapclient/SforceEnterpriseClient.php');
			require_once ('soapclient/SforceHeaderOptions.php');
			
			try 
			{
				$mySforceConnection = new SforceEnterpriseClient();
				$mySoapClient = $mySforceConnection->createConnection($path.'/enterprise.wsdl.xml');
				$mylogin = $mySforceConnection->login($USERNAME, $PASSWORD);
				
				
				$lead_query = "Select l.Id,l.Website,l.FirstName,l.LastName,l.IsConverted,l.ConvertedAccountId From Lead l where Email='".$email."'";
				$lead_result=$mySforceConnection->query($lead_query);
				
				if(count($lead_result->records)==0)
				{
					//Add Lead
					$addLead = new stdClass(); 
					$addLead->FirstName = $first_name; 
					$addLead->LastName = $last_name;
					$addLead->Email = $email;
					$addLead->LeadSource = 'Website';
					//$addLead->RecordTypeId = '01228000000SvdH';
					// $addLead->Status = 'Aware';
					// $addLead->MyNext_Step__c = 'Engaging';
					$response = $mySforceConnection->create(array($addLead),'Lead');
					// var_dump($response);
					// die;
				}
				else
				{
					$addLead = new stdClass(); 
					$addLead->Id = $lead_result->records[0]->Id;
					$addLead->FirstName = $first_name; 
					$addLead->LastName = $last_name;
					$addLead->Email = $email;
					$addLead->LeadSource = 'Website';
					//$addLead->RecordTypeId = '01228000000SvdH';
					// $addLead->Status = 'Interest';
					// $addLead->MyNext_Step__c = 'Engage';
					$response = $mySforceConnection->update(array($addLead),'Lead');
					//var_dump($response);
					//die;					


					$sObject = new stdClass(); 
					$sObject->WhoID = $lead_result->records[0]->Id;
					$sObject->Subject = $email;
					$sObject->Priority = 'Normal';
					$sObject->Status = 'Open';
					$sObject->Description = '<?xml version="1.0" encoding="ISO-8859-1"?> <lead> <email>'.$email.'</email> <persona__c>Consumer</persona__c> <LeadSource>Website</LeadSource > <Lead_Source_Detail__c>SubscribeButton</Lead_Source_Detail__c> <RecordType>01228000000SvdHAAS</RecordType> <LastName>'.$last_name.'</LastName> <Project__c>Alpha</Project__c> </lead>';
					$createResponse = $mySforceConnection->create(array($sObject),'Task');
					
				}
				
				$mailTo=$your_email_id;
				$mailSubject='Wine Oh Subscriber';
				$mailBody='<span> &lt;?xml version="1.0" encoding="ISO-8859-1"?&gt;
						&lt;lead&gt;
						&lt;email&gt;'.$email.'&lt;/email&gt;
						&lt;persona__c&gt;Consumer&lt;/persona__c&gt;
						&lt;LeadSource&gt;Website&lt;/LeadSource >
						&lt;Lead_Source_Detail__c&gt;Wine-Oh&lt;/Lead_Source_Detail__c&gt;
						&lt;RecordType&gt;01228000000SvdHAAS&lt;/RecordType&gt;
						&lt;LastName&gt;'.$last_name.'&lt;/LastName&gt;
						&lt;Project__c&gt;Alpha&lt;/Project__c&gt;
						&lt;/lead&gt;
						</span>';
				
				send_subcriber_email($mailTo,$mailSubject,$mailBody,$mailer_username,$mailer_password,$client_subscriber_id);

				$msg="Thanks for the subscription.";

			} 
			catch (Exception $e) 
			{
			  echo $e->faultstring;
			}
		
		
		}
		else
		{
			$msg="Please enter all required fields";
		}
		
	}
	
	
?>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<?php include('stylejs.php'); ?>

<div class="col-md-3 widget widget_text">
	<h2 class="widget-title maincolor1">LEARN MORE ABOUT Wine-OH!</h2>
	<form action="#subscriber-form" method="POST" id="subscriber-form" >
		<input type=hidden name="oid" value="<?php echo $salesforce_id; ?>">
		<div class="form-group">
			<label>Email<span class="field_required">*</span></label>
			<input id="email" name="email" type="text" class="form-control" placeholder=""  />
		</div>
<div class="form-group" style="width:100%;float:left;">
		<input class="btn btn-primary " type="submit" value="Sign Up" name="submit_newsletter"/>
</div>
		<?php
		if(trim($msg)!=null) 
		{
			?>
				<div class="alert alert-success-theme"><?php echo $msg; ?></div>
			<?php
		}
		?>
	</form>
</div>		
