<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Google Class
 *
 * Handles all google functions 
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists( 'WOO_Slg_Social_Google' ) ) {
	
	class WOO_Slg_Social_Google {
		
		var $google, $googleplus, $googleoauth2, $_google_user_cache;
		
		public function __construct(){
			
		}
		
		/**
		 * Include google Class
		 * 
		 * Handles to load google class
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_load_google() {
			
			global $woo_slg_options;
			
			//google class declaration
			if( !empty( $woo_slg_options['woo_slg_enable_googleplus'] ) 
				&& !empty( $woo_slg_options['woo_slg_gp_client_id'] ) && !empty( $woo_slg_options['woo_slg_gp_client_secret'] ) ) {
			 	
				if( !class_exists( 'apiClient' ) ) { // loads the Google class
					require_once ( WOO_SLG_SOCIAL_LIB_DIR . '/google/src/apiClient.php' ); 
				}
				if( !class_exists( 'apiPlusService' ) ) { // Loads the google plus service class for user data
					require_once ( WOO_SLG_SOCIAL_LIB_DIR . '/google/src/contrib/apiPlusService.php' ); 
				}
				if( !class_exists( 'apiOauth2Service' ) ) { // loads the google plus service class for user email
					require_once ( WOO_SLG_SOCIAL_LIB_DIR . '/google/src/contrib/apiOauth2Service.php' ); 
				}
				
				// Google Objects
				$this->google = new apiClient();
				$this->google->setApplicationName( "Google+ PHP Starter Application" );
				$this->google->setClientId( WOO_SLG_GP_CLIENT_ID );
				$this->google->setClientSecret( WOO_SLG_GP_CLIENT_SECRET );
				$this->google->setRedirectUri( WOO_SLG_GP_REDIRECT_URL );
				$this->google->setScopes( array( 'https://www.googleapis.com/auth/plus.me','https://www.googleapis.com/auth/userinfo.email' ) );
				
				$this->googleplus = new apiPlusService( $this->google ); // For getting user detail from google
				$this->googleoauth2 = new apiOauth2Service( $this->google ); // For gettting user email from google
				
				return true;
				
			} else {
				
				return false;
			}
		}
		
		/**
		 * Initialize API
		 * 
		 * Getting Initializes Google Plus API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_initialize_google() {
		
			global $woo_slg_options;
			
			//Google integration begins here
			// not isset state condition required, else google code executed when google called
			//and check wooslg is equal to google
			if( isset( $_GET['code'] ) && !isset( $_GET['state'] ) 
				&& isset( $_GET['wooslg'] ) && $_GET['wooslg'] == 'google' ) {
				
				//load google class
				$google = $this->woo_slg_load_google();
				
				//check google class is loaded
				if( !$google ) return false;
				
				//Get access tocken
				$gplus_access_token = $this->google->getAccessToken();
				
				if( empty( $gplus_access_token ) ) {//If empty access tocken
					
					//generate new access token
					$this->google->authenticate();
					
					//Get access tocken
					$gplus_access_token	= $this->google->getAccessToken();
				}
				
				//check access token is set or not
				if ( !empty( $gplus_access_token ) ) {
					
					$userdata	= $this->googleplus->people->get( 'me' );
					$useremail	= $this->googleoauth2->userinfo->get(); // to get email
					
					$userdata['email'] = $useremail['email'];
					$_SESSION['woo_slg_google_user_cache'] = $userdata;
				}
			}
		}
		
		/**
		 * Get Google User Data
		 * 
		 * Getting all the google+ connected user data
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		
		public function woo_slg_get_google_user_data() {
			
			$user_profile_data = '';
			
			$user_profile_data = $_SESSION['woo_slg_google_user_cache'];
			
			return $user_profile_data;
		}
		
		/**
		 * Get Google Authorize URL
		 * 
		 * Getting Authentication URL connect with google+
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		
		public function woo_slg_get_google_auth_url() {
		
			//load google class
			$google = $this->woo_slg_load_google();
			
			//check google class is loaded
			if( !$google ) return false;
		
			$url = $this->google->createAuthUrl();
			$authurl = isset( $url ) ? $url : '';
			
			return $authurl;
		}
	}
}
?>