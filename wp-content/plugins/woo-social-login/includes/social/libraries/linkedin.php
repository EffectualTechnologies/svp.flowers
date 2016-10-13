<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Linkedin Class
 *
 * Handles all Linkedin functions 
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists( 'WOO_Slg_Social_LinkedIn' ) ) {
	
	class WOO_Slg_Social_LinkedIn {
		
		public $linkedinconfig, $linkedin;
		
		public function __construct() {
			
		}
		
		/**
		 * Include LinkedIn Class
		 * 
		 * Handles to load Linkedin class
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_load_linkedin() {
			
			global $woo_slg_options;
			
			//linkedin declaration
			if( !empty( $woo_slg_options['woo_slg_enable_linkedin'] )
				&& !empty( $woo_slg_options['woo_slg_li_app_id'] ) && !empty( $woo_slg_options['woo_slg_li_app_secret'] ) ) {
				
				if( !class_exists( 'LinkedInOAuth2' ) ) {
					require_once( WOO_SLG_SOCIAL_LIB_DIR . '/linkedin/LinkedIn.OAuth2.class.php' );
				}
				
				$call_back_url	= site_url().'/?wooslg=linkedin';
				
				//linkedin api configuration
				$this->linkedinconfig = array(
										    	'appKey'       => WOO_SLG_LI_APP_ID,
											  	'appSecret'    => WOO_SLG_LI_APP_SECRET,
											  	'callbackUrl'  => $call_back_url
										  	 );
				
				//Load linkedin outh2 class
				$this->linkedin = new LinkedInOAuth2();
				
				return true;
			} else {
				return false;	
			}
		}
		
		/**
		 * Linkedin Initialize
		 * 
		 * Handles LinkedIn Login Initialize
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_initialize_linkedin() {
			
			global $woo_slg_options;
			
			//check enable linkedin & linkedin application id & linkedin application secret are not empty
			if( !empty( $woo_slg_options['woo_slg_enable_linkedin'] ) && !empty( $woo_slg_options['woo_slg_li_app_id'] )
				 && !empty( $woo_slg_options['woo_slg_li_app_secret'] ) ) {
				
			 	//check $_GET['wooslg'] equals to linkedin
				if( isset( $_GET['wooslg'] ) && $_GET['wooslg'] == 'linkedin' 
					&& !empty( $_GET['code'] ) && !empty( $_GET['state'] ) ) {
					
					//load linkedin class
					$linkedin	= $this->woo_slg_load_linkedin();
					$config		= $this->linkedinconfig;
					
					//check linkedin loaded or not
					if( !$linkedin ) return false;
					
					//Get Access token
					$arr_access_token	= $this->linkedin->getAccessToken( $config['appKey'], $config['appSecret'], $config['callbackUrl']);
					
					// code will excute when user does connect with linked in
					if( !empty( $arr_access_token['access_token'] ) ) { // if user allows access to linkedin
						
						//Get User Profiles
						$resultdata			= $this->linkedin->getProfile();
						
						//set user data to sesssion for further use
				        $_SESSION['woo_slg_linkedin_user_cache'] = $resultdata;
						
						// redirect the user back to the demo page
						//wp_redirect($_SERVER['PHP_SELF']);
						//exit;
						
					} else {
						
						// bad token access
						echo __( 'Access token retrieval failed', 'wooslg' );
					}
				}
			}
		}
		
		/**
		 * Get LinkedIn Auth URL
		 * 
		 * Handles to return linkedin auth url
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_linkedin_auth_url() {
			
			//Remove unused scope for login
			//$scope	= array( 'r_emailaddress', 'r_basicprofile', 'rw_nus', 'r_network', 'r_contactinfo', 'r_fullprofile', 'w_messages' );
			$scope	= array( 'r_emailaddress', 'r_basicprofile' );
			
			//load linkedin class
			$linkedin = $this->woo_slg_load_linkedin();
			
			//check linkedin loaded or not
			if( !$linkedin ) return false;
			
			//Get Linkedin config
			$config	= $this->linkedinconfig;
			
			try {//Prepare login URL
				$preparedurl	= $this->linkedin->getAuthorizeUrl( $config['appKey'], $config['callbackUrl'], $scope );
			} catch( Exception $e ) {
				$preparedurl	= '';
	        }
	        
			return $preparedurl;
		}
		
		/**
		 * Get LinkedIn User Data
		 * 
		 * Function to get LinkedIn User Data
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_get_linkedin_user_data() {
			
			$user_profile_data = '';
			
			$user_profile_data = $_SESSION['woo_slg_linkedin_user_cache'];
			
			return $user_profile_data;
		}
	}
}