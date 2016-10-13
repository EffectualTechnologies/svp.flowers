<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Paypal Class
 * 
 * Handles all paypal functions
 * 
 * @package WooCommerce - Social Login
 * @since 1.4.0
 */
if( !class_exists( 'WOO_Slg_Social_Paypal' ) ) {
	
	class WOO_Slg_Social_Paypal {
		
		public $api_endpoint, $auth_endpoint, $paypalenvironment;
		
		// live authentication endpoint
		const LIVE_AUTH_ENDPOINT = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
		
		// sandbox authentication endpoint
		const SANDBOX_AUTH_ENDPOINT = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
		
		// live API endpoint
		const LIVE_API_ENDPOINT = 'https://api.paypal.com/v1/identity/openidconnect';
		
		// sandbox API endpoint
		const SANDBOX_API_ENDPOINT = 'https://api.sandbox.paypal.com/v1/identity/openidconnect';
		
		public function __construct() {
			
			$this->paypalenvironment= WOO_SLG_PAYPAL_ENVIRONMENT;
			
			$this->api_endpoint		= ( 'live' == $this->paypalenvironment ) ? self::LIVE_API_ENDPOINT : self::SANDBOX_API_ENDPOINT;
			$this->auth_endpoint	= ( 'live' == $this->paypalenvironment ) ? self::LIVE_AUTH_ENDPOINT : self::SANDBOX_AUTH_ENDPOINT;
		}
		
		/**
		 * Get Paypal Authentication URL
		 * 
		 * Handles to get paypal authentication URL
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.4.0
		 */
		public function woo_slg_get_paypal_auth_url() {
			
			global $woo_slg_options;
			
			$url	= '';
			
			//paypal declaration
			if( !empty( $woo_slg_options['woo_slg_enable_paypal'] ) && !empty( $woo_slg_options['woo_slg_paypal_client_id'] ) && !empty( $woo_slg_options['woo_slg_paypal_client_secret'] ) ) {
				
				$params = array(
					'client_id'		=> WOO_SLG_PAYPAL_CLIENT_ID,
					'redirect_uri'	=> WOO_SLG_PAYPAL_REDIRECT_URL,
					'response_type'	=> 'code',
					'scope'			=> 'openid profile email'
				);
				
				$url	= $this->auth_endpoint . '?' . http_build_query( $params, '', '&' );
			}
			
			return apply_filters( 'woo_slg_get_paypal_auth_url', $url );
		}
		
		/**
		 * Initializes Paypal API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_initialize_paypal() {
			
			if ( isset( $_GET['code'] )  && isset( $_GET['wooslg'] ) && $_GET['wooslg'] == 'paypal' ) {
				
				$code	= $_GET['code'];
				
				$params	= array(
								'code'			=> $code,
								'client_id'		=> WOO_SLG_PAYPAL_CLIENT_ID,
								'client_secret'	=> WOO_SLG_PAYPAL_CLIENT_SECRET,
								'redirect_uri'	=> WOO_SLG_PAYPAL_REDIRECT_URL,
								'grant_type'	=> 'authorization_code'
							);
				
				$query			= "{$this->api_endpoint}/tokenservice".'?'.http_build_query($params,'','&');
				$response		= wp_remote_request( $query );
				$responseData	= wp_remote_retrieve_body( $response );
				
				if( is_wp_error( $response ) ) {
					
					$content = $response->get_error_message();
				} else {
					
					$responseData	= json_decode( $responseData );
					
					if( isset( $responseData->access_token ) && !empty( $responseData->access_token ) ) {
						$token	= $responseData->access_token;
						$_SESSION['woo_slg_paypal_user_cache']	= $this->woo_slg_get_paypal_profile_data( $token );
					}
				}
			}
		}
		
		/**
		 * Get User Profile Information
		 * 
		 * Handle to get user profile information
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_get_paypal_profile_data( $token ) {
			
			$profile_data	= array();
			
			if( isset( $token ) && !empty( $token ) ) { // if access token is not empty
				
				$url	= "{$this->api_endpoint}/userinfo".'?'.http_build_query(array( 'schema' => 'openid' ),'','&');
				
				$args	= array('headers'	=> array(
										'Authorization' => 'Bearer ' . $token
									)
								);
				
				$result	= wp_remote_retrieve_body( wp_remote_get( $url, $args ) );
				
				if( is_wp_error( $result ) ) {
					$content = $result->get_error_message();
				}
				
				$profile_data	= json_decode( $result );
			}
			
			return apply_filters( 'woo_slg_get_paypal_profile_data', $profile_data );
		}
		
		/**
		 * Get User Profile Information
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_get_paypal_user_data() {
			
			$user_profile_data	= '';
			$user_profile_data	= isset( $_SESSION['woo_slg_paypal_user_cache'] ) ? $_SESSION['woo_slg_paypal_user_cache'] : array();
			return apply_filters( 'woo_slg_get_paypal_user_data', $user_profile_data );
		}
	}
}