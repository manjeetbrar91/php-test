<?php

/**
 * Plugin Name: GetResponse Integration Plugin
 * Plugin URI: http://wordpress.org/extend/plugins/getresponse-integration/
 * Description: This plug-in enables installation of a GetResponse fully customizable sign up form on your WordPress site or blog. Once a web form is created and added to the site the visitors are automatically added to your GetResponse contact list and sent a confirmation email. The plug-in additionally offers sign-up upon leaving a comment.
 * Version: 3.2.2
 * Author: GetResponse
 * Author URI: http://getresponse.com/
 * Author: Grzegorz Struczynski
 * License: GPL2
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
class Gr_Integration
{
	const CUSTOM_TYPE = 'wordpress';

	/**
	 * Db Prefix
	 **/
	var $GrOptionDbPrefix = 'GrIntegrationOptions_';

	/**
	 * Plugin Version simple format
	 */
	var $PluginVersionSimpleFormat = '316';

	/**
	 * Billing fields - custom fields map
	 **/
	var $biling_fields = array(
		'firstname' => array('value' => 'billing_first_name', 'name' => 'firstname', 'default' => 'yes'),
		'lastname'  => array('value' => 'billing_last_name', 'name' => 'lastname', 'default' => 'yes'),
		'email'     => array('value' => 'billing_email', 'name' => 'email', 'default' => 'yes'),
		'address'   => array('value' => 'billing_address_1', 'name' => 'address', 'default' => 'no'),
		'city'      => array('value' => 'billing_city', 'name' => 'city', 'default' => 'no'),
		'state'     => array('value' => 'billing_state', 'name' => 'state', 'default' => 'no'),
		'phone'     => array('value' => 'billing_phone', 'name' => 'phone', 'default' => 'no'),
		'country'   => array('value' => 'billing_country', 'name' => 'country', 'default' => 'no'),
		'company'   => array('value' => 'billing_company', 'name' => 'company', 'default' => 'no'),
		'postcode'  => array('value' => 'billing_postcode', 'name' => 'postcode', 'default' => 'no')
	);

	/**
	 * BuddyPress Max days limit
	 * If user is unconfirmed until this number of days -
	 * will be removed from temporary queue
	 * @var int
	 */
	var $max_bp_unconfirmed_days = 30;

	/**
	 * @var bool
	 */
	var $buddypress_active = false;
	var $woocomerce_active = false;
	var $contact_form_url = "https://app.getresponse.com/feedback.html?devzone=yes&lang=en";

	/**
	 * @var GetResponse API Instance
	 */
	var $grApiInstance;

	/**
	 * @var array User Custom Fields
	 */
	var $all_custom_fields = array();

	/**
	 * @var int
	 */
	var $invalid_apikey_code = 1014;
	var $success_api_code = 200;

	/**
	 * gr 360 urls
	 * @var string
	 */
	var $api_url_360_com = 'https://api3.getresponse360.com/v3';
	var $api_url_360_pl = 'https://api3.getresponse360.pl/v3';

	/**
	 * Default traceroute host
	 */
	var $traceroute_host = 'api.getresponse.com';

    static $post_fields = array(
        'comment_campaign',
        'checkout_campaign',
        'comment_on',
        'comment_label',
        'comment_checked',
        'checkout_checked',
        'sync_order_data',
        'fields_prefix',
        'registration_campaign',
        'registration_on',
        'registration_label',
        'registration_checked',
        'bp_registration_campaign',
        'bp_registration_checked'
    );

	/**
	 * Constructor
	 */
	public function __construct()
	{
		require_once('lib/UnauthorizedRequestException.class.php');
		require_once('lib/GrApi.class.php');

		$this->grApiInstance = $this->GetApiInstance();

		// WooCommerce Plugin
		$this->setWoocomerceStatus();

		// BuddyPress Plugin
		$this->setBuddypressStatus();

		// settings link in plugin page
		if (is_admin())
		{
			add_filter('plugin_action_links', array(&$this, 'AddPluginActionLink'), 10, 2);
		}

		if (get_option($this->GrOptionDbPrefix . 'api_key'))
		{
			// on/off comment
			$this->setCommentParams();

			// on/off registration form
			$this->setRegistrationParams();

			// on/off checkout for WooCommerce
			$this->setWoocomerceParams();

			// on/off registration for BuddyPress
			$this->setBuddypressParams();
		}

		add_action('admin_menu', array(&$this, 'Init'));

		add_action('plugins_loaded', array($this, 'GrLangs'));

		// register widget and css file
		add_action('widgets_init', array($this, 'register_widgets'));

		// register ajax
		$this->registerAjax();

		// register shortcode
		add_shortcode('grwebform', array($this, 'showWebformShortCode'));

        global $pagenow;

		if (in_array($pagenow, array('post.php', 'post-new.php'))) {
			add_action('admin_head', array($this, 'GrJsShortcodes'));
			add_action('init', array($this, 'GrButtons'));
		}
	}

	/**
	 * Add admin page
	 */
	public function Init()
	{
		// settings menu
		add_options_page(
			__('GetResponse', 'Gr_Integration'),
			__('GetResponse', 'Gr_Integration'),
			'manage_options',
			__FILE__,
			array(&$this, 'AdminOptionsPage')
		);

		// enqueue CSS
		wp_enqueue_style('GrStyle');
		wp_enqueue_style('GrCustomsStyle');

		// enqueue JS
		wp_enqueue_script('GrCustomsJs');

		// detect adblock
		wp_register_script('GrAdsJs', plugins_url('js/ads.js', __FILE__));
		wp_enqueue_script('GrAdsJs');
	}

	/**
	 * Register ajax
	 */
	private function registerAjax()
	{
		add_action('wp_ajax_gr-traceroute-submit', array($this, 'gr_traceroute_ajax_request'));
		add_action('wp_ajax_gr-variants-submit', array($this, 'gr_variants_ajax_request'));
		add_action('wp_ajax_gr-forms-submit', array($this, 'gr_forms_ajax_request'));
		add_action('wp_ajax_gr-webforms-submit', array($this, 'gr_webforms_ajax_request'));
	}

	/**
	 * Add settings change button on plugin page
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return mixed
	 */
	public function AddPluginActionLink($links, $file)
	{
		if ($file == $this->PluginName())
		{
			$settings_link = '<a href="' . admin_url('options-general.php?page=' . $this->PluginName()) . '">' . __('Settings', 'Gr_Integration') . '</a>';
			array_unshift($links, $settings_link);
		}

		return $links;
	}

	/**
	 * Get plugin name
	 * @return string plugin name
	 */
	public function PluginName()
	{
		static $this_plugin;
		if (empty($this_plugin))
		{
			$this_plugin = plugin_basename(__FILE__);
		}

		return $this_plugin;
	}

	/**
	 * Ajax method get traceroute result
	 */
	public function gr_traceroute_ajax_request()
	{
		$response = '';
		if (preg_match("/^win.*/i", PHP_OS))
		{
			exec('tracert ' . $this->traceroute_host, $out, $code);
		}
		else
		{
			exec('traceroute -m15 ' . $this->traceroute_host . ' 2>&1', $out, $code);
		}
		if ($code && is_array($out))
		{
			$response = __('An error occurred while trying to traceroute: ', 'Gr_Integration') . join("\n", $out);
		}
		if ( !empty($out))
		{
			foreach ($out as $line)
			{
				$response .= $line . "<br/>";
			}
		}
		$response = json_encode(array('success' => $response));
		header("Content-Type: application/json");
		echo $response;
		exit;
	}

	/**
	 * Ajax method get variants result
	 */
	public function gr_variants_ajax_request()
	{
		$response = json_encode(array('error' => 'No variants'));
		if ( isset($_GET['form_id'])) {
			$variants = $this->grApiInstance->getFormVariants($_GET['form_id']);
			if ( !empty($variants)) {
				$response = json_encode(array('success' => $variants));
			}
		}
		header("Content-Type: application/json");
		echo $response;
		exit;
	}

	/**
	 * Ajax method get variants result
	 */
	public function gr_forms_ajax_request()
	{
	    try {
		    $forms = $this->grApiInstance->getForms(array('sort' => array('name' => 'asc')));
        } catch (UnauthorizedRequestException $e) {
            $this->disableIntegration();
        }
		$response = json_encode(array('success' => $forms));
		header("Content-Type: application/json");
		echo $response;
		exit;
	}

	/**
	 * Ajax method get variants result
	 */
	public function gr_webforms_ajax_request()
	{
        $forms = array();

	    try {
            $forms = $this->grApiInstance->getWebforms(array('sort' => array('name' => 'asc')));
        } catch (UnauthorizedRequestException $e) {
	        $this->disableIntegration();
        }

		$response = json_encode(array('success' => $forms));
		header("Content-Type: application/json");
		echo $response;
		exit;
	}

	/**
	 * Admin page settings
	 */
	public function AdminOptionsPage()
	{
		$api_url = $api_domain = '';

		//Check if curl extension is set and curl_init method is callable
		$this->checkCurlExtension();

		$apikey = isset($_POST['api_key']) ? $_POST['api_key'] : get_option($this->GrOptionDbPrefix . 'api_key');
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$getresponse_360_account = isset($_POST['getresponse_360_account']) ? $_POST['getresponse_360_account'] : null;
		}
		else
		{
			$getresponse_360_account = get_option($this->GrOptionDbPrefix . 'getresponse_360_account');
		}

		if (isset($getresponse_360_account))
		{
			$api_url = isset($_POST['api_url']) ? $_POST['api_url'] : get_option($this->GrOptionDbPrefix . 'api_url');
			$api_domain = isset($_POST['api_domain']) ? $_POST['api_domain'] : get_option($this->GrOptionDbPrefix . 'api_domain');
			if ( !empty($api_domain)) {
				$url_data = parse_url($api_url);
				$this->traceroute_host = $url_data['host'];
			}
		}

		if ( !empty($apikey) && !isset($_GET['error']))
		{
		    $ping = null;

		    try {
                $api = new GetResponseIntegration($apikey, $api_url, $api_domain);
                // api errors
                $ping = $api->ping();
            } catch (UnauthorizedRequestException $e) {
		        $this->disableIntegration();
            }

			$ping_to_array = (array)$ping;
			$invalid_apikey = ( empty($ping_to_array) || (isset($ping->code) && $ping->code == $this->invalid_apikey_code) ) ? true : false;

			if ($api->http_status != $this->success_api_code && $invalid_apikey == false)
			{ ?>
				<div class="GR_config_box">
					<table class="wp-list-table widefat">
						<thead>
						<tr>
							<th>
								<span class="GR_header"><?php _e('GetResponse Plugin - API Error', 'Gr_Integration'); ?></span>
							</th>
						</tr>
						</thead>
						<tbody id="the-list">
						<tr class="active" id="">
							<td class="desc">
								<?php _e('Oops there was a problem connecting to the GetResponse API server.', 'Gr_Integration'); ?>
								<br/><br/>
								<?php _e('Error code/message:', 'Gr_Integration'); ?>
								<blockquote>
									<code><?php echo !empty($ping->message) ? $ping->message : $api->http_status; ?></code>
								</blockquote>
								<?php _e('Traceroute result:', 'Gr_Integration'); ?>
								<blockquote>
									<div class="GR_traceroute" id="GrTraceroutResult">
										<img src="images/loading.gif"/>
										<?php _e('Receiving data, please be patient...', 'Gr_Integration'); ?>
									</div>
								</blockquote>
								<br/>
								<?php _e('Please', 'Gr_Integration'); ?>
								<?php echo '<a href="' . $this->contact_form_url . '" target="_blank"><strong>' . __('CONTACT US', 'Gr_Integration') . '</strong></a>'; ?>
								<?php _e('and send error code/message and traceroute result.', 'Gr_Integration'); ?>
								<br/><br/><br/>
								<a href="<?php echo 'options-general.php?page=' . $this->PluginName(); ?>&error=1"><?php _e('Back to GetResponse Plugin site', 'Gr_Integration'); ?></a>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<script>
					jQuery(document).ready(function ($) {
						$.ajax({
							url: 'admin-ajax.php',
							data: {
								'action': 'gr-traceroute-submit'
							},
							success: function (response) {
								$('#GrTraceroutResult').html(response.success);
							},
							error: function (errorThrown) {
								$('#GrTraceroutResult').html(errorThrown);
							}
						});
					});
				</script>
				<?php
				return;
			}

			if ($invalid_apikey == false && isset($ping->accountId))
			{
				update_option($this->GrOptionDbPrefix . 'api_key', $apikey);
				update_option($this->GrOptionDbPrefix . 'getresponse_360_account', $getresponse_360_account);

				if ($getresponse_360_account == 0)
				{
					$api_url = '';
					$api_domain = '';
				}

				update_option($this->GrOptionDbPrefix . 'api_url', $api_url);
				update_option($this->GrOptionDbPrefix . 'api_domain', $api_domain);

				$this->grApiInstance = $this->GetApiInstance();

				// admin page settings
				if (isset($_POST['comment_campaign']) || isset($_POST['checkout_campaign']))
				{
					foreach (self::$post_fields as $field)
					{
						$val = isset($_POST[$field]) ? $_POST[$field] : null;
						update_option($this->GrOptionDbPrefix . $field, sanitize_text_field(stripslashes($val)));
					}

					// woocommerce settings
					if ($this->woocomerce_active === true and isset($_POST['checkout_on']))
					{
						update_option($this->GrOptionDbPrefix . 'checkout_on', $_POST['checkout_on']);
						update_option($this->GrOptionDbPrefix . 'checkout_label', sanitize_text_field(stripslashes($_POST['checkout_label'])));
					}

					// buddypress settings
					if ($this->buddypress_active === true and isset($_POST['bp_registration_on']))
					{
						update_option($this->GrOptionDbPrefix . 'bp_registration_on', $_POST['bp_registration_on']);
						update_option($this->GrOptionDbPrefix . 'bp_registration_label', sanitize_text_field(stripslashes($_POST['bp_registration_label'])));
					}
					?>
					<div id="message" class="updated fade" style="margin: 2px; 0px; 0px;">
						<p><strong><?php _e('Settings saved', 'Gr_Integration'); ?></strong></p>
					</div>
					<?php
					// sync order data - custom fields
					if (isset($_POST['custom_field']))
					{
						foreach ($this->biling_fields as $value => $bf)
						{
							if (in_array($value, array_keys($_POST['custom_field'])) == true && preg_match('/^[_a-zA-Z0-9]{2,32}$/m', stripslashes($_POST['custom_field'][$value])) == true)
							{
								update_option($this->GrOptionDbPrefix . $value, sanitize_text_field(stripslashes($_POST['custom_field'][$value])));
							}
							else
							{
								delete_option($this->GrOptionDbPrefix . $value);
							}
						}
					}
					else
					{
						foreach (array_keys($this->biling_fields) as $value)
						{
							delete_option($this->GrOptionDbPrefix . $value);
						}
					}
				}
			}
			else
			{
				?>
				<div id="message" class="error " style="margin: 2px; 0px; 0px;">
					<p>
						<strong><?php _e('Settings error', 'Gr_Integration'); ?></strong> <?php if(!empty($api->error)) { echo $api->error; } else  { _e(' - Invalid API Key', 'Gr_Integration'); } ?>
					</p>
				</div>
				<?php
			}
		}

		if (isset($_POST['api_key']) and $_POST['api_key'] == '')
		{
			?>
			<div id="message" class="error " style="margin: 2px; 0px; 0px;">
				<p>
					<strong><?php _e('Settings error', 'Gr_Integration'); ?></strong> <?php _e(' - API Key can\'t be empty.', 'Gr_Integration') ?>
				</p>
			</div>
			<?php
			update_option($this->GrOptionDbPrefix . 'api_key', $apikey);
			update_option($this->GrOptionDbPrefix . 'getresponse_360_account', $getresponse_360_account);
			update_option($this->GrOptionDbPrefix . 'api_url', $api_url);
			update_option($this->GrOptionDbPrefix . 'api_domain', $api_domain);
		}

		?>
		<!-- CONFIG BOX -->
		<div class="GR_config_box">
			<table class="wp-list-table widefat">
				<thead>
				<tr>
					<th><span class="GR_header"><?php _e('GetResponse Plugin Settings', 'Gr_Integration'); ?></span>
					</th>
				</tr>
				</thead>
				<tbody id="the-list">

				<tr>
					<td>
						<span style="margin-top: 0px; color: #b81c23;">
							<?php _e('The new GetResponse Forms are now available! Enjoy a new era of growing your list, but be patient if any issues with the WordPress Plugin may occur during the BETA phase. We’re polishing it as we speak!'); ?>
						</span>
					</td>
				</tr>

				<tr class="active" id="">
					<td class="desc">
						<form method="post"
						      action="<?php echo admin_url('options-general.php?page=' . $this->PluginName()); ?>">

							<!-- API KEY -->
							<p>
								<label class="GR_label" for="api_key"><?php _e('API Key:', 'Gr_Integration'); ?></label>
								<input class="GR_api" type="text" name="api_key"
								       value="<?php echo get_option($this->GrOptionDbPrefix . 'api_key') ?>"/>

								<a class="gr-tooltip">
											<span class="gr-tip" style="width:178px">
												<span>
													<?php _e('Enter your API key. You can find it on your GetResponse profile in Account Details -> GetResponse API', 'Gr_Integration'); ?>
												</span>
											</span>
								</a>
							</p>

							<!-- GetResponse 360 -->
							<p>
								<label class="GR_label" for="api_key"><?php _e('GetResponse 360:', 'Gr_Integration'); ?></label>
								<input class="GR_checkbox" type="checkbox" name="getresponse_360_account" id="getresponse_360_account" value="1"
									   <?php if (get_option($this->GrOptionDbPrefix . 'getresponse_360_account', '') == 1)
									   { ?>checked="checked"<?php } ?>/>

								<a class="gr-tooltip">
									<span class="gr-tip" style="width:278px">
										<span>
											<?php _e('For GetResponse 360 accounts', 'Gr_Integration'); ?>
										</span>
									</span>
								</a>
							</p>

							<div id="getresponse_360_account_options"
								 <?php if (get_option($this->GrOptionDbPrefix . 'getresponse_360_account') == 0)
								 { ?>style="display: none;"<?php } ?>>

								<p style="font-style: italic;">*This data is available from your account manager.</p>

								<p>
									<label class="GR_label"
										   for="api_url"><?php _e('Type:', 'Gr_Integration'); ?></label>
									<select class="" name="api_url" id="api_url">
										<option
											value="<?php echo $this->api_url_360_pl; ?>" <?php selected(get_option($this->GrOptionDbPrefix . 'api_url'), $this->api_url_360_pl); ?>><?php _e('GetResponse360 PL', 'Gr_Integration'); ?></option>
										<option
											value="<?php echo $this->api_url_360_com; ?>" <?php selected(get_option($this->GrOptionDbPrefix . 'api_url'), $this->api_url_360_com); ?>><?php _e('GetResponse360 COM', 'Gr_Integration'); ?></option>
									</select>
								</p>

								<!-- Domain -->
								<p>
									<label class="GR_label" for="api_domain"><?php _e('Domain:', 'Gr_Integration'); ?></label>
									<input class="GR_api" type="text" name="api_domain"
										   value="<?php echo get_option($this->GrOptionDbPrefix . 'api_domain') ?>"/>

									<a class="gr-tooltip">
											<span class="gr-tip" style="width:178px">
												<span>
													<?php _e('Enter your domain without protocol http:// eg: "yourdomainname.com"', 'Gr_Integration'); ?>
												</span>
											</span>
									</a>
								</p>

							</div>

							<!-- SUBMIT -->
							<?php if (get_option($this->GrOptionDbPrefix . 'api_key') == '')
							{ ?>
								<input id="gr_primary_submit" type="submit" name="Submit"
								       value="<?php _e('Save', 'Gr_Integration'); ?>" class="button-primary"/>
							<?php } ?>
							<!-- WEBFORM SETTINGS -->
							<div id="settings" <?php if (get_option($this->GrOptionDbPrefix . 'api_key') == '')
							{ ?>style="display: none;"<?php } ?>>
								<!-- SUBSCRIBE VIA WEB FORM -->
								<h3>
									<?php _e('Subscribe via Web Form', 'Gr_Integration'); ?>
								</h3>

								<p>
									<?php _e('To activate a GetResponse Web Form widget drag it to a sidebar or click on it.', 'Gr_Integration'); ?>
									<?php echo '<a href="' . admin_url('widgets.php') . '"><strong>' . __('Go to Widgets site', 'Gr_Integration') . '</strong></a>'; ?>
								</p>

								<!-- SUBSCRIPTION VIA COMMENT -->
								<h3>
									<?php _e('Subscribe via Comment', 'Gr_Integration'); ?>
								</h3>

								<!-- COMMENT INTEGRATION SWITCH ON/OFF -->
								<?php
								$comment_type         = get_option($this->GrOptionDbPrefix . 'comment_on');
								$registration_type    = get_option($this->GrOptionDbPrefix . 'registration_on');
								$bp_registration_type = get_option($this->GrOptionDbPrefix . 'bp_registration_on');
								?>
								<p>
									<label class="GR_label"
									       for="comment_on"><?php _e('Comment integration:', 'Gr_Integration'); ?></label>
									<select class="GR_select2" name="comment_on" id="comment_integration">
										<option
											value="0" <?php selected($comment_type, 0); ?>><?php _e('Off', 'Gr_Integration'); ?></option>
										<option
											value="1" <?php selected($comment_type, 1); ?>><?php _e('On', 'Gr_Integration'); ?></option>
									</select> <?php _e('(allow subscriptions when visitors comment)', 'Gr_Integration'); ?>
								</p>

								<?php
								$comment_campaign         = get_option($this->GrOptionDbPrefix . 'comment_campaign');
								$checkout_campaign        = get_option($this->GrOptionDbPrefix . 'checkout_campaign');
								$registration_campaign    = get_option($this->GrOptionDbPrefix . 'registration_campaign');
								$bp_registration_campaign = get_option($this->GrOptionDbPrefix . 'bp_registration_campaign');
								if ($this->grApiInstance)
								{
								    try {
                                        $campaigns = $this->grApiInstance->getCampaigns(array('sort' => array('name' => 'asc')));
                                    } catch (UnauthorizedRequestException $e) {
								        $this->disableIntegration();
                                    }
								}
								?>

								<div id="comment_show"
								     <?php if (get_option($this->GrOptionDbPrefix . 'comment_on') != 1)
								     { ?>style="display: none;"<?php } ?>>
									<!-- CAMPAIGN TARGET -->
									<p>
										<label class="GR_label"
										       for="comment_campaign"><?php _e('Target Campaign:', 'Gr_Integration'); ?></label>
										<?php
										// check if no errors
										$this->returnCampaignSelector($campaigns, $comment_campaign, 'comment_campaign');
										?>
									</p>

									<!-- ADDITIONAL TEXT - COMMENT SUBSCRIPTION-->
									<p>
										<label class="GR_label"
										       for="comment_label"><?php _e('Additional text:', 'Gr_Integration'); ?></label>
										<input class="GR_input2" type="text" name="comment_label"
										       value="<?php echo esc_attr( get_option($this->GrOptionDbPrefix . 'comment_label', __('Sign up to our newsletter!', 'Gr_Integration'))) ?>"/>
									</p>

									<!-- DEFAULT CHECKED - COMMENT SUBSCRIPTION -->
									<p>
										<label class="GR_label"
										       for="comment_checked"><?php _e('Subscribe checkbox checked by default', 'Gr_Integration'); ?></label>
										<input class="GR_checkbox" type="checkbox" name="comment_checked" value="1"
										       <?php if (get_option($this->GrOptionDbPrefix . 'comment_checked', '') == 1)
										       { ?>checked="checked"<?php } ?>/>
									</p>
								</div>

								<script>
									jQuery('#comment_integration').change(function () {
										var value = jQuery(this).val();
										if (value == '1') {
											jQuery('#comment_show').show('slow');
										}
										else {
											jQuery('#comment_show').hide('slow');
										}
									});
								</script>

								<!-- SUBSCRIBE VIA REGISTRATION PAGE-->
								<h3>
									<?php _e('Subscribe via Registration Page', 'Gr_Integration'); ?>
								</h3>

								<p>
									<label class="GR_label"
									       for="registration_on"><?php _e('Registration integration:', 'Gr_Integration'); ?></label>
									<select class="GR_select2" name="registration_on" id="registration_integration">
										<option
											value="0" <?php selected($registration_type, 0); ?>><?php _e('Off', 'Gr_Integration'); ?></option>
										<option
											value="1" <?php selected($registration_type, 1); ?>><?php _e('On', 'Gr_Integration'); ?></option>
									</select> <?php _e('(allow subscriptions at the registration page)', 'Gr_Integration'); ?>
								</p>

								<div id="registration_show"
								     <?php if (get_option($this->GrOptionDbPrefix . 'registration_on') != 1)
								     { ?>style="display: none;"<?php } ?>>
									<!-- CAMPAIGN TARGET -->
									<p>
										<label class="GR_label"
										       for="registration_campaign"><?php _e('Target Campaign:', 'Gr_Integration'); ?></label>
										<?php
										$this->returnCampaignSelector($campaigns, $registration_campaign, 'registration_campaign');
										?>
									</p>

									<!-- ADDITIONAL TEXT - REGISTRATION SUBSCRIPTION-->
									<p>
										<label class="GR_label"
										       for="registration_label"><?php _e('Additional text:', 'Gr_Integration'); ?></label>
										<input class="GR_input2" type="text" name="registration_label"
										       value="<?php echo get_option($this->GrOptionDbPrefix . 'registration_label', __('Sign up to our newsletter!', 'Gr_Integration')) ?>"/>
									</p>

									<!-- DEFAULT CHECKED - REGISTRATION SUBSCRIPTION -->
									<p>
										<label class="GR_label"
										       for="registration_checked"><?php _e('Subscribe checkbox checked by default', 'Gr_Integration'); ?></label>
										<input class="GR_checkbox" type="checkbox" name="registration_checked" value="1"
										       <?php if (get_option($this->GrOptionDbPrefix . 'registration_checked', '') == 1)
										       { ?>checked="checked"<?php } ?>/>
									</p>
								</div>

								<script>
									jQuery('#registration_integration').change(function () {
										var value = jQuery(this).val();
										if (value == '1') {
											jQuery('#registration_show').show('slow');
										}
										else {
											jQuery('#registration_show').hide('slow');
										}
									});
								</script>

								<!-- SUBSCRIBE VIA BUDDYPRESS REGISTRATION PAGE-->
								<?php if ($this->buddypress_active === true)
								{ ?>
									<h3>
										<?php _e('Subscribe via BuddyPress Registration Page', 'Gr_Integration'); ?>
									</h3>

									<p>
										<label class="GR_label"
										       for="bp_registration_integration"><?php _e('Registration integration:', 'Gr_Integration'); ?></label>
										<select class="GR_select2" name="bp_registration_on"
										        id="bp_registration_integration">
											<option
												value="0" <?php selected($bp_registration_type, 0); ?>><?php _e('Off', 'Gr_Integration'); ?></option>
											<option
												value="1" <?php selected($bp_registration_type, 1); ?>><?php _e('On', 'Gr_Integration'); ?></option>
										</select> <?php _e('(allow subscriptions at the BuddyPress registration page)', 'Gr_Integration'); ?>
									</p>

									<div id="bp_registration_show"
									     <?php if (get_option($this->GrOptionDbPrefix . 'bp_registration_on') != 1)
									     { ?>style="display: none;"<?php } ?>>
										<!-- CAMPAIGN TARGET -->
										<p>
											<label class="GR_label"
											       for="bp_registration_campaign"><?php _e('Target Campaign:', 'Gr_Integration'); ?></label>
											<?php
											$this->returnCampaignSelector($campaigns, $bp_registration_campaign, 'bp_registration_campaign');
											?>
										</p>

										<!-- ADDITIONAL TEXT - REGISTRATION SUBSCRIPTION-->
										<p>
											<label class="GR_label"
											       for="bp_registration_label"><?php _e('Additional text:', 'Gr_Integration'); ?></label>
											<input class="GR_input2" type="text" name="bp_registration_label"
											       id="bp_registration_label"
											       value="<?php echo esc_attr(get_option($this->GrOptionDbPrefix . 'bp_registration_label', __('Sign up to our newsletter!', 'Gr_Integration'))) ?>"/>
										</p>

										<!-- DEFAULT CHECKED - REGISTRATION SUBSCRIPTION -->
										<p>
											<label class="GR_label"
											       for="bp_registration_checked"><?php _e('Subscribe checkbox checked by default', 'Gr_Integration'); ?></label>
											<input class="GR_checkbox" type="checkbox" name="bp_registration_checked"
											       id="bp_registration_checked" value="1"
											       <?php if (get_option($this->GrOptionDbPrefix . 'bp_registration_checked', '') == 1)
											       { ?>checked="checked"<?php } ?>/>
										</p>
									</div>

									<script>
										jQuery('#bp_registration_integration').change(function () {
											var value = jQuery(this).val();
											if (value == '1') {
												jQuery('#bp_registration_show').show('slow');
											}
											else {
												jQuery('#bp_registration_show').hide('slow');
											}
										});
									</script>
									<?php
								}
								?>

								<!-- SUBSCRIPTION VIA CHECKOUT PAGE -->
								<?php if ($this->woocomerce_active === true)
								{
									$checkout_type = get_option($this->GrOptionDbPrefix . 'checkout_on');
									?>
									<h3><?php _e('Subscribe via Checkout Page (WooCommerce)', 'Gr_Integration'); ?></h3>

									<!-- CHECKOUT INTEGRATION SWITCH ON/OFF -->
									<p>
										<label class="GR_label"
										       for="checkout_on"><?php _e('Checkout integration:', 'Gr_Integration'); ?></label>
										<select class="GR_select2" name="checkout_on" id="checkout_integration">
											<option
												value="0" <?php selected($checkout_type, 0); ?>><?php _e('Off', 'Gr_Integration'); ?></option>
											<option
												value="1" <?php selected($checkout_type, 1); ?>><?php _e('On', 'Gr_Integration'); ?></option>
										</select> <?php _e('(allow subscriptions at the checkout stage)', 'Gr_Integration'); ?>
										<br/>
									</p>

									<div id="checkout_show"
									     <?php if (get_option($this->GrOptionDbPrefix . 'checkout_on') == 0)
									     { ?>style="display: none;"<?php } ?>>
										<!-- CAMPAIGN TARGET -->
										<p>
											<label class="GR_label"
											       for="checkout_campaign"><?php _e('Target campaign:', 'Gr_Integration'); ?></label>

											<?php
											$this->returnCampaignSelector($campaigns, $checkout_campaign, 'checkout_campaign');
											?>
										</p>

										<!-- ADDITIONAL TEXT - CHECKOUT SUBSCRIPTION -->
										<p>
											<label class="GR_label"
											       for="comment_label"><?php _e('Additional text:', 'Gr_Integration'); ?></label>
											<input class="GR_input2" type="text" name="checkout_label"
											       value="<?php echo esc_attr(get_option($this->GrOptionDbPrefix . 'checkout_label', __('Sign up to our newsletter!', 'Gr_Integration'))) ?>"/>
										</p>

										<!-- DEFAULT CHECKED - CHECKOUT SUBSCRIPTION -->
										<p>
											<label class="GR_label"
											       for="checkout_checked"><?php _e('Sign up box checked by default', 'Gr_Integration'); ?></label>
											<input class="GR_checkbox" type="checkbox" name="checkout_checked" value="1"
											       <?php if (get_option($this->GrOptionDbPrefix . 'checkout_checked', '') == 1)
											       { ?>checked="checked"<?php } ?>/>
										</p>

										<!-- SYNC ORDER DATA - CHECKOUT SUBSCRIPTION -->
										<p>
											<label class="GR_label"
											       for="sync_order_data"><?php _e('Map custom fields:', 'Gr_Integration'); ?></label>
											<input class="GR_checkbox" type="checkbox" name="sync_order_data"
											       id="sync_order_data" value="1"
											       <?php if (get_option($this->GrOptionDbPrefix . 'sync_order_data', '') == 1)
											       { ?>checked="checked"<?php } ?>/>

											<a class="gr-tooltip">
														<span class="gr-tip" style="width:170px">
															<span>
																<?php _e('Check to update customer details. Each input can be max. 32 characters and include lowercase, a-z letters, digits or underscores. Incorrect or empty entries won’t be added.', 'Gr_Integration'); ?>
															</span>
														</span>
											</a>
										</p>

										<!-- CUSTOM FIELDS PREFIX - CHECKOUT SUBSCRIPTION -->
										<div id="customNameFields" style="display: none;">
											<div class="gr-custom-field" style="padding-left: 150px;">
												<select class="jsNarrowSelect" name="custom_field" multiple="multiple">
													<?php
													foreach ($this->biling_fields as $value => $filed)
													{
														$custom     = get_option($this->GrOptionDbPrefix . $value);
														$field_name = ($custom) ? $custom : $filed['name'];
														echo '<option data-inputvalue="' . $field_name . '" value="' . esc_attr($value) . '" id="' . $filed['value'] . '"', ($filed['default'] == 'yes' || $custom) ? ' selected="selected"' : '', $filed['default'] == 'yes' ? ' disabled="disabled"' : '', '>', $filed['name'], '</option>';
													} ?>
												</select>
											</div>
										</div>
									</div>

									<script>
										jQuery('#checkout_integration').change(function () {
											var value = jQuery(this).val();
											if (value == '1') {
												jQuery('#checkout_show').show();
											}
											else {
												jQuery('#checkout_show').hide();
											}
										});

										var sod = jQuery('#sync_order_data'), cfp = jQuery('#customNameFields');
										if (sod.prop('checked') == true) {
											cfp.show();
										}
										sod.change(function () {
											cfp.toggle('slow');
										});

										jQuery('.jsNarrowSelect').selectNarrowDown();
									</script>

									<?php
								}
								?>


								<!-- SUBMIT -->
								<input type="submit" name="Submit" value="<?php _e('Save', 'Gr_Integration'); ?>"
								       class="button-primary"/>

								<!-- WEB FORM SHORTCODE -->
								<h3><?php _e('Web Form Shortcode', 'Gr_Integration'); ?></h3>

								<p><?php _e('With the GetResponse Wordpress plugin, you can use shortcodes to place web forms in blog posts. Simply place the following tag in your post wherever you want the web form to appear:', 'Gr_Integration'); ?>
									<br/><br/>
									<code>[grwebform url="PUT_WEBFORM_URL_HERE" css="on/off" center="on/off"
										center_margin="200"/]</code>
									<br/><br/>
									<b><?php _e('Allowed attributes:', 'Gr_Integration'); ?></b>
									<br/>
									<code>CSS</code>
									- <?php _e('Set this parameter to ON, and the form will be displayed in a GetResponse format; set it to OFF, and the form will be displayed in a standard Wordpress format. Allowed only for old forms.', 'Gr_Integration'); ?>
									<br/>
									<code>CENTER</code>
									- <?php _e('Set this parameter to ON, and the form will be centralized; set it to OFF, and the form will be displayed in the standard left side without margin.', 'Gr_Integration'); ?>
									<br/>
									<code>CENTER_MARGIN</code>
									- <?php _e('Set this parameter to customize margin (element width) [Default is 200px] ', 'Gr_Integration'); ?>
									<br/>
									<code>VARIANT</code>
									- <?php _e('Set this parameter to customize form variant, allowed values: A-H. Variants can be set in your GetResponse panel. Allowed only for the new forms.', 'Gr_Integration'); ?>
								</p>

								<div class="GR_img_webform_shortcode"></div>

								<br/>

								<h3><?php _e('Having problems with the plugin?', 'Gr_Integration'); ?></h3>
								<?php _e('You can drop us a line including the following details and we\'ll do what we can. ', 'Gr_Integration'); ?>
								<?php echo '<a href="' . $this->contact_form_url . '" target="_blank"><strong>' . __('CONTACT US', 'Gr_Integration') . '</strong></a>'; ?>
								<textarea id="GrDetails" onclick="copyText(this)"
								          style="width: 100%; height: 150px; resize:vertical ;"><?php
									$this->getWpDetailsList();
									$this->getActivePluginsList();
									$this->getGrPluginDetailsList();
									$this->getWidgetsList();
									?></textarea>
								<script>
									if (window.canRunAds === undefined) {
										jQuery('#GrDetails').append('\nAdBlock : active');
									}

									function copyText(element) {
										element.focus();
										element.select();
									}

									jQuery('#getresponse_360_account').change(function () {
										var value = jQuery('#getresponse_360_account:checked').val();
										if (value == '1')
										{
											jQuery('#getresponse_360_account_options').show();
										}
										else
										{
											jQuery('#getresponse_360_account_options').hide();
										}
									});
								</script>
							</div>
						</form>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<!-- RSS BOX -->
		<div class="GR_rss_box">
			<table class="wp-list-table widefat">
				<thead>
				<tr>
					<th>GetResponse RSS</th>
				</tr>
				</thead>
				<tbody id="the-list2">
				<tr class="active" id="">
					<td class="desc">
						<?php $this->GrRss(); ?>
					</td>
				</tr>
				</tbody>
			</table>
			<!-- SOCIAL BOX -->
			<br/>
			<table class="wp-list-table widefat">
				<thead>
				<tr>
					<th>GetResponse Social</th>
				</tr>
				</thead>
				<tbody id="the-list2">
				<tr class="active" id="">
					<td class="desc">
						<ul>
							<li>
								<a class="GR_ico sprite facebook-ico" href="http://www.facebook.com/getresponse"
								   target="_blank" title="Facebook">Facebook</a>
							</li>
							<li>
								<a class="GR_ico sprite twitter-ico" href="http://twitter.com/getresponse"
								   target="_blank" title="Twitter">Twitter</a>
							</li>
							<li>
								<a class="GR_ico sprite linkedin-ico" href="http://www.linkedin.com/company/implix"
								   target="_blank" title="LinkedIn">LinkedIn</a>
							</li>
							<li>
								<a class="GR_ico sprite blog-ico" href="http://blog.getresponse.com/" target="_blank"
								   title="Blog">Blog</a>
							</li>
						</ul>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Register widgets
	 */
	public function register_widgets()
	{
		wp_register_style('GrStyle', plugins_url('css/getresponse-integration.css', __FILE__));
		wp_register_style('GrCustomsStyle', plugins_url('css/getresponse-custom-field.css', __FILE__));
		wp_register_script('GrCustomsJs', plugins_url('js/getresponse-custom-field.src-verified.js', __FILE__));
		include_once('lib/class-gr-widget-webform.php');
		register_widget('GR_Widget');
	}

	/**
	 * Display shortcode for webform
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public static function showWebformShortCode($atts)
	{
		$params = shortcode_atts(array(
			'url'           => 'null',
			'css'           => 'on',
			'center'        => 'off',
			'center_margin' => '200',
			'variant'       => ''
		), $atts);

		$div_start = $div_end = '';
		if ($params['center'] == 'on')
		{
			$div_start = '<div style="margin-left: auto; margin-right: auto; width: ' . $params['center_margin'] . 'px;">';
			$div_end   = '</div>';
		}

		$css = ($params['css'] == "off") ? htmlspecialchars("&css=1") : "";

		$variant_maps = array('A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7);
		$params['variant'] = strtoupper($params['variant']);
		$variant = (in_array($params['variant'], array_keys($variant_maps))) ? htmlspecialchars("&v=" . $variant_maps[$params['variant']]) : "";

		$params['url'] = self::replaceHttpsToHttpIfSslOn($params['url']);

		return $div_start . '<script type="text/javascript" src="' . $params['url'] . $css . $variant . '"></script>' . $div_end;
	}

	/**
	 * Add Checkbox to comment form
	 */
	public function AddCheckboxToComment()
	{
		$checked = get_option($this->GrOptionDbPrefix . 'comment_checked');
		?>
		<p>
			<input class="GR_checkbox" value="1" id="gr_comment_checkbox" type="checkbox" name="gr_comment_checkbox"
				   <?php if ($checked)
				   { ?>checked="checked"<?php } ?>/>
			<?php echo get_option($this->GrOptionDbPrefix . 'comment_label'); ?>
		</p><br/>
		<?php
	}

	/**
	 * Add Checkbox to checkout form
	 */
	public function AddCheckboxToCheckoutPage()
	{
		$checked = get_option($this->GrOptionDbPrefix . 'checkout_checked');
		?>
		<p class="form-row form-row-wide">
			<input class="input-checkbox GR_checkoutbox" value="1" id="gr_checkout_checkbox" type="checkbox"
			       name="gr_checkout_checkbox" <?php if ($checked)
			       { ?>checked="checked"<?php } ?> />
			<label for="gr_checkout_checkbox" class="checkbox">
				<?php echo get_option($this->GrOptionDbPrefix . 'checkout_label'); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Add Checkbox to BuddyPress registration form
	 * styles can be simply edit: /css/getresponse-bp-form.css
	 */
	public function AddCheckboxToBpRegistrationPage()
	{
		$bp_checked = get_option($this->GrOptionDbPrefix . 'bp_registration_checked');
		?>
		<div class="gr_bp_register_checkbox">
			<label>
				<input class="input-checkbox GR_bpbox" value="1" id="gr_bp_checkbox" type="checkbox"
				       name="gr_bp_checkbox" <?php if ($bp_checked)
				{ ?> checked="checked"<?php } ?> />
				<span
					class="gr_bp_register_label"><?php echo get_option($this->GrOptionDbPrefix . 'bp_registration_label'); ?></span>
			</label>
		</div>
		<?php
	}

	/**
	 * Add checkbox styles to BuddyPress integration
	 */
	public function AddGrBpCss()
	{
		echo '<link rel="stylesheet" id="gr-bp-css" href="' . WP_PLUGIN_URL . '/getresponse-integration/css/getresponse-bp-form.css" type="text/css" media="all">';
	}

	/**
	 * Add Checkbox to registration form
	 */
	public function AddCheckboxToRegistrationForm()
	{
		if ( ! is_user_logged_in())
		{
			$checked = get_option($this->GrOptionDbPrefix . 'registration_checked');
			?>
			<p class="form-row form-row-wide">
				<input class="input-checkbox GR_registrationbox" value="1" id="gr_registration_checkbox" type="checkbox"
				       name="gr_registration_checkbox" <?php if ($checked)
				       { ?>checked="checked"<?php } ?> />
				<label for="gr_registration_checkbox" class="checkbox">
					<?php echo get_option($this->GrOptionDbPrefix . 'registration_label'); ?>
				</label>
			</p><br/>
			<?php
		}
	}

	/**
	 * Grab email from checkout form
	 */
	public function GrabEmailFromCheckoutPage()
	{
		if ($_POST['gr_checkout_checkbox'] != 1 || false === $this->grApiInstance || empty($_POST['billing_email'])) {
			return;
		}

		$firstname = isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : null;
		$lastname = isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : null;

		$name = 'Friend';

		if (!empty($firstname) || !empty($lastname)) {
			$name = trim($firstname . ' ' . $lastname);
		}

		$customs = array();
		$campaign = get_option($this->GrOptionDbPrefix . 'checkout_campaign');
		if (get_option($this->GrOptionDbPrefix . 'sync_order_data') == true) {
			foreach ($this->biling_fields as $custom_name => $custom_field) {
				$custom = get_option($this->GrOptionDbPrefix . $custom_name);
				if ($custom && !empty($_POST[$custom_field['value']])) {
					$customs[$custom] = $_POST[$custom_field['value']];
				}
			}
		}

		$this->addContact($campaign, $name, $_POST['billing_email'], 0, $customs);
	}

	/**
	 * Grab email from BuddyPress registration form
	 */
	public function SetBpActivateKey()
	{
		if ($_POST['gr_bp_checkbox'] == 1 && ! empty($_POST['signup_email']))
		{
			$email        = $_POST['signup_email'];
			$emails       = get_option($this->GrOptionDbPrefix . 'bp_registered_emails');
			$emails_array = unserialize($emails);
			if ( ! empty($emails_array))
			{
				if (is_array($emails_array))
				{
					$emails = array_merge($emails_array, array($email));
					update_option($this->GrOptionDbPrefix . 'bp_registered_emails', serialize($emails));
				}
			}
			else
			{
				update_option($this->GrOptionDbPrefix . 'bp_registered_emails', serialize(array($email)));
			}
		}
	}

	/**
	 * Add activated contact to GR (BuddyPress)
	 */
	public function addActivatedBpContactsToGr()
	{
		$emails = get_option($this->GrOptionDbPrefix . 'bp_registered_emails');
		if ( ! empty($emails))
		{
			$emails_array = unserialize($emails);
			if (is_array($emails_array))
			{
				foreach ($emails_array as $k => $v)
				{
					$unset = false;
					$user  = $this->getUserDetailsByEmail($v);

					if ( ! empty($user))
					{
						if ($user->activation_key == null)
						{
							if ($this->grApiInstance)
							{
								$campaign = get_option($this->GrOptionDbPrefix . 'bp_registration_campaign');
								$this->addContact($campaign, $user->display_name, $user->user_email, 0);
							}
							$unset = true;
						}
						else if ((bool) ($this->getDateDiffWithCurrentDate($user->user_registered) >= $this->max_bp_unconfirmed_days) == true)
						{
							$unset = true;
						}
					}
					if ($unset == true)
					{
						unset($emails_array[$k]);
					}
				}
			}
			update_option($this->GrOptionDbPrefix . 'bp_registered_emails', serialize($emails_array));
		}
	}

	/**
	 * Grab email from checkout form - paypal express
	 */
	public function GrabEmailFromCheckoutPagePE()
	{
		if ($_POST['gr_checkout_checkbox'] == 1)
		{
			if ($this->grApiInstance)
			{
				$campaign = get_option($this->GrOptionDbPrefix . 'checkout_campaign');
				$this->addContact($campaign, 'Friend', $_POST['billing_email']);
			}
		}
	}

	/**
	 * Grab email from comment form
	 */
	public function GrabEmailFromComment()
	{
		if ($_POST['gr_comment_checkbox'] == 1 AND isset($_POST['email']))
		{
			if ($this->grApiInstance)
			{
				$campaign = get_option($this->GrOptionDbPrefix . 'comment_campaign');
				$this->addContact($campaign, $_POST['author'], $_POST['email']);
			}
		}
		else if ($_POST['gr_comment_checkbox'] == 1 && is_user_logged_in())
		{
			$current_user = wp_get_current_user();
			$name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
			if (strlen(trim($name)) > 1)
			{
				$campaign = get_option($this->GrOptionDbPrefix . 'comment_campaign');

				try {
                    $this->addContact($campaign, $name, $current_user->user_email);
                } catch (UnauthorizedRequestException $e) {
				    $this->disableIntegration();
                }
			}
		}
	}

	/**
	 * Grab email from registration form
	 */
	public function GrabEmailFromRegistrationForm()
	{
		if ($_POST['gr_registration_checkbox'] == 1 && $this->grApiInstance)
		{
			if ($this->woocomerce_active === true && isset($_POST['email']))
			{
				$email = $_POST['email'];
				$name  = isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : 'Friend';
			}
			else if (isset($_POST['user_email']) && isset($_POST['user_login']))
			{
				$email = $_POST['user_email'];
				$name  = $_POST['user_login'];
			}

			if ( ! empty($email) && ! empty($name))
			{
				$campaign = get_option($this->GrOptionDbPrefix . 'registration_campaign');
				$this->addContact($campaign, $name, $email);
			}
		}
	}

	/**
	 * Display GetResponse blog 10 RSS links
	 */
	public function GrRss()
	{

		$lang     = get_bloginfo("language") == 'pl-PL' ? 'pl' : 'com';
		$feed_url = 'http://blog.getresponse.' . $lang . '/feed';

		$num = 15; // numbers of feeds:
		include_once(ABSPATH . WPINC . '/feed.php');
		$rss = fetch_feed($feed_url);

		if (is_wp_error($rss))
		{
			_e('No rss items, feed might be broken.', 'Gr_Integration');
		}
		else
		{
			$rss_items = $rss->get_items(0, $rss->get_item_quantity($num));

			// If the feed was erroneously
			if ( ! $rss_items)
			{
				$md5 = md5($feed_url);
				delete_transient('feed_' . $md5);
				delete_transient('feed_mod_' . $md5);
				$rss       = fetch_feed($feed_url);
				$rss_items = $rss->get_items(0, $rss->get_item_quantity($num));
			}

			$content = '<ul class="GR_rss_ul">';
			if ( ! $rss_items)
			{
				$content .= '<li class="GR_rss_li">' . _e('No rss items, feed might be broken.', 'Gr_Integration') . '</li>';
			}
			else
			{
				foreach ($rss_items as $item)
				{
					$url = preg_replace('/#.*/', '', esc_url($item->get_permalink(), $protocolls = null, 'display'));
					$content .= '<li class="GR_rss_li">';
					$content .= '<a class="GR_rss_a" href="' . $url . '" target="_blank">' . esc_html($item->get_title()) . '</a> ';
					$content .= '</li>';
				}
			}
			$content .= '</ul>';
			echo $content;
		}
	}

	/**
	 * GetResponse MCE buttons
	 */
	public function GrButtons()
	{
		add_filter('mce_buttons', array(&$this, 'GrRegisterButtons'));
		add_filter("mce_external_plugins", array(&$this, 'GrAddButtons'));
	}

	/**
	 * GetResponse MCE plugin
	 */
	public function GrAddButtons($plugin_array)
	{
		global $wp_version;

		if ($wp_version >= 3.9)
		{
			$plugin_array['GrShortcodes'] = untrailingslashit(plugins_url('/', __FILE__)) . '/js/gr-plugin.js?v' . $this->PluginVersionSimpleFormat;
		}
		else
		{
			$plugin_array['GrShortcodes'] = untrailingslashit(plugins_url('/', __FILE__)) . '/js/gr-plugin_3_8.js?v' . $this->PluginVersionSimpleFormat;
		}

		return $plugin_array;
	}

	/**
	 * Display GetResponse MCE buttons
	 */
	public function GrRegisterButtons($buttons)
	{
		array_push(
			$buttons,
			'separator',
			'GrShortcodes'
		);

		return $buttons;
	}

	/**
	 * Display GetResponse MCE buttons
	 */
	public function GrJsShortcodes()
	{
		$GrOptionDbPrefix = 'GrIntegrationOptions_';
		$api_key          = get_option($GrOptionDbPrefix . 'api_key');
		$api_url          = get_option($GrOptionDbPrefix . 'api_url');
		$api_domain       = get_option($GrOptionDbPrefix . 'api_domain');

		$webforms  = null;
		$forms     = null;
		$campaingns = null;
		if ( !empty($api_key))
		{
		    try {
                $api     = new GetResponseIntegration($api_key, $api_url, $api_domain, is_ssl());
                $webforms = $api->getWebforms(array('sort' => array('name' => 'asc')));
                $forms    = $api->getForms(array('sort' => array('name' => 'asc')));
                $api_key = 'true';
                $campaingns = $api->getCampaigns(); // for 3.8 version
            } catch (UnauthorizedRequestException $e) {
                $this->disableIntegration();
            }
		}
		else
		{
			$api_key = 'false';
		}

		if (strlen($api_domain) > 0)
		{
			$webforms = $this->setGetResponse360domainToWebFormUrl($webforms, $api_domain);
			$forms = $this->setGetResponse360domainToWebFormUrl($forms, $api_domain);
		}

		$webforms  = json_encode($webforms);
		$forms     = json_encode($forms);
		$campaingns = json_encode($campaingns); // for 3.8 version
		?>
		<script type="text/javascript">
			var my_webforms = <?php echo $webforms; ?>;
			var my_forms = <?php echo $forms; ?>;
			var my_campaigns = <?php echo $campaingns;  // for 3.8 version ?>;
			var text_forms = '<?php echo __('New Forms', 'Gr_Integration'); ?>';
			var text_webforms = '<?php echo __('Old Web Forms', 'Gr_Integration'); ?>';
			var text_no_forms = '<?php echo __('No Forms', 'Gr_Integration'); ?>';
			var text_no_webforms = '<?php echo __('No Web Forms', 'Gr_Integration'); ?>';
			var api_key = <?php echo $api_key; ?>;
		</script>
		<?php
	}

	/**
	 *
	 * Set GetResponse360 domain To WebForm Url
	 *
	 * @param $webforms
	 * @param $api_domain
	 * @return mixed
	 */
	public function setGetResponse360domainToWebFormUrl($webforms, $api_domain)
	{
		if (is_array($webforms))
		{
			foreach ($webforms as $webform)
			{
				$webform->scriptUrl = 'http://' . $api_domain . '/' . $webform->scriptUrl;
			}
		}

		return $webforms;
	}

	/**
	 * API Instance
	 */
	public function GetApiInstance()
	{
		$api_key = get_option($this->GrOptionDbPrefix . 'api_key');
		$api_url = get_option($this->GrOptionDbPrefix . 'api_url');
		$api_domain = get_option($this->GrOptionDbPrefix . 'api_domain');
		if ( ! empty($api_key))
		{
			$apiInstance = new GetResponseIntegration($api_key, $api_url, $api_domain);
			return $apiInstance;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Languages
	 */
	public function GrLangs()
	{
		load_plugin_textdomain('Gr_Integration', false, plugin_basename(dirname(__FILE__)) . "/langs");
	}

	/**
	 * Check if curl extension is set and curl_init method is callable
	 */
	function checkCurlExtension()
	{
		if ( ! extension_loaded('curl') or ! is_callable('curl_init'))
		{
			echo '<h3>' . __('cURL Error !', 'Gr_Integration') . '</h3>';
			echo '<h3>' . __('GetResponse Integration Plugin requires PHP cURL extension', 'Gr_Integration') . '</h3>';

			return;
		}
	}

	/**
	 * Return user details by email address
	 *
	 * @param $email
	 *
	 * @return bool|mixed
	 */
	public function getUserDetailsByEmail($email)
	{
		if (empty($email))
		{
			return false;
		}

		global $wpdb;
		$sql = "
				SELECT
				users.user_email,
				users.display_name,
				users.user_registered,
				(SELECT usermeta.meta_value FROM $wpdb->usermeta usermeta
				WHERE usermeta.meta_key = 'activation_key' and usermeta.user_id = users.ID) as activation_key
				FROM $wpdb->users users
				WHERE
				users.user_email = '" . $email . "'
				";

		return $wpdb->get_row($sql);
	}

	/**
	 * Return GetResponse plugin details
	 *
	 * @return bool|mixed
	 */
	public function getGrPluginDetails()
	{
		global $wpdb;
		$sql = "
				SELECT *
				FROM $wpdb->options options
				WHERE options.`option_name` LIKE 'GrIntegrationOptions%'
				ORDER BY options.`option_name` DESC;
				";

		return $wpdb->get_results($sql);
	}

	/**
	 * Return different between current date and registered date in days
	 *
	 * @param $user_registered_date
	 *
	 * @return bool
	 */
	public function getDateDiffWithCurrentDate($user_registered_date)
	{
		$diff = strtotime(date("Y-m-d H:i:s")) - (strtotime($user_registered_date));

		return floor($diff / 3600 / 24);
	}

	/**
	 * Return list of active plugins
	 */
	public function getActivePluginsList()
	{
		echo "Active plugins:\n";
		foreach (get_plugins() as $plugin_name => $plugin_details)
		{
			if (is_plugin_active($plugin_name) === true)
			{
				foreach ($plugin_details as $details_key => $details_value)
				{
					if (in_array($details_key, array('Name', 'Version', 'PluginURI')))
					{
						echo $details_key . " : " . $details_value . "\n";
					}
				}
				echo "Path : " . $plugin_name . "\n";
			}
		}
	}

	/**
	 * Return list of active plugins
	 */
	public function getGrPluginDetailsList()
	{
		echo "Getresponse-integration details:\n";
		$details = $this->getGrPluginDetails();
		if ( ! empty($details))
		{
			foreach ($details as $detail)
			{
				echo str_replace('GrIntegrationOptions_', '', $detail->option_name) . " : " . $detail->option_value . "\n";
			}
		}
	}

	/**
	 * Return list WP details
	 */
	public function getWpDetailsList()
	{
		echo "Version : " . get_bloginfo('version') . "\n";
		echo "Charset : " . get_bloginfo('charset') . "\n";
		echo "Url : " . get_bloginfo('url') . "\n";
		echo "Language : " . get_bloginfo('language') . "\n";
		echo "PHP : " . phpversion() . "\n";
	}

	/**
	 * Return active widgets
	 */
	public function getWidgetsList()
	{
		echo "Widgets:\n";
		$widgets = get_option('sidebars_widgets');
		echo serialize($widgets);
	}

	/**
	 * Return select with campaigns
	 *
	 * @param array $campaigns
	 * @param string $current_campaign
	 * @param string $name
	 */
	private function returnCampaignSelector($campaigns, $current_campaign, $name)
	{
		if ( !empty($campaigns))
		{
			?>
			<select name="<?php echo $name;?>" id="<?php echo $name;?>" class="GR_select">
				<?php
				foreach ($campaigns as $campaign)
				{
					echo '<option value="' . $campaign->campaignId . '" id="' . $campaign->campaignId . '"', $current_campaign == $campaign->campaignId ? ' selected="selected"' : '', '>', $campaign->name, '</option>';
				}
				?>
			</select>
		<?php }
		else
		{
			_e('No Campaigns.', 'Gr_Integration');
		}
	}

	/**
	 * set woocomerce_active
	 */
	private function setWoocomerceStatus()
	{
		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
		{
			$this->woocomerce_active = true;
		}
	}

	/**
	 * set buddypress_active
	 */
	private function setBuddypressStatus()
	{
		if (in_array('buddypress/bp-loader.php', apply_filters('active_plugins', get_option('active_plugins'))))
		{
			$this->buddypress_active = true;
		}
	}

	/**
	 * on/off comment
	 */
	private function setCommentParams()
	{
		if (get_option($this->GrOptionDbPrefix . 'comment_on'))
		{
			add_action('comment_form', array(&$this, 'AddCheckboxToComment'));
			add_action('comment_post', array(&$this, 'GrabEmailFromComment'));
		}
	}

	/**
	 * on/off registration form
	 */
	private function setRegistrationParams()
	{
		if (get_option($this->GrOptionDbPrefix . 'registration_on'))
		{
			add_action('register_form', array(&$this, 'AddCheckboxToRegistrationForm'));
			add_action('user_register', array(&$this, 'GrabEmailFromRegistrationForm'));
		}
	}

	/**
	 * on/off checkout for WooCommerce
	 */
	private function setWoocomerceParams()
	{
		if ($this->woocomerce_active === true && get_option($this->GrOptionDbPrefix . 'checkout_on'))
		{
			add_action('woocommerce_after_checkout_billing_form', array(&$this, 'AddCheckboxToCheckoutPage'), 5);
			add_action('woocommerce_ppe_checkout_order_review', array(&$this, 'AddCheckboxToCheckoutPage'), 5);
			add_action('woocommerce_checkout_update_order_meta', array(&$this, 'GrabEmailFromCheckoutPage'), 5, 2);
			add_action('woocommerce_ppe_do_payaction', array(&$this, 'GrabEmailFromCheckoutPagePE'), 5, 1);
			add_action('woocommerce_register_form', array(&$this, 'AddCheckboxToRegistrationForm'), 5, 1);
		}
	}

	/**
	 * on/off registration for BuddyPress
	 */
	private function setBuddypressParams()
	{
		if ($this->buddypress_active === true && get_option($this->GrOptionDbPrefix . 'bp_registration_on'))
		{
			add_action('bp_before_registration_submit_buttons', array(
				&$this,
				'AddCheckboxToBpRegistrationPage'
			), 5);
			add_action('bp_after_registration_confirmed', array(&$this, 'SetBpActivateKey'), 5, 1);
			add_action('bp_after_activation_page', array(&$this, 'addActivatedBpContactsToGr'), 5, 1);
			add_action('wp_head', array(&$this, 'AddGrBpCss'));
		}
	}


	/**
	 * Add (or update) contact to gr campaign
	 *
	 * @param       $campaign
	 * @param       $name
	 * @param       $email
	 * @param int   $cycle_day
	 * @param array $user_customs
	 *
	 * @return mixed
	 */
	public function addContact($campaign, $name, $email, $cycle_day = 0, $user_customs = array())
	{
		$user_customs['origin'] = self::CUSTOM_TYPE;

		$params = array(
			'name'       => $name,
			'email'      => $email,
			'dayOfCycle' => $cycle_day,
			'campaign'   => array('campaignId' => $campaign),
			'ipAddress'  => $_SERVER['REMOTE_ADDR'],
		);

		$this->all_custom_fields = $this->getCustomFields();

		try {
            $results = (array)$this->grApiInstance->getContacts(array(
                'query' => array(
                    'email' => $email,
                    'campaignId' => $campaign
                )
            ));

            $contact = array_pop($results);
            // if contact already exists in gr account
            if ( !empty($contact) && isset($contact->contactId))
            {
                $results = $this->grApiInstance->getContact($contact->contactId);
                if ( !empty($results->customFieldValues))
                {
                    $params['customFieldValues'] = $this->mergeUserCustoms($results->customFieldValues, $user_customs);
                }

                return $this->grApiInstance->updateContact($contact->contactId, $params);
            }
            else
            {
                $params['customFieldValues'] = $this->setCustoms($user_customs);

                return $this->grApiInstance->addContact($params);
            }

        } catch (UnauthorizedRequestException $e) {
		    $this->disableIntegration();
        }
	}

	/**
	 * Merge user custom fields selected on WP admin site with those from gr account
	 * @param $results
	 * @param $user_customs
	 *
	 * @return array
	 */
	public function mergeUserCustoms($results, $user_customs)
	{
		$custom_fields = array();

		if (is_array($results))
		{
			foreach ($results as $customs)
			{
				$value = $customs->value;
				if (in_array($customs->name, array_keys($user_customs)))
				{
					$value = array($user_customs[$customs->name]);
					unset($user_customs[$customs->name]);
				}

				$custom_fields[] = array(
					'customFieldId' => $customs->customFieldId,
					'value'         => $value
				);
			}
		}

		return array_merge($custom_fields, $this->setCustoms($user_customs));
	}

	/**
	 * Set user custom fields
	 * @param $user_customs
	 *
	 * @return array
	 */
	public function setCustoms($user_customs)
	{
		$custom_fields = array();

		if (empty($user_customs))
		{
			return $custom_fields;
		}

		foreach ($user_customs as $name => $value)
		{
			// if custom field is already created on gr account set new value
			if (in_array($name, array_keys($this->all_custom_fields)))
			{
				$custom_fields[] = array(
					'customFieldId' => $this->all_custom_fields[$name],
					'value'         => array($value)
				);
			}
			// create new custom field
			else
			{
			    try {
                    $custom = $this->grApiInstance->addCustomField(array(
                        'name' => $name,
                        'type' => "text",
                        'hidden' => "false",
                        'values' => array($value)
                    ));

                    if (!empty($custom) && !empty($custom->customFieldId)) {
                        $custom_fields[] = array(
                            'customFieldId' => $custom->customFieldId,
                            'value' => array($value)
                        );
                    }
                } catch (UnauthorizedRequestException $e) {
			        $this->disableIntegration();
                }
			}
        }

        return $custom_fields;
	}

	/**
	 * Get all user custom fields from gr account
	 * @return array
	 */
	public function getCustomFields()
	{
		$all_customs = array();
		$results     = $this->grApiInstance->getCustomFields();
		if ( !empty($results))
		{
			foreach ($results as $ac)
			{
				if (isset($ac->name) && isset($ac->customFieldId)) {
					$all_customs[$ac->name] = $ac->customFieldId;
				}
			}
		}

		return $all_customs;
	}

	/**
	 * Replace https prefix in url if ssl is off
	 * @param $url
	 *
	 * @return mixed
	 */
	public static function replaceHttpsToHttpIfSslOn($url)
	{
		return ( !empty($url) && !is_ssl() && strpos($url, 'https') === 0) ? str_replace('https', 'http', $url) : $url;
	}

    /**
     * Disable integration
     */
    private function disableIntegration()
    {
        foreach (self::$post_fields as $field) {
            update_option($this->GrOptionDbPrefix . $field, null);
        }

        update_option($this->GrOptionDbPrefix . 'web_forms', null);
        update_option($this->GrOptionDbPrefix . 'api_key', null);
        update_option($this->GrOptionDbPrefix . 'bp_registration_on', null);
        update_option($this->GrOptionDbPrefix . 'checkout_on', null);
    }

} //Gr_Integration

/**
 * Init plugin
 */
if (defined('ABSPATH') and defined('WPINC'))
{
	if (empty($GLOBALS['Gr_Integration']))
	{
		$GLOBALS['Gr_Integration'] = new Gr_Integration();
	}
}

?>