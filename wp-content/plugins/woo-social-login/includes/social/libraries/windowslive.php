<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Windows Live Class
 *
 * Handles all Windows Live functions
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

if( !class_exists( 'WOO_Slg_Social_Windowslive' ) ) {
	
	class WOO_Slg_Social_Windowslive {

		var $windowslive;
		var $windowslive_client_id;
		var $windowslive_client_secret;
		var $windowslive_redirect_uri;
		
		public function __construct() {
			
		}
		/**
		 * Initialize some user data
		 * 
		 * Handles to initialize some user
		 * data
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_initialize_windowslive() {
			
			global $woo_slg_options;
			
			//check facebook is enable and application id and application secret is not empty			
			if( !empty( $woo_slg_options['woo_slg_enable_windowslive'] ) 
				&& !empty( $woo_slg_options['woo_slg_wl_client_id'] ) && !empty($woo_slg_options['woo_slg_wl_client_secret'] ) ) {
				
				// Check $_GET['code'] is set and not empty
				if( isset( $_GET['code'] ) && !empty( $_GET['code'] ) 
					&& isset( $_GET['wooslg'] ) && $_GET['wooslg'] == 'windowslive' ) {
					
					$access_token_url = 'https://login.live.com/oauth20_token.srf';
		    		
					$postdata = 'code='.$_REQUEST['code'].'&client_id='.WOO_SLG_WL_CLIENT_ID.'&client_secret='.WOO_SLG_WL_CLIENT_SECRET.
								'&redirect_uri='.WOO_SLG_WL_REDIRECT_URL.'&grant_type=authorization_code';
					
					$data = $this->woo_slg_get_data_from_url( $access_token_url , $postdata, true );
					
					if( !empty( $data->access_token ) ) { 
						
						// Set the session access token
						$_SESSION['woo_slg_windowslive_access_token'] = $data->access_token;
						
						$accessurl = 'https://apis.live.net/v5.0/me?access_token=' . $data->access_token;
						
						//get user data from access token
						$userdata = $this->woo_slg_get_data_from_url( $accessurl );
						
						// Set the session access token
						$_SESSION['woo_slg_windowslive_user_cache'] = $userdata;
					}
				}
			}
		}
		
		/**
		 * Get Auth Url
		 * 
		 * Handles to Get authentication url
		 * from windows live
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_get_wl_auth_url() {
			
			$wlauthurl = add_query_arg( array(	
												'client_id'		=>	WOO_SLG_WL_CLIENT_ID,
												'scope'			=>	'wl.basic+wl.emails',
												'response_type'	=>	'code',
												'redirect_uri'	=>	WOO_SLG_WL_REDIRECT_URL
											),
										'https://login.live.com/oauth20_authorize.srf' );
			return $wlauthurl;
		}
		
		/**
		 * Get Data From URL
		 * 
		 * Handels to return data from url 
		 * via calling CURL
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_get_data_from_url( $url, $data = array(), $post = false ) {
			
			$ch = curl_init();
			
			// Set the cURL URL
			curl_setopt($ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			
			//IF NEED TO POST SOME FIELD && $data SHOULD NOT BE EMPTY
			if( $post == TRUE && !empty( $data ) ) {
				
				curl_setopt( $ch, CURLOPT_POST, TRUE );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
				
			}
			
			$data = curl_exec($ch);
			
			// Close the cURL connection
			curl_close($ch);
			
			// Decode the JSON request and remove the access token from it
			$data = json_decode( $data );
			
			return $data;
			
		}
		
		/**
		 * Get User Data
		 * 
		 * Handles to Get Windows Live User Data
		 * from access token
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_get_windowslive_user_data() {
			
			$user_profile_data = isset($_SESSION['woo_slg_windowslive_user_cache']) ? $_SESSION['woo_slg_windowslive_user_cache'] : '';
			
			return $user_profile_data;
			
		}
		
	}
	
}