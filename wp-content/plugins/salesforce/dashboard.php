<?php include('header.php'); ?>

    <div id="body">
    	<div class="container">
        	<div class="content-pad-4x">
                <div class="row">
                    <div id="content" class="col-md-12" role="main">
                        <article class="single-page-content">
							
							<section class="container-wrap">
								<div class="row">
									<div class="col-md-12">
										<div class="wine-slider">
											<div class="row">
												<div class="col-xs-12">
													<div class="title-wrap">
														<h1 class="title pull-left">Wines</h1>
														<a class="view-all-btn pull-right" href="#">View All »</a>
													</div>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-md-12 col-sm-12 col-xs-12">
													<div class="wine-slides">
														<div id="owl-demo" class="owl-carousel">
														  <div class="item"><img class="lazyOwl" data-src="<?php echo plugin_dir_url( __FILE__ ); ?>images/barefoot.jpg">
															<div class="owl-text">
																<a href="#">Barefoot</a>
															</div>
														  </div>
														  <div class="item"><img class="lazyOwl" data-src="<?php echo plugin_dir_url( __FILE__ ); ?>images/black-box.jpg">
															<div class="owl-text">
																<a href="#">Black Box</a>
															</div>
														  </div>
														  <div class="item"><img class="lazyOwl" data-src="<?php echo plugin_dir_url( __FILE__ ); ?>images/la-carema.jpg">
															<div class="owl-text">
																<a href="#">Le Crema</a>
															</div>
														  </div>
														  <div class="item"><img class="lazyOwl" data-src="<?php echo plugin_dir_url( __FILE__ ); ?>images/cupcake.jpg">
															<div class="owl-text">
																<a href="#">Cupcake</a>
															</div>
														  </div>
														  <div class="item"><img class="lazyOwl" data-src="<?php echo plugin_dir_url( __FILE__ ); ?>images/barefoot.jpg">
															<div class="owl-text">
																<a href="#">Barefoot</a>
															</div>
														  </div>
														  <div class="item"><img class="lazyOwl" data-src="<?php echo plugin_dir_url( __FILE__ ); ?>images/black-box.jpg">
															<div class="owl-text">
																<a href="#">Black Box</a>
															</div>
														  </div>
														  <div class="item"><img class="lazyOwl" data-src="<?php echo plugin_dir_url( __FILE__ ); ?>images/la-carema.jpg">
															<div class="owl-text">
																<a href="#">Le Crema</a>
															</div>
														  </div>
														  <div class="item"><img class="lazyOwl" data-src="<?php echo plugin_dir_url( __FILE__ ); ?>images/cupcake.jpg">
															<div class="owl-text">
																<a href="#">Cupcake</a>
															</div>
														  </div>
														  
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="second-row">
											<div class="row">
												<div class="col-md-6">
													<div class="tag-wrap">
														<div class="row">
																<div class="col-xs-12">
																	<div class="title-wrap">
																		<h1 class="title pull-left">Tags</h1>
																		<a class="view-all-btn pull-right" href="#">View All »</a>
																	</div>
																</div>
														</div>
														<hr>
														<div class="tags-img">
															<img style="margin:auto;" class="img-responsive"  style="margin:auto;" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/tags-img.jpg">
														</div>
													</div>
												</div>
												
												<div class="col-md-6">
													<div class="incentives-wrap  ">
														<div class="row">
																<div class="col-xs-12">
																	<div class="title-wrap">
																		<h1 class="title pull-left">Incentives</h1>
																		<a class="view-all-btn pull-right" href="#">View All »</a>
																	</div>
																</div>
														</div>
														<hr>
														<div class="tags-img">
															<img class="img-responsive" style="margin:auto;" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/incentives.png">
														</div>
													</div>
												</div>
												
												<div class="col-md-6">
													<div class="wine-ohs-wrap  ">
														<div class="row">
																<div class="col-xs-12">
																	<div class="title-wrap">
																		<h1 class="title pull-left">Wine-Ohs</h1>
																		<a class="view-all-btn pull-right" href="#">View All »</a>
																	</div>
																</div>
														</div>
														<hr>
														<div class="tags-img">
															<img class="img-responsive" style="margin:auto;" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/wine-oh.png">
														</div>
													</div>
												</div>
												
												
												<div class="col-md-6">
													<div class="wine-ohs-wrap  ">
														<div class="row">
																<div class="col-xs-12">
																	<div class="title-wrap">
																		<h1 class="title pull-left">Notifications</h1>
																		<a class="view-all-btn pull-right" href="#">View All »</a>
																	</div>
																</div>
														</div>
														<hr>
														<div class="notifications-list">
															<ul>
																<li><h3>15 Wines Updated</h3>
																	<p>Your upload of 15 Wine Updates was processed on January 1, 2017 at 12:31 am.</p>
																</li>
																<hr>
																<li><h3>#1 in App Store Downloads</h3>
																	<p>We are pleased to announce that Wine-Oh! is the most downloaded mobile
																		application in the App Store!</p>
																</li>
																<hr>
																<li><h3>Prohibition Appealed</h3>
																	<p>The Cullen–Harrison Act, signed by President Franklin D. Roosevelt on March 22,
																		1933, authorizes the sale of 3.2 percent beer and wine!</p>
																</li>
															
															</ul>
														</div>
													</div>
												</div>
												
											</div>
											
											
										</div>
									</div>
								</div>
							</section>
						</article>
                    </div><!--/content-->
                </div><!--/row-->
            </div><!--/content-pad-4x-->
        </div><!--/container-->
    </div><!--/body-->
	
	
	
	<input type="hidden" id="okta_url" value="<?php echo $okta_url; ?>">
	<input type="hidden" id="client_id" value="<?php echo $client_id; ?>">
	<input type="hidden" id="redirect_url" value="<?php echo home_url(); ?>/login/">
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
				location.href = redirect_url;
			}
		});
		
	</script>