<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<style>
form label.error{
	    width: 40% !important;
}
</style>
<?php include('stylejs.php'); 
include('function.php');
include('keys.php');
$msg="";
$loginmsg="";
if(isset($_GET['tokenI']))
{
	if(trim($_GET['tokenI'])!=null)
	{
		$user_id=$_GET['tokenI'];
	}
}

if(isset($_POST['btnReset']))
{
	if(trim($_POST['txtPassword'])!=null && trim($_POST['c_txtPassword'])!=null)
	{
		$password=$_POST['txtPassword'];
		$c_password=$_POST['c_txtPassword'];
		if($password==$c_password)
		{
			okta_reset_password ($user_id,$password);
			if(isset($add_user_result->errorCauses))
			{									
				$msg=$add_user_result->errorCauses[0]->errorSummary;
			}
			else
			{
				$msg="Your Password is successfully reset.";
				echo '<script>location.href="'.home_url().'/login/";</script>';
			}
		}
		else
		{
			$msg="confirm password does't matched";
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
								<h2 class="h1">	Reset Password </h2>
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
			<form method="post" action="" id="reset-form">
				<div class="form_body">
					<div class="form-group">
						<label>Enter Password<span class="field_required">*</span> </label>
						<input type="password" name="txtPassword"  id="txtPassword" value="" size="20" class="form-control" />
						
						<label>Confirm Password<span class="field_required">*</span> </label>
						<input type="password" name="c_txtPassword" id="c_txtPassword" value="" size="20" class="form-control" />
					</div>
					
					<div class="login_fields">
						
						<div class="form-group" style="width:100%;float:left;">
							<input type="submit" name="btnReset" value="Reset Password" tabindex="14" class="user-submit" />
						</div>
					
					</div>
					
				</div>
			</form>
		</div>	
		
	<?php //} else {  ?>

	<!--<div class="sidebox">
		<h3>Welcome, <?php echo $user_identity; ?></h3>
		<div class="usericon">
			<?php global $userdata; echo get_avatar($userdata->ID, 60); ?>

		</div>
		<div class="userinfo">
			<p>You&rsquo;re logged in as <strong><?php echo $user_identity; ?></strong></p>
			<p>
				<a href="<?php echo wp_logout_url('index.php'); ?>">Log out</a> | 
				<?php if (current_user_can('manage_options')) { 
					echo '<a href="' . admin_url() . '">' . __('Admin') . '</a>'; } else { 
					echo '<a href="' . admin_url() . 'profile.php">' . __('Profile') . '</a>'; } ?>

			</p>
		</div>
	</div>-->

	<?php //} ?>
