<?php
 $msg='';
	function send_email($mailTo,$mailSubject,$mailBody)
	{
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n"; 
		$headers .= 'From: Wine Oh <info@wineoh.com>' . "\r\n";
		mail($mailTo,$mailSubject,$mailBody,$headers);

	}

	$fields_string='';
    $url = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
	if(isset($_POST['submit']))
	{
		$ch_register_first_name=$_POST['first_name'];
		$ch_register_last_name=$_POST['last_name'];
		$ch_register_email=$_POST['email'];
		$ch_register_mobile=$_POST['mobile'];
		$ch_register_company=$_POST['company'];
		$ch_register_city=$_POST['city'];
		$ch_register_state=$_POST['state'];
		$ch_register_salesforce_id=$_POST['oid'];
		$fields = array(
				'first_name'=>urlencode($ch_register_first_name),
				'last_name'=>urlencode($ch_register_last_name),
				'email'=>urlencode($ch_register_email),
				'mobile'=>urlencode($ch_register_mobile),
				'company'=>urlencode($ch_register_company),
				'city'=>urlencode($ch_register_city),
				'state'=>urlencode($ch_register_state),
				'oid' => $ch_register_salesforce_id, // insert with your id 
				'retURL' => urlencode('thank-you/'), // sending this just in case
				'debug' => '0',
				'debugEmail' => urlencode(""), // your debugging email
		);


		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');


		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, TRUE);

		$result = curl_exec($ch);

		curl_close($ch);
		
		$mailTo='nishu.developtech@gmail.com';
		$mailSubject='Wine Oh XML File';
		$mailBody="<span> &lt;?xml version=\"1.0\" encoding=\"ISO-8859-1\"?&gt;
		&lt;lead&gt;
		&lt;persona__c&gt;Investor&lt;/persona__c&gt;
		&lt;LeadSource&gt;Website&lt;/LeadSource >
		&lt;Lead_Source_Detail__c&gt;Wine-Oh&lt;/Lead_Source_Detail__c&gt;
		&lt;RecordType&gt;01228000000SvdHAAS&lt;/RecordType&gt;
		&lt;LastName&gt;Unknown&lt;/LastName&gt;
		&lt;Project__c&gt;beta&lt;/Project__c&gt;
		&lt;/lead&gt;
		</span>";
		
		if(send_email($mailTo,$mailSubject,$mailBody))
		{
			$msg="Thanks for contacting us! We will get in touch with you shortly.";
		}
		else
		{
			$msg="Thanks for contacting us! We will get in touch with you shortly.";
		}
				
	}
?>

<link rel='stylesheet' href="<?php echo plugin_dir_url( __FILE__ ); ?>css/style.css" type='text/css' media='all'>
<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/jquery.validate.js"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/jquery.js"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/app.js"></script>

  
      <div class="ia-heading ia-heading-heading_7887 heading-align-left " data-delay="0">
         <h2>
            Contact Us                
         </h2>
         <div class="clearfix"></div>
      </div>
      <div class="form_wrapper">
         <form action="#" method="POST" id="contact-form" >
				<input type=hidden name="oid" value="00D7F0000010bec">
				<input type=hidden name="retURL" value="http://localhost/thankyou.php">
				 <div class="form_body">
					 <ul id="" class="form_fields top_label form_sublabel_below description_below">
					  <li>
						 <label>First Name<span class="field_required">*</span></label>
						 <div class="ginput_container ginput_container_text"><input id="first_name" name="first_name" type="text" value="" class="medium"  ></div>
					  </li>
					  <li>
						 <label>Last Name<span class="field_required">*</span></label>
						 <div class="ginput_container ginput_container_text"><input id="last_name" name="last_name" type="text" value="" class="medium"  ></div>
					  </li>
					  <li class="field">
						 <label>Email<span class="field_required">*</span></label>
						 <div class="ginput_container ginput_container_email">
							<input id="email" name="email"  type="text" value="" class="medium" >
						 </div>
					  </li>
					  <li class="field">
						 <label>Mobile<span class="field_required">*</span></label>
						 <div class="ginput_container ginput_container_phone"><input id="mobile" name="mobile" id="input_1_3" type="text" value="" class="medium" ></div>
					  </li>

					</ul>
					<div class="form_footer top_label">
					<input type="submit" value="Submit" name="submit">
					</div>
					<?php echo $msg; ?>
				</div>
			</form>
      </div>
     

