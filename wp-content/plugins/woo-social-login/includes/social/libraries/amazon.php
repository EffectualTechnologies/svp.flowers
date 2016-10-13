<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Amazon Class
 * 
 * Handles all amazon functions
 * 
 * @package WooCommerce - Social Login
 * @since 1.4.0
 */
if( !class_exists( 'WOO_Slg_Social_Amazon' ) ) {

	class WOO_Slg_Social_Amazon {

		public $amazon, $requires_ssl;
		
		public function __construct() {
			$this->requires_ssl = true;
		}

		/**
		 * Include Amazon Class
		 * 
		 * Handles to load amazon code
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.4.0
		 */
		public function woo_slg_get_amazon_auth_url() {

			global $woo_slg_options;
			
			$oauth_url	= 'https://www.amazon.com/ap/oa';
			$url		= '';			
			
			//amazon declaration
			if( !empty( $woo_slg_options['woo_slg_enable_amazon'] ) && !empty( $woo_slg_options['woo_slg_amazon_client_id'] ) && !empty( $woo_slg_options['woo_slg_amazon_client_secret'] ) ) {
				
				$params = array(
					'client_id'		=> WOO_SLG_AMAZON_CLIENT_ID,
					'redirect_uri'	=> WOO_SLG_AMAZON_REDIRECT_URL,
					'response_type'	=> 'code',
					'scope'			=> 'profile postal_code'
				);
				$url= $oauth_url.'?'.http_build_query($params, '', '&');
			}
					
			return apply_filters( 'woo_slg_get_amazon_auth_url', $url );
		}

		/**
		 * Initializes Amazon API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_initialize_amazon() {
			
			//check yahoo is enable,consumer key not empty,consumer secrets not empty and app id should not empty
			if ( isset( $_GET['code'] )  && isset( $_GET['wooslg'] ) && $_GET['wooslg'] == 'amazon' ) {

				$code	= $_GET['code'];
				$url	= 'https://api.amazon.com/auth/o2/token';
				$params	= array(
								'code'			=> $code,
								'client_id'		=> WOO_SLG_AMAZON_CLIENT_ID,
								'client_secret'	=> WOO_SLG_AMAZON_CLIENT_SECRET,
								'redirect_uri'	=> WOO_SLG_AMAZON_REDIRECT_URL,
								'grant_type'	=> 'authorization_code'
							);
				
				$query		= http_build_query($params, '', '&');
				
				$wp_http_args	= array(
										'method'      => 'POST',
										'body'        => $query,
										'headers'     => 'Content-type: application/x-www-form-urlencoded',
										'cookies'     => array(),
								);
				
				$response		= wp_remote_request($url, $wp_http_args);
				$responseData	= wp_remote_retrieve_body( $response );
				
				if( is_wp_error( $response ) ) {
					$content = $response->get_error_message();
				} else {
					
					$responseData	= json_decode( $responseData );
					
					if( isset( $responseData->access_token ) && !empty( $responseData->access_token ) ) {
						$token	= $responseData->access_token;
						$_SESSION['woo_slg_amazon_user_cache']	= $this->woo_slg_get_amazon_profile_data( $token );						
					}
				}
			}
		}
		
		/**
		 * Get USer Profile Information
		 * 
		 * Handle to get user profile information
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_get_amazon_profile_data( $token ) {
			
			$profile_data	= array();
			
			if( isset( $token ) && !empty( $token ) ) { // if access token is not empty
				
				$url	= 'https://api.amazon.com/user/profile';
				$args	= array(
									'headers'	=> array(
									'Authorization' => 'bearer ' . $token
								)
							);
				
				$result			= wp_remote_retrieve_body( wp_remote_get( $url, $args ) );
				$profile_data	= json_decode( $result );
			}
			
			return apply_filters( 'woo_slg_get_amazon_profile_data', $profile_data, $token );
		}
		
		/**
		 * Get USer Profile Information
		 *  
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_get_amazon_user_data() {
			
			$user_profile_data	= '';
			$user_profile_data	= isset( $_SESSION['woo_slg_amazon_user_cache'] ) ? $_SESSION['woo_slg_amazon_user_cache'] : array();
			
			return apply_filters( 'woo_slg_get_amazon_user_data', $user_profile_data );
		}
	}
}