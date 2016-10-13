<?php
/**
 * Integrate Facebook Login Checkout for WooCommerce Store
 *
 * Plugin Name: WooCommerce Facebook Login Checkout
 * Plugin URI: http://terrytsang.com/shop/shop/woocommerce-facebook-login-checkout/
 * Description: Integrated WooCommerce Checkout with Facebook Login
 * Version: 1.0.0
 * Author: Terry Tsang
 * Author URI: http://terrytsang.com

 *
 * Copyright 2012 Terry Tsang
 * License: Single Site
 */

/**
 * WooCommerce Facebook Checkout Base Class
 */
if ( !class_exists( 'WooCommerce_Facebook_Checkout' ) ) {

	class WooCommerce_Facebook_Checkout {
	
		public static $plugin_prefix;
		public static $plugin_url;
		public static $plugin_path;
		public static $plugin_basefile;
		
		public $facebook;

		/**
		 * Constructor
		 */
		public function __construct() {
			self::$plugin_prefix = 'woo_fbcheckout_';
			self::$plugin_basefile = plugin_basename(__FILE__);
			self::$plugin_url = plugin_dir_url(self::$plugin_basefile);
			self::$plugin_path = trailingslashit(dirname(__FILE__));
		}
		
		/**
		 * Load the init and admin hooks
		 */
		public function load() {
			// load the hooks
			add_action( 'init', array( $this, 'load_hooks' ) );
		}
	
		/**
		 * Load the facebook php sdk files
		 */
		public function includes() {
			include_once( 'classes/facebook/base_facebook.php' );
			include_once( 'classes/facebook/facebook.php' );
			
			include_once( 'classes/class-woo-fbcheckout-settings.php' );
		}

		/**
		 * Load the init hooks
		 */
		public function load_hooks() {	
			if ( $this->is_woocommerce_activated() ) {
				$this->includes();
				
				$this->settings = new WooCommerce_Facebook_Checkout_Settings();
				$this->settings->load();
				
				$enable_fbcheckout	= self::$plugin_prefix.'enable_fbcheckout';
				$fbcheckout_enable 	= get_option($enable_fbcheckout);
				
				if($fbcheckout_enable)
				{
					add_action( 'woocommerce_before_checkout_form', array( $this, 'custom_checkout_login_form'), 10 );
					//add_action( 'woocommerce_before_customer_login_form', array( $this, 'custom_checkout_login_form'), 10 );
				}
				
			}
		}
		
		/**
		 * Load the custom login form hooks
		 */
		public function custom_checkout_login_form() {
			global $woocommerce;
				
			$app_id 	= self::$plugin_prefix.'app_id';
			$app_secret = self::$plugin_prefix.'app_secret';
				
			$fbcheckout_app_id 		= get_option($app_id);
			$fbcheckout_app_secret 	= get_option($app_secret);
				
			$facebook = new Facebook(array(
					'appId'  => $fbcheckout_app_id,
					'secret' => $fbcheckout_app_secret,
			));
				
			// Get User ID
			$user = $facebook->getUser();
				
			if ($user) {
				try {
					// Proceed knowing you have a logged in user who's authenticated.
					$fb_profile = $facebook->api('/me');

					$user_id = get_current_user_id();
					
					//get all the related info from facebook api
					$fb_username = $fb_profile['username'];
					$fb_password = $fb_profile['id'];
					$fb_email = $fb_profile['email'];
					
					$fb_name = $fb_profile['name'];
					$fb_first_name = $fb_profile['first_name'];
					$fb_last_name = $fb_profile['last_name'];
					
				
						// Create customer account and log them in
						if (!$user_id && $fb_email != "") :
						
							
							// if there are no errors, let's create the user account
							if (!email_exists($fb_email)) 
							{
							
								$user_pass 	= esc_attr( $fb_password);
								$user_id 	= wp_create_user( $fb_email, $user_pass, $fb_email );
								
								if ( !$user_id ) :
									$woocommerce->add_error( '<strong>' . __('ERROR', 'woocommerce') . '</strong>: ' . __('Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'woocommerce') );
		                    		break;
								endif;
							
								// Change role if not admin login
								if( ! current_user_can( 'manage_options' ) ) 
								{
									wp_update_user( array ('ID' => $user_id, 'role' => 'customer') ) ;
									
									// Action
									do_action( 'woocommerce_created_customer', $user_id );
									
									// send the user a confirmation and their login details
									$mailer = $woocommerce->mailer();
									$mailer->customer_new_account( $user_id, $user_pass );
									
									// set the WP login cookie
									$secure_cookie = is_ssl() ? true : false;
									wp_set_auth_cookie($user_id, true, $secure_cookie);
									
									
									//update user meta fields
									if (!email_exists($fb_email)) 
									{
										wp_update_user( array ( 'ID' => $user_id, 'user_email' => $fb_email ) ) ;
										update_user_meta( $user_id, 'billing_email', $fb_email );
									}
									
									if($fb_first_name != "")
									{
										wp_update_user( array ( 'ID' => $user_id, 'first_name' => $fb_first_name ) ) ;
										update_user_meta( $user_id, 'billing_first_name', $fb_first_name );
									}
									
									if($fb_last_name != "")
									{
										wp_update_user( array ( 'ID' => $user_id, 'last_name' => $fb_last_name ) ) ;
										update_user_meta( $user_id, 'billing_last_name', $fb_first_name );
									}
									
								}
							}
							else
							{
								$credentials = array();
							    $credentials['user_login'] = $fb_email;
							    $credentials['user_password'] = $fb_password;
							    if ( !empty( $remember ) ){ 
							        $credentials['remember'] = true;
							    }
							    $wpuser = wp_signon( $credentials, true );
							    
							}
							
							// if the user is already connected, then redirect them to landing page or show some content
							wp_redirect(get_permalink(woocommerce_get_page_id('checkout')));
							
							exit;
						
						endif;
					
						
				} catch (FacebookApiException $e) {
					error_log($e);
					$user = null;
				}
			}
			
			if ($user) {
				$logoutUrl = $facebook->getLogoutUrl();
			} else {
				$loginUrl   = $facebook->getLoginUrl(
						array(
								'scope'         => 'email,offline_access,publish_stream,user_birthday,user_location,user_about_me,user_hometown',	
						)
				);
			}
				
			
			
			$login_html = "";
			
			if(!is_user_logged_in())
			{
				if (!$user):
					if($fbcheckout_app_id != "" && $fbcheckout_app_secret != ""):
						$login_html .= '<p>';
						$login_html .= '<a href="'.$loginUrl.'"><img src="'.plugins_url('/images/woocommerce-fb-login.png', __FILE__).'" alt="WooCommerce Facebook Login Checkout" title="WooCommerce Facebook Login Checkout" border="0" /></a>';
						$login_html .= '</p>';
					endif;
				endif;
			}
			
				
			echo $login_html;
		}

		
		/**
		 * Check if woocommerce is activated
		 */
		public function is_woocommerce_activated() {
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				return true;
			} else {
				return false;
			}
		}
	}
}

/**
 * Instance of plugin
 */
$woo_fbcheckout = new WooCommerce_Facebook_Checkout();
$woo_fbcheckout->load();

?>