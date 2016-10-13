<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Misc Functions
 * 
 * All misc functions handles to
 * different functions
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

/**
 * All Social Deals Networks
 * 
 * Handles to return all social networks
 * names
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_social_networks() {

	$socialnetworks = array(
								'facebook'		=>	__( 'Facebook', 'wooslg' ),
								'twitter'		=>	__( 'Twitter', 'wooslg' ),
								'googleplus'	=>	__( 'Google+', 'wooslg' ),
								'linkedin'		=>	__( 'LinkedIn', 'wooslg' ),
								'yahoo'			=>	__( 'Yahoo', 'wooslg' ),
								'foursquare'	=>	__( 'Foursquare', 'wooslg' ),
								'windowslive'	=>	__( 'Windows Live', 'wooslg' ),
								'vk'			=>	__( 'VK', 'wooslg' ),
								'instagram'		=>	__( 'Instagram', 'wooslg' ),
								'amazon'		=>	__( 'Amazon', 'wooslg' ),
								'paypal'		=>	__( 'Paypal', 'wooslg' ),
							);

	return apply_filters( 'woo_slg_social_networks', $socialnetworks );
}

/**
 * Get Social Network Sorted List
 * as per saved in options
 * 
 * Handles to return social networks sorted
 * array to list in page
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_get_sorted_social_network() {

	global $woo_slg_options;

	$woo_social_order = $woo_slg_options['woo_social_order'];

	$socials = woo_slg_social_networks();

	if( !isset( $woo_social_order ) || empty( $woo_social_order ) ) {
		return $socials;
	}
	
	$sorted_socials = $woo_social_order;
	$return = array();
	for( $i = 0; $i < count( $socials ); $i++ ) {
		$return[$sorted_socials[$i]] = $socials[$sorted_socials[$i]];
	}

	return apply_filters( 'woo_slg_sorted_social_networks', $return );
}

/**
 * Initialize some needed variables
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_initialize() {

	global $woo_slg_options;				

	//facebook variable initialization
	$fb_app_id = isset( $woo_slg_options['woo_slg_fb_app_id'] ) ? $woo_slg_options['woo_slg_fb_app_id'] : '';
	$fb_app_secret = isset( $woo_slg_options['woo_slg_fb_app_secret'] ) ? $woo_slg_options['woo_slg_fb_app_secret'] : '';

	if( !defined( 'WOO_SLG_FB_APP_ID' ) ) {
		define( 'WOO_SLG_FB_APP_ID', $fb_app_id );
	}
	if( !defined( 'WOO_SLG_FB_APP_SECRET' ) ) {
		define( 'WOO_SLG_FB_APP_SECRET', $fb_app_secret );
	}

	//google+ variable initialization
	$gp_client_id = isset( $woo_slg_options['woo_slg_gp_client_id'] ) ? $woo_slg_options['woo_slg_gp_client_id'] : '';
	$gp_client_secret = isset( $woo_slg_options['woo_slg_gp_client_secret'] ) ? $woo_slg_options['woo_slg_gp_client_secret'] : '';

	if( !defined( 'WOO_SLG_GP_CLIENT_ID' ) ) {
		define( 'WOO_SLG_GP_CLIENT_ID', $gp_client_id );
	}
	if( !defined( 'WOO_SLG_GP_CLIENT_SECRET' ) ) {
		define( 'WOO_SLG_GP_CLIENT_SECRET', $gp_client_secret );
	}
	if( !defined( 'WOO_SLG_GP_REDIRECT_URL' ) ) {
		$googleurl = add_query_arg( 'wooslg', 'google', site_url() );
		define( 'WOO_SLG_GP_REDIRECT_URL', $googleurl );
	}

	//linkedin variable initialization
	$li_app_id = isset( $woo_slg_options['woo_slg_li_app_id'] ) ? $woo_slg_options['woo_slg_li_app_id'] : '';
	$li_app_secret = isset( $woo_slg_options['woo_slg_li_app_secret'] ) ? $woo_slg_options['woo_slg_li_app_secret'] : '';

	if( !defined( 'WOO_SLG_LI_APP_ID' ) ) {
		define( 'WOO_SLG_LI_APP_ID', $li_app_id );
	}
	if( !defined( 'WOO_SLG_LI_APP_SECRET' ) ) {
		define( 'WOO_SLG_LI_APP_SECRET', $li_app_secret );
	}

	// For LinkedIn Port http / https
	if( !defined( 'LINKEDIN_PORT_HTTP' ) ) { //http port value
	 	define( 'LINKEDIN_PORT_HTTP', '80' );
	}
	if( !defined( 'LINKEDIN_PORT_HTTP_SSL' ) ) { //ssl port value
	  	define( 'LINKEDIN_PORT_HTTP_SSL', '443' );
	}
	if( !defined( 'WOO_SLG_LI_REDIRECT_URL' ) ) {
		$linkedinurl = add_query_arg( 'wooslg', 'linkedin', trailingslashit( site_url() ) );
		define( 'WOO_SLG_LI_REDIRECT_URL', $linkedinurl );
	}

	//twitter variable initialization
	$tw_consumer_key = isset( $woo_slg_options['woo_slg_tw_consumer_key'] ) ? $woo_slg_options['woo_slg_tw_consumer_key'] : '';
	$tw_consumer_secrets = isset( $woo_slg_options['woo_slg_tw_consumer_secret'] ) ? $woo_slg_options['woo_slg_tw_consumer_secret'] : '';

	if( !defined( 'WOO_SLG_TW_CONSUMER_KEY' ) ) {
		define( 'WOO_SLG_TW_CONSUMER_KEY', $tw_consumer_key );
	}
	if( !defined( 'WOO_SLG_TW_CONSUMER_SECRET' ) ) {
		define( 'WOO_SLG_TW_CONSUMER_SECRET', $tw_consumer_secrets );
	}

	//yahoo variable initialization
	$yh_consumer_key = isset( $woo_slg_options['woo_slg_yh_consumer_key'] ) ? $woo_slg_options['woo_slg_yh_consumer_key'] : '';
	$yh_consumer_secret = isset( $woo_slg_options['woo_slg_yh_consumer_secret'] ) ? $woo_slg_options['woo_slg_yh_consumer_secret'] : '';
	$yh_app_id = isset( $woo_slg_options['woo_slg_yh_app_id'] ) ? $woo_slg_options['woo_slg_yh_app_id'] : '';

	if( !defined( 'WOO_SLG_YH_CONSUMER_KEY' ) ) {
		define( 'WOO_SLG_YH_CONSUMER_KEY', $yh_consumer_key );
	}
	if( !defined( 'WOO_SLG_YH_CONSUMER_SECRET' ) ) {
		define( 'WOO_SLG_YH_CONSUMER_SECRET', $yh_consumer_secret );
	}
	if( !defined( 'WOO_SLG_YH_APP_ID' ) ) {
		define( 'WOO_SLG_YH_APP_ID', $yh_app_id );
	}
	if( !defined( 'WOO_SLG_YH_REDIRECT_URL' ) ) {
		$yahoourl = add_query_arg( 'wooslg', 'yahoo', site_url() );
		define( 'WOO_SLG_YH_REDIRECT_URL', $yahoourl );
	}

	//foursquare variable initialization
	$fs_client_id = isset( $woo_slg_options['woo_slg_fs_client_id'] ) ? $woo_slg_options['woo_slg_fs_client_id'] : '';
	$fs_client_secrets = isset( $woo_slg_options['woo_slg_fs_client_secret'] ) ? $woo_slg_options['woo_slg_fs_client_secret'] : '';

	if( !defined( 'WOO_SLG_FS_CLIENT_ID' ) ) {
		define( 'WOO_SLG_FS_CLIENT_ID', $fs_client_id );
	}
	if( !defined( 'WOO_SLG_FS_CLIENT_SECRET' ) ) {
		define( 'WOO_SLG_FS_CLIENT_SECRET', $fs_client_secrets );
	}
	if( !defined( 'WOO_SLG_FS_REDIRECT_URL' ) ) {
		$fsredirecturl = add_query_arg( 'wooslg', 'foursquare', site_url() );
		define( 'WOO_SLG_FS_REDIRECT_URL', $fsredirecturl );
	}

	//windows live variable initialization
	$wl_client_id = isset( $woo_slg_options['woo_slg_wl_client_id'] ) ? $woo_slg_options['woo_slg_wl_client_id'] : '';
	$wl_client_secrets = isset( $woo_slg_options['woo_slg_wl_client_secret'] ) ? $woo_slg_options['woo_slg_wl_client_secret'] : '';

	if( !defined( 'WOO_SLG_WL_CLIENT_ID' ) ) {
		define( 'WOO_SLG_WL_CLIENT_ID', $wl_client_id );
	}
	if( !defined( 'WOO_SLG_WL_CLIENT_SECRET' ) ) {
		define( 'WOO_SLG_WL_CLIENT_SECRET', $wl_client_secrets );
	}
	if( !defined( 'WOO_SLG_WL_REDIRECT_URL' ) ) {
		$wlredirecturl = add_query_arg( 'wooslg', 'windowslive', site_url() );
		define( 'WOO_SLG_WL_REDIRECT_URL', $wlredirecturl );
	}

	//vk variable initialization
	$vk_client_id = isset( $woo_slg_options['woo_slg_vk_app_id'] ) ? $woo_slg_options['woo_slg_vk_app_id'] : '';
	$vk_client_secrets = isset( $woo_slg_options['woo_slg_vk_app_secret'] ) ? $woo_slg_options['woo_slg_vk_app_secret'] : '';

	if( !defined( 'WOO_SLG_VK_APP_ID' ) ) {
		define( 'WOO_SLG_VK_APP_ID', $vk_client_id );
	}
	if( !defined( 'WOO_SLG_VK_APP_SECRET' ) ) {
		define( 'WOO_SLG_VK_APP_SECRET', $vk_client_secrets );
	}
	if( !defined( 'WOO_SLG_VK_REDIRECT_URL' ) ) {
		$vkredirecturl = add_query_arg( 'wooslg', 'vk', site_url() );
		define( 'WOO_SLG_VK_REDIRECT_URL', $vkredirecturl );
	}

	if( !defined( 'WOO_SLG_VK_LINK' ) ) {       //  define vk variable for link
		$vk_link = 'https://vk.com';
		define( 'WOO_SLG_VK_LINK', $vk_link );
	}

	//Instagram variable initialization
	$inst_client_id = isset( $woo_slg_options['woo_slg_inst_client_id'] ) ? $woo_slg_options['woo_slg_inst_client_id'] : '';
	$inst_client_secrets = isset( $woo_slg_options['woo_slg_inst_client_secret'] ) ? $woo_slg_options['woo_slg_inst_client_secret'] : '';

	if( !defined( 'WOO_SLG_INST_CLIENT_ID' ) ) {
		define( 'WOO_SLG_INST_CLIENT_ID', $inst_client_id );
	}
	if( !defined( 'WOO_SLG_INST_CLIENT_SECRET' ) ) {
		define( 'WOO_SLG_INST_CLIENT_SECRET', $inst_client_secrets );
	}
	if( !defined( 'WOO_SLG_INST_REDIRECT_URL' ) ) {
		$instredirecturl = add_query_arg( 'wooslg', 'instagram', site_url() );
		define( 'WOO_SLG_INST_REDIRECT_URL', $instredirecturl );
	}
	
	//Amazon variable initialization
	$amazon_client_id = isset( $woo_slg_options['woo_slg_amazon_client_id'] ) ? $woo_slg_options['woo_slg_amazon_client_id'] : '';
	$amazon_client_secrets = isset( $woo_slg_options['woo_slg_amazon_client_secret'] ) ? $woo_slg_options['woo_slg_amazon_client_secret'] : '';
	if( !defined( 'WOO_SLG_AMAZON_CLIENT_ID' ) ) {
		define( 'WOO_SLG_AMAZON_CLIENT_ID', $amazon_client_id );
	}
	if( !defined( 'WOO_SLG_AMAZON_CLIENT_SECRET' ) ) {
		define( 'WOO_SLG_AMAZON_CLIENT_SECRET', $amazon_client_secrets );
	}
	
	if( !defined( 'WOO_SLG_AMAZON_REDIRECT_URL' ) ) {
		$amazonredirecturl = add_query_arg( 'wooslg', 'amazon', site_url() );
		define( 'WOO_SLG_AMAZON_REDIRECT_URL', $amazonredirecturl );
	}
	
	//Payapl variable initialization
	$paypal_client_id = isset( $woo_slg_options['woo_slg_paypal_client_id'] ) ? $woo_slg_options['woo_slg_paypal_client_id'] : '';
	$paypal_client_secrets = isset( $woo_slg_options['woo_slg_paypal_client_secret'] ) ? $woo_slg_options['woo_slg_paypal_client_secret'] : '';
	$paypal_environment = isset( $woo_slg_options['woo_slg_paypal_environment'] ) ? $woo_slg_options['woo_slg_paypal_environment'] : 'sandbox';
	
	if( !defined( 'WOO_SLG_PAYPAL_CLIENT_ID' ) ) {
		define( 'WOO_SLG_PAYPAL_CLIENT_ID', $paypal_client_id );
	}
	
	if( !defined( 'WOO_SLG_PAYPAL_CLIENT_SECRET' ) ) {
		define( 'WOO_SLG_PAYPAL_CLIENT_SECRET', $paypal_client_secrets );
	}
	
	if( !defined( 'WOO_SLG_PAYPAL_REDIRECT_URL' ) ) {
		$paypalredirecturl = add_query_arg( 'wooslg', 'paypal', site_url() );
		define( 'WOO_SLG_PAYPAL_REDIRECT_URL', $paypalredirecturl );
	}
	
	if( !defined( 'WOO_SLG_PAYPAL_ENVIRONMENT' ) ) {
		define( 'WOO_SLG_PAYPAL_ENVIRONMENT', $paypal_environment );
	}
	
}

/**
 * Checkout Page URL
 * 
 * Handles to return checkout page url
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_send_on_checkout_page( $queryarg = array() ) {

	global $woo_slg_options;

	$sendcheckout = get_permalink( $woo_slg_options['purchase_page'] );

	$sendcheckouturl = add_query_arg( $queryarg, $sendcheckout );

	wp_redirect( apply_filters( 'woo_slg_checkout_page_redirect', $sendcheckouturl, $queryarg ) );
	exit;
}

/**
 * Check Any One Social Media
 * Login is enable or not
 * 
 * Handles to Check any one social 
 * media login is enable or not
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_check_social_enable() {

	global $woo_slg_options;

	$return = false;

	//check if any social is activated or not
	if( ( $woo_slg_options['woo_slg_enable_facebook'] == "yes" ) || ( $woo_slg_options['woo_slg_enable_googleplus'] == "yes" ) || 
		( $woo_slg_options['woo_slg_enable_linkedin'] == "yes" ) || ( $woo_slg_options['woo_slg_enable_twitter'] == "yes" ) || 
		( $woo_slg_options['woo_slg_enable_yahoo'] == "yes" ) 	  || ( $woo_slg_options['woo_slg_enable_windowslive'] == "yes" ) || 
		( $woo_slg_options['woo_slg_enable_vk'] == "yes" ) || ( $woo_slg_options['woo_slg_enable_instagram'] == "yes" ) || ( $woo_slg_options['woo_slg_enable_amazon'] == "yes" )|| ( $woo_slg_options['woo_slg_enable_paypal'] == "yes" ) )  {

		$return = true;
	}

	return apply_filters( 'woo_slg_check_social_enable', $return );
}

/**
 * Current Page URL
 * 
 * @package  WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_get_current_page_url() {

	$curent_page_url = remove_query_arg( array( 'oauth_token', 'oauth_verifier' ), woo_get_current_page_url() );
	return $curent_page_url;
}

/**
 * Current Page URL
 * 
 * @package  WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_get_current_page_url() {

	global $post;

	if ( is_front_page() ) :
		$page_url = home_url();
	else :
		$page_url = 'http';

	if ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" )
		$page_url .= "s";

	$page_url .= "://";

	if ( $_SERVER["SERVER_PORT"] != "80" )
		$page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else
		$page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	endif;

	return apply_filters( 'woo_get_current_page_url', esc_url( $page_url ) );
}

/**
 * Social link buttons
 * 
 * @package  WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_link_buttons( $redirect_url = '' ) {
	
	global $woo_slg_options;
	
	$can_show_container = woo_slg_can_show_all_social_link_container();
	
	$link_button_html	= '';
	
	if( $can_show_container ) { // can show container
		
		// get redirect url from settings
		$link_redirect_url = isset( $woo_slg_options['woo_slg_redirect_url'] ) ? $woo_slg_options['woo_slg_redirect_url'] : '';
		$link_redirect_url = !empty( $redirect_url ) ? $redirect_url : $link_redirect_url; // check redirect url first from shortcode or if checkout page then use cuurent page is redirect url
		
		ob_start(); ?>
		<p><?php echo __( 'You can link your account to the following providers:', 'wooslg' );?></p>
		<div class="woo-slg-social-container woo-slg-social-wrap woo-slg-social-container-checkout woo-social-link-buttons">
			<input type="hidden" class="woo-slg-redirect-url" id="woo_slg_redirect_url" value="<?php echo $link_redirect_url;?>" />
			<!-- Display buttons which are not linked--><?php 
			
			do_action ( 'woo_slg_checkout_social_login_link' );?>
			<div class="woo-slg-login-error"></div>
		</div><?php
		
		$link_button_html .= ob_get_clean();
	}
	
	echo apply_filters( 'woo_slg_link_buttons', $link_button_html );
	wp_enqueue_script( 'woo-slg-public-script' );

}

/**
 * Display Or Not On Thankyou Page
 * 
 * Handles to check wether it display on thankyou page or not
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */
function woo_slg_link_display_on_thankyou_page() {

	global $woo_slg_options;

	$enable	= false;

	$link_on_thankyou_page = isset( $woo_slg_options['woo_slg_display_link_thank_you'] ) ? $woo_slg_options['woo_slg_display_link_thank_you'] : '';

	if( $link_on_thankyou_page == 'yes' ) {
		$enable = true;
	}

	return apply_filters( 'woo_slg_link_display_on_thankyou_page', $enable );
}

/**
 * Display Link Buttons On MyAccount
 * 
 * Handles to display link buttons on my account page
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */
function woo_slg_login_display_on_myaccount_page() {

	global $woo_slg_options;

	$enable	= false;
	$login_on_myaccount_page = isset( $woo_slg_options['woo_slg_enable_login_page'] ) ? $woo_slg_options['woo_slg_enable_login_page'] : '';

	if( $login_on_myaccount_page == 'yes' ) {
		$enable = true;
	}

	return apply_filters( 'woo_slg_login_display_on_myaccount_page', $enable );
}

/**
 * Get Woocommerce Screen ID
 * 
 * Handles to get woocommerce screen id
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */
function woo_slg_get_wc_screen_id() {

	$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
	return apply_filters( 'woo_slg_get_wc_screen_id', $wc_screen_id );
}

/**
 * Can Show Social Link
 * 
 * Handles to check this social link can show or not
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */
function woo_slg_can_show_social_link( $social = '' ) {
	
	global $current_user;
	
	$display = false;
	
	//user id not found
	$user_id		= isset( $current_user->ID ) ? $current_user->ID : '';
	
	if( !empty( $user_id ) ) {
		
		//get primary social api
		$primary_social	= get_user_meta( $user_id, 'woo_slg_social_user_connect_via', true );
		
		//get currunt social api meta
		$social_profile = get_user_meta( $user_id, 'woo_slg_social_' . $social . '_data', true );
		
		// check  current provider is linked or not
		if ( !$social_profile && $primary_social != $social ) {
			
			$display = true;
		}
	}
	
	return apply_filters( 'woo_slg_can_display_social_link', $display, $social );
}

/**
 * Can Show Social Link Container
 * 
 * Handles to check this social link can show or not
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */
function woo_slg_can_show_all_social_link_container() {
	
	global $current_user, $woo_slg_options;
	
	$display = false;
	
	//user id not found
	$user_id		= isset( $current_user->ID ) ? $current_user->ID : '';
	
	if( !empty( $user_id ) ) { // if user is not empty
		
		//get all social api in order
		$woo_social_order = get_option( 'woo_social_order' );
		
		if( !empty( $woo_social_order ) ) {
			
			//profile already linked as primary account
			$primary_social		= get_user_meta( $user_id, 'woo_slg_social_user_connect_via', true );
			
			foreach ( $woo_social_order as $social ) {
				
				//profile already linked as secondary account
				$social_profile = get_user_meta( $user_id, 'woo_slg_social_' . $social . '_data', true );
				
				//if enable social account
				$enable_social	= ( $woo_slg_options['woo_slg_enable_' . $social ] == "yes" ) ? true : false;
				
				if ( !$social_profile && $primary_social != $social && $enable_social ) {
					
					$display = true;
					break;
				}
			}
		}
	}
	
	return apply_filters( 'woo_slg_can_show_all_social_link_container', $display );
}

/**
 * Update Last Login Social Account
 * 
 * Handles to update last login social account
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */
function woo_slg_update_social_last_login_timestamp( $user_id, $social_type ) {

	if( !empty( $user_id ) && !empty( $social_type ) ) { // if user id and social type is not empty

		//get primary account
		$primary_social	= get_user_meta( $user_id, 'woo_slg_social_user_connect_via', true );

		$timestamp		= current_time( 'timestamp' );
		$timestamp_gmt	= time();

		if( $primary_social == $social_type ) { // if $social_type is primary account

			update_user_meta( $user_id, 'woo_slg_social_login_timestamp', $timestamp );
			update_user_meta( $user_id, 'woo_slg_social_login_timestamp_gmt', $timestamp_gmt );

		} else { // If $social_type is secondary account

			update_user_meta( $user_id, 'woo_slg_social_' . $social_type . '_login_timestamp', $timestamp );
			update_user_meta( $user_id, 'woo_slg_social_' . $social_type . '_login_timestamp_gmt', $timestamp_gmt );

		}
	}
}

/**
 * Get Last Login Social Account
 * 
 * Handles to get last login social account
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */
function woo_slg_get_social_last_login_timestamp( $user_id, $social_type, $is_gmt = false ) {

	$social_login_timestamp	= array( 'timestamp' => '', 'timestamp_gmt' => '' );

	if( !empty( $user_id ) && !empty( $social_type ) ) { // if user id and social type is not empty

		//get primary account
		$primary_social	= get_user_meta( $user_id, 'woo_slg_social_user_connect_via', true );

		if( $primary_social == $social_type ) { // if $social_type is primary account

			$social_login_timestamp['timestamp']	= get_user_meta( $user_id, 'woo_slg_social_login_timestamp', true );
			$social_login_timestamp['timestamp_gmt']= get_user_meta( $user_id, 'woo_slg_social_login_timestamp_gmt', true );

		} else { // If $social_type is secondary account

			$social_login_timestamp['timestamp']	= get_user_meta( $user_id, 'woo_slg_social_' . $social_type . '_login_timestamp', true );
			$social_login_timestamp['timestamp_gmt']= get_user_meta( $user_id, 'woo_slg_social_' . $social_type . '_login_timestamp_gmt', true );

		}
	}

	$login_timestamp	= ( $is_gmt ) ? $social_login_timestamp['timestamp_gmt'] : $social_login_timestamp['timestamp'];

	return apply_filters( 'woo_slg_get_social_last_login_timestamp', $login_timestamp, $user_id, $social_type, $is_gmt );
}

/**
 * Social Login Messages
 * 
 * Handles to change social login mesages
 * and links displayed at front side
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */
function woo_slg_messages() {
	
	return apply_filters( 'woo_slg_messages', array(
								'connected_link_heading'	=> __( 'Your account is connected to the following social login providers.','wooslg' ),
								'no_social_connected'		=> __( 'You have no social login profiles connected.','wooslg' ),
								'add_more_link'				=> __( 'Add More...', 'wooslg' ),
								'connect_now_link'			=> __( 'Connect one now', 'wooslg' ),
								'account_unlinked_notice'	=> __( '%s account was successfully unlinked from your account.', 'wooslg' ),
								'already_linked_error'		=> __( 'This account is already linked with another account.', 'wooslg' ),
								'account_exist_error'		=> __( 'This account is already exist', 'wooslg' ),
								'fberrormsg'				=> __( 'Please enter Facebook API Key & Secret in settings page.', 'wooslg' ),
								'gperrormsg'				=> __( 'Please enter Google+ Client ID & Secret in settings page.', 'wooslg' ),
								'lierrormsg'				=> __( 'Please enter LinkedIn API Key & Secret in settings page.', 'wooslg' ),
								'twerrormsg'				=> __( 'Please enter Twitter Consumer Key & Secret in settings page.', 'wooslg' ),
								'yherrormsg'				=> __( 'Please enter Yahoo API Consumer Key, Secret & App Id in settings page.', 'wooslg' ),
								'fserrormsg'				=> __( 'Please enter Foursquare API Client ID & Secret in settings page.', 'wooslg' ),
								'wlerrormsg'				=> __( 'Please enter Windows Live API Client ID & Secret in settings page.', 'wooslg' ),
								'vkerrormsg'				=> __( 'Please enter VK API Client ID & Secret in settings page.', 'wooslg' ),
								'insterrormsg'				=> __( 'Please enter Instagram API Client ID & Secret in settings page.', 'wooslg' ),
								'amazonerrormsg'			=> __( 'Please enter Amazon API Client ID & Secret in settings page.', 'wooslg' ),
								'paypalerrormsg'			=> __( 'Please enter Paypal API Client ID & Secret in settings page.', 'wooslg' ),
							));
}