<?php
 $msg='';
	include('function.php');
	include('keys.php');
	
	$fields_string='';
    $url = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
	if(isset($_POST['submit']))
	{
		$first_name=$_POST['first_name'];
		$last_name=$_POST['last_name'];
		$email=$_POST['email'];
		$mobile=$_POST['mobile'];
		$message=$_POST['message'];
		$city=$_POST['city'];
		$state=$_POST['state'];
		$salesforce_id=$_POST['oid'];
        $path= plugin_dir_url( __FILE__ ).'soapclient';	
		require_once ('soapclient/SforceEnterpriseClient.php');
		require_once ('soapclient/SforceHeaderOptions.php');
		if(trim($first_name)!=null && trim($last_name)!=null && trim($email)!=null && trim($mobile)!=null  && trim($message)!=null)
		{
			try 
			{
				$mySforceConnection = new SforceEnterpriseClient();
				$mySoapClient = $mySforceConnection->createConnection($path.'/enterprise.wsdl.xml');
				$mylogin = $mySforceConnection->login($USERNAME, $PASSWORD);
				
				
				//Add Lead
				$addLead = new stdClass(); 
				$addLead->FirstName = $first_name; 
				$addLead->LastName = $last_name;
				$addLead->Email = $email;
				$addLead->LeadSource = 'Website';
				$addLead->RecordTypeId = '01228000000SvdH';
                // $addLead->Lead_Source_Detail__c = 'Wine-Oh';
				$addLead->Project__c = 'beta';
                $addLead->Type__c = 'beta';
				$response = $mySforceConnection->create(array($addLead),'Lead');
				// var_dump($response);
				
				$mailTo=$your_email_id;
				$mailSubject='Wine Oh Contact';
				$mailBody='<span> &lt;?xml version="1.0" encoding="ISO-8859-1"?&gt;
						&lt;lead&gt;
						&lt;email&gt;'.$email.'&lt;/email&gt;
						&lt;persona__c&gt;Consumer&lt;/persona__c&gt;
						&lt;LeadSource&gt;Website&lt;/LeadSource >
						&lt;Lead_Source_Detail__c&gt;Wine-Oh&lt;/Lead_Source_Detail__c&gt;
						&lt;RecordType&gt;Person&lt;/RecordType&gt;
						&lt;LastName&gt;'.$last_name.'&lt;/LastName&gt;
						&lt;Project__c&gt;beta&lt;/Project__c&gt;
						&lt;/lead&gt;
						</span>';
				
				send_email($mailTo,$mailSubject,$mailBody);
				$msg="Thanks for contacting us! We will get in touch with you shortly.";

			} 
			catch (Exception $e) 
			{
				$e->faultstring;
				$msg= 'Something went wrong. Please try again later.';	
			  	writeToLog($e->faultstring);
			}
			
		}
		else
		{
			$msg="Please enter all required fields";
		}
				
	}
?>
<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
<?php include('stylejs.php'); ?>

<div class="vc_column-inner ">
	<div class="wpb_wrapper">
		<div class="form_wrapper">
			<?php
				if(trim($msg)!=null) 
				{
					?>
						<div class="alert alert-success-theme"><?php echo $msg; ?></div>
					<?php
				}
			?>
			<form action="" method="POST" id="lead-form" >
				<input type=hidden name="oid" value="<?php echo $salesforce_id; ?>">
				<div class="form_body">
					<div class="form-group">
						<label>First Name<span class="field_required">*</span></label>
						<input id="first_name" name="first_name" type="text" value="" class="form-control"  >
					</div>
					<div class="form-group">
						<label>Last Name<span class="field_required">*</span></label>
						<input id="last_name" name="last_name" type="text" value="" class="form-control"  >
					</div>
					<div class="form-group">
						<label>Email<span class="field_required">*</span></label>
						<input id="email" name="email"  type="text" value="" class="form-control" >
						
					</div>
					<div class="form-group">
						<label>Mobile<span class="field_required">*</span></label>
						<input id="mobile" name="mobile"  type="text" value="" class="form-control" >
					</div>
					<div class="form-group">
						<label>Message<span class="field_required">*</span></label>
						<textarea id="message" name="message" class="form-control"  rows="4" cols="100" style=" background-color: #fffdfd;"></textarea>
					</div>
<div class="g-recaptcha" data-sitekey="6LfMOjIUAAAAAAv8wiojRVu0XKrwP0E9NswErqPa"></div>
<p id="captchaerror" class="error" style="display:none; background: #e0456d; color: #fff; padding: 7px 0px 9px 13px; border: 1px solid #333; width: 35%;">* Please check the recaptcha</p>
					
					<input class="btn btn-primary lead-submit " type="submit" value="Submit" name="submit">
				</div>
			</form>
		</div>
	</div>
</div>
