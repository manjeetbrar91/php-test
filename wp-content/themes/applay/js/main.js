window.onload = function() {

    // Check if browser support "animate"
    if (!document.body.animate){

        // Load another page, if not support
        location.href = location.protocol + '//' + location.hostname;

    } else {

        var d = document, // Document
            sky = d.querySelector('[data-sky]'), // Element for sky img's
            sun = d.querySelector('[data-sun]'), // Element for sun img
            mountains = d.querySelector('[data-mount]'), // Element for mountains img's
            clouds = d.querySelector('[data-cloud]'), // Element for clouds img
            blur = d.querySelector('[data-blur]'), // Element for blur img
            loginWrap = d.querySelector('[data-login]'), // Element for loginWrap block
            audioFirst = d.querySelector('.js-pageAudioFirst'), // Element for audio
            audioSecond = d.querySelector('.js-pageAudioSecond'), // Element for audio
            audioThird = d.querySelector('.js-pageAudioThird'); // Element for audio

        // Okta sign in init
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
			logo: 'svg/logo.svg',
			features: {
				rememberMe: false
			},
			helpLinks: {
				help: 'https://help.wine-oh.io/hc/en-us/articles/115004120233-Need-Help-Signing-In-',
				custom: [{
					text: 'Skip this step',
					href: 'https://wine-oh.io/home/'
				}]
			},
			i18n: {
				en: {
					'primaryauth.title': 'Sign-In'
				}
			},
			authParams: {
				responseType: ['id_token', 'token']
			},
			/*
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
		  ]*/
		});
		
		var showLogin = function () {
			
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
            // oktaSignIn = new OktaSignIn({
                // baseUrl: orgUrl,
                // logo: 'svg/logo.svg',
                // features: {
                    // rememberMe: false
                // },
                // helpLinks: {
                    // help: 'https://help.wine-oh.io/hc/en-us/articles/115004120233-Need-Help-Signing-In-',
                    // custom: [{
                        // text: 'Skip this step',
                        // href: 'https://wine-oh.io/home/'
                    // }]
                // },
                // i18n: {
                    // en: {
                        // 'primaryauth.title': 'Sign-In'
                    // }
                // }
            // });

        // Keyframes array's
        var opacityOut = [
            {opacity: 1},
            {opacity: 0}
        ],
        opacityIn = [
            {opacity: 0},
            {opacity: 1}
        ],
        opacityIn2 = [
            {opacity: .1},
            {opacity: 1}
        ],
        opacityTop = [
            {
                opacity: 0,
                bottom: '-70%'
            }, {
                opacity: 1,
                bottom: '5%',
                offset: .4
            }, {
                opacity: 1,
                bottom: 0,
                offset: .5
            }, {
                opacity: 1,
                bottom: 0
            }
        ],
        opacityScale = [
            {
                opacity: 0,
                transform: 'scale(.1)'
            }, {
                opacity: 1,
                transform: 'scale(1)'
            }
        ];

        var options = { // Default options
            iterations: 1,
            delay: 2000,
            duration: 4500,
            fill: 'forwards',
            easing: 'ease-in-out'
        },
        // Options for out elements
        optionsOut = {
            iterations: 1,
            delay: 6500,
            duration: 1000,
            fill: 'forwards',
            easing: 'ease-in-out'
        },
        // Options for fastin elements
        optionsInFast = {
            iterations: 1,
            delay: 3500,
            duration: 3000,
            fill: 'forwards',
            easing: 'ease-in-out'
        },
        // Options for blur image
        optionBlur = {
            iterations: 1,
            delay: 200,
            duration: 1000,
            fill: 'forwards',
            easing: 'ease-in-out'
        },
        // Options for login wrapper block
        optionlogin = {
            iterations: 1,
            duration: 500,
            fill: 'forwards',
            easing: 'ease-in-out'
        };

        // Set volume of music
        audioFirst.volume = 0.5;
        audioSecond.volume = 0.5;
        audioThird.volume = 0.5;

        // Start play music
        setTimeout(function(){
            audioFirst.play();
        }, 1000);

        audioFirst.addEventListener('ended', function(){

            audioSecond.play();

            audioSecond.onplaying = function() {
                setTimeout(function(){
                    audioThird.play();
                }, 1500)
            };

        }, false);

        // Start animation for sun
        var sunLastAnim = sun.querySelector('.morning').animate(opacityTop, optionsInFast);

        // Start animation for clouds
        clouds.querySelector('img').animate(opacityIn2, options);

        // Start animation for sky
        sky.querySelector('.night').animate(opacityOut, options);
        sky.querySelector('.morning').animate(opacityIn, options);

        // Start animation for mountains
        mountains.querySelector('.night').animate(opacityOut, optionsOut);
        mountains.querySelector('.morning').animate(opacityIn, options);

        sunLastAnim.onfinish = function() {

            // Render okta sign in block
            oktaSignIn.session.get(function (res) {

                // Session exists, show logged in state.
                if (res.status === 'ACTIVE') {

                    // Redirect to partner page
                    location.href = location.protocol + '//' + location.hostname + 'partner-portal/';

                } else {

                }

                // No session, show the login form
                oktaSignIn.renderEl(
                    { el: '#okta-login-container' },
                    function error(err) {
                        // handle errors as needed
                        console.error(err);
                    }
                );

                oktaSignIn.on('pageRendered', function (data) {

                    var text = d.createElement("span"),
                        textnode = d.createTextNode("to access your Wine-Oh! account");

                    text.appendChild(textnode);

                    d.querySelector('.okta-form-title.o-form-head').appendChild(text);
                    d.querySelector('#okta-signin-submit').value = 'Sign-In';

                });

            });

            // Start blur animation
            var blurAnimation = blur.querySelector('img').animate(opacityIn, optionBlur);

            blurAnimation.onfinish = function() {

                // Start login wrapper animation
                loginWrap.animate(opacityScale, optionlogin)

            };
        };

    }

};