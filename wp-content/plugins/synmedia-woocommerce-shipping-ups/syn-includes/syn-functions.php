<?php
/*
	* SYN functions.
	* Version: 1.0.2
*/

if ( ! class_exists( 'SYN_Auto_Update' ) )
	require_once( 'syn-auto-update.php' );

if( !function_exists('is_woo_enabled') ){

	function is_woo_enabled(){
		
		/**
		 * Check if WooCommerce is active
		 **/
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return is_plugin_active('woocommerce/woocommerce.php');
		
	}
	
}

?>