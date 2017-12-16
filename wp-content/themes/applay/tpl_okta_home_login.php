<?php 
/* Template Name: Home Login Okta */
?>
<!DOCTYPE html>
<html>
<head>

    <!-- CHARSET -->
    <meta charset="UTF-8">

    <!-- MOBILE META's -->
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#562345">
    <meta name="theme-color" content="#562345">

    <!-- FAVICONS -->
    <link rel="apple-touch-icon" sizes="60x60" href="<?php bloginfo('template_url'); ?>/svg/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php bloginfo('template_url'); ?>/svg/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php bloginfo('template_url'); ?>/svg/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo('template_url'); ?>/svg/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php bloginfo('template_url'); ?>/svg/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php bloginfo('template_url'); ?>/svg/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php bloginfo('template_url'); ?>/svg/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php bloginfo('template_url'); ?>/svg/favicons/favicon-16x16.png">

    <!-- TITLE -->
    <title>iWine</title>

    <!-- STYLES -->
    <link href="https://ok1static.oktacdn.com/assets/js/sdk/okta-signin-widget/1.13.0/css/okta-sign-in.min.css" type="text/css" rel="stylesheet">
    <link href="https://ok1static.oktacdn.com/assets/js/sdk/okta-signin-widget/1.13.0/css/okta-theme.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/main.css?1">

    <!-- SCRIPTS -->
    <script src="https://ok1static.oktacdn.com/assets/js/sdk/okta-signin-widget/1.13.0/js/okta-sign-in.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/main.js?1"></script>

</head>
<body>
<?php
$okta_url='https://dev-817806.oktapreview.com';
$client_id= '0oaczdrxkxd2Y2lVX0h7';
$facebook= '0oabyiii12jDce0oy0h7';
$google= '0oabyj0ouuJrj7of00h7';
$linkedin= '0oabyihwknLhwCDib0h7';
$microsoft= '0oabyiwgcvAe0Mbb80h7';

?>
    <div class="login-wrapper" data-login>
        <div class="login-text">
            <h2>Already a <img src="<?php bloginfo('template_url'); ?>/svg/logo.svg" alt="">?</h2>
            <p>Sign in to your Wine-Oh! account to access your wines, offers and other settings. If you don’t have a Wine-Oh! account you can create one <a href="https://wine-oh.io/register/">here</a>.</p>
            <a href="https://wine-oh.io/features/">Learn more about Wine-Oh!</a>
        </div>
		<input type="hidden" id="okta_url" value="<?php echo $okta_url; ?>">
		<input type="hidden" id="client_id" value="<?php echo $client_id; ?>">
		<input type="hidden" id="redirect_url" value="<?php echo home_url(); ?>/partner-portal/dashboard/">
		<input type="hidden" id="facebook" value="<?php echo $facebook; ?>">
		<input type="hidden" id="google" value="<?php echo $google; ?>">
		<input type="hidden" id="linkedin" value="<?php echo $linkedin; ?>">
		<input type="hidden" id="microsoft" value="<?php echo $microsoft; ?>">
        <div id="okta-login-container"></div>
    </div>

    <div class="scene-block" data-scene="night">

        <div class="sky" data-sky>
            <img src="<?php bloginfo('template_url'); ?>/svg/night/bg.svg" class="night">
            <img src="<?php bloginfo('template_url'); ?>/svg/morning/bg.svg" class="morning">
        </div>
        <div class="sun" data-sun>
            <img src="<?php bloginfo('template_url'); ?>/svg/morning/sun.svg" class="morning">
        </div>
        <div class="mountains" data-mount>
            <img src="<?php bloginfo('template_url'); ?>/svg/night/mountain.svg" class="night">
            <img src="<?php bloginfo('template_url'); ?>/svg/morning/mountain.svg" class="morning">
        </div>
        <div class="clouds" data-cloud>
            <img src="<?php bloginfo('template_url'); ?>/svg/clouds.svg" alt="">
        </div>
        <div class="blur" data-blur>
            <img src="<?php bloginfo('template_url'); ?>/svg/blur.jpg" alt="">
        </div>

    </div>

    <audio class="page-audio js-pageAudioFirst" preload="auto">
        <source src="<?php bloginfo('template_url'); ?>/audio/cork.mp3" type="audio/mpeg">
        <source src="<?php bloginfo('template_url'); ?>/audio/cork.wav" type="audio/wav">
    </audio>
    <audio class="page-audio js-pageAudioSecond" preload="auto">
        <source src="<?php bloginfo('template_url'); ?>/audio/pour.mp3" type="audio/mpeg">
        <source src="<?php bloginfo('template_url'); ?>/audio/pour.wav" type="audio/wav">
    </audio>
    <audio class="page-audio js-pageAudioThird" preload="auto">
        <source src="<?php bloginfo('template_url'); ?>/audio/rain.mp3" type="audio/mpeg">
        <source src="<?php bloginfo('template_url'); ?>/audio/rain.wav" type="audio/wav">
    </audio>

</body>
</html>