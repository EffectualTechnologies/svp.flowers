<?php

/**
 * Plugin Name: WooCommerce - Social Login
 * Plugin URI: http://wpweb.co.in
 * Description: Allow your customers to login and checkout with social networks such as  Facebook, Twitter, Google, Yahoo, LinkedIn, Foursquare, Windows Live, VK.com, Instagram, Amazon and PayPal.
 * Version: 1.4.4
 * Author: WPWeb
 * Author URI: http://wpweb.co.in
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions 
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
global $wpdb;

if( !defined( 'WOO_SLG_VERSION' ) ) {
	define( 'WOO_SLG_VERSION', '1.4.4' ); //version of plugin
}
if( !defined( 'WOO_SLG_URL' ) ) {
	define( 'WOO_SLG_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'WOO_SLG_DIR' ) ) {
	define( 'WOO_SLG_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'WOO_SLG_SOCIAL_DIR' ) ) {
	define( 'WOO_SLG_SOCIAL_DIR', WOO_SLG_DIR . '/includes/social' ); // social dir
}
if( !defined( 'WOO_SLG_SOCIAL_LIB_DIR' ) ) {
	define( 'WOO_SLG_SOCIAL_LIB_DIR', WOO_SLG_DIR . '/includes/social/libraries' ); // lib dir
}
if( !defined( 'WOO_SLG_IMG_URL' ) ) {
	define( 'WOO_SLG_IMG_URL', WOO_SLG_URL . 'includes/images' ); // image url
}
if( !defined( 'WOO_SLG_ADMIN' ) ) {
	define( 'WOO_SLG_ADMIN', WOO_SLG_DIR . '/includes/admin' ); // plugin admin dir
}
if( !defined( 'WOO_SLG_USER_PREFIX' ) ) {
	define( 'WOO_SLG_USER_PREFIX', 'woo_user_' ); // username prefix
}
if( !defined( 'WOO_SLG_USER_META_PREFIX' ) ) {
	define( 'WOO_SLG_USER_META_PREFIX', 'wooslg_' ); // username prefix
}
if( !defined( 'WOO_SLG_BASENAME') ) {
	define( 'WOO_SLG_BASENAME', basename( WOO_SLG_DIR ) );
}
if ( ! defined( 'WOO_SLG_PLUGIN_KEY' ) ) {
	define( 'WOO_SLG_PLUGIN_KEY', 'wooslg' );
}

// Required Wpweb updater functions file
if ( ! function_exists( 'wpweb_updater_install' ) ) {
	require_once( 'includes/wpweb-upd-functions.php' );
}

$upload_dir		= wp_upload_dir();
$upload_path	= isset( $upload_dir['basedir'] ) ? $upload_dir['basedir'].'/' : ABSPATH;
$upload_url		= isset( $upload_dir['baseurl'] ) ? $upload_dir['baseurl'] : site_url();

// woocommerce social login upload folder path
if( !defined( 'WOO_SLG_TMP_DIR' ) ) {
	define( 'WOO_SLG_TMP_DIR' , $upload_path . 'woocommerce_uploads/wooslg-tmp' );
}

global $woo_slg_options;

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'woo_slg_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_install() {

	global $wpdb, $woo_slg_options;

	//Get plugin version option
	$woo_slg_set_option	= get_option( 'woo_slg_set_option' );

	//get social order options
	$woo_social_order = get_option( 'woo_social_order' );

	if( empty( $woo_slg_set_option ) ) { //check plugin version option

		//get option for when plugin is activating first time
		woo_slg_default_settings();

		$woo_social_order = array( 'facebook', 'twitter', 'googleplus', 'linkedin', 'yahoo', 'foursquare', 'windowslive', 'vk' );

		update_option( 'woo_social_order', $woo_social_order );

		//update plugin version to option
		update_option( 'woo_slg_set_option', '1.0' );
	}

	//get social order options
	$woo_slg_set_option = get_option( 'woo_slg_set_option' );

	if( $woo_slg_set_option == '1.0' ) {

		//Update default behaviour for new user's username
		update_option( 'woo_slg_base_reg_username', '' );

		//update plugin version to option
		update_option('woo_slg_set_option','1.1');
	}

	//get social order options
	$woo_slg_set_option = get_option( 'woo_slg_set_option' );

	if( $woo_slg_set_option == '1.1' ) {

		//Instagram api added in api array
		$inst_array = array( 'instagram' );
		$woo_social_order = array_merge( $woo_social_order, $inst_array );
		update_option( 'woo_social_order', $woo_social_order );

		//Instagram options
		$instagram_options	= array( 
									'woo_slg_enable_instagram'		=> '',
									'woo_slg_inst_client_id'		=> '',
									'woo_slg_inst_client_secret'	=> '',
									'woo_slg_inst_icon_url'			=> WOO_SLG_IMG_URL . '/instagram.png',
									'woo_slg_enable_inst_avatar'	=> ''
								);

		foreach ( $instagram_options as $key => $value ) {
			update_option( $key, $value );
		}

		//update plugin version to option
		update_option( 'woo_slg_set_option', '1.2' );
	}

	//get social order options
	$woo_slg_set_option = get_option( 'woo_slg_set_option' );

	if( $woo_slg_set_option == '1.2' ) {

		//Custom icon link options
		$link_options	= array(
									'woo_slg_fb_link_icon_url'			=> WOO_SLG_IMG_URL . '/facebook-link.png',
									'woo_slg_gp_link_icon_url'			=> WOO_SLG_IMG_URL . '/googleplus-link.png',
									'woo_slg_li_link_icon_url'			=> WOO_SLG_IMG_URL . '/linkedin-link.png',
									'woo_slg_tw_link_icon_url'			=> WOO_SLG_IMG_URL . '/twitter-link.png',
									'woo_slg_yh_link_icon_url'			=> WOO_SLG_IMG_URL . '/yahoo-link.png',
									'woo_slg_fs_link_icon_url'			=> WOO_SLG_IMG_URL . '/foursquare-link.png',
									'woo_slg_wl_link_icon_url'			=> WOO_SLG_IMG_URL . '/windowslive-link.png',
									'woo_slg_vk_link_icon_url'			=> WOO_SLG_IMG_URL . '/vk-link.png',
									'woo_slg_inst_link_icon_url'		=> WOO_SLG_IMG_URL . '/instagram-link.png',
									'woo_slg_display_link_thank_you'	=> 'yes'
								);

		foreach ( $link_options as $key => $value ) {
			update_option( $key, $value );
		}

		//update plugin version to option
		update_option( 'woo_slg_set_option', '1.3' );
	}

	//get social order options
	$woo_slg_set_option = get_option( 'woo_slg_set_option' );
	
	if( $woo_slg_set_option == '1.3' ) {

		// Amazon and paypal api added in api array
		$authorize_array = array( 'amazon','paypal' );
		$woo_social_order = array_merge( $woo_social_order, $authorize_array );
		update_option( 'woo_social_order', $woo_social_order );

		// Amazon and paypal options
		$authorize_options	= array( 
									'woo_slg_enable_amazon'			=> '',
									'woo_slg_amazon_client_id'		=> '',
									'woo_slg_amazon_client_secret'	=> '',
									'woo_slg_amazon_icon_url'		=> WOO_SLG_IMG_URL . '/amazon.png',
									'woo_slg_amazon_link_icon_url'	=> WOO_SLG_IMG_URL . '/amazon-link.png',
									'woo_slg_enable_paypal'			=> '',
									'woo_slg_paypal_client_id'		=> '',
									'woo_slg_paypal_client_secret'	=> '',
									'woo_slg_paypal_icon_url'		=> WOO_SLG_IMG_URL . '/paypal.png',
									'woo_slg_paypal_link_icon_url'	=> WOO_SLG_IMG_URL . '/paypal-link.png',
									'woo_slg_paypal_environment'    => 'sandbox',
								);
		
		foreach ( $authorize_options as $key => $value ) {
			update_option( $key, $value );
		}

		//update plugin version to option
		update_option( 'woo_slg_set_option', '1.4' );
	}

	//get social order options
	$woo_slg_set_option = get_option( 'woo_slg_set_option' );

	if( $woo_slg_set_option == '1.4' ) {

		// Social Buttons options
		$Social_buttons_options	= array( 
									'woo_slg_fb_icon_text'			=> __( 'Sign in with Facebook', 'wooslg' ),
									'woo_slg_fb_link_icon_text'		=> __( 'Link your account to Facebook', 'wooslg' ),
									'woo_slg_gp_icon_text'			=> __( 'Sign in with Google+', 'wooslg' ),
									'woo_slg_gp_link_icon_text'		=> __( 'Link your account to Google+', 'wooslg' ),
									'woo_slg_li_icon_text'			=> __( 'Sign in with LinkedIn', 'wooslg' ),
									'woo_slg_li_link_icon_text'		=> __( 'Link your account to LinkedIn', 'wooslg' ),
									'woo_slg_tw_icon_text'			=> __( 'Sign in with Twitter', 'wooslg' ),
									'woo_slg_tw_link_icon_text'		=> __( 'Link your account to Twitter', 'wooslg' ),
									'woo_slg_yh_icon_text'			=> __( 'Sign in with Yahoo', 'wooslg' ),
									'woo_slg_yh_link_icon_text'		=> __( 'Link your account to Yahoo', 'wooslg' ),
									'woo_slg_fs_icon_text'			=> __( 'Sign in with Foursquare', 'wooslg' ),
									'woo_slg_fs_link_icon_text'		=> __( 'Link your account to Foursquare', 'wooslg' ),
									'woo_slg_wl_icon_text'			=> __( 'Sign in with Windows Live', 'wooslg' ),
									'woo_slg_wl_link_icon_text'		=> __( 'Link your account to Windows Live', 'wooslg' ),
									'woo_slg_vk_icon_text'			=> __( 'Sign in with VK.com', 'wooslg' ),
									'woo_slg_vk_link_icon_text'		=> __( 'Link your account to VK.com', 'wooslg' ),
									'woo_slg_inst_icon_text'		=> __( 'Sign in with Instagram', 'wooslg' ),
									'woo_slg_inst_link_icon_text'	=> __( 'Link your account to Instagram', 'wooslg' ),
									'woo_slg_amazon_icon_text'		=> __( 'Sign in with Amazon', 'wooslg' ),
									'woo_slg_amazon_link_icon_text'	=> __( 'Link your account to Amazon', 'wooslg' ),
									'woo_slg_paypal_icon_text'		=> __( 'Sign in with Paypal', 'wooslg' ),
									'woo_slg_paypal_link_icon_text'	=> __( 'Link your account to Paypal', 'wooslg' ),
								);
		
		foreach ( $Social_buttons_options as $key => $value ) {
			update_option( $key, $value );
		}
		
		//update plugin version to option
		update_option( 'woo_slg_set_option', '1.5' );
	}
	
	//get social order options
	$woo_slg_set_option = get_option( 'woo_slg_set_option' );
	
	if( $woo_slg_set_option == '1.5' ) {
		
		// Future code will be here.
	}
}

/**
 * Deafult Options
 * 
 * Default social login options
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_default_settings() {
	
	$options	= array( 
					'woo_slg_login_heading'				=> __( 'Prefer to Login with Social Media', 'wooslg' ),
					'woo_slg_enable_notification'		=> '',
					'woo_slg_redirect_url'				=> '',
					'woo_slg_enable_login_page'			=> '',
					'woo_slg_enable_expand_collapse'	=> 'collapse',
					'woo_slg_enable_facebook'			=> '',
					'woo_slg_fb_app_id'					=> '',
					'woo_slg_fb_app_secret'				=> '',
					'woo_slg_fb_language'				=> 'en_US',
					'woo_slg_fb_icon_url'				=> WOO_SLG_IMG_URL . '/facebook.png',
					'woo_slg_fb_link_icon_url'			=> WOO_SLG_IMG_URL . '/facebook-link.png',
					'woo_slg_enable_fb_avatar'			=> '',
					'woo_slg_enable_googleplus'			=> '',
					'woo_slg_gp_client_id'				=> '',
					'woo_slg_gp_client_secret'			=> '',
					'woo_slg_gp_icon_url'				=> WOO_SLG_IMG_URL . '/googleplus.png',
					'woo_slg_gp_link_icon_url'			=> WOO_SLG_IMG_URL . '/googleplus-link.png',
					'woo_slg_enable_gp_avatar'			=> '',
					'woo_slg_enable_linkedin'			=> '',
					'woo_slg_li_app_id'					=> '',
					'woo_slg_li_app_secret'				=> '',
					'woo_slg_li_icon_url'				=> WOO_SLG_IMG_URL . '/linkedin.png',
					'woo_slg_li_link_icon_url'			=> WOO_SLG_IMG_URL . '/linkedin-link.png',
					'woo_slg_enable_li_avatar'			=> '',
					'woo_slg_enable_twitter'			=> '',
					'woo_slg_tw_consumer_key'			=> '',
					'woo_slg_tw_consumer_secret'		=> '',
					'woo_slg_tw_icon_url'				=> WOO_SLG_IMG_URL . '/twitter.png',
					'woo_slg_tw_link_icon_url'			=> WOO_SLG_IMG_URL . '/twitter-link.png',
					'woo_slg_enable_tw_avatar'			=> '',
					'woo_slg_enable_yahoo'				=> '',
					'woo_slg_yh_consumer_key'			=> '',
					'woo_slg_yh_consumer_secret'		=> '',
					'woo_slg_yh_app_id'					=> '',
					'woo_slg_yh_icon_url'				=> WOO_SLG_IMG_URL . '/yahoo.png',
					'woo_slg_yh_link_icon_url'			=> WOO_SLG_IMG_URL . '/yahoo-link.png',
					'woo_slg_enable_yh_avatar'			=> '',
					'woo_slg_enable_foursquare'			=> '',
					'woo_slg_fs_client_id'				=> '',
					'woo_slg_fs_client_secret'			=> '',
					'woo_slg_fs_icon_url'				=> WOO_SLG_IMG_URL . '/foursquare.png',
					'woo_slg_fs_link_icon_url'			=> WOO_SLG_IMG_URL . '/foursquare-link.png',
					'woo_slg_enable_fs_avatar'			=> '',
					'woo_slg_enable_windowslive'		=> '',
					'woo_slg_wl_client_id'				=> '',
					'woo_slg_wl_client_secret'			=> '',
					'woo_slg_wl_icon_url'				=> WOO_SLG_IMG_URL . '/windowslive.png',
					'woo_slg_wl_link_icon_url'			=> WOO_SLG_IMG_URL . '/windowslive-link.png',
					'woo_slg_enable_vk'					=> '',
					'woo_slg_vk_app_id'					=> '',
					'woo_slg_vk_app_secret'				=> '',
					'woo_slg_vk_icon_url'				=> WOO_SLG_IMG_URL . '/vk.png',
					'woo_slg_vk_link_icon_url'			=> WOO_SLG_IMG_URL . '/vk-link.png',
					'woo_slg_enable_vk_avatar'			=> '',
					'woo_slg_display_link_thank_you'	=> 'yes',
					'woo_slg_enable_amazon'				=> '',
					'woo_slg_amazon_client_id'			=> '',
					'woo_slg_amazon_client_secret'		=> '',
					'woo_slg_amazon_icon_url'			=> WOO_SLG_IMG_URL . '/amazon.png',
					'woo_slg_amazon_link_icon_url'		=> WOO_SLG_IMG_URL . '/amazon-link.png',
					'woo_slg_enable_paypal'				=> '',
					'woo_slg_paypal_client_id'			=> '',
					'woo_slg_paypal_client_secret'		=> '',
					'woo_slg_paypal_icon_url'			=> WOO_SLG_IMG_URL . '/paypal.png',
					'woo_slg_paypal_link_icon_url'		=> WOO_SLG_IMG_URL . '/paypal-link.png',
					'woo_slg_paypal_environment'		=> 'sandbox',
					
					'woo_slg_social_btn_type'		=> '0',
					'woo_slg_fb_icon_text'			=> __( 'Sign in with Facebook', 'wooslg' ),
					'woo_slg_fb_link_icon_text'		=> __( 'Link your account to Facebook', 'wooslg' ),
					'woo_slg_gp_icon_text'			=> __( 'Sign in with Google+', 'wooslg' ),
					'woo_slg_gp_link_icon_text'		=> __( 'Link your account to Google+', 'wooslg' ),
					'woo_slg_li_icon_text'			=> __( 'Sign in with LinkedIn', 'wooslg' ),
					'woo_slg_li_link_icon_text'		=> __( 'Link your account to LinkedIn', 'wooslg' ),
					'woo_slg_tw_icon_text'			=> __( 'Sign in with Twitter', 'wooslg' ),
					'woo_slg_tw_link_icon_text'		=> __( 'Link your account to Twitter', 'wooslg' ),
					'woo_slg_yh_icon_text'			=> __( 'Sign in with Yahoo', 'wooslg' ),
					'woo_slg_yh_link_icon_text'		=> __( 'Link your account to Yahoo', 'wooslg' ),
					'woo_slg_fs_icon_text'			=> __( 'Sign in with Foursquare', 'wooslg' ),
					'woo_slg_fs_link_icon_text'		=> __( 'Link your account to Foursquare', 'wooslg' ),
					'woo_slg_wl_icon_text'			=> __( 'Sign in with Windows Live', 'wooslg' ),
					'woo_slg_wl_link_icon_text'		=> __( 'Link your account to Windows Live', 'wooslg' ),
					'woo_slg_vk_icon_text'			=> __( 'Sign in with VK.com', 'wooslg' ),
					'woo_slg_vk_link_icon_text'		=> __( 'Link your account to VK.com', 'wooslg' ),
					'woo_slg_inst_icon_text'		=> __( 'Sign in with Instagram', 'wooslg' ),
					'woo_slg_inst_link_icon_text'	=> __( 'Link your account to Instagram', 'wooslg' ),
					'woo_slg_amazon_icon_text'		=> __( 'Sign in with Amazon', 'wooslg' ),
					'woo_slg_amazon_link_icon_text'	=> __( 'Link your account to Amazon', 'wooslg' ),
					'woo_slg_paypal_icon_text'		=> __( 'Sign in with Paypal', 'wooslg' ),
					'woo_slg_paypal_link_icon_text'	=> __( 'Link your account to Paypal', 'wooslg' ),
				);

	foreach( $options as $key => $value ) {
		update_option( $key, $value );
	}
}

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package WooCommerce - Social Login
 * @since 1.2.6
 */
function woo_slg_load_text_domain() {
	
	// Set filter for plugin's languages directory
	$woo_slg_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$woo_slg_lang_dir	= apply_filters( 'woo_slg_languages_directory', $woo_slg_lang_dir );
	
	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'wooslg' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'wooslg', $locale );
	
	// Setup paths to current locale file
	$mofile_local	= $woo_slg_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . WOO_SLG_BASENAME . '/' . $mofile;
	
	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/woo-social-login folder
		load_textdomain( 'wooslg', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/woo-social-login/languages/ folder
		load_textdomain( 'wooslg', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'wooslg', false, $woo_slg_lang_dir );
	}
}

/**
 * Add plugin action links
 * 
 * Adds a Settings, Support and Docs link to the plugin list.
 * 
 * @package WooCommerce - Social Login
 * @since 1.2.2
 */
function woo_slg_add_plugin_links( $links ) {
	$plugin_links = array(
		'<a href="admin.php?page=wc-settings&tab=social-login">' . __( 'Settings', 'wooslg' ) . '</a>',
		'<a href="http://support.wpweb.co.in/">' . __( 'Support', 'wooslg' ) . '</a>',
		'<a href="http://wpweb.co.in/documents/woocommerce-social-login/">' . __( 'Docs', 'wooslg' ) . '</a>'
	);
	
	return array_merge( $plugin_links, $links );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woo_slg_add_plugin_links' );

// Add action to read plugin default option to Make it WPML Compatible
add_action( 'plugins_loaded', 'woo_slg_read_default_options', 999 );

/**
 * Re read all options to make it wpml compatible
 *
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */
function woo_slg_read_default_options() {
	
	// Re-read settings because read plugin default option to Make it WPML Compatible
	global $woo_slg_options;
	$woo_slg_options['woo_slg_login_heading'] = get_option( 'woo_slg_login_heading' );
}

//add action to load plugin
add_action( 'plugins_loaded', 'woo_slg_plugin_loaded' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_plugin_loaded() {
	
	//check WooCommerce is activated or not
	if( class_exists( 'Woocommerce' ) ) {
		
		// load first text domain.
		woo_slg_load_text_domain();
		
		/**
		 * Deactivation Hook
		 * 
		 * Register plugin deactivation hook.
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		register_deactivation_hook( __FILE__, 'woo_slg_uninstall' );
		
		/**
		 * Plugin Setup (On Deactivation)
		 * 
		 * Delete  plugin options.
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		function woo_slg_uninstall() {
			
			global $wpdb;
			
			// Getting delete option
			$woo_vou_delete_options = get_option( 'woo_slg_delete_options' );
			
			if( $woo_vou_delete_options == 'yes' ) {
				
				$options	= array(
								'woo_slg_set_option', 'woo_social_order', 'woo_slg_login_heading', 'woo_slg_enable_notification',
								'woo_slg_redirect_url', 'woo_slg_enable_login_page', 'woo_slg_enable_facebook', 'woo_slg_fb_app_id',
								'woo_slg_fb_app_secret', 'woo_slg_fb_language', 'woo_slg_fb_icon_url','woo_slg_fb_link_icon_url', 'woo_slg_enable_fb_avatar',
								'woo_slg_enable_googleplus', 'woo_slg_gp_client_id', 'woo_slg_gp_client_secret', 'woo_slg_gp_icon_url',
								'woo_slg_enable_gp_avatar', 'woo_slg_enable_linkedin', 'woo_slg_li_app_id', 'woo_slg_li_app_secret',
								'woo_slg_li_icon_url', 'woo_slg_enable_li_avatar', 'woo_slg_enable_twitter', 'woo_slg_tw_consumer_key',
								'woo_slg_tw_consumer_secret', 'woo_slg_tw_icon_url', 'woo_slg_enable_tw_avatar', 'woo_slg_enable_yahoo',
								'woo_slg_yh_consumer_key', 'woo_slg_yh_consumer_secret', 'woo_slg_yh_app_id', 'woo_slg_yh_icon_url',
								'woo_slg_enable_yh_avatar', 'woo_slg_enable_foursquare', 'woo_slg_fs_client_id', 'woo_slg_fs_client_secret',
								'woo_slg_fs_icon_url', 'woo_slg_enable_fs_avatar', 'woo_slg_enable_windowslive', 'woo_slg_wl_client_id',
								'woo_slg_wl_client_secret', 'woo_slg_wl_icon_url', 'woo_slg_enable_vk', 'woo_slg_vk_app_id',
								'woo_slg_vk_app_secret', 'woo_slg_vk_icon_url', 'woo_slg_enable_vk_avatar', 'woo_slg_enable_instagram',
								'woo_slg_inst_client_id', 'woo_slg_inst_client_secret',	'woo_slg_inst_icon_url', 'woo_slg_enable_inst_avatar',
								'woo_slg_delete_options','woo_slg_enable_expand_collapse', 'woo_slg_fb_link_icon_url', 'woo_slg_gp_link_icon_url',
								'woo_slg_li_link_icon_url', 'woo_slg_tw_link_icon_url', 'woo_slg_yh_link_icon_url', 'woo_slg_fs_link_icon_url',
								'woo_slg_wl_link_icon_url', 'woo_slg_vk_link_icon_url', 'woo_slg_inst_link_icon_url','woo_slg_display_link_thank_you',
								'woo_slg_enable_amazon', 'woo_slg_amazon_client_id', 'woo_slg_amazon_client_secret', 'woo_slg_amazon_icon_url',
								'woo_slg_amazon_link_icon_url', 'woo_slg_enable_paypal', 'woo_slg_paypal_client_id', 'woo_slg_paypal_client_secret', 'woo_slg_paypal_icon_url',
								'woo_slg_paypal_link_icon_url', 'woo_slg_paypal_environment'
							);

				//Get v1.4.1 options
				$options_v141	= array(
									'woo_slg_fb_icon_text', 'woo_slg_fb_link_icon_text', 'woo_slg_gp_icon_text', 'woo_slg_gp_link_icon_text', 'woo_slg_li_icon_text',
									'woo_slg_li_link_icon_text', 'woo_slg_tw_icon_text', 'woo_slg_tw_link_icon_text', 'woo_slg_yh_icon_text', 'woo_slg_yh_link_icon_text',
									'woo_slg_fs_icon_text', 'woo_slg_fs_link_icon_text', 'woo_slg_wl_icon_text', 'woo_slg_wl_link_icon_text', 'woo_slg_vk_icon_text',
									'woo_slg_vk_link_icon_text', 'woo_slg_inst_icon_text', 'woo_slg_inst_link_icon_text', 'woo_slg_amazon_icon_text',
									'woo_slg_amazon_link_icon_text', 'woo_slg_paypal_icon_text', 'woo_slg_paypal_link_icon_text', 'woo_slg_social_btn_type'
								);

				//Merge version 1.4.1 options key to old
				$options	= array_merge( $options, $options_v141 );

				//delete all options
				foreach ( $options as $key ) {
					delete_option( $key );
				}
			}
		}

		/**
		 * Start Session
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		function woo_slg_start_session() {

			if( !session_id() ) {
				session_start();
			}
		}

		//add action init for starting a session
		add_action( 'init', 'woo_slg_start_session');

		/**
		 * Includes Files
		 * 
		 * Includes some required files for plugin
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		function woo_slg_global_settings() {

			global $woo_slg_options;

			$woo_slg_options['woo_slg_login_heading']	    	= get_option( 'woo_slg_login_heading' );
			$woo_slg_options['woo_slg_enable_notification']		= get_option( 'woo_slg_enable_notification' );
			$woo_slg_options['woo_slg_redirect_url']	    	= get_option( 'woo_slg_redirect_url' );
			$woo_slg_options['woo_slg_enable_login_page']		= get_option( 'woo_slg_enable_login_page' );
			$woo_slg_options['woo_slg_enable_expand_collapse']	= get_option( 'woo_slg_enable_expand_collapse' );
			$woo_slg_options['woo_slg_enable_facebook']	    	= get_option( 'woo_slg_enable_facebook' );
			$woo_slg_options['woo_slg_fb_app_id']				= get_option( 'woo_slg_fb_app_id' );
			$woo_slg_options['woo_slg_fb_app_secret']			= get_option( 'woo_slg_fb_app_secret' );
			$woo_slg_options['woo_slg_fb_language']				= get_option( 'woo_slg_fb_language' );
			$woo_slg_options['woo_slg_fb_icon_url']				= get_option( 'woo_slg_fb_icon_url' );
			$woo_slg_options['woo_slg_fb_link_icon_url']		= get_option( 'woo_slg_fb_link_icon_url' );
			$woo_slg_options['woo_slg_enable_fb_avatar']		= get_option( 'woo_slg_enable_fb_avatar' );
			$woo_slg_options['woo_slg_enable_googleplus']		= get_option( 'woo_slg_enable_googleplus' );
			$woo_slg_options['woo_slg_gp_client_id']			= get_option( 'woo_slg_gp_client_id' );
			$woo_slg_options['woo_slg_gp_client_secret']		= get_option( 'woo_slg_gp_client_secret' );
			$woo_slg_options['woo_slg_gp_icon_url']				= get_option( 'woo_slg_gp_icon_url' );
			$woo_slg_options['woo_slg_gp_link_icon_url']		= get_option( 'woo_slg_gp_link_icon_url' );
			$woo_slg_options['woo_slg_enable_gp_avatar']		= get_option( 'woo_slg_enable_gp_avatar' );
			$woo_slg_options['woo_slg_enable_linkedin']			= get_option( 'woo_slg_enable_linkedin' );
			$woo_slg_options['woo_slg_li_app_id']				= get_option( 'woo_slg_li_app_id' );
			$woo_slg_options['woo_slg_li_app_secret']			= get_option( 'woo_slg_li_app_secret' );
			$woo_slg_options['woo_slg_li_icon_url']				= get_option( 'woo_slg_li_icon_url' );
			$woo_slg_options['woo_slg_li_link_icon_url']		= get_option( 'woo_slg_li_link_icon_url' );
			$woo_slg_options['woo_slg_enable_li_avatar']		= get_option( 'woo_slg_enable_li_avatar' );
			$woo_slg_options['woo_slg_enable_twitter']			= get_option( 'woo_slg_enable_twitter' );
			$woo_slg_options['woo_slg_tw_consumer_key']			= get_option( 'woo_slg_tw_consumer_key' );
			$woo_slg_options['woo_slg_tw_consumer_secret']		= get_option( 'woo_slg_tw_consumer_secret' );
			$woo_slg_options['woo_slg_tw_icon_url']				= get_option( 'woo_slg_tw_icon_url' );
			$woo_slg_options['woo_slg_tw_link_icon_url']		= get_option( 'woo_slg_tw_link_icon_url' );
			$woo_slg_options['woo_slg_enable_tw_avatar']		= get_option( 'woo_slg_enable_tw_avatar' );
			$woo_slg_options['woo_slg_enable_yahoo']			= get_option( 'woo_slg_enable_yahoo' );
			$woo_slg_options['woo_slg_yh_consumer_key']			= get_option( 'woo_slg_yh_consumer_key' );
			$woo_slg_options['woo_slg_yh_consumer_secret']		= get_option( 'woo_slg_yh_consumer_secret' );
			$woo_slg_options['woo_slg_yh_app_id']				= get_option( 'woo_slg_yh_app_id' );
			$woo_slg_options['woo_slg_yh_icon_url']				= get_option( 'woo_slg_yh_icon_url' );
			$woo_slg_options['woo_slg_yh_link_icon_url']		= get_option( 'woo_slg_yh_link_icon_url' );
			$woo_slg_options['woo_slg_enable_yh_avatar']		= get_option( 'woo_slg_enable_yh_avatar' );
			$woo_slg_options['woo_slg_enable_foursquare']		= get_option( 'woo_slg_enable_foursquare' );
			$woo_slg_options['woo_slg_fs_client_id']			= get_option( 'woo_slg_fs_client_id' );
			$woo_slg_options['woo_slg_fs_client_secret']		= get_option( 'woo_slg_fs_client_secret' );
			$woo_slg_options['woo_slg_fs_icon_url']				= get_option( 'woo_slg_fs_icon_url' );
			$woo_slg_options['woo_slg_fs_link_icon_url']		= get_option( 'woo_slg_fs_link_icon_url' );
			$woo_slg_options['woo_slg_enable_fs_avatar']		= get_option( 'woo_slg_enable_fs_avatar' );
			$woo_slg_options['woo_slg_enable_windowslive']		= get_option( 'woo_slg_enable_windowslive' );
			$woo_slg_options['woo_slg_wl_client_id']			= get_option( 'woo_slg_wl_client_id' );
			$woo_slg_options['woo_slg_wl_client_secret']		= get_option( 'woo_slg_wl_client_secret' );
			$woo_slg_options['woo_slg_wl_icon_url']				= get_option( 'woo_slg_wl_icon_url' );
			$woo_slg_options['woo_slg_wl_link_icon_url']		= get_option( 'woo_slg_wl_link_icon_url' );
			$woo_slg_options['woo_slg_enable_vk']				= get_option( 'woo_slg_enable_vk' );
			$woo_slg_options['woo_slg_vk_app_id']				= get_option( 'woo_slg_vk_app_id' );
			$woo_slg_options['woo_slg_vk_app_secret']			= get_option( 'woo_slg_vk_app_secret' );
			$woo_slg_options['woo_slg_vk_icon_url']				= get_option( 'woo_slg_vk_icon_url' );
			$woo_slg_options['woo_slg_vk_link_icon_url']		= get_option( 'woo_slg_vk_link_icon_url' );
			$woo_slg_options['woo_slg_enable_vk_avatar']		= get_option( 'woo_slg_enable_vk_avatar' );
			$woo_slg_options['woo_slg_enable_instagram']		= get_option( 'woo_slg_enable_instagram' );
			$woo_slg_options['woo_slg_inst_client_id']			= get_option( 'woo_slg_inst_client_id' );
			$woo_slg_options['woo_slg_inst_client_secret']		= get_option( 'woo_slg_inst_client_secret' );
			$woo_slg_options['woo_slg_inst_icon_url']			= get_option( 'woo_slg_inst_icon_url' );
			$woo_slg_options['woo_slg_inst_link_icon_url']		= get_option( 'woo_slg_inst_link_icon_url' );
			$woo_slg_options['woo_slg_enable_inst_avatar']		= get_option( 'woo_slg_enable_inst_avatar' );
			$woo_slg_options['woo_social_order']				= get_option( 'woo_social_order' );
			$woo_slg_options['woo_slg_base_reg_username']		= get_option( 'woo_slg_base_reg_username' );
			$woo_slg_options['woo_slg_display_link_thank_you']	= get_option( 'woo_slg_display_link_thank_you' );			
			$woo_slg_options['woo_slg_enable_amazon']			= get_option( 'woo_slg_enable_amazon' );
			$woo_slg_options['woo_slg_amazon_client_id']		= get_option( 'woo_slg_amazon_client_id' );
			$woo_slg_options['woo_slg_amazon_client_secret']	= get_option( 'woo_slg_amazon_client_secret' );
			$woo_slg_options['woo_slg_amazon_icon_url']			= get_option( 'woo_slg_amazon_icon_url' );
			$woo_slg_options['woo_slg_amazon_link_icon_url']	= get_option( 'woo_slg_amazon_link_icon_url' );						
			$woo_slg_options['woo_slg_enable_paypal']			= get_option( 'woo_slg_enable_paypal' );
			$woo_slg_options['woo_slg_paypal_client_id']		= get_option( 'woo_slg_paypal_client_id' );
			$woo_slg_options['woo_slg_paypal_client_secret']	= get_option( 'woo_slg_paypal_client_secret' );
			$woo_slg_options['woo_slg_paypal_icon_url']			= get_option( 'woo_slg_paypal_icon_url' );
			$woo_slg_options['woo_slg_paypal_link_icon_url']	= get_option( 'woo_slg_paypal_link_icon_url' );
			$woo_slg_options['woo_slg_paypal_environment']	    = get_option( 'woo_slg_paypal_environment' );

			$woo_slg_options['woo_slg_social_btn_type']	    	= get_option( 'woo_slg_social_btn_type' );
			$woo_slg_options['woo_slg_fb_icon_text']	    	= get_option( 'woo_slg_fb_icon_text' );
			$woo_slg_options['woo_slg_fb_link_icon_text']	    = get_option( 'woo_slg_fb_link_icon_text' );
			$woo_slg_options['woo_slg_gp_icon_text']	    	= get_option( 'woo_slg_gp_icon_text' );
			$woo_slg_options['woo_slg_gp_link_icon_text']	    = get_option( 'woo_slg_gp_link_icon_text' );
			$woo_slg_options['woo_slg_li_icon_text']	    	= get_option( 'woo_slg_li_icon_text' );
			$woo_slg_options['woo_slg_li_link_icon_text']	    = get_option( 'woo_slg_li_link_icon_text' );
			$woo_slg_options['woo_slg_tw_icon_text']	    	= get_option( 'woo_slg_tw_icon_text' );
			$woo_slg_options['woo_slg_tw_link_icon_text']	    = get_option( 'woo_slg_tw_link_icon_text' );
			$woo_slg_options['woo_slg_yh_icon_text']	    	= get_option( 'woo_slg_yh_icon_text' );
			$woo_slg_options['woo_slg_yh_link_icon_text']	    = get_option( 'woo_slg_yh_link_icon_text' );
			$woo_slg_options['woo_slg_fs_icon_text']	    	= get_option( 'woo_slg_fs_icon_text' );
			$woo_slg_options['woo_slg_fs_link_icon_text']	    = get_option( 'woo_slg_fs_link_icon_text' );
			$woo_slg_options['woo_slg_wl_icon_text']	    	= get_option( 'woo_slg_wl_icon_text' );
			$woo_slg_options['woo_slg_wl_link_icon_text']	    = get_option( 'woo_slg_wl_link_icon_text' );
			$woo_slg_options['woo_slg_vk_icon_text']	    	= get_option( 'woo_slg_vk_icon_text' );
			$woo_slg_options['woo_slg_vk_link_icon_text']	    = get_option( 'woo_slg_vk_link_icon_text' );
			$woo_slg_options['woo_slg_inst_icon_text']	    	= get_option( 'woo_slg_inst_icon_text' );
			$woo_slg_options['woo_slg_inst_link_icon_text']	    = get_option( 'woo_slg_inst_link_icon_text' );
			$woo_slg_options['woo_slg_amazon_icon_text']	    = get_option( 'woo_slg_amazon_icon_text' );
			$woo_slg_options['woo_slg_amazon_link_icon_text']	= get_option( 'woo_slg_amazon_link_icon_text' );
			$woo_slg_options['woo_slg_paypal_icon_text']	    = get_option( 'woo_slg_paypal_icon_text' );
			$woo_slg_options['woo_slg_paypal_link_icon_text']	= get_option( 'woo_slg_paypal_link_icon_text' );
			
			return apply_filters( 'woo_slg_global_settings', $woo_slg_options );
		}
		
		//Global variables
		global 	$woo_slg_model, $woo_slg_scripts, $woo_slg_render,
				$woo_slg_shortcodes, $woo_slg_public, $woo_slg_admin,
				$woo_slg_admin_settings_tabs, $woo_slg_options,$woo_slg_opath;
		
		//Global Options
		$woo_slg_options	= woo_slg_global_settings();
		
		// loads the Misc Functions file
		require_once ( WOO_SLG_DIR . '/includes/woo-slg-misc-functions.php' );
		woo_slg_initialize();
		
		//social class loads
		require_once( WOO_SLG_SOCIAL_DIR . '/woo-slg-social.php' );
		
		//Model Class for generic functions
		require_once( WOO_SLG_DIR . '/includes/class-woo-slg-model.php' );
		$woo_slg_model = new WOO_Slg_Model();
		
		//Scripts Class for scripts / styles
		require_once( WOO_SLG_DIR . '/includes/class-woo-slg-scripts.php' );
		$woo_slg_scripts = new WOO_Slg_Scripts();
		$woo_slg_scripts->add_hooks();
		
		//Renderer Class for HTML
		require_once( WOO_SLG_DIR . '/includes/class-woo-slg-renderer.php' );
		$woo_slg_render = new WOO_Slg_Renderer();
		
		//Shortcodes class for handling shortcodes
		require_once( WOO_SLG_DIR . '/includes/class-woo-slg-shortcodes.php' );
		$woo_slg_shortcodes = new WOO_Slg_Shortcodes();
		$woo_slg_shortcodes->add_hooks();
		
		//Public Class for public functionlities
		require_once( WOO_SLG_DIR . '/includes/class-woo-slg-public.php' );
		$woo_slg_public = new WOO_Slg_Public();
		$woo_slg_public->add_hooks();
		
		//Setting Tab Class for handling tab content
		require_once( WOO_SLG_ADMIN . '/class-woo-slg-admin-settings-tabs.php' );
		$woo_slg_admin_settings_tabs = new WOO_Slg_Settings_Tabs();
		$woo_slg_admin_settings_tabs->add_hooks();
		
		//Admin Pages Class for admin site
		require_once( WOO_SLG_ADMIN . '/class-woo-slg-admin.php' );
		$woo_slg_admin = new WOO_Slg_Admin();
		$woo_slg_admin->add_hooks();
		
		//Register Widget
		require_once( WOO_SLG_DIR . '/includes/widgets/class-woo-slg-login-buttons.php' );
		
		//Loads the Templates Functions file
		require_once ( WOO_SLG_DIR . '/includes/woo-slg-template-functions.php' );
		
		//Loads the Template Hook File
		require_once( WOO_SLG_DIR . '/includes/woo-slg-template-hooks.php' );
	}
}

//check Social Updater is activated
if( class_exists( 'Wpweb_Upd_Admin' ) ) {
	
	// Plugin updates
	wpweb_queue_update( plugin_basename( __FILE__ ), WOO_SLG_PLUGIN_KEY );
	
	/**
	 * Include Auto Updating Files
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	require_once( WPWEB_UPD_DIR . '/updates/class-plugin-update-checker.php' ); // auto updating
	
	$WpwebWooslgUpdateChecker = new WpwebPluginUpdateChecker (
		'http://wpweb.co.in/Updates/WOOSLG/license-info.php',
		__FILE__,
		WOO_SLG_PLUGIN_KEY
	);
	
	/**
	 * Auto Update
	 * 
	 * Get the license key and add it to the update checker.
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	function woo_slg_add_secret_key( $query ) {
		
		$plugin_key	= WOO_SLG_PLUGIN_KEY;
		
		$query['lickey'] = wpweb_get_plugin_purchase_code( $plugin_key );
		return $query;
	}
	
	$WpwebWooslgUpdateChecker->addQueryArgFilter( 'woo_slg_add_secret_key' );
} // end check WPWeb Updater is activated