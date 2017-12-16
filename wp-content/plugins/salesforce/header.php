<?php 
if(isset($_REQUEST['logout']))
{	
	if($_REQUEST['logout']=='true')
	{
		session_destroy();
		echo '<script>location.href="'.home_url().'/login/";</script>';
	}	
}
$msg='';
// include('sessions.php');
include('keys.php');
$path= plugin_dir_url( __FILE__ ).'soapclient';	
require_once ('soapclient/SforceEnterpriseClient.php');
require_once ('soapclient/SforceHeaderOptions.php');
include('function.php');

			
?>

<style>
.page-heading{
            background-image:url(<?php echo plugin_dir_url( __FILE__ ); ?>images/background.jpg);
			background-position: center center;
            background-size: cover;
            background-attachment: fixed;
			
			padding-top: 120px !important;
			padding-bottom: 25px !important;
			position: relative !important;
}

</style>


<div class="page-heading main-color-1-bg dark-div">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12 col-sm-12">
				<div id="owl-demo1" class="owl-carousel">
					<?php
					try 
					{
						$mySforceConnection = new SforceEnterpriseClient();
						$mySoapClient = $mySforceConnection->createConnection($path.'/enterprise.wsdl.xml');
						$mylogin = $mySforceConnection->login($USERNAME, $PASSWORD);

						
						$account_id= $_SESSION['salesforce_account_id'];
						$contact_id= $_SESSION['salesforce_contact_id'];
						
						$admin_contact_query = "select Id,FirstName,LastName,Email from Contact where AccountId='".$account_id."' and RecordTypeId='01228000000TLju'";
						$admin_contact_result=$mySforceConnection->query($admin_contact_query);

						if(count($admin_contact_result->records)==0)  //It means no admin
						{
							?>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/dashboard">
										<div class="dashboard_menu">
										</div>
										<p>Dashboard</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/wines">
										<div class="wines_menu">
										</div>
										<p>Wines</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/tags">
										<div class="tags_menu">
										</div>
										<p>Tags</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="#">
										<div class="wineoh_menu">
										</div>
										<p>Wine-Ohs</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/prices">
										<div class="prices_menu">
										</div>
										<p>Prices</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/incentives">
										<div class="incentives_menu">
										</div>
										<p>Incentives</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/locations">
										<div class="locations_menu">
										</div>
										<p>Locations</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="#">
										<div class="associates_menu">
										</div>
										<p>Associates</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="#">
										<div class="profile_menu">
										</div>
										<p>Profile</p>
									</a>
								</div>
							<?php
							
						}
						else
						{
							?>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/dashboard">
										<div class="dashboard_menu">
										</div>
										<p>Dashboard</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/wines">
										<div class="wines_menu">
										</div>
										<p>Wines</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/tags">
										<div class="tags_menu">
										</div>
										<p>Tags</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="#">
										<div class="wineoh_menu">
										</div>
										<p>Wine-Ohs</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/prices">
										<div class="prices_menu">
										</div>
										<p>Prices</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/incentives">
										<div class="incentives_menu">
										</div>
										<p>Incentives</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="<?php echo home_url(); ?>/locations">
										<div class="locations_menu">
										</div>
										<p>Locations</p>
									</a>
								</div>
								<div class="item owl-name">
									<a class="menu-link" href="#">
										<div class="associates_menu">
										</div>
										<p>Associates</p>
									</a>
								</div>
								
							<?php
						}
						
						

					} 
					catch (Exception $e) 
					{
					  echo $e->faultstring;
					}
					?>
					
					
				  
				</div>
				
				
            </div>
			<div class="col-md-12 col-sm-12">
				<!--
				<div class="logout-button">
					<a href="<?php echo home_url(); ?>/dashboard?logout=true">Logout</a>
				</div>-->
				<div id="okta-login-container" style="display: none"></div>
				<div class="logout-button" id="active" style="display: none">
					
					<button id="logout">Logout</button>
				</div>
				<h3 id="welcome"></h3>
			</div>
        </div><!--/row-->
		
    </div><!--/container-->
</div><!--/page-heading-->