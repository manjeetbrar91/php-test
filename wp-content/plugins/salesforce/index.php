<?php
/**
 * Plugin Name: Salesforce
 * Description: Allows you to Add Lead and Send Message
 * Author: DT
 */ 

$plugin_dir = dirname( __FILE__ );
$plugin_dir_rel = dirname( plugin_basename( __FILE__ ) );
$plugin_url = plugin_dir_url( __FILE__ );

// add_action('admin_menu','add_contact_plugin');

add_shortcode('add_lead','add_lead');
add_shortcode('add_partner','add_partner');
add_shortcode('add_subscriber','add_subscriber');
add_shortcode('add_wines','add_wines');
add_shortcode('add_locations','add_locations');
add_shortcode('add_prices','add_prices');
add_shortcode('add_incentives','add_incentives');
add_shortcode('add_offer','add_offer');
add_shortcode('add_recommendation','add_recommendation');
add_shortcode('add_tag','add_tag');


add_shortcode('add_login','add_login');
add_shortcode('add_member','add_member');
add_shortcode('add_activation_link','add_activation_link');
add_shortcode('add_forgot_pass','add_forgot_pass');
add_shortcode('add_reset_pass','add_reset_pass');
add_shortcode('add_dashboard','add_dashboard');

add_shortcode('add_mail','add_mail');
add_shortcode('add_registereduser','add_registereduser');
add_shortcode('send_verification_email','send_verification_email');

// function add_contact_plugin()
// {
	// add_menu_page("menu page","Salesforce",9,__FILE__,"salesforce");
	// add_submenu_page(__FILE__," Sublevel 1", "Partner", 8, "sub-page", "partner");
// }

// function salesforce()
// {
	// include("details.php");
// }

// Register the new widget at the widgets_init action hook
class wp_my_plugin extends WP_Widget {

	// constructor
	function wp_my_plugin() {
		parent::WP_Widget(false, $name = __('Zendesk Articles', 'wp_widget_plugin') );
	}

	// widget form creation
	function form($instance)
	{	
		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);
			 
			 $articles = esc_textarea($instance['articles']);
		} else {
			 $title = '';
			 $articles = '';
		}
		?>

		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		

		<p>
		<label for="<?php echo $this->get_field_id('articles'); ?>"><?php _e('No. of Articles to show:', 'wp_widget_plugin'); ?></label>
		
		<input class="widefat" id="<?php echo $this->get_field_id('articles'); ?>" name="<?php echo $this->get_field_name('articles'); ?>" type="number" step="1" min="1" value="<?php echo $articles; ?>" size="3">
		<small>Default Value set to 3</small>
		</p>
		<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		 $instance = $old_instance;
		  // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		  
		  $instance['articles'] = strip_tags($new_instance['articles']);
		 return $instance;
	}

	// widget display
	function widget($args, $instance) {
		extract( $args );
	   // these are the widget options
	   $title = apply_filters('widget_title', $instance['title']);
	   
	   $articles = $instance['articles'];
	   echo $before_widget;
	   // Display the widget
	   echo '<div class="widget-text wp_widget_plugin_box">';

	   // Check if title is set
	   if ( $title ) {
		  echo $before_title . $title . $after_title;
	   }
	   else
	   {
		   echo $before_title . 'Popular Help Articles' . $after_title;
	   }

	   // Check if articles is set
	  

		$article_result = zendesk_data ("/api/v2/help_center/articles.json?sort_by=updated_at&sort_order=desc","GET",array());
		if(isset($article_result->error))
		{
			if(isset($article_result->error->title) && isset($article_result->error->message))
			{
				echo $article_result->error->title ."<br/>";
				echo $article_result->error->message;
			}
			else
			{
				echo $article_result->error ."<br/>";
				echo $article_result->description;
			}
		}
		else
		{
			$i=0;

			if(trim($articles)==null || trim($articles)<=0) 
			{
				$articles=3;
			}

			foreach($article_result->articles as $article_results)
			{
				if($i<$articles)
				{
					?>
					<ul>
						<li>
							<a href='<?php echo home_url() ?>/article?id=<?php echo $article_results->id ?>'>
							<?php echo $article_results->name; ?></a>
						</li>
					</ul>
				
				<?php
				}
				$i++;
			}	
		}

	   echo '</div>';
	   echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_my_plugin");'));

function add_lead()
{
	include("lead.php");
}
function add_partner()
{	
	include("partneruser.php");
}
function add_subscriber()
{	
	include("subscriber.php");
}
function add_wines()
{	
	include("wines.php");
}
function add_locations()
{	
	include("locations.php");
}
function add_prices()
{	
	include("prices.php");
}
function add_incentives()
{	
	include("incentives.php");
}
function add_offer()
{	
	include("offer.php");
}
function add_recommendation()
{	
	include("recommendation.php");
}
function add_tag()
{	
	include("tag.php");
}


function send_verification_email()
{	
	include("send_verification_email.php");
}


function add_login()
{
	include("login.php");
}
function add_member()
{
	include("partner.php");
}
function add_activation_link()
{
	include("activation-link.php");
}
function add_forgot_pass()
{
 include("forgot_pass.php");
}
function add_reset_pass()
{
 include("reset_pass.php");
}
function add_dashboard()
{
 include("dashboard.php");
}

function add_mail()
{	
	include("mail.php");
}
function add_registereduser()
{	
	include("partneruser.php");
}
?>