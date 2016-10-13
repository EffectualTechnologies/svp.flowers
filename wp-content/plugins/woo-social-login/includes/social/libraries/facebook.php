<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Facebook Class
 * 
 * Handles all facebook functions
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists( 'WOO_Slg_Social_Facebook' ) ) {

	class WOO_Slg_Social_Facebook {

		public $facebook;

		public function __construct() {
			
		}

		/**
		 * Include Facebook Class
		 * 
		 * Handles to load facebook class
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_load_facebook() {

			global $woo_slg_options;

			//check facebook is enable and application id and application secret is not empty
			if( !empty( $woo_slg_options['woo_slg_enable_facebook'] ) 
				&& !empty( $woo_slg_options['woo_slg_fb_app_id'] ) && !empty($woo_slg_options['woo_slg_fb_app_secret']) ) {

				if( !class_exists( 'Facebook' ) ) { // loads the facebook class
					require_once ( WOO_SLG_SOCIAL_LIB_DIR . '/facebook/facebook.php' );
				}

				$this->facebook = new Facebook( array(
						'appId' => WOO_SLG_FB_APP_ID,
						'secret' => WOO_SLG_FB_APP_SECRET,
						'cookie' => true
				));

				return true;
			} else {
				return false;
			}
		}

		/**
		 * Get Facebook User
		 * 
		 * Handles to return facebook user id
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_get_fb_user() {

			//load facebook class
			$facebook = $this->woo_slg_load_facebook();

			//check facebook class is exis or not
			if( !$facebook ) return false;

			$user = $this->facebook->getUser();

			return apply_filters( 'woo_slg_get_fb_user', $user );
		}

		/**
		 * Facebook User Data
		 * 
		 * Getting the all the needed data of the connected Facebook user.
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_get_fb_userdata( $user ) {

			//load facebook class
			$facebook = $this->woo_slg_load_facebook();

			//check facebook class is exis or not
			if( !$facebook ) return false;

			$fb		= array();
			$fields	= array( 'fields' => 'email,name,first_name,last_name,link' );

			$fb = $this->facebook->api( '/'.$user, 'GET', $fields );
			$fb['picture'] = $this->woo_slg_fb_get_profile_picture( array( 'type' => 'normal' ), $user );

			return  apply_filters( 'woo_slg_get_fb_userdata', $fb, $user );
		}

		/**
		 * Access Token
		 * 
		 * Getting the access token from Facebook.
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_fb_getaccesstoken() {

			//load facebook class
			$facebook = $this->woo_slg_load_facebook();

			//check facebook class is exis or not
			if( !$facebook ) return false;

			return $this->facebook->getAccessToken();
		}

		/**
		 * Check Application Permission
		 * 
		 * Handles to check facebook application
		 * permission is given by user or not
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_check_fb_app_permission( $perm = '' ) {

			$data = '1';

			if( !empty( $perm ) ) {

				$facebook 	= $this->woo_slg_load_facebook();
				$userID 	= $this->woo_slg_get_fb_user();

				$permissions = $this->facebook->api("/$userID/permissions");

				$permission_data	= isset( $permissions['data'] ) ? $permissions['data'] : array();

				if( !empty( $permission_data ) ) {

					foreach ( $permission_data as $permission_field ) {

						$field_name		= isset( $permission_field['permission'] ) ? $permission_field['permission'] : '';
						$field_status	= isset( $permission_field['status'] ) ? $permission_field['status'] : '';

						if( $field_name == 'email' && $field_status == 'granted' ) {
							$data = 1;
							break;
						}
					}
				}
			}

			return apply_filters( 'woo_slg_check_fb_app_permission', $data );
		}

		/**
		 * User Image
		 * 
		 * Getting the the profile image of the connected Facebook user.
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_fb_get_profile_picture( $args = array(), $user ) {

			if( isset( $args['type'] ) && !empty( $args['type'] ) ) {
				$type = $args['type'];
			} else {
				$type = 'normal';
			}

			$type	= apply_filters( 'woo_slg_fb_profile_picture_type', $type, $user );
			$url	= 'https://graph.facebook.com/' . $user . '/picture?type=' . $type;

			return apply_filters( 'woo_slg_fb_get_profile_picture', $url );
		}
	}
}