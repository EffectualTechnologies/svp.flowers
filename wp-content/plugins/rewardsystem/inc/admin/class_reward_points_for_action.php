<?php

class RSRewardPointsForAction{
    
    public function __construct() {   
         add_action('init', array($this, 'reward_system_default_settings'));
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_reward_points_for_action', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_reward_points_for_action', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                                        
                        
    }
    
    /*
     * Function to Define Name of the tab.
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_reward_points_for_action'] = __('Reward Points For Action','rewardsystem');
        return $setting_tabs;
    }
    
    /*
     * Function for label Settings in Reward Points For Action.
     */
    public static function reward_system_admin_fields() {
         global $woocommerce;
         return apply_filters('woocommerce_rewardsystem_reward_points_for_action_settings', array(
            array(
                'name' => __('Reward Points for Action Settings', 'rewardsystem'),
                'type' => 'title',                
                'id' => 'rs_reward_points_for_action_setting',
            ),                        
            array(
                'name' => __('Enable Reward Points for Account Signup After First Purchase', 'rewardsystem'),
                'desc' => __('Enable the Reward Points that will be earned for Account Signup after first purchase', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_signup_after_first_purchase',                
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'rs_reward_signup_after_first_purchase',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Reward Points for Account Signup', 'rewardsystem'),
                'desc' => __('Please Enter the Reward Points that will be earned for Account Signup', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_signup',
                'css' => 'min-width:150px;',
                'std' => '1000',
                'default'=>'1000',
                'type' => 'text',
                'newids' => 'rs_reward_signup',
                'desc_tip' => true,
            ),
                        
            array(
                'name' => __('Restrict Reward Points for One Review per Product per Member', 'rewardsystem'),
                'desc' => __('Restrict the Reward Points for Review a Product per User', 'rewardsystem'),
                'id' => 'rs_restrict_reward_product_review',
                'css' => 'min-width:150px;',
                'type' => 'checkbox',
                'newids' => 'rs_restrict_reward_product_review',
                'desc_tip' => true,
            ),  
             
              array(
                'name' => __('Award Points only when User has Purchased the Product', 'rewardsystem'),
                'desc' => __('Enable the checkbox to Earn Reward Points for Product Review If User Purchased the Product', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_for_comment_product_review',                
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'rs_reward_for_comment_product_review',
                'desc_tip' => true,
            ),
             
            array(
                'name' => __('Reward Points for Product Review', 'rewardsystem'),
                'desc' => __('Please Enter the Reward Points that will be earned for Reviewing a Product', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_product_review',
                'css' => 'min-width:150px;',
                'std' => '1000',
                'default'=>'1000',
                'type' => 'text',
                'newids' => 'rs_reward_product_review',
                'desc_tip' => true,
            ),  
             
              array(
                'name' => __('Enable Reward Points for Comment on Blog Post', 'rewardsystem'),
                'desc' => __('Enable the checkbox to Earn Reward Points for Commenting on Blog Post', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_for_comment_Post',                
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'rs_reward_for_comment_Post',
                'desc_tip' => true,
            ),
             
             
              array(
                'name' => __('Reward Points for Comment on Blog Post', 'rewardsystem'),
                'desc' => __('Please Enter the Reward Points that will be earned for Post Comment', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_post_review',
                'css' => 'min-width:150px;',
                'std' => '1000',
                'default'=>'1000',
                'type' => 'text',
                'newids' => 'rs_reward_post_review',
                'desc_tip' => true,
            ), 
             
             array(
                'name' => __('Enable Reward Points for Comment on Page', 'rewardsystem'),
                'desc' => __('Enable the checkbox to Earn Reward Points for Commenting a Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_for_comment_Page',                
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'rs_reward_for_comment_Page',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Reward Points for Comment on Page', 'rewardsystem'),
                'desc' => __('Please Enter the Reward Points that will be earned for Page Comment', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_page_review',
                'css' => 'min-width:150px;',
                'std' => '1000',
                'default'=>'1000',
                'type' => 'text',
                'newids' => 'rs_reward_page_review',
                'desc_tip' => true,
            ), 
             
             
             array(
                'name' => __('Enable Reward Points for Creating a Blog Post', 'rewardsystem'),
                'desc' => __('Enable the checkbox to Earn Reward Points for Creating a Blog Post', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_for_Creating_Post',                
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'rs_reward_for_Creating_Post',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Reward Points for Creating a Blog Post', 'rewardsystem'),
                'desc' => __('Please Enter the Reward Points that will be earned for Posting', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_post',
                'css' => 'min-width:150px;',
                'std' => '1000',
                'default'=>'1000',
                'type' => 'text',
                'newids' => 'rs_reward_post',
                'desc_tip' => true,
            ),     
            array(
                'name' => __('Enable Referral Reward Points for Account Signup after first purchase', 'rewardsystem'),
                'desc' => __('Enable the Referral Reward Points that will be earned for Account Signup after first purchase', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_referral_reward_signup_after_first_purchase',
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'rs_referral_reward_signup_after_first_purchase',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Referral Reward Points for Account Signup', 'rewardsystem'),
                'desc' => __('Please Enter the Referral Reward Points that will be earned for Account Signup', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_referral_reward_signup',
                'css' => 'min-width:150px;',
                'std' => '1000',
                 'default'=>'1000',
                'type' => 'text',
                'newids' => 'rs_referral_reward_signup',
                'desc_tip' => true,
            ),
            array('type'=>'sectionend','id' => 'rs_reward_points_for_action_setting'),
                        
            array(
                'name' => __('Reward Points for login', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Reward Points for the following actions', 'rewardsystem'),
                'id' => '_rs_reward_point_action'
            ),
            array(
                'name' => __('Enable Reward Points for login once per day', 'rewardsystem'),
                'desc' => __('Enable Reward Points for login once per day', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_enable_reward_points_for_login',
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'rs_enable_reward_points_for_login',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Reward Points for login once per day', 'rewardsystem'),
                'desc' => __('Please Enter the Reward Points that will be earned for login once per day', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_points_for_login',
                'css' => 'min-width:150px;',
                'std' => '10',
                'type' => 'text',
                'newids' => 'rs_reward_points_for_login',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_action'),           
            array(
                'name' => __('Payment Gateway Reward Points', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_reward_point_for_payment_gateway',
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_for_payment_gateway'),            
        ));
     }
    
    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSRewardPointsForAction::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSRewardPointsForAction::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSRewardPointsForAction::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
    
    /*
     * Function to Display Lable settings of Payment Gateway in Reward points for Action.
     */    
    
    
}

new RSRewardPointsForAction();
