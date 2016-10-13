<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Model Class
 *
 * Handles generic plugin functionality.
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
class WOO_Slg_Model {
	
	public $foursquare;
	
	public function __construct() {
		
		global $woo_slg_social_foursquare;
		
		$this->foursquare = $woo_slg_social_foursquare;
	}
	
	/**
	 * Escape Tags & Slashes
	 *
	 * Handles escapping the slashes and tags
	 *
	 * @package  WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_escape_attr($data){
		return esc_attr(stripslashes($data));
	}
	
	/**
	 * Strip Slashes From Array
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_escape_slashes_deep($data = array(),$flag=false){
			
		if($flag != true) {
			$data = $this->woo_slg_nohtml_kses($data);
		}
		$data = stripslashes_deep($data);
		return $data;
	}
	
	/**
	 * Strip Html Tags
	 * 
	 * It will sanitize text input (strip html tags, and escape characters)
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_nohtml_kses($data = array()) {

		if ( is_array($data) ) {

			$data = array_map(array($this,'woo_slg_nohtml_kses'), $data);

		} elseif ( is_string( $data ) ) {

			$data = wp_filter_nohtml_kses($data);
		}

		return $data;
	}

	/**
	 * Convert Object To Array
	 * 
	 * Converting Object Type Data To Array Type
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_object_to_array($result)
	{
	    $array = array();
	    foreach ($result as $key=>$value)
	    {	
	        if (is_object($value))
	        {
	            $array[$key]=$this->woo_slg_object_to_array($value);
	        } else {
	        	$array[$key]=$value;
	        }
	    }
	    return $array;
	}

	/**
	 * Create User
	 *
	 * Function to add connected users to the WordPress users database
	 * and add the role subscriber
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */

	public function woo_slg_add_wp_user( $criteria ) {

		global $wp_version, $woo_slg_options;
		
		$usermeta_prefix	= WOO_SLG_USER_META_PREFIX;
		
		$username = $this->woo_slg_create_username($criteria);
		
		$name = $criteria['name'];
		$first_name = $criteria['first_name'];
		$last_name = $criteria['last_name'];
		$password = wp_generate_password(12, false);
		$email = $criteria['email'];
		$wp_id = 0;
		
		//create the WordPress user
		if ( version_compare($wp_version, '3.1', '<') ) {
			require_once( ABSPATH . WPINC . '/registration.php' );
		}
		
		//check user id is exist or not
		if ( $this->woo_slg_check_user_exists($criteria) == false ) {
			
			$wp_id = wp_create_user( $username, $password, $email );
			
			if( !empty( $wp_id ) ) { //if user is created then update some data
				$role = get_option( 'default_role' );
				$user = new WP_User( $wp_id );
				$user->set_role( $role );
				
				if( !empty($woo_slg_options['woo_slg_enable_notification']) && $woo_slg_options['woo_slg_enable_notification'] == 'yes' ) { // check enable email notification from settings
					wp_new_user_notification( $wp_id, null, apply_filters('woo_slg_new_user_notify_to', 'both') );
				}
				
				do_action( 'woo_slg_social_user_created', $wp_id, $user, $password, $criteria );
			}
			
			//Update unique id to usermeta
			update_user_meta( $wp_id, $usermeta_prefix.'unique_id', $criteria['id'] );
			
		} else {
			//get user from email or username
			$userdata = $this->woo_slg_get_user_by( $criteria );
			
			if( !empty( $userdata ) ) { //check user is exit or not
				$wp_id = $userdata->ID;
			}			
		}
		return $wp_id;
	}
	
	/**
	 * Get Social Connected Users Count
	 * 
	 * Handles to return connected user counts
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_social_get_users( $args = array() ) {
		
		$userargs = array();
		$metausr1 = array();
		
		if( isset( $args['network'] ) && !empty( $args['network'] ) ) { //check network is set or not
			$metausr1['key'] = 'woo_slg_social_user_connect_via';
			$metausr1['value'] = $args['network'];
		}
		
		if( !empty($metausr1) ) { //meta query
			$userargs['meta_query'] = array( $metausr1 );
		}
		
		//get users data
		$result = new WP_User_Query($userargs);
		
		if ( isset( $args['getcount'] ) && !empty( $args['getcount'] ) ) { //get count of users
			$users = $result->total_users;
		} else {
			//retrived data is in object format so assign that data to array for listing
			$users = $this->woo_slg_object_to_array($users->results);
		}
	
		return $users;
	}
	
	/**
	 * Create User Name for VK.com and Instagram
	 *
	 * Function to check type is vk or instagram then create user name based on user id.
	 * 
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.3.0
	 */
	public function woo_slg_create_username( $criteria ) {
		
		global $woo_slg_options;
		
		$prefix	= WOO_SLG_USER_PREFIX;
		
		//Initilize username
		$username	= '';
		
		//Get base of username
		$woo_user_base	= isset( $woo_slg_options['woo_slg_base_reg_username'] ) ? $woo_slg_options['woo_slg_base_reg_username'] : '';
		
		switch( $woo_user_base ) {
			
			case 'realname' :
				
				//Get first name
				$first_name	= isset( $criteria['first_name'] ) ? strtolower( $criteria['first_name'] ) : '';
				//Get last name
				$last_name	= isset( $criteria['last_name'] ) ? strtolower( $criteria['last_name'] ) : '';
				
				//Get username using fname and lname
				$username	= $this->woo_slg_username_by_fname_lname( $first_name, $last_name );
				break;
			
			case 'emailbased' :
				
				//Get user email
				$user_email	= isset( $criteria['email'] ) ? $criteria['email'] : '';
				
				//Create username using email
				$username	= $this->woo_slg_username_by_email( $user_email );
				break;
				
			default :
				break;
		}
		
		if( empty( $username ) ) {//If username get empty
									
			if( $criteria['type'] == 'vk' || $criteria['type'] == 'instagram' ) { // if service is vk.com OR instagram then create username with unique id
				$username = $prefix . $criteria['id'];
			} else { // else create create username with random string
				$username = $prefix . wp_rand( 100, 9999999 );
			}
		}
		
		//Apply filter to modify username logic
		$username = apply_filters( 'woo_slg_social_username', $username, $criteria );
		
		//Assign username to temporary variable
		$temp_user_name	= $username;
		
		//Make sure the name is unique: if we've already got a user with this name, append a number to it.
		$counter = 1;
		if ( username_exists( $temp_user_name ) ) {
			
			do {
				$username	= $temp_user_name;
				$counter++;
				$username	= $username . $counter;
			} while ( username_exists( $username ) );
		} else {
			
			$username = $temp_user_name;
		}
		
		return $username;		
	}
	
	
	/**
	 * Check User Exists
	 *
	 * Function to check user is exists or not based on either username or email
	 * for VK and 
	 * for Instragram(only by username, because it can't contain email)
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_check_user_exists($criteria) {
		
		$prefix = WOO_SLG_USER_PREFIX;
		
		if (($criteria['type'] == 'vk' && empty($criteria['email'])) || ($criteria['type'] == 'instagram') ) {
			
			//return username_exists($prefix.$criteria['id']);
			return $this->woo_slg_user_meta_exists( $criteria['id'] );
			
		} else {
			
			return email_exists($criteria['email']);
		}
	}
	
	/**
	 * Get User by email or username
	 *
	 * Function to get user by email or username
	 * for VK and 
	 * for Instragram(only by username, because it can't contain email)
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_get_user_by($criteria) {
		
		$prefix = WOO_SLG_USER_PREFIX;
		
		if ( ($criteria['type'] == 'vk' && empty($criteria['email'])) || ($criteria['type'] == 'instagram') ) {
			
			//return get_user_by('login',$prefix.$criteria['id']);
			return $this->woo_slg_get_user_by_meta( $criteria['id'] );
			
		} else {
			
			return get_user_by('email',$criteria['email']);
		}
	}
	
	/**
	 * User exist from meta
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.4
	 */
	public function woo_slg_user_meta_exists( $criteria_id = '', $only_id = true ) {
		
		//Usermeta prefix
		$user_meta_prefix	= WOO_SLG_USER_META_PREFIX;
		
		$user	= array();
		
		//Get user by meta
		$users	= get_users(
							array(
								'meta_key'		=> $user_meta_prefix . 'unique_id',
								'meta_value'	=> $criteria_id,
								'number'		=> 1,
								'count_total'	=> false
							)
						);
		
		if( !empty( $users ) ) {//If user not empty
			$user	= reset( $users );
		}
		
		return isset( $user->ID ) ? $user->ID : false;
	}
	
	/**
	 * User Data By MetaData
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.4
	 */
	public function woo_slg_get_user_by_meta( $criteria_id ) {
		
		//Usermeta prefix
		$user_meta_prefix	= WOO_SLG_USER_META_PREFIX;
		
		$user	= array();
		
		//Get user by meta
		$users	= get_users(
							array(
								'meta_key'		=> $user_meta_prefix . 'unique_id',
								'meta_value'	=> $criteria_id,
								'number'		=> 1,
								'count_total'	=> false
							)
						);
		
		if( !empty( $users ) ) {//If user not empty
			$user	= reset( $users );
		}
		
		return apply_filters( 'woo_slg_get_user_by_meta', $user, $criteria_id );
	}
	
	/**
	 * Username Using Fname And Lname
	 * 
	 * Handle to create username using api firstname and lastname
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.4
	 */
	public function woo_slg_username_by_fname_lname( $first_name = '', $last_name = '' ) {
		
		//Initilize username
		$username	= '';
		
		if( !empty( $first_name ) ) {//If firstname is not empty
			$username	.= $first_name;
		}
		if( !empty( $last_name ) ) {//If lastname is not empty
			$username	.= '_' . $last_name;
		}
		
		return apply_filters( 'woo_slg_username_by_fname_lname', $username, $first_name, $last_name );
	}
	
	/**
	 * Username Using Email
	 * 
	 * Handle to create username using social email address
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.4
	 */
	public function woo_slg_username_by_email( $user_email = '' ) {
		
		//Initilize username
		$username	= '';
		
		$username	= str_replace( '@', '_', $user_email );
		$username	= str_replace( '.', '_', $username );
		
		return apply_filters( 'woo_slg_username_by_email', $username, $user_email );
	}
	
	/**
	 * Get User profile pic
	 *
	 * Function to get user profile pic from user meta type its social type 
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.1
	 */
	public function woo_slg_get_user_profile_pic( $user_id = false ) {
		
		global $woo_slg_options;
		
		// Taking some defaults
		$profile_pic_url = '';
		
		// If user id is passed then take otherwise take current user
		$user_id = !empty( $user_id ) ? $user_id : '';
		
		if( $user_id ) {
			
			// Getting some user details
			$woo_slg_social_type 	= get_user_meta( $user_id, 'woo_slg_social_user_connect_via', true );
			$woo_slg_data 			= get_user_meta( $user_id, 'woo_slg_social_data', true );
			
			if( !empty($woo_slg_social_type) && !empty($woo_slg_data) ) {
				
				// If facebook avatar is enable
				if( !empty($woo_slg_options['woo_slg_enable_fb_avatar']) && $woo_slg_options['woo_slg_enable_fb_avatar'] == "yes" ) {
					
					// If user is from facebook
					if( $woo_slg_social_type == 'facebook' ) {
						$profile_pic_url = !empty($woo_slg_data['picture']) ? $woo_slg_data['picture'] : '';
					}
				}
				
				// If twitter avatar is enable
				if( isset($woo_slg_options['woo_slg_enable_tw_avatar']) && !empty($woo_slg_options['woo_slg_enable_tw_avatar']) && $woo_slg_options['woo_slg_enable_tw_avatar'] == "yes" ) {
					
					// If user is from twitter
					if( $woo_slg_social_type == 'twitter' ) {
						
						$profile_pic_url = !empty($woo_slg_data->profile_image_url_https) ? $woo_slg_data->profile_image_url_https : '';
					}
				}
				
				// If google plus avatar is enable
				if( !empty($woo_slg_options['woo_slg_enable_gp_avatar']) && $woo_slg_options['woo_slg_enable_gp_avatar'] == "yes" ) {
					
					// If user is from googleplus
					if( $woo_slg_social_type == 'googleplus' ) {
						
						$profile_pic_url = !empty($woo_slg_data['image']['url']) ? $woo_slg_data['image']['url'] : '';
					}
				}
				
				// If linked in avatar is enable
				if( !empty($woo_slg_options['woo_slg_enable_li_avatar']) && $woo_slg_options['woo_slg_enable_li_avatar'] == "yes" ) {
					
					// If user is from linkedin
					if( $woo_slg_social_type == 'linkedin' ) {
						
						$profile_pic_url = '';
						
						if( !empty($woo_slg_data['picture-url']) ) { // Added for backward compitibility
							$profile_pic_url = $woo_slg_data['picture-url'];
						} elseif ( !empty($woo_slg_data['pictureUrl']) ){
							$profile_pic_url = $woo_slg_data['pictureUrl'];
						}
					}
				}
				
				// If yahoo avatar is enable
				if( !empty($woo_slg_options['woo_slg_enable_yh_avatar']) && $woo_slg_options['woo_slg_enable_yh_avatar'] == "yes" ) {
					
					// If user is from yahoo
					if( $woo_slg_social_type == 'yahoo' ) {
						
						$profile_pic_url = !empty($woo_slg_data->image->imageUrl) ? $woo_slg_data->image->imageUrl : '';
					}
				}
				
				// If foursquer avatar is enable
				if( !empty($woo_slg_options['woo_slg_enable_fs_avatar']) && $woo_slg_options['woo_slg_enable_fs_avatar'] == "yes" ) {
					
					// If user is from foursquare
					if( $woo_slg_social_type == 'foursquare' ) {
						$profile_pic_url = $this->foursquare->woo_slg_get_foursquare_profile_picture( array('size' => '64'), $woo_slg_data );
					}
				}
				
				// If vk avatar is enable
				if( !empty($woo_slg_options['woo_slg_enable_vk_avatar']) && $woo_slg_options['woo_slg_enable_vk_avatar'] == "yes" ) {
					
					// If user is from vk
					if( $woo_slg_social_type == 'vk' ) {
						
						$profile_pic_url = !empty($woo_slg_data['photo_big']) ? $woo_slg_data['photo_big'] : '';
					}
				}
				
				// If instagram avatar is enable
				if( !empty($woo_slg_options['woo_slg_enable_inst_avatar']) && $woo_slg_options['woo_slg_enable_inst_avatar'] == "yes" ) {
					
					// If user is from instagram
					if( $woo_slg_social_type == 'instagram' ) {
						
						$profile_pic_url = !empty($woo_slg_data->profile_picture) ? $woo_slg_data->profile_picture : '';
					}
				}
			}
		}
		
		return apply_filters( 'woo_slg_get_user_profile_pic', $profile_pic_url, $user_id );
	}
	
	/**
	 * Common Social Data Convertion
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.3.0
	 */
	public function woo_slg_get_user_common_social_data( $social_data = array(), $social_type = '' ) {
		
		$common_social_data	= array();
		
		if( !empty( $social_type ) ) { // If social type is not empty
			
			switch ( $social_type ) {
				
				case 'facebook' :
					$common_social_data['first_name']	= $social_data['first_name'];
					$common_social_data['last_name']	= $social_data['last_name'];
					$common_social_data['name']			= $social_data['name'];
					$common_social_data['email']		= $social_data['email'];
					$common_social_data['type']			= $social_type;
					$common_social_data['all']			= $social_data;
					$common_social_data['link']			= $social_data['link'];
					$common_social_data['id']			= $social_data['id'];
					break;
					
				case 'googleplus':
					$common_social_data['first_name']	= $social_data['name']['givenName'];
					$common_social_data['last_name']	= $social_data['name']['familyName'];
					$common_social_data['name']			= $social_data['displayName'];
					$common_social_data['email']		= $social_data['email'];
					$common_social_data['type']			= $social_type;
					$common_social_data['all']			= $social_data;
					$common_social_data['link']			= $social_data['url'];
					$common_social_data['id']			= $social_data['id'];
					break;
					
				case 'linkedin' :
					$common_social_data['first_name']	= $social_data['firstName'];
					$common_social_data['last_name']	= $social_data['lastName'];
					$common_social_data['name']			= $social_data['firstName'].' '.$social_data['lastName'];
					$common_social_data['email']		= $social_data['emailAddress'];
					$common_social_data['type']			= $social_type;
					$common_social_data['all']			= $social_data;
					$common_social_data['link']			= $social_data['publicProfileUrl'];
					$common_social_data['id']			= $social_data['id'];
					break;
					
				case 'yahoo' :
					$common_social_data['first_name']	= $social_data->givenName;
					$common_social_data['last_name']	= $social_data->familyName;
					$common_social_data['name']			= $social_data->givenName.' '.$social_data->familyName;
					$common_social_data['email']		= $social_data->yh_primary_email;
					$common_social_data['type']			= $social_type;
					$common_social_data['all']			= $social_data;
					$common_social_data['link']			= $social_data->profileUrl;
					$common_social_data['id']			= $social_data->guid;
					break;
					
				case 'foursquare' :
					$common_social_data['first_name']	= $social_data->firstName;
					$common_social_data['last_name']	= $social_data->lastName;
					$common_social_data['name']			= $social_data->firstName.' '.$social_data->lastName;
					$common_social_data['email']		= $social_data->contact->email;
					$common_social_data['type']			= $social_type;
					$common_social_data['all']			= $social_data;
					$common_social_data['link']			= 'https://foursquare.com/user/' . $social_data->id;
					$common_social_data['id']			= $social_data->id;
					break;
					
				case 'windowslive' :
					$common_social_data['first_name']	= $social_data->first_name;
					$common_social_data['last_name']	= $social_data->last_name;
					$common_social_data['name']			= $social_data->name;
					$common_social_data['email']		= $social_data->wlemail;
					$common_social_data['type']			= $social_type;
					$common_social_data['all']			= $social_data;
					$common_social_data['link']			= $social_data->link;
					$common_social_data['id']			= $social_data->id;
					break;
					
				case 'vk' :
					$common_social_data['first_name']	= $social_data['first_name'];
					$common_social_data['last_name']	= $social_data['last_name'];
					$common_social_data['name']			= $social_data['first_name'] . ' ' . $social_data['last_name'];
					$common_social_data['email']		= isset($social_data['email']) ? $social_data['email'] : '';  
					$common_social_data['type']			= $social_type;
					$common_social_data['all']			= $social_data;
					$common_social_data['link']			= WOO_SLG_VK_LINK . '/' . $social_data['screen_name'];
					$common_social_data['id']			= $social_data['uid'];
					break;
					
				case 'instagram' :
					$common_social_data['first_name']	= $social_data->first_name;
					$common_social_data['last_name']	= $social_data->last_name;
					$common_social_data['name']			= $social_data->username;
					$common_social_data['email']		= '';
					$common_social_data['type']			= $social_type;
					$common_social_data['all']			= $social_data;
					$common_social_data['link']			= $social_data->profile_picture;
					$common_social_data['id']			= $social_data->id;
					break;
					
				case 'twitter' :
					$common_social_data['first_name']	= $social_data->name;
					$common_social_data['last_name']	= '';
					$common_social_data['name']			= $social_data->screen_name; //display name of user
					$common_social_data['type']			= 'twitter';
					$common_social_data['all']			= $social_data;
					$common_social_data['link']			= 'https://twitter.com/' . $social_data->screen_name;
					$common_social_data['id']			= $social_data->id;
					break;
					
				case 'amazon':
					$common_social_data['name']			= $social_data->name; //display name of user
					$common_social_data['id']			= $social_data->user_id;
					$common_social_data['email']		= $social_data->email;
					$common_social_data['all']			= $social_data;	
					$common_social_data['type']			= $social_type;					
				
				case 'paypal' :
					$common_social_data['first_name']	= isset( $social_data->given_name ) ? $social_data->given_name : '';
					$common_social_data['last_name']	= isset( $social_data->family_name ) ? $social_data->family_name : '';
					$common_social_data['email']		= $social_data->email;
					$common_social_data['name']			= isset( $social_data->name ) ? $social_data->name : ''; //display name of user
					$common_social_data['type']			= $social_type;
					$common_social_data['all']			= $social_data;
					$common_social_data['id']			= $social_data->user_id;
			}
		}
		
		return apply_filters( 'woo_slg_get_user_common_social_data', $common_social_data, $social_type );
	}
}