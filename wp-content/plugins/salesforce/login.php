<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<style>
form label.error{
	    width: auto !important;
}

</style>
<?php 

session_start(); 

if( ($_SESSION)!=null)
{
echo '<script>location.href="'.home_url().'/dashboard/";</script>';
}
 include('stylejs.php'); 
include('function.php');
include('keys.php');
$path= plugin_dir_url( __FILE__ ).'soapclient';	
require_once ('soapclient/SforceEnterpriseClient.php');
require_once ('soapclient/SforceHeaderOptions.php');
$msg="";
$loginmsg="";

if(isset($_GET['msg']))
{
	if(trim($_GET['msg'])!=null)
	{
		$msg=$_GET['msg'];
	}
}
if(isset($_POST['btnLogin']))
{
	if(trim($_POST['txtEmail'])!=null && trim($_POST['txtPassword'])!=null)
	{
		$email=$_POST['txtEmail'];
		$password=$_POST['txtPassword'];
		$login_response=Okta_Login($email,$password);

		if($login_response->status=="SUCCESS")
		{
			//login
			 $id=$login_response->_embedded->user->id;
			 $firstname=$login_response->_embedded->user->profile->firstName;
			 $lastname=$login_response->_embedded->user->profile->lastName;

			$msg="Welcome to wine-oh.";
			
try 
			{
				$mySforceConnection = new SforceEnterpriseClient();
				$mySoapClient = $mySforceConnection->createConnection($path.'/enterprise.wsdl.xml');
				$mylogin = $mySforceConnection->login($USERNAME, $PASSWORD);
				
				//$email_id="nishu.developtech@gmail.com";
				$contact_query = "select Id, Name,AccountId from Contact where Email='".$email."' ";
				$contact_result=$mySforceConnection->query($contact_query);
				
				$contact_account_id= $contact_result->records[0]->AccountId;
				$contact_id= $contact_result->records[0]->Id;
				
				$account_query = "select Id,Name from Account where Id='".$contact_account_id."'";
				$account_result=$mySforceConnection->query($account_query);
				$_SESSION['okta_sessionToken']= $login_response->sessionToken;
				$_SESSION['okta_id']= $id;
				$_SESSION['salesforce_account_id']= $contact_account_id;
				$_SESSION['salesforce_contact_id']= $contact_id;
				$_SESSION['email_id']= $email;

			} 
			catch (Exception $e) 
			{
			  echo $e->faultstring;
			}
$dash_url= home_url().'/partner-portal/dashboard/';
//echo '<script>location.href="'.home_url().'/dashboard/";</script>';
//echo '<script>location.href="https://dev-817806.oktapreview.com/login/sessionCookieRedirect?token='.$_SESSION['okta_sessionToken'].'&redirectUrl=http://wine-oh.io/partner-portal/dashboard/";</script>';

echo '<script>location.href="https://dev-817806.oktapreview.com/login/sessionCookieRedirect?token='.$_SESSION['okta_sessionToken'].'&redirectUrl='.$dash_url.'";</script>';
		}
		else
		{
			$msg="Please enter valid credentials";
		}
	}
}
?>
						<div class="container wineforms-container">


						  <div id="okta-login-container"></div>
							<div id="active" style="display: none">
								<h3 id="welcome"></h3>
								<button id="logout">Logout</button>
							</div>
	
							<input type="hidden" id="okta_url" value="<?php echo $okta_url; ?>">
							<input type="hidden" id="client_id" value="<?php echo $client_id; ?>">
							<input type="hidden" id="redirect_url" value="<?php echo home_url(); ?>/partner-portal/dashboard/">
							<input type="hidden" id="facebook" value="<?php echo $facebook; ?>">
							<input type="hidden" id="google" value="<?php echo $google; ?>">
							<input type="hidden" id="linkedin" value="<?php echo $linkedin; ?>">
							<input type="hidden" id="microsoft" value="<?php echo $microsoft; ?>">
							<!-- Script to init the widget -->
							<script>

								var okta_url = document.getElementById("okta_url").value;
								var client_id = document.getElementById("client_id").value;
								var redirect_url = document.getElementById("redirect_url").value;
								var facebook = document.getElementById("facebook").value;
								var google = document.getElementById("google").value;
								var linkedin = document.getElementById("linkedin").value;
								var microsoft = document.getElementById("microsoft").value;
								
								var orgUrl = okta_url;
								var oktaSignIn = new OktaSignIn({baseUrl: orgUrl});
								var oktaSignIn = new OktaSignIn({
								  baseUrl: okta_url,
								  clientId: client_id,
								  redirectUri: redirect_url,
								  authParams: {
									 responseType: ['id_token', 'token']
								  },
								  idps: [
									{
									  type: 'FACEBOOK',
									  id: facebook
									},
									{
									  type: 'GOOGLE',
									  id: google
									},
									{
									  type: 'LINKEDIN',
									  id: linkedin
									},
									{
									  type: 'MICROSOFT',
									  id: microsoft
									},
								  ]
								});
								
								var showLogin = function () {
									document.getElementById('active').style.display = 'none';
									oktaSignIn.renderEl(
										{el: '#okta-login-container'},
										function (response) {
											if (response.status === 'SUCCESS') {
												location.href = redirect_url;
												oktaSignIn.tokenManager.add('accessToken', response[1]);
												showUser(response[0].claims.email);
											}
										}
									);
								};

								var showUser = function(email) {
									document.getElementById('active').style.display = 'none';
									document.getElementById('welcome').innerHTML = 'Welcome ' + email;
									document.getElementById('okta-login-container').innerHTML = '';
									document.getElementById('logout').onclick = function () {
										oktaSignIn.signOut(function () {
											location.href = location.href.toString();
										});
									}
								};
								// alert(oktaSignIn.session.get(status));
								oktaSignIn.session.get(function (response) {
									if (response.status !== 'INACTIVE') 
									{
										location.href = redirect_url;
										var accessToken = oktaSignIn.tokenManager.get('accessToken');
										showUser(response.login);
									}
									else 
									{
										
										showLogin();
									}
								});
								
							</script>
						</div>

<!--	
		<div class="ia_row">
			<div class="vc_row wpb_row vc_row-fluid">
				<div class="wpb_column vc_column_container vc_col-sm-12">
					<div class="vc_column-inner ">
						<div class="wpb_wrapper"> 
						
						<div class="ia-heading ia-heading-heading_725 heading-align-center " data-delay="0">
								<h2 class="h1">	Login </h2>
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
				<div class="form_body">
					<form method="post" action="" class="wp-user-form" id="login-form">
						<div class="form-group username">
							<label for="user_login">Email<span class="field_required">*</span> </label>
							<input type="text" name="txtEmail" id="txtEmail" value="" size="20" id="user_login"  class="form-control" />
						</div>
						<div class="form-group password">
							<label for="user_pass">Password<span class="field_required">*</span> </label>
							<input type="password" name="txtPassword" id="txtPassword" value="" size="20" id="user_pass" class="form-control"  autocomplete="new-password"/>
						</div>
						<div class="login_fields">

							<div class="form-group" style="width:100%;float:left;">
								<input type="submit" name="btnLogin" id="btnLogin" value="Login" tabindex="14" class="user-submit" />
							</div>
						</div>
					</form>	
					<div class="form-group" style="width:100%;float:left;">	
						<center>
							Not a member? <a href="<?php echo get_home_url(); ?>/partner-portal" style="color:#c8385e;">Join now</a> | <a href="<?php echo home_url();?>/forgot-password" style="color:#c8385e;">Forgot Password</a>
						</center>
					</div>
				</div>
		</div>-->	

