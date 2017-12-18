window.onload = function() {

    var css3animation = false,
        css3animationstring = 'animation',
        css3keyframeprefix = '',
        css3domPrefixes = 'Webkit Moz O ms Khtml'.split(' '),
        css3pfx  = '',
        css3elem = document.createElement('div');

    if( css3elem.style.animationName !== undefined ) { css3animation = true; }

    if( css3animation === false ) {
        for( var i = 0; i < css3domPrefixes.length; i++ ) {
            if( css3elem.style[ css3domPrefixes[i] + 'AnimationName' ] !== undefined ) {
                css3pfx = css3domPrefixes[ i ];
                css3animationstring = css3pfx + 'Animation';
                css3keyframeprefix = '-' + css3pfx.toLowerCase() + '-';
                css3animation = true;
                break;
            }
        }
    }

    // If browser isn't support css3 animation
    if(css3animation === false){

        // Redirect to partner page
        location.href = location.protocol + '//' + location.hostname + 'partner-portal/';

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
        var orgUrl = 'https://dev-310121.oktapreview.com',
            oktaSignIn = new OktaSignIn({
                baseUrl: orgUrl,
                logo: 'svg/logo.svg',
                features: {
                    rememberMe: false
                },
                helpLinks: {
                    help: 'https://help.wine-oh.io/hc/en-us/articles/115004120233-Need-Help-Signing-In-',
                    custom: [{
                        text: 'Skip this step',
                        href: 'http://wine-oh.io/'
                    }]
                },
                i18n: {
                    en: {
                        'primaryauth.title': 'Sign-In'
                    }
                }
            });

        // Okta sign in render function wrapper
        function renderOkta(el){
            el.session.get(function (res) {

                // Session exists, show logged in state.
                if (res.status === 'ACTIVE') {

                } else {

                }

                // No session, show the login form
                el.renderEl(
                    { el: '#okta-login-container' },
                    function error(err) {
                        // handle errors as needed
                        console.error(err);
                    }
                );

                el.on('pageRendered', function (data) {

                    var text = d.createElement("span"),
                        textnode = d.createTextNode("to access your Wine-Oh! account");

                    text.appendChild(textnode);

                    d.querySelector('.okta-form-title.o-form-head').appendChild(text);
                    d.querySelector('#okta-signin-submit').value = 'Sign-In';

                });

            });
        }

        // Set volume of music
        audioFirst.volume = 0.5;
        audioSecond.volume = 0.5;
        audioThird.volume = 0.5;


        // Check if browser support "animate" (WAAPI)
        if (!document.body.animate){

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
            sun.className += " animated";

            // Start animation for clouds
            clouds.className += " animated";

            // Start animation for sky
            sky.className += " animated";

            // Start animation for mountains
            mountains.className += " animated";

            // Start blur animation
            blur.className += " animated";

            // Start login wrapper animation
            loginWrap.className += " animated";

            setTimeout(function(){

                // Render okta sign in block
                renderOkta(oktaSignIn);

            }, 6500);

        } else {

            // Keyframes array's
            var opacityOut = [
                {opacity: 1},
                {opacity: 0}
            ],
            opacityIn = [
                {opacity: 0},
                {opacity: 1}
            ],
            keyframesClouds = [
                {opacity: .1},
                {opacity: 1}
            ],
            keyframesSun = [
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
            keyframesLogin = [
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
            // Options for sun element
            optionsSun = {
                iterations: 1,
                delay: 3500,
                duration: 3000,
                fill: 'forwards',
                easing: 'ease-in-out'
            },
            // Options for blur image
            optionsBlur = {
                iterations: 1,
                delay: 200,
                duration: 1000,
                fill: 'forwards',
                easing: 'ease-in-out'
            },
            // Options for login wrapper block
            optionsLogin = {
                iterations: 1,
                duration: 500,
                fill: 'forwards',
                easing: 'ease-in-out'
            };

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
            var sunLastAnim = sun.querySelector('.morning').animate(keyframesSun, optionsSun);

            // Start animation for clouds
            clouds.querySelector('img').animate(keyframesClouds, options);

            // Start animation for sky
            sky.querySelector('.night').animate(opacityOut, options);
            sky.querySelector('.morning').animate(opacityIn, options);

            // Start animation for mountains
            mountains.querySelector('.night').animate(opacityOut, optionsOut);
            mountains.querySelector('.morning').animate(opacityIn, options);

            sunLastAnim.onfinish = function() {

                // Render okta sign in block
                renderOkta(oktaSignIn);

                // Start blur animation
                var blurAnimation = blur.querySelector('img').animate(opacityIn, optionsBlur);

                blurAnimation.onfinish = function() {

                    // Start login wrapper animation
                    loginWrap.animate(keyframesLogin, optionsLogin)

                };
            };

        }

    }

};