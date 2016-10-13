<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 * 
 * Handles generic Admin functionality and AJAX requests.
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
class WOO_Slg_Admin {
	
	var $model, $scripts;
	
	public function __construct() {
		
		global $woo_slg_model, $woo_slg_scripts;
		
		$this->model = $woo_slg_model;
		$this->scripts = $woo_slg_scripts;
	}
	
	/**
	 * Register All need admin menu page
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_admin_menu_pages() {
		 
		$woo_slg_social_login = add_submenu_page( 'woocommerce' , __( 'WooCommerce Social Login', 'wooslg' ), __( 'Social Login', 'wooslg' ), 'manage_options', 'woo-social-login', array( $this, 'woo_slg_social_login' ) ); 
	}
	
	/**
	 * Add Social Login Page
	 * 
	 * Handles to load social login 
	 * page to show social login register data
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_social_login() {
		
		include_once( WOO_SLG_ADMIN . '/forms/woo-social-login-data.php' );
	}
	
	/**
	 * Pop Up On Editor
	 *
	 * Includes the pop up on the WordPress editor
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.1.1
	 */
	public function wps_deals_shortcode_popup() {
		
		include_once( WOO_SLG_ADMIN . '/forms/woo-slg-admin-popup.php' );
	}
	
	public function wps_deals_admin_ssl_notice(){
		
		global $woo_slg_options;
		
		$woo_social_order = get_option( 'woo_social_order' );	
		
		foreach ( $woo_social_order as $provider ) {
			
			global ${"woo_slg_social_".$provider};
			
			if( $woo_slg_options['woo_slg_enable_'.$provider] == "yes" && isset(${"woo_slg_social_".$provider}->requires_ssl) && ${"woo_slg_social_".$provider}->requires_ssl) {			?>
			<div class="error">
        		<p><?php _e( 'WooCommerce Social Login : <b>'. $provider .'</b> requires SSL for authentication. ', 'wooslg' ); ?></p>
    		</div>
    
	<?php }
		}
	
	}
	
	/**
	 * Adding Hooks
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//add admin menu pages
		add_action ( 'admin_menu', array( $this, 'woo_slg_admin_menu_pages' ) );
		
		// mark up for popup
		add_action( 'admin_footer-post.php', array( $this,'wps_deals_shortcode_popup' ) );
		add_action( 'admin_footer-post-new.php', array( $this,'wps_deals_shortcode_popup' ) );
		if(!is_ssl()){
			add_action( 'admin_notices', array( $this,'wps_deals_admin_ssl_notice' ) ); 
		}
	}
}