<?php

class RSSms {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_sms', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_sms', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_sms'] = __('SMS','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_sms_settings', array(
            array(
                'name' => __('SMS Setting', 'rewardsystem'),
                'type' => 'title',              
                'id' => '_rs_sms_setting'
            ),      
            array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',                    
                    'std' => 'no',
                    'id' => 'rs_enable_send_sms_to_user',
                    'desc' => __('Enable this checkbox to send SMS to your Users', 'rewardsystem'),
                    'newids' => 'rs_enable_send_sms_to_user',
                ),
         array(
                'name' => __('Select SMS API', 'rewardsystem'),
                'desc' => __('Here you can choose the sms sending APi', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_sms_sending_api_option',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'Twilio', '2' => 'Nexmo'),
                'newids' => 'rs_sms_sending_api_option',
                'desc_tip' => true,
            ),
         array(
                    'title' => __('Send SMS for Earning Points', 'woocommerce'),
                    'type' => 'checkbox',                    
                    'std' => 'no',
                    'id' => 'rs_send_sms_earning_points',
                    'desc' => __('Enable this checkbox to send SMS When your User Earn Points', 'rewardsystem'),
                    'newids' => 'rs_send_sms_earning_points',
		  
                ),
         array(
                    'title' => __('Send SMS for Redeeming Points', 'woocommerce'),
                    'type' => 'checkbox',                    
                    'std' => 'no',
                    'id' => 'rs_send_sms_redeeming_points',
                    'desc' => __('Enable this checkbox to send SMS When your User Redeems Points', 'rewardsystem'),
                    'newids' => 'rs_send_sms_redeeming_points',
		  
                ),
         array(
                'name' => __('Twilio Account SID', 'rewardsystem'),
                'desc' => __('Enter Twilio Account Id', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_twilio_secret_account_id',
                'css' => 'min-width:550px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_twilio_secret_account_id',
                'desc_tip' => true,
            ),
         array(
                'name' => __('Twilio Account Auth Token', 'rewardsystem'),
                'desc' => __('Enter Twilio Auth Token', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_twilio_auth_token_id',
                'css' => 'min-width:550px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_twilio_auth_token_id',
                'desc_tip' => true,
            ),
         array(
                'name' => __('Twilio From Number', 'rewardsystem'),
                'desc' => __('Enter Twilio From Number', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_twilio_from_number',
                'css' => 'min-width:550px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_twilio_from_number',
                'desc_tip' => true,
            ),
         array(
                'name' => __('Nexmo Key', 'rewardsystem'),
                'desc' => __('Enter Nexmo Key', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_nexmo_key',
                'css' => 'min-width:550px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_nexmo_key',
                'desc_tip' => true,
            ),
         array(
                'name' => __('Nexmo  Secret', 'rewardsystem'),
                'desc' => __('Enter Nexmo Secret', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_nexmo_secret',
                'css' => 'min-width:550px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_nexmo_secret',
                'desc_tip' => true,
            ),
         array(
                'name' => __('SMS Content', 'rewardsystem'),
                'desc' => __('Enter the SMS Content Here', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_points_sms_content',
                'css' => 'min-width:550px;',
                'std' => 'Hi {username}, {rewardpoints} points is in your account use it to make discount {sitelink}',
                'type' => 'textarea',
                'newids' => 'rs_points_sms_content',
                'desc_tip' => true,
            ),         
           array('type'=>'sectionend', 'id'=>'_rs_sms_setting'),
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSSms::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSSms::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSSms::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}
new RSSms();