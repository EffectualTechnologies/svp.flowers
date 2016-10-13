<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Instagram Class
 *
 * Handles all instagram functions 
 *
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */
if( !class_exists( 'WOO_Slg_Social_Instagram' ) ) {
	
	class WOO_Slg_Social_Instagram {
		
		var $instagram;
		
		public function __construct() {
			
		}
		
		/**
		 * Include instagram Class
		 * 
		 * Handles to load instagram class
		 * 
		 * @package WooCommerce - Social Login
 		 * @since 1.3.0
		 */
		public function woo_slg_load_instagram() {
			
			global $woo_slg_options;
			
			//instagram declaration
			if( !empty( $woo_slg_options['woo_slg_enable_instagram'] ) && !empty( $woo_slg_options['woo_slg_inst_client_id'] ) 
				&& !empty( $woo_slg_options['woo_slg_inst_client_secret'] ) ) {
			
				if( !class_exists( 'Instagram' ) ) { // loads the Instagram class
					
		 			require_once ( WOO_SLG_SOCIAL_LIB_DIR . '/instagram/instagram.php' );
				}
				
				// initialize class
				$this->instagram = new Instagram(array(
				  'apiKey'      => WOO_SLG_INST_CLIENT_ID,
				  'apiSecret'   => WOO_SLG_INST_CLIENT_SECRET,
				  'apiCallback' => WOO_SLG_INST_REDIRECT_URL
				));
				
				return true;
				
			} else {
				
				return false;
			}
			
		}
		
		/**
		 * Initializes Instagram API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.3.0
		 */
		public function woo_slg_initialize_instagram() {			
									
			//check instagram is enable,consumer key not empty,consumer secrets not empty and app id should not empty
			if ( isset( $_GET['code'] ) && !empty($_GET['code']) && isset( $_GET['wooslg'] ) && $_GET['wooslg'] == 'instagram' ) {
				
				//load instagram class
				$instagram = $this->woo_slg_load_instagram();
				
				//check instagram class is loaded or not
				if( !$instagram ) return false;
				
				// receive OAuth token object
				$data = $this->instagram->getOAuthToken($_GET['code']);
				
				if( isset( $data->user ) && !empty( $data->user ) ) {
					
					$_SESSION['woo_slg_instagram_user_cache'] = $data->user;
				}
			}		
		}
						
		/**
		 * Get auth url for instagram
		 *
		 * @param WooCommerce - Social Login
		 * @since 1.3.0
		 */	
		public function woo_slg_get_instagram_auth_url () {
			
			//load instagram class
			$instagram = $this->woo_slg_load_instagram();
			
			//check instagram is loaded or not
			if( !$instagram ) return false;
			
			$url = $this->instagram->getLoginUrl();
			return $url;
		}
		 
		/**
		 * Get Instagram user's Data
		 * 
		 * @param WooCommerce - Social Login
		 * @since 1.3.0
		 */		
		public function woo_slg_get_instagram_user_data() {
					
			$user_data = '';
			
			$user_data = $_SESSION['woo_slg_instagram_user_cache'];
			
			return $user_data;
		}
	}
}
?>