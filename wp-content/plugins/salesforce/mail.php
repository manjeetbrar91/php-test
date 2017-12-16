<?php
$msg='';

require 'PHPMailer/PHPMailerAutoload.php';

$mailTo='nishu.developtech@gmail.com';
$mailSubject='Wine Oh Contact';
$mailBody='This is test';

$mail = new PHPMailer;

// SMTP configuration
// $mail->isSMTP();
// $mail->Host = '146.148.61.11';
// $mail->SMTPAuth = false;
// $mail->Port = 25;


$mail->setFrom($mailTo, 'Wineoh');
$mail->addAddress($mailTo, 'Wineoh');
$mail->isHTML(true);
$mail->Subject = $mailSubject;
$mail->msgHTML($mailBody);
$mail->AltBody = 'This is a plain-text message body';

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    echo "Message has been sent successfully.";
}



/*function send_email($mailTo,$mailSubject,$mailBody)
{
	date_default_timezone_set('Asia/Kolkata');
	$mail = new PHPMailer;
	$mail->isSMTP();
	// $mail->SMTPDebug = 1;
	$mail->Debugoutput = 'html';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = "nishu.developtech@gmail.com";
	$mail->Password = "nishu@123";
	$mail->setFrom($mailTo, 'Wineoh');
	$mail->addAddress($mailTo, 'Wineoh');
	$mail->Subject = $mailSubject;
	$mail->msgHTML($mailBody);
	$mail->AltBody = 'This is a plain-text message body';
	$mail->send();

}*/

	if(isset($_POST['submit']))
	{
		$first_name=$_POST['first_name'];
		$last_name=$_POST['last_name'];
		$email=$_POST['email'];
		$mobile=$_POST['mobile'];
		$message=$_POST['message'];
		
		if(trim($first_name)!=null && trim($last_name)!=null && trim($email)!=null && trim($mobile)!=null  && trim($message)!=null)
		{
			
			$mailTo='nishu.developtech@gmail.com';
			$mailSubject='Wine Oh Contact';
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
			
			
	
				
			
			send_email($mailTo,$mailSubject,$mailBody);
			$msg="Thanks for contacting us! We will get in touch with you shortly.";
			
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

					<input class="btn btn-primary " type="submit" value="Submit" name="submit">
				</div>
			</form>
		</div>
	</div>
</div>
