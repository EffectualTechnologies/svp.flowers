<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page Class
 * 
 * Handles Settings Page functionality.
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.2
 */
class WOO_Slg_Settings_Tabs {
	
	var $model;
	public function __construct() {
		
		global $woo_slg_model;
		
		$this->model	= $woo_slg_model;
	}
	
	/**
	 * Settings Tab
	 * 
	 * Adds the Social Login tab to the WooCommerce settings page.
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.2
	 */
	function woo_slg_add_settings_tab( $tabs ) {
		
		$tabs['social-login'] = __( 'Social Login', 'wooslg' );
		
		return $tabs;
	}
	
	/**
	 * Settings Tab Content
	 * 
	 * Adds the settings content to the social login tab.
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.2
	 */
	function woo_slg_settings_tab() {
		
		woocommerce_admin_fields( $this->woo_slg_settings() );
	}
	
	/**
	 * Update Settings
	 * 
	 * Updates the social login options when being saved.
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.2
	 */
	function woo_slg_update_settings() {
		
		global $woo_slg_options;
		
		woocommerce_update_options( $this->woo_slg_settings() );
		
		//Update global variable when update settings
		$woo_slg_options = woo_slg_global_settings();
	}
	
	/**
	 * Register Settings
	 * 
	 * Handels to add settings in settings page
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.2
	 */
	public function woo_slg_settings() {
		
		global $woo_slg_settings;
		
		$select_fblanguage = array( 'en_US' => __( 'English', 'wooslg' ), 'af_ZA' => __( 'Afrikaans', 'wooslg' ), 'sq_AL' => __( 'Albanian', 'wooslg' ), 'ar_AR' => __( 'Arabic', 'wooslg' ), 'hy_AM' => __( 'Armenian', 'wooslg' ), 'eu_ES' => __( 'Basque', 'wooslg' ), 'be_BY' => __( 'Belarusian', 'wooslg' ), 'bn_IN' => __( 'Bengali', 'wooslg' ), 'bs_BA' => __( 'Bosanski', 'wooslg' ), 'bg_BG' => __( 'Bulgarian', 'wooslg' ), 'ca_ES' => __( 'Catalan', 'wooslg' ), 'zh_CN' => __( 'Chinese', 'wooslg' ), 'cs_CZ' => __( 'Czech', 'wooslg' ), 'da_DK' => __( 'Danish', 'wooslg' ), 'fy_NL' => __( 'Dutch', 'wooslg' ), 'eo_EO' => __( 'Esperanto', 'wooslg' ), 'et_EE' => __( 'Estonian', 'wooslg' ), 'et_EE' => __( 'Estonian', 'wooslg' ), 'fi_FI' => __( 'Finnish', 'wooslg' ), 'fo_FO' => __( 'Faroese', 'wooslg' ), 'tl_PH' => __( 'Filipino', 'wooslg' ), 'fr_FR' => __( 'French', 'wooslg' ), 'gl_ES' => __( 'Galician', 'wooslg' ), 'ka_GE' => __( 'Georgian', 'wooslg' ), 'de_DE' => __( 'German', 'wooslg' ), 'zh_CN' => __( 'Greek', 'wooslg' ), 'he_IL' => __( 'Hebrew', 'wooslg' ), 'hi_IN' => __( 'Hindi', 'wooslg' ), 'hr_HR' => __( 'Hrvatski', 'wooslg' ), 'hu_HU' => __( 'Hungarian', 'wooslg' ), 'is_IS' => __( 'Icelandic', 'wooslg' ), 'id_ID' => __( 'Indonesian', 'wooslg' ), 'ga_IE' => __( 'Irish', 'wooslg' ), 'it_IT' => __( 'Italian', 'wooslg' ), 'ja_JP' => __( 'Japanese', 'wooslg' ), 'ko_KR' => __( 'Korean', 'wooslg' ), 'ku_TR' => __( 'Kurdish', 'wooslg' ), 'la_VA' => __( 'Latin', 'wooslg' ), 'lv_LV' => __( 'Latvian', 'wooslg' ), 'fb_LT' => __( 'Leet Speak', 'wooslg' ), 'lt_LT' => __( 'Lithuanian', 'wooslg' ), 'mk_MK' => __( 'Macedonian', 'wooslg' ), 'ms_MY' => __( 'Malay', 'wooslg' ), 'ml_IN' => __( 'Malayalam', 'wooslg' ), 'nl_NL' => __( 'Nederlands', 'wooslg' ), 'ne_NP' => __( 'Nepali', 'wooslg' ), 'nb_NO' => __( 'Norwegian', 'wooslg' ), 'ps_AF' => __( 'Pashto', 'wooslg' ), 'fa_IR' => __( 'Persian', 'wooslg' ), 'pl_PL' => __( 'Polish', 'wooslg' ), 'pt_PT' => __( 'Portugese', 'wooslg' ), 'pa_IN' => __( 'Punjabi', 'wooslg' ), 'ro_RO' => __( 'Romanian', 'wooslg' ), 'ru_RU' => __( 'Russian', 'wooslg' ), 'sk_SK' => __( 'Slovak', 'wooslg' ), 'sl_SI' => __( 'Slovenian', 'wooslg' ), 'es_LA' => __( 'Spanish', 'wooslg' ), 'sr_RS' => __( 'Srpski', 'wooslg' ), 'sw_KE' => __( 'Swahili', 'wooslg' ), 'sv_SE' => __( 'Swedish', 'wooslg' ), 'ta_IN' => __( 'Tamil', 'wooslg' ), 'te_IN' => __( 'Telugu', 'wooslg' ), 'th_TH' => __( 'Thai', 'wooslg' ), 'tr_TR' => __( 'Turkish', 'wooslg' ), 'uk_UA' => __( 'Ukrainian', 'wooslg' ), 'vi_VN' => __( 'Vietnamese', 'wooslg' ), 'cy_GB' => __( 'Welsh', 'wooslg' ), 'zh_TW' => __( 'Traditional Chinese Language', 'wooslg' ) );
		
		$woo_slg_settings = array(
				array(
					'id'	=> 'woo_slg_settings',
					'title' => __( 'Social Login Options', 'wooslg' ),
					'type' 	=> 'title',
					'desc' 	=> ''
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_settings'
				),
				
				//General Settings
				array(
					'id'	=> 'woo_slg_general_settings',
					'title' => __( 'General Settings', 'wooslg' ),
					'desc' 	=> __( 'Configure Social Login General Settings', 'wooslg' ),
					'type'	=> 'title'
				),
				array(
					'id'		=> 'woo_slg_delete_options',
					'name'		=> __( 'Delete Options:', 'wooslg' ),
					'desc'		=> '',
					'type'		=> 'checkbox',
					'desc_tip'	=> '<p class="description">'.__( 'If you don\'t want to use the Social Login Plugin on your site anymore, you can check that box. This makes sure, that all the settings and tables are being deleted from the database when you deactivate the plugin.','wooslg' ).'</p>'
				),
				array(
					'id'		=> 'woo_slg_enable_login_page',
					'name'		=> __( 'Display Social Login buttons on Login page:', 'wooslg' ),
					'desc'		=> '',
					'type'		=> 'checkbox',
					'desc_tip'	=> '<p class="description">'.__( 'Check this box to add social login buttons on woocommerce login page and default wordpress login page.','wooslg' ).'</p>'
				),
				array(
					'id'		=> 'woo_slg_display_link_thank_you',
					'name'		=> __( 'Display "Link Your Account" button on Thank You page:', 'wooslg' ),
					'desc'		=> '',
					'type'		=> 'checkbox',
					'desc_tip'	=> '<p class="description">'.__( ' Check this box to allow customers to link their social account on the Thank You page for faster login & checkout next time they purchase.','wooslg' ).'</p>'
				),/*
				array(
					'id'		=> 'woo_slg_enable_login_page',
					'title'		=> __( 'Enable On Admin Login Page :', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to add social login option on default wordpress login page.', 'wooslg' ),
					'type'		=> 'checkbox'
				),*/
				array(
					'id'		=> 'woo_slg_login_heading',
					'title'		=> __( 'Social Login Title:', 'wooslg' ),
					'desc_tip' 	=> __( 'Enter Social Login Title.', 'wooslg' ),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_enable_notification',
					'title'		=> __( 'Enable Email Notification:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to notify admin and user when user is registered by social media.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_redirect_url',
					'title'		=> __( 'Redirect URL:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter a redirect URL for users after they login with social media. The URL must start with', 'wooslg' ).' http://',
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_base_reg_username',
					'name'	=> __( 'Autoregistered Usernames:', 'wooslg' ),
					'desc'	=> '',
					'type'	=> 'radio',
					'size'	=> 'regular',
					'std' 	=> '',
					'options'=> array(
						''				=> __( 'Based on unique ID & random number ( i.e. woo_slg_123456 )', 'wooslg' ),
						'realname'		=> __( 'Based on real name ( i.e. john_smith )', 'wooslg' ),
						'emailbased'	=> __( 'Based on email ID ( i.e. john.smith@example.com to john_smith_example_com )', 'wooslg' )
					)
				),
				array(
					'id'		=> 'woo_slg_enable_expand_collapse',
					'name'		=> __( 'Expand/Collapse Buttons:', 'wooslg' ),
					'desc'		=> '<br />'. __('Here you can select how to show the social login buttons.','wooslg'),
					'type'		=> 'select',
					'class'		=> '',
				    'std' 		=> '',
					'default' 	=> '',
					'options' 	=> array( '' => __('None','wooslg'), 'collapse' => __('Collapse','wooslg'), 'expand' => __('Expand','wooslg') )	
				),
				array(
					'id'	=> 'woo_slg_social_btn_type',
					'name'	=> __( 'Social Buttons Image/Text:', 'wooslg' ),
					'desc'	=> '',
					'type'	=> 'radio',
					'size'	=> 'regular',
					'default'	=> '0',
					'class' 	=> 'woo_slg_social_btn_change',
					'options'	=> array(
						'0'	=> __( 'Use image as buttons', 'wooslg' ),
						'1'	=> __( 'Use text as buttons', 'wooslg' )
					)
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_general_settings'
				),
				
				//Facebbok Settings
				array(
					'id'		=> 'woo_slg_facebook_settings',
					'title'		=> __( 'Facebook Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login Facebook Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'	=> 'woo_slg_facebook_desc',
					'title'	=> __( 'Facebook Application:', 'wooslg' ),
					'desc'	=> __( 'Before you can start using Facebook for the social login, you need to create a Facebook Application. You can get a step by step tutorial on how to create Facebook Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/facebook/">'. __( 'Documentation', 'wooslg' ). '</a>',
					'type'	=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_facebook',
					'title'		=> __( 'Enable Facebook:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable facebook social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_fb_app_id',
					'title'		=> __( 'Facebook App ID/API Key:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Facebook API Key.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_fb_app_secret',
					'title'		=> __( 'Facebook App Secret:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Facebook App Secret.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_fb_language',
					'title'		=> __( 'Facebook API Locale:', 'wooslg' ),
					'desc_tip'	=> __( 'Select the language for Facebook. With this option, you can explicitly tell which language you want to use for communicating with Facebook.', 'wooslg' ),
					'type'		=> 'select',
					'options'	=> $select_fblanguage
				),
				array(
					'id'	=> 'woo_slg_fb_icon_url',
					'title'	=> __( 'Custom Facebook Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Facebook Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'	=> 'woo_slg_fb_link_icon_url',
					'title'	=> __( 'Custom Facebook Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Facebook Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_fb_icon_text',
					'title'		=> __( 'Custom Facebook Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Facebook Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with Facebook',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_fb_link_icon_text',
					'title'		=> __( 'Custom Facebook Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Facebook Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to Facebook',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_enable_fb_avatar',
					'title'		=> __( 'Enable Facebook Avatar:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to use Facebook profile pictures as avatars.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_facebook_settings'
				),
				
				//Google+ Settings
				array(
					'id'		=> 'woo_slg_googleplus_settings',
					'title'		=> __( 'Google+ Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login Google+ Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'		=> 'woo_slg_googleplus_desc',
					'title'		=> __( 'Google+ Application:', 'wooslg' ),
					'desc'		=> __( 'Before you can start using Google+ for the social login, you need to create a Google+ Application. You can get a step by step tutorial on how to create Google+ Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/google/">' . __( 'Documentation', 'wooslg' ) . '</a>',
					'type'		=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_googleplus',
					'title'		=> __( 'Enable Google+:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable google+ social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_gp_client_id',
					'title'		=> __( 'Google+ Client ID:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Google+ Client ID.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_gp_client_secret',
					'title'		=> __( 'Google+ Client Secret:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Google+ Client Secret.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_gp_redirect_url',
					'title'		=> __( 'Google+ Callback URL:', 'wooslg' ),
					'desc'		=> __( '<code>'.WOO_SLG_GP_REDIRECT_URL.'</code>', 'wooslg' ),
					'type'		=> 'wooslg_desc',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_gp_icon_url',
					'title'	=> __( 'Custom Google+ Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Google+ Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'	=> 'woo_slg_gp_link_icon_url',
					'title'	=> __( 'Custom Google+ Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Google+ Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_gp_icon_text',
					'title'		=> __( 'Custom Google+ Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Google+ Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with Google+',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_gp_link_icon_text',
					'title'		=> __( 'Custom Google+ Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Google+ Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to Google+',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_enable_gp_avatar',
					'title'		=> __( 'Enable Google Plus Avatar:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to use Google Plus profile pictures as avatars.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_googleplus_settings'
				),
				
				//LinkedIn Settings
				array(
					'id'		=> 'woo_slg_linkedin_settings',
					'title'		=> __( 'LinkedIn Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login LinkedIn Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'		=> 'woo_slg_linkedin_desc',
					'title'		=> __( 'LinkedIn Application:', 'wooslg' ),
					'desc'		=> __( 'Before you can start using LinkedIn for the social login, you need to create a LinkedIn Application. You can get a step by step tutorial on how to create LinkedIn Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/linkedin/">' . __( 'Documentation', 'wooslg' ) . '</a>',
					'type'		=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_linkedin',
					'title'		=> __( 'Enable LinkedIn:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable LinkedIn social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_li_app_id',
					'title'		=> __( 'LinkedIn App ID/API Key:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter LinkedIn App ID/API Key.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_li_app_secret',
					'title'		=> __( 'LinkedIn App Secret:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter LinkedIn App Secret.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_li_redirect_url',
					'title'		=> __( 'LinkedIn Callback URL:', 'wooslg' ),
					'desc'		=> __( '<code>'.WOO_SLG_LI_REDIRECT_URL.'</code>', 'wooslg' ),
					'type'		=> 'wooslg_desc',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_li_icon_url',
					'title'	=> __( 'Custom LinkedIn Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own LinkedIn Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'	=> 'woo_slg_li_link_icon_url',
					'title'	=> __( 'Custom LinkedIn Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own LinkedIn Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_li_icon_text',
					'title'		=> __( 'Custom LinkedIn Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own LinkedIn Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with LinkedIn',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_li_link_icon_text',
					'title'		=> __( 'Custom LinkedIn Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own LinkedIn Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to LinkedIn',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_enable_li_avatar',
					'title'		=> __( 'Enable LinkedIn Avatar:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to use LinkedIn profile pictures as avatars.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_linkedin_settings'
				),
				
				//twitter Settings
				array(
					'id'		=> 'woo_slg_twitter_settings',
					'title'		=> __( 'Twitter Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login Twitter Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'		=> 'woo_slg_twitter_desc',
					'title'		=> __( 'Twitter Application:', 'wooslg' ),
					'desc'		=> __( 'Before you can start using Twitter for the social login, you need to create a Twitter Application. You can get a step by step tutorial on how to create Twitter Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/twitter/">' . __( 'Documentation', 'wooslg' ) . '</a>',
					'type'		=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_twitter',
					'title'		=> __( 'Enable Twitter:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable Twitter social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_tw_consumer_key',
					'title'		=> __( 'Twitter API Key:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Twitter API Key.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_tw_consumer_secret',
					'title'		=> __( 'Twitter API Secret:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Twitter API Secret.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_tw_icon_url',
					'title'	=> __( 'Custom Twitter Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Twitter Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'	=> 'woo_slg_tw_link_icon_url',
					'title'	=> __( 'Custom Twitter Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Twitter Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_tw_icon_text',
					'title'		=> __( 'Custom Twitter Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Twitter Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with Twitter',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_tw_link_icon_text',
					'title'		=> __( 'Custom Twitter Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Twitter Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to Twitter',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_enable_tw_avatar',
					'title'		=> __( 'Enable Twitter Avatar:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to use Twitter profile pictures as avatars.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_twitter_settings'
				),
				
				//yahoo Settings
				array(
					'id'		=> 'woo_slg_yahoo_settings',
					'title'		=> __( 'Yahoo Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login Yahoo Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'		=> 'woo_slg_yahoo_desc',
					'title'		=> __( 'Yahoo Application:', 'wooslg' ),
					'desc'		=> __( 'Before you can start using Yahoo for the social login, you need to create a Yahoo Application. You can get a step by step tutorial on how to create Yahoo Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/yahoo/">' . __( 'Documentation', 'wooslg' ) . '</a>',
					'type'		=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_yahoo',
					'title'		=> __( 'Enable Yahoo:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable Yahoo social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_yh_consumer_key',
					'title'		=> __( 'Yahoo Consumer Key:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Yahoo Consumer Key.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_yh_consumer_secret',
					'title'		=> __( 'Yahoo Consumer Secret:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Yahoo Consumer Secret.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_yh_app_id',
					'title'		=> __( 'Yahoo App Id:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Yahoo App Id.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_yh_redirect_url',
					'title'		=> __( 'Yahoo Callback URL:', 'wooslg' ),
					'desc'		=> __( '<code>'.WOO_SLG_YH_REDIRECT_URL.'</code>', 'wooslg' ),
					'type'		=> 'wooslg_desc',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_yh_icon_url',
					'title'	=> __( 'Custom Yahoo Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Yahoo Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'	=> 'woo_slg_yh_link_icon_url',
					'title'	=> __( 'Custom Yahoo Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Yahoo Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_yh_icon_text',
					'title'		=> __( 'Custom Yahoo Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Yahoo Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with Yahoo',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_yh_link_icon_text',
					'title'		=> __( 'Custom Yahoo Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Yahoo Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to Yahoo',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_enable_yh_avatar',
					'title'		=> __( 'Enable Yahoo Avatar:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to use Yahoo profile pictures as avatars.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_yahoo_settings'
				),
				
				//Foursquare Settings
				array(
					'id'		=> 'woo_slg_foursquare_settings',
					'title'		=> __( 'Foursquare Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login Foursquare Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'		=> 'woo_slg_foursquare_desc',
					'title'		=> __( 'Foursquare Application:', 'wooslg' ),
					'desc'		=> __( 'Before you can start using Foursquare for the social login, you need to create a Foursquare Application. You can get a step by step tutorial on how to create Foursquare Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/foursquare/">' . __( 'Documentation', 'wooslg' ) . '</a>',
					'type'		=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_foursquare',
					'title'		=> __( 'Enable Foursquare:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable Foursquare social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_fs_client_id',
					'title'		=> __( 'Foursquare Client ID:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Foursquare Client ID.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_fs_client_secret',
					'title'		=> __( 'Foursquare Client Secret:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Foursquare Client Secret.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_fs_icon_url',
					'title'	=> __( 'Custom Foursquare Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Foursquare Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'	=> 'woo_slg_fs_link_icon_url',
					'title'	=> __( 'Custom Foursquare Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Foursquare Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_fs_icon_text',
					'title'		=> __( 'Custom Foursquare Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Foursquare Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with Foursquare',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_fs_link_icon_text',
					'title'		=> __( 'Custom Foursquare Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Foursquare Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to Foursquare',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_enable_fs_avatar',
					'title'		=> __( 'Enable Foursquare Avatar:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to use Foursquare profile pictures as avatars.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_foursquare_settings'
				),
				
				//Windows Live Settings
				array(
					'id'		=> 'woo_slg_windowslive_settings',
					'title'		=> __( 'Windows Live Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login Windows Live Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'		=> 'woo_slg_windowslive_desc',
					'title'		=> __( 'Windows Live Application:', 'wooslg' ),
					'desc'		=> __( 'Before you can start using Windows Live for the social login, you need to create a Windows Live Application. You can get a step by step tutorial on how to create Windows Live Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/windows_live/">' . __( 'Documentation', 'wooslg' ) . '</a>',
					'type'		=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_windowslive',
					'title'		=> __( 'Enable Windows Live:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable Windows Live social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_wl_client_id',
					'title'		=> __( 'Windows Live Client ID:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Windows Live Client ID.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_wl_client_secret',
					'title'		=> __( 'Windows Live Client Secret:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Windows Live Client Secret.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_wl_redirect_url',
					'title'		=> __( 'Windows Live Callback URL:', 'wooslg' ),
					'desc'		=> __( '<code>'.site_url().'</code>', 'wooslg' ),
					'type'		=> 'wooslg_desc',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_wl_icon_url',
					'title'	=> __( 'Custom Windows Live Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Windows Live Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'	=> 'woo_slg_wl_link_icon_url',
					'title'	=> __( 'Custom Windows Live Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Windows Live Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_wl_icon_text',
					'title'		=> __( 'Custom Windows Live Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Windows Live Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with Windows Live',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_wl_link_icon_text',
					'title'		=> __( 'Custom Windows Live Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Windows Live Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to Windows Live',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_windowslive_settings'
				),
				
				//VK Settings
				array(
					'id'		=> 'woo_slg_vk_settings',
					'title'		=> __( 'VK Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login VK Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'		=> 'woo_slg_vk_desc',
					'title'		=> __( 'VK Application:', 'wooslg' ),
					'desc'		=> __( 'Before you can start using VK for the social login, you need to create a VK Application. You can get a step by step tutorial on how to create VK Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/vk/">' . __( 'Documentation', 'wooslg' ) . '</a>',
					'type'		=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_vk',
					'title'		=> __( 'Enable VK:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable vk social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_vk_app_id',
					'title'		=> __( 'VK Application ID:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter VK Application ID.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_vk_app_secret',
					'title'		=> __( 'VK Secret Key:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter VK Secret Key.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_vk_icon_url',
					'title'	=> __( 'Custom VK Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own VK Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'	=> 'woo_slg_vk_link_icon_url',
					'title'	=> __( 'Custom VK Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own VK Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_vk_icon_text',
					'title'		=> __( 'Custom VK Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own VK Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with VK.com',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_vk_link_icon_text',
					'title'		=> __( 'Custom VK Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own VK Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to VK.com',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_enable_vk_avatar',
					'title'		=> __( 'Enable VK Avatar:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to use VK profile pictures as avatars.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_vk_settings'
				),
				
				//Instagram Settings
				array(
					'id'		=> 'woo_slg_instagram_settings',
					'title'		=> __( 'Instagram Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login Instagram Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'		=> 'woo_slg_instagram_desc',
					'title'		=> __( 'Instagram Application:', 'wooslg' ),
					'desc'		=> __( 'Before you can start using Instagram for the social login, you need to create a Instagram Application. You can get a step by step tutorial on how to create Instagram Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/instagram/">' . __( 'Documentation', 'wooslg' ) . '</a>',
					'type'		=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_instagram',
					'title'		=> __( 'Enable Instagram:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable Instagram social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_inst_client_id',
					'title'		=> __( 'Instagram Client ID:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Instagram Client ID.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_inst_client_secret',
					'title'		=> __( 'Instagram Client Secret:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Instagram Client Secret.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_inst_redirect_url',
					'title'		=> __( 'Instagram Callback URL:', 'wooslg' ),
					'desc'		=> __( '<code>'.WOO_SLG_INST_REDIRECT_URL.'</code>', 'wooslg' ),
					'type'		=> 'wooslg_desc',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_inst_icon_url',
					'title'	=> __( 'Custom Instagram Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Instagram Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),				
				array(
					'id'	=> 'woo_slg_inst_link_icon_url',
					'title'	=> __( 'Custom Instagram Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Instagram Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_inst_icon_text',
					'title'		=> __( 'Custom Instagram Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Instagram Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with Instagram',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_inst_link_icon_text',
					'title'		=> __( 'Custom Instagram Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Instagram Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to Instagram',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_enable_inst_avatar',
					'title'		=> __( 'Enable Instagram Avatar:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to use Instagram profile pictures as avatars.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_instagram_settings'
				),
				
				
				//Amazon Settings
				array(
					'id'		=> 'woo_slg_amazon_settings',
					'title'		=> __( 'Amazon Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login Amazon Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'		=> 'woo_slg_amazon_desc',
					'title'		=> __( 'Amazon Application:', 'wooslg' ),
					'desc'		=> __( 'Before you can start using Amazon for the social login, you need to create a Amazon Application. You can get a step by step tutorial on how to create Amazon Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/amazon/">' . __( 'Documentation', 'wooslg' ) . '</a>',
					'type'		=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_amazon',
					'title'		=> __( 'Enable Amazon:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable Amazon social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_amazon_client_id',
					'title'		=> __( 'Amazon Client ID:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Amazon Client ID.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_amazon_client_secret',
					'title'		=> __( 'Amazon Client Secret:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Amazon Client Secret.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_amazon_redirect_url',
					'title'		=> __( 'Amazon Callback URL:', 'wooslg' ),
					'desc'		=> __( '<code>'.WOO_SLG_AMAZON_REDIRECT_URL.'</code>', 'wooslg' ),
					'type'		=> 'wooslg_desc',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_amazon_icon_url',
					'title'	=> __( 'Custom Amazon Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Amazon Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),				
				array(
					'id'	=> 'woo_slg_amazon_link_icon_url',
					'title'	=> __( 'Custom Amazon Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Amazon Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_amazon_icon_text',
					'title'		=> __( 'Custom Amazon Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Amazon Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with Amazon',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_amazon_link_icon_text',
					'title'		=> __( 'Custom Amazon Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Amazon Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to Amazon',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_amazon_settings'
				),
				
				
				//paypal Settings
				array(
					'id'		=> 'woo_slg_paypal_settings',
					'title'		=> __( 'Paypal Settings', 'wooslg' ),
					'desc_tip'	=> __( 'Configure Social Login Paypal Settings', 'wooslg' ),
					'type'		=> 'title'
				),
				array(
					'id'		=> 'woo_slg_payapl_desc',
					'title'		=> __( 'Paypal Application:', 'wooslg' ),
					'desc'		=> __( 'Before you can start using Paypal for the social login, you need to create a Paypal Application. You can get a step by step tutorial on how to create Paypal Application on our', 'wooslg' ) . ' <a target="_blank" href="http://wpweb.co.in/documents/social-network-integration/paypal/">' . __( 'Documentation', 'wooslg' ) . '</a>',
					'type'		=> 'wooslg_desc'
				),
				array(
					'id'		=> 'woo_slg_enable_paypal',
					'title'		=> __( 'Enable paypal:', 'wooslg' ),
					'desc'		=> __( 'Check this box, if you want to enable Paypal social login registration.', 'wooslg' ),
					'type'		=> 'checkbox'
				),
				array(
					'id'		=> 'woo_slg_paypal_client_id',
					'title'		=> __( 'Paypal Client ID:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Paypal Client ID.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_paypal_client_secret',
					'title'		=> __( 'Paypal Client Secret:', 'wooslg' ),
					'desc_tip'	=> __( 'Enter Paypal Client Secret.', 'wooslg'),
					'type'		=> 'text',
					'class' 	=> 'regular-text'
				),
				array(
					'id'		=> 'woo_slg_paypal_redirect_url',
					'title'		=> __( 'Paypal Callback URL:', 'wooslg' ),
					'desc'		=> __( '<code>'.WOO_SLG_PAYPAL_REDIRECT_URL.'</code>', 'wooslg' ),
					'type'		=> 'wooslg_desc',
					'class' 	=> 'regular-text'
				),
				array(
					'id'	=> 'woo_slg_paypal_icon_url',
					'title'	=> __( 'Custom paypal Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Paypal Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'	=> 'woo_slg_paypal_link_icon_url',
					'title'	=> __( 'Custom Paypal Link Icon:', 'wooslg' ),
					'desc'	=> __( 'If you want to use your own Paypal Link Icon, upload one here.', 'wooslg' ),
					'type'	=> 'wooslg_upload_file',
					'class'	=> 'woo_slg_social_btn_image'
				),
				array(
					'id'		=> 'woo_slg_paypal_icon_text',
					'title'		=> __( 'Custom Paypal Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Paypal Text, Enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Sign in with Paypal',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_paypal_link_icon_text',
					'title'		=> __( 'Custom Paypal Link Text:', 'wooslg' ),
					'desc_tip'	=> __( 'If you want to use your own Paypal Link Text, enter here.', 'wooslg' ),
					'type'		=> 'text',
					'default'	=> 'Link your account to Paypal',
					'class'		=> 'regular-text woo_slg_social_btn_text'
				),
				array(
					'id'		=> 'woo_slg_paypal_environment',
					'title'		=> __( 'Environment:', 'wooslg' ),
					'desc_tip'		=> __('Select which environment to process logins under.', 'wooslg'),
					'type'		=> 'select',					
					'options' 	=> array( 'live' => __('Live','wooslg'), 'sandbox' => __('Sandbox','wooslg') )	
				),
				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'woo_slg_paypal_settings'
				)
				
			);
			
			return $woo_slg_settings;
	}
	
	/**
	 * Validate Settings
	 * 
	 * Handles to validate settings
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.1.1
	 */
	/*function woo_slg_settings_validate( $input ) {
		
		// General Settings
		$input['woo_slg_login_heading'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_login_heading'] );
		$input['woo_slg_redirect_url'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_redirect_url'] );
		
		// Facebook Settings
		$input['woo_slg_fb_app_id'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_fb_app_id'] );
		$input['woo_slg_fb_app_secret'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_fb_app_secret'] );
		$input['woo_slg_fb_icon_url'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_fb_icon_url'] );
		$input['woo_slg_fb_link_icon_url'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_fb_link_icon_url'] );
		
		// Google+ Settings
		$input['woo_slg_gp_client_id'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_gp_client_id'] );
		$input['woo_slg_gp_client_secret'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_gp_client_secret'] );
		$input['woo_slg_gp_icon_url'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_gp_icon_url'] );
		$input['woo_slg_gp_link_icon_url'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_gp_link_icon_url'] );
		
		// LinkedIn Settings
		$input['woo_slg_li_app_id'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_li_app_id'] );
		$input['woo_slg_li_app_secret'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_li_app_secret'] );
		$input['woo_slg_li_icon_url'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_li_icon_url'] );
		
		// Twitter Settings
		$input['woo_slg_tw_consumer_key'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_tw_consumer_key'] );
		$input['woo_slg_tw_consumer_secret']= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_tw_consumer_secret'] );
		$input['woo_slg_tw_icon_url'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_tw_icon_url'] );
		
		// Yahoo Settings
		$input['woo_slg_yh_consumer_key'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_yh_consumer_key'] );
		$input['woo_slg_yh_consumer_secret']= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_yh_consumer_secret'] );
		$input['woo_slg_yh_app_id'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_yh_app_id'] );
		$input['woo_slg_yh_icon_url'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_yh_icon_url'] );
		
		// Foursquare Settings
		$input['woo_slg_fs_client_id'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_fs_client_id'] );
		$input['woo_slg_fs_client_secret'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_fs_client_secret'] );
		$input['woo_slg_fs_icon_url'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_fs_icon_url'] );
		
		// Windows Live Settings
		$input['woo_slg_wl_client_id'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_wl_client_id'] );
		$input['woo_slg_wl_client_secret'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_wl_client_secret'] );
		$input['woo_slg_wl_icon_url'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_wl_icon_url'] );
		
		// VK Settings
		$input['woo_slg_vk_app_id'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_vk_app_id'] );
		$input['woo_slg_vk_app_secret'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_vk_app_secret'] );
		$input['woo_slg_vk_icon_url'] 		= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_vk_icon_url'] );
		
		// Instagram Settings
		$input['woo_slg_inst_client_id'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_inst_client_id'] );
		$input['woo_slg_inst_client_secret']= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_inst_client_secret'] );
		$input['woo_slg_inst_icon_url'] 	= $this->model->woo_slg_escape_slashes_deep( $input['woo_slg_inst_icon_url'] );
		
		return $input;
	}*/
	
	/**
	 * Upload Callback
	 * 
	 * Renders Woo social login wooslg_desc fields.
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	function woo_slg_desc_callback( $field ) {
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
			</th>
			<td>
				<?php echo $field['desc']; ?>
			</td>
		</tr><?php
	}
	
	/**
	 * Upload Callback
	 * 
	 * Renders upload fields.
	 * 
	 * @since 1.0.0
	 * @package WooCommerce - Social Login
	 */
	function woo_slg_upload_file_callback( $field ) {
		
		if ( isset( $field['title'] ) && isset( $field['id'] ) ) {
			
			$filetype	= isset( $field['options'] ) ? $field['options'] : '';
			$file_val	= get_option( $field['id']);
			$file_val	= !empty($file_val) ? $file_val : '';
			$file_class	= !empty( $field['class'] ) ? 'class="'.$field['class'].'"' : '';
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<fieldset>
						<input name="<?php echo esc_attr( $field['id']  ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" <?php echo $file_class; ?> type="text" value="<?php echo esc_attr( $file_val ); ?>" style="min-width: 300px;"/><?php echo $filetype;?>
						<input type="button" class="woo-slg-upload-file-button button-secondary" value="<?php _e( 'Upload File', 'wooslg' );?>"/>
					</fieldset>
					<span class="description"><?php echo $field['desc'];?></span>
				</td>
			</tr><?php
		}
	}
	
	/**
	 * Adding Hooks
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.2
	 */
	public function add_hooks() {
		
		//add social login tab to woocommerce setting page
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'woo_slg_add_settings_tab'), 99 );
		
		//add social login tab content
		add_action( 'woocommerce_settings_tabs_social-login', array( $this, 'woo_slg_settings_tab') );
		
		//save custom update content
		add_action( 'woocommerce_update_options_social-login', array( $this, 'woo_slg_update_settings'), 100 );
		
		//add social login tab content for custom field
		add_action( 'woocommerce_admin_field_wooslg_desc', array( $this, 'woo_slg_desc_callback' ) );
		
		//add social login tab upload content for custom field.
		add_action( 'woocommerce_admin_field_wooslg_upload_file', array( $this, 'woo_slg_upload_file_callback' ) );
		
		// call custom function before save options in database
		//add_filter( 'woocommerce_settings_tabs_social-login_sanitize', array( $this, 'woo_slg_settings_validate') );
		
	}
}