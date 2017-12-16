<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<style>
form label.error{
	    width: auto !important;
}
</style>
<?php include('stylejs.php'); 
include('function.php');
include('keys.php');
$msg="";
$loginmsg="";
if(isset($_POST['btnForgot']))
{
	if(trim($_POST['txtEmail'])!=null)
	{
		$email=$_POST['txtEmail'];
		
		$email_check=Okta_email_check($email);

		
		$id=$email_check[0]->id;
		$status=$email_check[0]->status;
		$firstName=$email_check[0]->profile->firstName;
		$lastName=$email_check[0]->profile->lastName;
		
		if($status=="ACTIVE")
		{
			//send mail
			$mailSubject="Forgot Password";
			$mailBody='<!DOCTYPE html>
						<html>
						<body style="font-family:Verdana, Geneva, sans-serif; margin:0;">
						<div style="max-width:710px; width:100%; margin:auto;border:1px solid #333;">
						<div style="background-color:black; padding:0 15px;text-align:center">
							<img src="http://wine-oh.io/wp-content/uploads/2017/08/wineoh_logo-1.png" style="margin:5px auto; height:65px;">
						<span style="font-family: cursive;font-size: 56px;color: #C8385E;">Wine-Oh!</span>
						</div>

						<div style="max-width:680px; width:100%; margin:auto; border:1px solid #333; padding:15px;    min-height: 150px;">
						<p style="margin-bottom:5px; color:#000;">
						Hello '.$firstName.' '.$lastName.',<br/><br/>

						We had received your request for reseting the pasword.
						<br/>
						Click the following <a href='.home_url().'/reset-password?tokenI='.$id.'> link </a> to reset your password. This link expires in 7 days.<br/>
						<br/>

						Thanks,<br/>
						Team Wine-Oh 
						</p>
						</div>
						<div style="background-color:black; color:#fff; text-align:center; padding:30px 15px; font-size:14px; max-width:685px; width:100%; margin:auto;">Copyright © 2017 Wine-Oh!</div>

						</body>
						</html>';
			// $link="<a href=".home_url()."/send-verification-email?tokenT=".$contact_type."&tokenI=".$okta_msg['id'].">click here to verify.</a>";
			send_email($email,$mailSubject,$mailBody);
			$msg="A confirmation email has been sent to your email id for recovering the password.";
		}
else if($status=="SUSPENDED")
  {
    Zendesk_Api_generate_ticket($email);
    $msg="Ticket generated for suspended account.";
  }
		else
		{
			$msg="Please enter valid credentials.";
		}

	}
}
?>
		<div class="ia_row">
			<div class="vc_row wpb_row vc_row-fluid">
				<div class="wpb_column vc_column_container vc_col-sm-12">
					<div class="vc_column-inner ">
						<div class="wpb_wrapper">
					
							<div class="ia-heading ia-heading-heading_725 heading-align-center " data-delay="0">
								<h2 class="h1">	Forgot Password </h2>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
			</div>    			
		</div>
		<div class="container wineforms-container">
						<?php
							if(trim($msg)!=null) 
							{
							?>
								<div class="alert alert-success-theme"><?php echo $msg; ?></div>
							<?php
							}
						?>	
			<form method="post" action="" id="forgot-form">
				<div class="form_body">
					<div class="form-group ">
						<label>Email<span class="field_required">*</span> </label>
						<input type="text" name="txtEmail"  id="txtEmail" value="" size="20" class="form-control" />
					</div>
					
					<div class="login_fields">
						
						
						<div class="form-group" style="width:100%;float:left;">
							<input type="submit" name="btnForgot" id="btnForgot" value="Send Verification Link" tabindex="14" class="user-submit" />
						</div>
					
					</div>
					
				</div>
			</form>
		</div>	
		
	