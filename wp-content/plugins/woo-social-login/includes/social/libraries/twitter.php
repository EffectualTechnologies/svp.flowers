<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Twitter Class
 *
 * Handles all twitter functions 
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists( 'WOO_Slg_Social_Twitter' ) ) {
	
	class WOO_Slg_Social_Twitter {
		
		var $twitter;
		
		public function __construct(){
			
		}
		
		/**
		 * Include Twitter Class
		 * 
		 * Handles to load twitter class
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_load_twitter() { 
			
			global $woo_slg_options;
			
			//twitter declaration
			if( !empty( $woo_slg_options['woo_slg_enable_twitter'] )
				 && !empty( $woo_slg_options['woo_slg_tw_consumer_key'] ) && !empty( $woo_slg_options['woo_slg_tw_consumer_secret'] ) ) {
			
				if( !class_exists( 'TwitterOAuth' ) ) { // loads the Twitter class
					require_once ( WOO_SLG_SOCIAL_LIB_DIR . '/twitter/twitteroauth.php' ); 
				}
				
				// Twitter Object
				$this->twitter = new TwitterOAuth( WOO_SLG_TW_CONSUMER_KEY, WOO_SLG_TW_CONSUMER_SECRET );
				
				return true;
				
			} else {
	 		
				return false;
			}	
			
		}
		
		/**
		 * Initializes Twitter API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		function woo_slg_initialize_twitter() {
			
			//when user is going to logged in in twitter and verified successfully session will create
			if ( isset( $_REQUEST['oauth_verifier'] ) && isset( $_REQUEST['oauth_token'] ) ) {
			
				//load twitter class
				$twitter = $this->woo_slg_load_twitter();
			
				//check twitter class is loaded or not
				if( !$twitter ) return false;
				
				$oauth_token = $_SESSION['woo_slg_twt_oauth_token'];
				$oauth_token_secret = $_SESSION['woo_slg_twt_oauth_token_secret'];
				
				if( isset( $oauth_token ) && $oauth_token == $_REQUEST['oauth_token'] ) {
						
					$this->twitter = new TwitterOAuth( WOO_SLG_TW_CONSUMER_KEY, WOO_SLG_TW_CONSUMER_SECRET, $oauth_token, $oauth_token_secret );
					
					// Request access tokens from twitter
					$woo_slg_tw_access_token = $this->twitter->getAccessToken($_REQUEST['oauth_verifier']);
					
					//session create for access token & secrets		
					$_SESSION['woo_slg_twt_oauth_token'] = $woo_slg_tw_access_token['oauth_token'];
					$_SESSION['woo_slg_twt_oauth_token_secret'] = $woo_slg_tw_access_token['oauth_token_secret'];
					
					//session for verifier
					$verifier['oauth_verifier'] = $_REQUEST['oauth_verifier'];
					//$_SESSION['woo_slg_twt_user_cache'] = $verifier;
					
					$_SESSION[ 'woo_slg_twt_user_cache' ] = $verifier;
					
					//getting user data from twitter
					$response = $this->twitter->get('account/verify_credentials');
					
					//if user data get successfully
					if ( $response->id_str ) {
						
						$data['user'] = $response;
						
						//all data will assign to a session
						$_SESSION['woo_slg_twt_user_cache'] = $data;	
						
					}
				}
			}
		}
		
		/**
		 * Get auth url for twitter
		 *
		 * @param WooCommerce - Social Login
		 * @since 1.0.0
		 */	
		public function woo_slg_get_twitter_auth_url () {
			
			// Save temporary credentials to session.
			// Get temporary credentials.
			global $post;
			
			//load twitter class
			$twitter = $this->woo_slg_load_twitter();
			
			//check twitter class is loaded or not
			if( !$twitter ) return false;
			
			$request_token = $this->twitter->getRequestToken( woo_slg_get_current_page_url()  ); // get_permalink( $post->ID )
		
			// If last connection failed don't display authorization link. 
			switch( $this->twitter->http_code ) { //
				
			  case 200:
			  	
						//$woo_slg_twt_oauth_token = $_SESSION['woo_slg_twt_oauth_token'];
						
						//if( empty( $woo_slg_twt_oauth_token ) ) {
						
					    	// Build authorize URL and redirect user to Twitter. 
					    	// Save temporary credentials to session.
					    	$_SESSION['woo_slg_twt_oauth_token'] = $request_token['oauth_token'];
					    	$_SESSION['woo_slg_twt_oauth_token_secret'] = $request_token['oauth_token_secret'];
						//}
						
				    	$token = $request_token['oauth_token'];
						$url = $this->twitter->getAuthorizeURL( $token );
						
				    	break;
			  default:
					    // Show notification if something went wrong.
					    $url = '';
			}		
			return $url;
		}	
		
		/**
		 * Get Twitter user's Data
		 * 
		 * @param WooCommerce - Social Login
		 * @since 1.0.0
		 */		
		public function woo_slg_get_twitter_user_data() {
		
			$user_profile_data = '';
			
			$user_cache = $_SESSION['woo_slg_twt_user_cache'];
			
			$user_profile_data = $user_cache['user'];
			
			return $user_profile_data;
		}
		
	}
	
}
?>