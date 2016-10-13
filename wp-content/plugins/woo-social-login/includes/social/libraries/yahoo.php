<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Yahoo Class
 * 
 * Handles all yahoo functions
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists( 'WOO_Slg_Social_Yahoo' ) ) {

	class WOO_Slg_Social_Yahoo {

		var $yahoo;

		public function __construct() {

		}

		/**
		 * Include Yahoo Class
		 * 
		 * Handles to load yahoo class
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_load_yahoo() {

			global $woo_slg_options;

			//yahoo declaration
			if( !empty( $woo_slg_options['woo_slg_enable_yahoo'] ) && !empty( $woo_slg_options['woo_slg_yh_consumer_key'] ) 
				&& !empty( $woo_slg_options['woo_slg_yh_consumer_secret'] ) && !empty( $woo_slg_options['woo_slg_yh_app_id'] ) ) {

				if( !class_exists( 'OAuthToken' ) ) { // loads the OAuthToken class
					require_once ( WOO_SLG_SOCIAL_LIB_DIR . '/yahoo/OAuth/OAuth.php' );
				}
				// Require Yahoo! PHP5 SDK libraries
				if( !class_exists( 'YahooOAuthApplication' ) ) {
					require_once ( WOO_SLG_SOCIAL_LIB_DIR . '/yahoo/Yahoo/YahooOAuthApplication.class.php' ); 
				}

				$woo_domain_url = WOO_SLG_YH_REDIRECT_URL;
			   /* if( is_ssl() ) { // Check page is secure
			     	$woo_domain_url = str_replace( 'http://', 'https://', $woo_domain_url );
			    }*/

			    // Yahoo Object
			    $this->yahoo = new YahooOAuthApplication( WOO_SLG_YH_CONSUMER_KEY, WOO_SLG_YH_CONSUMER_SECRET, WOO_SLG_YH_APP_ID, $woo_domain_url );

				return true;
			} else {

				return false;
			}
		}

		/**
		 * Initializes Yahoo API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_initialize_yahoo() {
			
			//check yahoo is enable,consumer key not empty,consumer secrets not empty and app id should not empty
			if ( isset( $_GET['openid_mode'] ) && $_GET['openid_mode'] == 'id_res'
				 && isset( $_GET['wooslg'] ) && $_GET['wooslg'] == 'yahoo' ) {

				//load yahoo class
				$yahoo = $this->woo_slg_load_yahoo();

				//check yahoo class is loaded or not
				if( !$yahoo ) return false;

			 	$request_token = new YahooOAuthRequestToken( $_GET['openid_oauth_request_token'],'' );

			    // exchange request token for access token
			    $this->yahoo->token = $this->yahoo->getAccessToken( $request_token );

			    // store access token for later use
			    $yahoo_access_token = $this->yahoo->token->to_string();

			    //check yahoo oauth access token is set or not
				 if ( !empty( $yahoo_access_token ) ) {

				 	// if session is still present ( not expired),then restore access token from session
	        		$this->yahoo->token = YahooOAuthAccessToken::from_string( $yahoo_access_token );

	        		$user_data = $this->yahoo->getProfile();

					if( isset( $user_data->profile ) && !empty( $user_data->profile ) ) {

						$_SESSION['woo_slg_yahoo_user_cache'] = $user_data->profile;
					}
			    }
			}
		}

		/**
		 * Get auth url for yahoo
		 *
		 * @param WooCommerce - Social Login
		 * @since 1.0.0
		 */	
		public function woo_slg_get_yahoo_auth_url () {
			
			//load yahoo class
			$yahoo = $this->woo_slg_load_yahoo();
			
			//check yahoo is loaded or not
			if( !$yahoo ) return false;
			
			$url = $this->yahoo->getOpenIDUrl( $this->yahoo->callback_url );
			return $url;
		}
		 
		/**
		 * Get Yahoo user's Data
		 * 
		 * @param WooCommerce - Social Login
		 * @since 1.0.0
		 */		
		public function woo_slg_get_yahoo_user_data() {
		
			$user_profile_data = '';
			
			$user_profile_data = $_SESSION['woo_slg_yahoo_user_cache'];
			
			return $user_profile_data;
		}
		
	}
	
}
?>