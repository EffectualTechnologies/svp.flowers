<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 * 
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
class WOO_Slg_Scripts{

	public function __construct() {

	}

	/**
	 * Enqueue Styles for backend on needed page
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_admin_styles( $hook_suffix ) {

		//get woocommerce screen id
		$wc_screen_id = woo_slg_get_wc_screen_id();

		$pages_hook_suffix = array( 'post-new.php', 'post.php', $wc_screen_id.'_page_woo-social-login' );

		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {

			wp_register_style( 'woo-slg-admin-styles', WOO_SLG_URL . 'includes/css/style-admin.css', array(), WOO_SLG_VERSION );
			wp_enqueue_style( 'woo-slg-admin-styles' );
		}
	}

	/**
	 * Enqueue Scripts for backend on needed page
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_admin_scripts( $hook_suffix ) {

		global $wp_version;
		$newui = $wp_version >= '3.5' ? '1' : '0'; //check wp version for showing media uploader

		//get woocommerce screen id
		$wc_screen_id = woo_slg_get_wc_screen_id();

		$pages_hook_suffix = array( $wc_screen_id.'_page_woo-social-login' );
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';

		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) || ( !empty( $tab ) && $tab == 'social-login' ) ) {

			wp_register_script( 'woo-slg-admin-scripts', WOO_SLG_URL . 'includes/js/woo-slg-admin.js', array('jquery', 'jquery-ui-sortable' ) , WOO_SLG_VERSION, true );
			wp_enqueue_script( 'woo-slg-admin-scripts' );

			wp_localize_script( 'woo-slg-admin-scripts', 'WooVouAdminSettings', array( 'new_media_ui' => $newui ) );
			wp_enqueue_media();
		}
	}

	/**
	 * Enqueue Scripts for public side
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_public_scripts() {

		global $woo_slg_options, $post;

		//check if site is secure then use https:// else http://
		$suffix = is_ssl() ? 'https://' : 'http://';

		//check facebook social login enable or not
		if( !empty( $woo_slg_options['woo_slg_enable_facebook'] ) && WOO_SLG_FB_APP_ID != '' && WOO_SLG_FB_APP_SECRET != '' ) {

			wp_deregister_script('facebook');
			wp_register_script('facebook', $suffix.'connect.facebook.net/'.$woo_slg_options['woo_slg_fb_language'].'/all.js#xfbml=1&appId='.WOO_SLG_FB_APP_ID, false, WOO_SLG_VERSION );
			wp_register_script( 'woo-slg-fbinit', WOO_SLG_URL . 'includes/js/woo-slg-fbinit.js', array( 'jquery' ), WOO_SLG_VERSION, true );
			wp_localize_script( 'woo-slg-fbinit', 'WOOSlgFbInit', array( 'app_id' => WOO_SLG_FB_APP_ID ) );
		}
		
		if( !empty( $woo_slg_options['woo_slg_enable_amazon'] ) && WOO_SLG_AMAZON_CLIENT_ID != '' && WOO_SLG_AMAZON_CLIENT_SECRET != '' ) {

			wp_deregister_script('amazon');			
			wp_register_script( 'amazon', 'https://api-cdn.amazon.com/sdk/login1.js' );
			
		}

		//if there is no authentication data entered in settings page then so error
		$fberror = $gperror = $lierror = $twerror = $yherror = $fserror = $wlerror = $vkerror = $insterror = $amazonerror = $paypalerror = '';
		if( WOO_SLG_FB_APP_ID == '' || WOO_SLG_FB_APP_SECRET == '' ) { $fberror = '1'; }
		if( WOO_SLG_GP_CLIENT_ID == '' || WOO_SLG_GP_CLIENT_SECRET == '' ) { $gperror = '1'; }
		if( WOO_SLG_LI_APP_ID == '' || WOO_SLG_LI_APP_SECRET == '' ) { $lierror = '1'; }
		if( WOO_SLG_TW_CONSUMER_KEY == '' || WOO_SLG_TW_CONSUMER_SECRET == '' ) { $twerror = '1'; }
		if( WOO_SLG_YH_CONSUMER_KEY == '' || WOO_SLG_YH_CONSUMER_SECRET == '' || WOO_SLG_YH_APP_ID == '' ) { $yherror = '1'; }
		if( WOO_SLG_FS_CLIENT_ID == '' || WOO_SLG_FS_CLIENT_SECRET == '' ) { $fserror = '1'; }
		if( WOO_SLG_WL_CLIENT_ID == '' || WOO_SLG_WL_CLIENT_SECRET == '' ) { $wlerror = '1'; }
		if( WOO_SLG_VK_APP_ID == '' || WOO_SLG_VK_APP_SECRET == '' ) { $vkerror = '1'; }
		if( WOO_SLG_INST_CLIENT_ID == '' || WOO_SLG_INST_CLIENT_SECRET == '' ) { $insterror = '1'; }
		if( WOO_SLG_AMAZON_CLIENT_ID == '' || WOO_SLG_AMAZON_CLIENT_SECRET == '' ) { $amazonerror = '1'; }
		if( WOO_SLG_PAYPAL_CLIENT_ID == '' || WOO_SLG_PAYPAL_CLIENT_SECRET == '' ) { $paypalerror = '1'; }
		
		//get login url
		$loginurl	= wp_login_url();
		$login_array= array( 
							'woo_slg_social_login'	=> 1,
						 	'wooslgnetwork'		=> 'twitter'
						 );

		if( is_singular() ) {
			$login_array['page_id'] = $post->ID;
		}

		$loginurl = add_query_arg( $login_array, $loginurl );
		$userid = '';
		if( is_user_logged_in() ) {
			$userid = get_current_user_id();
		}

		//messages
		$messages = woo_slg_messages();

		wp_register_script( 'woo-slg-public-script', WOO_SLG_URL . 'includes/js/woo-slg-public.js', array( 'jquery' ), WOO_SLG_VERSION, true );		
		wp_localize_script( 'woo-slg-public-script', 'WOOSlg', array(
																		'ajaxurl'			=>	admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ),
																		'fberror'			=>	$fberror,
																		'gperror'			=>	$gperror,
																		'lierror'			=>	$lierror,
																		'twerror'			=>	$twerror,
																		'yherror'			=>	$yherror,
																		'fserror'			=>	$fserror,
																		'wlerror'			=>	$wlerror,
																		'vkerror'			=>	$vkerror,
																		'insterror'			=>	$insterror,
																		'amazonerror'		=>	$amazonerror,
																		'paypalerror'		=>	$paypalerror,
																		'fberrormsg'		=>	'<span>'.( isset( $messages['fberrormsg'] ) ? $messages['fberrormsg'] : '' ).'</span>',
																		'gperrormsg'		=>	'<span>'.( isset( $messages['gperrormsg'] ) ? $messages['gperrormsg'] : '' ).'</span>',
																		'lierrormsg'		=>	'<span>'.( isset( $messages['lierrormsg'] ) ? $messages['lierrormsg'] : '' ).'</span>',
																		'twerrormsg'		=>	'<span>'.( isset( $messages['twerrormsg'] ) ? $messages['twerrormsg'] : '' ).'</span>',
																		'yherrormsg'		=>	'<span>'.( isset( $messages['yherrormsg'] ) ? $messages['yherrormsg'] : '' ).'</span>',
																		'fserrormsg'		=>	'<span>'.( isset( $messages['fserrormsg'] ) ? $messages['fserrormsg'] : '' ).'</span>',
																		'wlerrormsg'		=>	'<span>'.( isset( $messages['wlerrormsg'] ) ? $messages['wlerrormsg'] : '' ).'</span>',
																		'vkerrormsg'		=>	'<span>'.( isset( $messages['vkerrormsg'] ) ? $messages['vkerrormsg'] : '' ).'</span>',
																		'insterrormsg'		=>	'<span>'.( isset( $messages['insterrormsg'] ) ? $messages['insterrormsg'] : '' ).'</span>',
																		'amazonerrormsg'	=>	'<span>'.( isset( $messages['amazonerrormsg'] ) ? $messages['amazonerrormsg'] : '' ).'</span>',
																		'paypalerrormsg'	=>	'<span>'.( isset( $messages['paypalerrormsg'] ) ? $messages['paypalerrormsg'] : '' ).'</span>',
																		'socialloginredirect'=>	$loginurl,
																		'userid'			 => $userid,
																		'woo_slg_amazon_client_id' =>WOO_SLG_AMAZON_CLIENT_ID
																	) );

		// unlink script
		wp_register_script( 'woo-slg-unlink-script', WOO_SLG_URL . 'includes/js/woo-slg-unlink.js', array( 'jquery' ), WOO_SLG_VERSION, true );
		wp_localize_script( 'woo-slg-unlink-script', 'WOOSlgUnlink', array(
																	'ajaxurl' => admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) )
																) );
	}

	/**
	 * Enqueue Styles
	 * 
	 * Loads the css file for the front end.
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */	
	public function woo_slg_public_styles() {

		wp_register_style( 'woo-slg-public-style', WOO_SLG_URL . 'includes/css/style-public.css', array(), WOO_SLG_VERSION );
		wp_enqueue_style( 'woo-slg-public-style' );
	}

	/**
	 * Register and Enqueue Script For Chart
	 * 
	 * Handles to load chart scipts
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_chart_scripts( $hook_suffix ) {

		//get woocommerce screen id
		$wc_screen_id = woo_slg_get_wc_screen_id();

		$pages_hook_suffix = array( $wc_screen_id.'_page_woo-social-login' );

		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {

			//check if site is secure then use https:// else http://
			$suffix = is_ssl() ? 'https://' : 'http://';

			wp_register_script( 'google-jsapi', $suffix.'www.google.com/jsapi', array('jquery'), WOO_SLG_VERSION, false ); // in header
			wp_enqueue_script( 'google-jsapi' );
		}
	}

	/**
	 * Display button in post / page container
	 * 
	 * Handles to display button in post / page container
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.1.0
	 */
	public function woo_slg_shortcode_display_button( $buttons ) {

		array_push( $buttons, "|", "woo_social_login" );
		return $buttons;
	}

	/**
	 * Include js for add button in post / page container
	 * 
	 * Handles to include js for add button in post / page container
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.1.0
	 */
	public function woo_slg_shortcode_button( $plugin_array ) {

		$plugin_array['woo_social_login'] = WOO_SLG_URL . 'includes/js/woo-slg-shortcodes.js?ver='.WOO_SLG_VERSION;
		return $plugin_array;
	}

	/**
	 * Display button in post / page container
	 * 
	 * Handles to display button in post / page container
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.1.0
	 */
	public function woo_slg_add_shortcode_button() {

		if( current_user_can( 'manage_options' ) || current_user_can( 'edit_posts' ) ) {
			add_filter( 'mce_external_plugins', array( $this, 'woo_slg_shortcode_button' ) );
   			add_filter( 'mce_buttons', array( $this, 'woo_slg_shortcode_display_button' ) );
		}
	}

	/**
	 * Add Faceook Root Div
	 * 
	 * Handles to add facebook root
	 * div to page
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_fb_root() {

		echo '<div id="fb-root"></div>';
	}

	/**
	 * Adding Hooks
	 * 
	 * Adding proper hoocks for the scripts.
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function add_hooks() {

		//add styles for back end
		add_action( 'admin_enqueue_scripts', array($this, 'woo_slg_admin_styles') );

		//add script to back side for social login
		add_action( 'admin_enqueue_scripts', array($this, 'woo_slg_admin_scripts') );

		//add script for chart in social login
		add_action( 'admin_enqueue_scripts', array( $this, 'woo_slg_chart_scripts' ) );

		//add script to front side for social login
		add_action( 'wp_enqueue_scripts', array( $this, 'woo_slg_public_scripts' ) );

		//add styles for front end
		add_action( 'wp_enqueue_scripts', array( $this, 'woo_slg_public_styles' ) );

		//add styles for login page
		add_action( 'login_enqueue_scripts', array( $this, 'woo_slg_public_styles' ) );

		//add scripts for login page
		add_action( 'login_enqueue_scripts', array( $this, 'woo_slg_public_scripts' ) );

		// add filters for add add button in post / page container
		add_action( 'admin_init', array( $this, 'woo_slg_add_shortcode_button' ) );

		//add facebook root div
		add_action( 'wp_footer', array( $this, 'woo_slg_fb_root' ) );
	}
}