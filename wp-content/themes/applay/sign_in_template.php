<?php 
/*
Template Name: Login Template
*/
$content_padding = get_post_meta(get_the_ID(),'content_padding',true);
$single_page_layout = get_post_meta(get_the_ID(),'sidebar_layout',true);
global $global_page_layout;
$layout = $single_page_layout ? $single_page_layout : ($global_page_layout ? $global_page_layout : ot_get_option('page_layout','right'));
$global_page_layout = $layout;
?>
<script src="https://ok1static.oktacdn.com/assets/js/sdk/okta-signin-widget/1.13.0/js/okta-sign-in.min.js" type="text/javascript"></script>
  <link href="https://ok1static.oktacdn.com/assets/js/sdk/okta-signin-widget/1.13.0/css/okta-sign-in.min.css" type="text/css" rel="stylesheet">
  <!-- Optional, customizable css theme options. Link your own customized copy of this file or override styles in-line -->
  <link href="https://ok1static.oktacdn.com/assets/js/sdk/okta-signin-widget/1.13.0/css/okta-theme.css" type="text/css" rel="stylesheet">
  <style>
  .dropdown{
	  background: transparent;
  }
  .social-icon{
	  padding: 10px !important;
  }
  .okta_sign_footer .back-to-top{
	  padding: 10px;
  }
  #okta-sign-in .okta-sign-in-header {
    display: none;
}
.o-form-head {
 margin-top: 0;
}
.auth-divider {
 margin-bottom: 60px;
}
.auth-divider .auth-divider-text {
 top: 0.5em;
}
  </style>
<?php
get_header();
?>

	<?php get_template_part( 'templates/header/header', 'heading' ); ?>    
    <div id="body">
    	<?php if($layout!='true-full'){ ?>
    	<div class="container">
        <?php }?>
        	<?php if($content_padding!='off'){ ?>
        	<div class="content-pad-4x">
            <?php }?>
                <div class="row">
                    <div id="content" class="col-md-12 " role="main">
                        <article class="single-page-content">
						<div class="container wineforms-container">
						  <div id="okta-login-container"></div>
							<div id="active" style="display: none">
								<h3 id="welcome"></h3>
								<button id="logout">Logout</button>
							</div>
						
							<?php while ( have_posts() ) : the_post();
								the_content();
							endwhile; ?>
						</div>	
		
						
						
                        	
                        </article>
                    </div><!--/content-->
                 
                </div><!--/row-->
            <?php if($content_padding!='off'){ ?>
            </div><!--/content-pad-4x-->
            <?php }?>
        <?php if($layout!='true-full'){ ?>
        </div><!--/container-->
        <?php }?>
    </div><!--/body-->
	<?php 
	$okta_url='https://dev-817806.oktapreview.com';
	//$redirect_url= home_url().'/sign-in/';
	$client_id= '0oaczdrxkxd2Y2lVX0h7';
	$facebook= '0oabyiii12jDce0oy0h7';
	$google= '0oabyj0ouuJrj7of00h7';
	$linkedin= '0oabyihwknLhwCDib0h7';
	$microsoft= '0oabyiwgcvAe0Mbb80h7';
	
	?>
	<input type="hidden" id="okta_url" value="<?php echo $okta_url; ?>">
	<input type="hidden" id="client_id" value="<?php echo $client_id; ?>">
	<input type="hidden" id="redirect_url" value="<?php echo home_url(); ?>/sign-in/">
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
						oktaSignIn.tokenManager.add('accessToken', response[1]);
						showUser(response[0].claims.email);
					}
				}
			);
		};

		var showUser = function(email) {
			document.getElementById('active').style.display = 'block';
			document.getElementById('welcome').innerHTML = 'Welcome ' + email;
			document.getElementById('okta-login-container').innerHTML = '';
			document.getElementById('logout').onclick = function () {
				oktaSignIn.signOut(function () {
					location.href = location.href.toString();
				});
			}
		};

		oktaSignIn.session.get(function (response) {
			if (response.status !== 'INACTIVE') {
				var accessToken = oktaSignIn.tokenManager.get('accessToken');
				showUser(response.login);
			} else {
				showLogin();
			}
		});
		
	</script>
<?php get_footer(); ?>