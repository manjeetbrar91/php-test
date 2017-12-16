<?php 
$msg="";
$loginmsg="";
if( ($_SESSION)!=null)
{
echo '<script>location.href="'.home_url().'/dashboard/";</script>';
}
global $user_ID, $user_identity; 
	// if (!$user_ID) 
	// { 

		$path= plugin_dir_url( __FILE__ ).'soapclient';	
		require_once ('soapclient/SforceEnterpriseClient.php');
		require_once ('soapclient/SforceHeaderOptions.php');
		include('function.php');
		include('keys.php');
		if(isset($_POST['submit']))
			{
				$first_name=$_POST['first_name'];
				$last_name=$_POST['last_name'];
				$email_text=$_POST['email'];
				$mobile=$_POST['mobile'];
				$company=$_POST['company'];
				$user_url=$_POST['user_url'];
				$email= $email_text.'@'.$user_url;
				$account_type=$_POST['account_type'];
				$password=$_POST['user_pass'];
				
				
				if(trim($first_name)!=null && trim($last_name)!=null && trim($email)!=null && trim($company)!=null)
				{
					$mailTo=$your_email_id;
					$mailSubject='Wine Oh Partner';

					$mailBody='<span> &lt;?xml version="1.0" encoding="ISO-8859-1"?&gt;
						&lt;lead&gt;
						&lt;email&gt;'.$email.'&lt;/email&gt;
						&lt;persona__c&gt;Company&lt;/persona__c&gt;
						&lt;LeadSource&gt;Website&lt;/LeadSource >
						&lt;Lead_Source_Detail__c&gt;Wine-Oh&lt;/Lead_Source_Detail__c&gt;
						&lt;RecordType&gt;Contact&lt;/RecordType&gt;
						&lt;LastName&gt;'.$last_name.'&lt;/LastName&gt;
						&lt;Project__c&gt;beta&lt;/Project__c&gt;
						&lt;/lead&gt;
						</span>';
					
					
					try 
					{
						if(count(Okta_email_check($email))>0)
						{
							$msg="This email id is already registered.Please login.";
						}
						else
						{
							$mySforceConnection = new SforceEnterpriseClient();
							$mySoapClient = $mySforceConnection->createConnection($path.'/enterprise.wsdl.xml');
							$mylogin = $mySforceConnection->login($USERNAME, $PASSWORD);
							
							//Check Account name exist or not
							
							$account_query = "select Id,Name from Account where (website='http://".$user_url."' or website='".$user_url."' or website='http://www.".$user_url."') and Type='Partner'";
							$account_result=$mySforceConnection->query($account_query);

							$contact_query = "select Id, Name from Contact where Email='".$email."' ";
							$contact_result=$mySforceConnection->query($contact_query);
							
							$lead_query = "Select l.Id,l.Website,l.FirstName,l.LastName,l.IsConverted,l.ConvertedAccountId From Lead l where Email='".$email."'";
							$lead_result=$mySforceConnection->query($lead_query);
							
							if(count($account_result->records)==0 && count($contact_result->records)==0 && count($lead_result->records)==0)
							{
								
								$addAccount = new stdClass(); 
								$addAccount->Name = $company; 
								$addAccount->Website = $user_url;
								$addAccount->Type = 'Partner';
								$addAccount->RecordTypeId =$account_type;
//account full contact api
								
								$account_creation_response = $mySforceConnection->create(array($addAccount),'Account');  
								
								$account_id = $account_creation_response->records[0]->Id;
								
								//Full Contact API Work
								$contact_type="01228000000TLju";
								$add_new_contact=full_contact_api($email,$apikey,$contact_type,$account_id,$first_name,$last_name,$password);
								
								$update_contact_response = $mySforceConnection->create(array($add_new_contact),'Contact'); 
		
								//create okta --- zendesk
								
								$msg=set_okta_zendesk_account($email,$first_name,$last_name,$contact_type,$account_type,$password);							
							
							}
							else if(count($account_result->records)>0)  //If account exists
							{
								$account_id = $account_result->records[0]->Id;
								
								//Check if their are any admin contacts related to that account 
									
									
									$admin_contact_query = "select Id,FirstName,LastName,Email from Contact where AccountId='".$account_id."' and RecordTypeId='01228000000TLju'";
									$admin_contact_result=$mySforceConnection->query($admin_contact_query);	
									
									if(count($admin_contact_result->records)==0)  //It means no admin
									{
										$contact_type='01228000000TLju';	//admin record type
									}
									else
									{
										$contact_type='01228000000TLjz';	//associate record type
									}
									//Full Contact API Work
									$add_new_contact=full_contact_api($email,$apikey,$contact_type,$account_id,$first_name,$last_name,$password);
									
									$update_contact_response = $mySforceConnection->create(array($add_new_contact),'Contact'); 
									
									//create okta --- zendesk
									$msg=set_okta_zendesk_account($email,$first_name,$last_name,$contact_type,$account_type,$password);
								
									if(count($admin_contact_result->records)!=0)  //It means no admin
									{
										foreach($admin_contact_result->records as $adminData)
										{
											$email=$adminData->Email; //notify this admin that a new associate has been added 
											
											send_associate_notification($email,$first_name,$last_name);
										}
									}
							}
							
							else if(count($lead_result->records)>0)  //If lead exists, convert it to account
							{	
								$leadId=$lead_result->records[0]->Id;
								$is_converted= intval($lead_result->records[0]->IsConverted);
								
								$leadConvert=get_lead_contact_data($user_url,$apikey,$is_converted,$account_type,$leadId);
								
								$leadConvertArray = array($leadConvert);
								$leadConvertResponse = $mySforceConnection->convertLead($leadConvertArray);  
								
								//Now, get the account id
								$get_account_query = "Select Id,Name From Account where Website='".$lead_result->records[0]->Website."'";
								$get_account_result=$mySforceConnection->query($get_account_query);
							
								if(count($get_account_result->records)>0) //Get The Account Id
								{
									//Check if their are any admin contacts related to that account 
									$account_id = $get_account_result->records[0]->Id;
									
									$admin_contact_query = "select Id,FirstName,LastName,Email from Contact where AccountId='".$account_id."' and RecordTypeId='01228000000TLju'";
									$admin_contact_result=$mySforceConnection->query($admin_contact_query);	
									
									if(count($admin_contact_result->records)==0)  //It means no admin
									{
										$contact_type='01228000000TLju';	//admin record type
									}
									else
									{
										$contact_type='01228000000TLjz';	//associate record type
									}
									//Full Contact API Work
									$add_new_contact=full_contact_api($email,$apikey,$contact_type,$account_id,$first_name,$last_name,$password);
									
									$update_contact_response = $mySforceConnection->create(array($add_new_contact),'Contact'); 
									
									//create okta --- zendesk
									$msg=set_okta_zendesk_account($email,$first_name,$last_name,$contact_type,$account_type,$password);

									if(count($admin_contact_result->records)!=0)  //It means no admin
									{
										foreach($admin_contact_result->records as $adminData)
										{
											$email=$adminData->Email; //notify this admin that a new associate has been added 
											
											send_associate_notification($email,$first_name,$last_name);
										}
									}
								}
								else
								{
									//Some Error .. It means we did not get the account
								}
							}
							else if(count($contact_result->records)>0)
							{

							}
						}
						

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
		<div class="ia_row">
			<div class="vc_row wpb_row vc_row-fluid">
				<div class="wpb_column vc_column_container vc_col-sm-12">
					<div class="vc_column-inner ">
						<div class="wpb_wrapper"> 
							<div class="ia-heading ia-heading-heading_725 heading-align-center " data-delay="0">
								<h2 class="h1">	Become a Partner </h2>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
			</div>    			
		</div>
		<div class="partner-form-container wpb_column vc_column_container ">
			<div class="vc_column-inner ">
				<div class="wpb_wrapper">

					<h2 style="font-size: 30px;text-align: left" class="vc_custom_heading">Please fill this form</h2>
					<?php
						if(trim($msg)!=null) 
						{
							?>
								<div class="alert alert-success-theme"><?php echo $msg; ?></div>
							<?php
						}
					?>
					<div id="errorContainer" class="alert alert-success-theme">
						<p>Please correct the following errors and try again:</p>
						<ul />
					</div>
<div  id="error" class="alert alert-success-theme" style="display: none;">
					Password must have at least 8 characters, a lowercase letter, an uppercase letter, a number, no parts of your firstname and lastname.
					</div>
					<form action="" method="POST" id="partner-form" >
					<p>
					<br/>
						
						Hi, my name is 
							<input id="first_name" name="first_name" type="text" value="" placeholder="First Name"  autocomplete="off">
							<input id="last_name" name="last_name" type="text" value=""  placeholder="Last Name"  
 autocomplete="off"> 
						and I would like to register
							<input id="company" name="company" type="text" value="" placeholder="Company" autocomplete="off">
						as a wine 
							<select id="account_type" name="account_type">
								<option value="01228000000Sn6X">Producer</option>
								<option value="01228000000TJWy">Seller</option>
								<option value="01228000000Sn6X">Both</option>
							</select> 
						with the domain 
							<input type="text" name="user_url" id="user_url" placeholder="Domain" value="" autocomplete="off">. 
						My company email address is 
							<input id="email" name="email"  type="text" value="" placeholder="Email" autocomplete="off">
							@
							<input id="domain" name="domain"  type="text" value="" placeholder="Domain"  autocomplete="off"> 
						and I would like my password to be 
							<input id="user_pass" name="user_pass" type="password" value="" placeholder="Password" autocomplete="new-password">
						
						<br/>
						</p>
					<input class="btn btn-primary " type="submit" value="Submit" name="submit">
					</form>
				</div>
			</div>
			<center>Already Registered? <a href="<?php echo get_home_url(); ?>/login" style="color:#c8385e;">Sign In</a>
		</div>

	