<?php 
$msg="";
$loginmsg="";

global $user_ID, $user_identity; if (!$user_ID) 
	{ 

		$path= plugin_dir_url( __FILE__ ).'soapclient';	
		require_once ('soapclient/SforceEnterpriseClient.php');
		require_once ('soapclient/SforceHeaderOptions.php');
		include('function.php');
		include('keys.php');
		if(isset($_POST['submit']))
			{
				$first_name=$_POST['first_name'];
				$last_name=$_POST['last_name'];
				$email=$_POST['email'];
				$mobile=$_POST['mobile'];
				$company=$_POST['company'];
				$city=$_POST['city'];
				$state=$_POST['state'];
				if(trim($first_name)!=null && trim($last_name)!=null && trim($email)!=null && trim($mobile)!=null && trim($company)!=null)
				{
					$mailTo=$your_email_id;
					$mailSubject='Wine Oh Partner';
					/*$mailBody='<?xml version="1.0" encoding="ISO-8859-1"?>
							<lead>
							<email>'.$email.'</email>
							<persona__c>Company</persona__c>
							<LeadSource>Website</LeadSource >
							<Lead_Source_Detail__c>Wine-Oh</Lead_Source_Detail__c>
							<RecordType>Contact</RecordType>
							<LastName>'.$last_name.'</LastName>
							<Project__c>beta</Project__c>
							</lead>';*/
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
						$mySforceConnection = new SforceEnterpriseClient();
						$mySoapClient = $mySforceConnection->createConnection($path.'/enterprise.wsdl.xml');
						$mylogin = $mySforceConnection->login($USERNAME, $PASSWORD);
						
						//Check Lead Exists With Same Email Id
						$query = "Select l.Id, l.IsConverted,ConvertedAccountId From Lead l where Email='".$email."'";
						$result=$mySforceConnection->query($query);
						$sObject = new SObject($result->records[0]);
						$is_lead_converted_account= $sObject->IsConverted;
						
						if(count($result->records[0])>=1)
						{
							if($is_lead_converted_account!=1)
							{
								$leadId=$sObject->Id;
								$leadConvert = new stdClass;
								$leadConvert->convertedStatus='Converted';
								$leadConvert->doNotCreateOpportunity='true';
								$leadConvert->leadId=$leadId;
								$leadConvert->overwriteLeadSource='true';
								// $leadConvert->Account_Record_Type__c='Company';
								$leadConvert->sendNotificationEmail='true';

								$leadConvertArray = array($leadConvert);
								$leadConvertResponse = $mySforceConnection->convertLead($leadConvertArray); 
								
								
								
								global $wpdb;
								$chars = array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
								$password = '';
								$max = count($chars)-1;
								for($i=0;$i<6;$i++)
								{
									$password.= $chars[rand(0, $max)];
								}
								$data_array = array('user_login'=>$email,'user_pass'=>md5($password),'user_nicename'=>$first_name,'user_email'=>$email,'display_name'=>$first_name,'user_type'=>'partner', 'user_first_name'=>$first_name, 'user_last_name'=>$last_name, 'user_mobile'=>$mobile, 'user_company'=>$company,'user_registered'=>$cur_date_time);
								$result= $wpdb->insert( $wpdb->prefix . 'users', $data_array );
								if($result)
								{
									$mailTo1=$_POST['email'];
									$mailSubject1='Wine Oh Password';
									$mailBody1 = "Your Password is: ".$password;
									
									send_email($mailTo1,$mailSubject1,$mailBody1);
									
								}
								
								send_email($mailTo,$mailSubject,$mailBody);
								$msg= "Congrats! You have become our partner.";
							}	
							else
							{
								$msg= "You have already registered as a partner.";
							}
							
						}
						else
						{
							//Add Lead
							$addLead = new stdClass(); 
							$addLead->FirstName = $first_name; 
							$addLead->LastName = $last_name;
							$addLead->Title = 'Lead from Partner Page';
							$addLead->LeadSource = 'Website';
							$addLead->Email = $email;
							$addLead->Phone = $mobile;
							$addLead->Company = $company;
							$addLead->Status  = 'Unaware';
							$addLead->Project__c = 'beta';
							$addLead->RecordTypeId = '01228000000SbE8';
							// $addLead->PostalCode = '147852';
							// $addLead->State = 'Punjab';
							// $addLead->Country = 'India';
							// $addLead->NumberOfEmployees = (int)(100);
							$response = $mySforceConnection->create(array($addLead),'Lead');  
							
							// var_dump($response);
							
							$leadId=$response[0]->id;
							
							//Convert Lead
							$leadConvert = new stdClass;
							$leadConvert->convertedStatus='Converted';
							$leadConvert->doNotCreateOpportunity='true';
							$leadConvert->leadId=$leadId;
							$leadConvert->overwriteLeadSource='true';
							// $leadConvert->Account_Record_Type__c='Company';
							$leadConvert->sendNotificationEmail='true';

							$leadConvertArray = array($leadConvert);
							$leadConvertResponse = $mySforceConnection->convertLead($leadConvertArray); 
							
							// var_dump($leadConvertResponse);
							
							global $wpdb;
							$chars = array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
							$password = '';
							$max = count($chars)-1;
							for($i=0;$i<6;$i++)
							{
								$password.= $chars[rand(0, $max)];
							}
							$data_array = array('user_login'=>$email,'user_pass'=>md5($password),'user_nicename'=>$first_name,'user_email'=>$email,'display_name'=>$first_name,'user_type'=>'partner', 'user_first_name'=>$first_name, 'user_last_name'=>$last_name, 'user_mobile'=>$mobile, 'user_company'=>$company,'user_registered'=>$cur_date_time);
							$result= $wpdb->insert( $wpdb->prefix . 'users', $data_array );
							if($result)
							{
								$mailTo1=$_POST['email'];
								$mailSubject1='Wine Oh Password';
								$mailBody1 = "Your Password is: ".$password;
								
								send_email($mailTo1,$mailSubject1,$mailBody1);
								
							}
							
							send_email($mailTo,$mailSubject,$mailBody);
							$msg="Thanks for contacting us! We will get in touch with you shortly.";
							
						}
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
		<div class="left_register_sidebar wpb_column vc_column_container vc_col-sm-6">
			<div class="vc_column-inner ">
				<div class="wpb_wrapper">

					<h2 style="font-size: 30px;text-align: left" class="vc_custom_heading">New Partner</h2>
					<div class="form_wrapper">
						<?php
							if(trim($msg)!=null) 
							{
								?>
									<div class="alert alert-success-theme"><?php echo $msg; ?></div>
								<?php
							}
						?>
						<form action="" method="POST" id="partner-form" >
							<div class="form_body">
								<div class="form-group">
									<label>Company Name<span class="field_required">*</span></label>
									<input id="company" name="company" type="text" value="" class="form-control"  >
								</div>
								<div class="form-group">
									<label>Email<span class="field_required">*</span></label>
									<input id="email" name="email"  type="text" value="" class="form-control" >
								</div>
								<div class="form-group">
									<label>First Name<span class="field_required">*</span></label>
									<input id="first_name" name="first_name" type="text" value="" class="form-control"  >
								</div>
								<div class="form-group">
									<label>Last Name<span class="field_required">*</span></label>
									<input id="last_name" name="last_name" type="text" value="" class="form-control"  >
								</div>
								
								<div class="form-group">
									<label>Mobile<span class="field_required">*</span></label>
									<input id="mobile" name="mobile"  type="text" value="" class="form-control" >
								</div>
							
								<input class="btn btn-primary " type="submit" value="Submit" name="submit">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="right_login_sidebar wpb_column vc_column_container vc_col-sm-6">
			<div class="vc_column-inner ">
				<div class="wpb_wrapper">
					<h2 style="font-size: 30px;text-align: left" class="vc_custom_heading">Existing Users</h2>
					<div class="form_wrapper">

						<?php 
						if(count($_GET)>=1)
						{
							$loginmsg="ERROR: Invalid email or password. ";
						}
						if(trim($loginmsg)!=null) 
							{
						?>
							<div class="alert alert-success-theme"><?php echo $loginmsg; ?></div>
						<?php
							}
						?>

						<form method="post" action="<?php bloginfo('url') ?>/wp-login.php" class="wp-user-form" id="login-form">
							<div class="form_body">
								<div class="form-group username">
									<label for="user_login"><?php _e('Email'); ?><span class="field_required">*</span> </label>
									<input type="text" name="log" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login"  class="form-control" />
								</div>
								<div class="form-group password">
									<label for="user_pass"><?php _e('Password'); ?><span class="field_required">*</span> </label>
									<input type="password" name="pwd" value="" size="20" id="user_pass" class="form-control" />
								</div>
								<div class="login_fields">
									<div class="rememberme">
										<label for="rememberme">
											<input type="checkbox" name="rememberme" value="forever" checked="checked" id="rememberme" tabindex="13" /> Remember me
										</label>
									</div>
									<?php do_action('login_form'); ?>
									<input type="submit" name="user-submit" value="<?php _e('Login'); ?>" tabindex="14" class="user-submit" />
									<input type="hidden" name="redirect_to" value="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" />
									<input type="hidden" name="user-cookie" value="1" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php 
	} 
	else 
	{  
	?>
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

	  
		  <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/tab.css">
		   <h4 style="margin-bottom: 30px;" class="text-right">
			   <a href="<?php echo wp_logout_url('index.php'); ?>" style="color: #c8385e;">Logout</a>
			</h4>
	<div class="tabs">
	  
	  <input type="radio" id="tab1" name="tab-control" checked>
	  <input type="radio" id="tab2" name="tab-control">
	  <input type="radio" id="tab3" name="tab-control">  
	  <input type="radio" id="tab4" name="tab-control">
	  <input type="radio" id="tab5" name="tab-control">
	  <input type="radio" id="tab6" name="tab-control">
		<ul>
			<li title="Profile">
				<label for="tab1" role="button"><svg viewBox="0 0 24 24"><path d="M14,2A8,8 0 0,0 6,10A8,8 0 0,0 14,18A8,8 0 0,0 22,10H20C20,13.32 17.32,16 14,16A6,6 0 0,1 8,10A6,6 0 0,1 14,4C14.43,4 14.86,4.05 15.27,4.14L16.88,2.54C15.96,2.18 15,2 14,2M20.59,3.58L14,10.17L11.62,7.79L10.21,9.21L14,13L22,5M4.93,5.82C3.08,7.34 2,9.61 2,12A8,8 0 0,0 10,20C10.64,20 11.27,19.92 11.88,19.77C10.12,19.38 8.5,18.5 7.17,17.29C5.22,16.25 4,14.21 4,12C4,11.7 4.03,11.41 4.07,11.11C4.03,10.74 4,10.37 4,10C4,8.56 4.32,7.13 4.93,5.82Z"/>
				</svg><br><span>Profile</span></label>
			</li>
			<li title="Upload File">
				<label for="tab2" role="button"><svg viewBox="0 0 24 24"><path d="M2,10.96C1.5,10.68 1.35,10.07 1.63,9.59L3.13,7C3.24,6.8 3.41,6.66 3.6,6.58L11.43,2.18C11.59,2.06 11.79,2 12,2C12.21,2 12.41,2.06 12.57,2.18L20.47,6.62C20.66,6.72 20.82,6.88 20.91,7.08L22.36,9.6C22.64,10.08 22.47,10.69 22,10.96L21,11.54V16.5C21,16.88 20.79,17.21 20.47,17.38L12.57,21.82C12.41,21.94 12.21,22 12,22C11.79,22 11.59,21.94 11.43,21.82L3.53,17.38C3.21,17.21 3,16.88 3,16.5V10.96C2.7,11.13 2.32,11.14 2,10.96M12,4.15V4.15L12,10.85V10.85L17.96,7.5L12,4.15M5,15.91L11,19.29V12.58L5,9.21V15.91M19,15.91V12.69L14,15.59C13.67,15.77 13.3,15.76 13,15.6V19.29L19,15.91M13.85,13.36L20.13,9.73L19.55,8.72L13.27,12.35L13.85,13.36Z" />
				</svg><br><span>Upload File</span></label>
			</li>
			<li title="Add Wines">
				<label for="tab3" role="button"><i class="fa icon-glass-of-burgundy"></i> <br><span>Add Wines</span></label>
			</li>   
			<li title="Our Wines">
				<label for="tab4" role="button"><i class="fa icon-wine-bottle-in-bucket-with-two-glasses"></i> <br><span>Our Wines</span></label>
			</li>
			<li title="Our Locations">
				<label for="tab5" role="button"><i class="fa fa-map-marker"></i> <br><span>Our Locations</span></label>
			</li>
			<li title="Our Prices">
				<label for="tab6" role="button"><i class="fa icon-corckscrew"></i> <br><span>Our Prices</span></label>
			</li>
		</ul>
	  
	  <div class="slider"><div class="indicator"></div></div>
	  <div class="content">
		<section>
		  <h2>Profile</h2>
		  <?php $user_info = get_userdata($user_ID);?>
		  <b>Welcome <?php echo $user_identity; ?></b><br/><br/>
		  
		  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea dolorem sequi, quo tempore in eum obcaecati atque quibusdam officiis est dolorum minima deleniti ratione molestias numquam. Voluptas voluptates quibusdam cum?
		  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea dolorem sequi, quo tempore in eum obcaecati atque quibusdam officiis est dolorum minima deleniti ratione molestias numquam. Voluptas voluptates quibusdam cum?
		  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea dolorem sequi, quo tempore in eum obcaecati atque quibusdam officiis est dolorum minima deleniti ratione molestias numquam. Voluptas voluptates quibusdam cum?
		  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea dolorem sequi, quo tempore in eum obcaecati atque quibusdam officiis est dolorum minima deleniti ratione molestias numquam. Voluptas voluptates quibusdam cum?</p>
		</section>
		<section>
			<h2>Upload File</h2>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem quas adipisci a accusantium eius ut voluptatibus ad impedit nulla, ipsa qui. Quasi temporibus eos commodi aliquid impedit amet, similique nulla.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem quas adipisci a accusantium eius ut voluptatibus ad impedit nulla, ipsa qui. Quasi temporibus eos commodi aliquid impedit amet, similique nulla.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem quas adipisci a accusantium eius ut voluptatibus ad impedit nulla, ipsa qui. Quasi temporibus eos commodi aliquid impedit amet, similique nulla.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem quas adipisci a accusantium eius ut voluptatibus ad impedit nulla, ipsa qui. Quasi temporibus eos commodi aliquid impedit amet, similique nulla.
		</section>
		<section>
			<h2>Add Wines</h2>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam nemo ducimus eius, magnam error quisquam sunt voluptate labore, excepturi numquam! Alias libero optio sed harum debitis! Veniam, quia in eum.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam nemo ducimus eius, magnam error quisquam sunt voluptate labore, excepturi numquam! Alias libero optio sed harum debitis! Veniam, quia in eum.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam nemo ducimus eius, magnam error quisquam sunt voluptate labore, excepturi numquam! Alias libero optio sed harum debitis! Veniam, quia in eum.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam nemo ducimus eius, magnam error quisquam sunt voluptate labore, excepturi numquam! Alias libero optio sed harum debitis! Veniam, quia in eum.
			</section>
		<section>
			<h2>Our Wines</h2>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
		</section>
		<section>
			<h2>Our Locations</h2>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
		</section>
		<section>
			<h2>Our Prices</h2>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa dicta vero rerum? Eaque repudiandae architecto libero reprehenderit aliquam magnam ratione quidem? Nobis doloribus molestiae enim deserunt necessitatibus eaque quidem incidunt.
		</section>
	  </div>
	</div>


<?php } ?>