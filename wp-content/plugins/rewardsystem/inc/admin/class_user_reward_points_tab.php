<?php

class RSUserRewardPoints {
    
    public function __construct() {   
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_user_reward_points', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_user_reward_points', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
        
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_user_reward_points'] = __('User Reward Points','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
         global $woocommerce;
         return apply_filters('woocommerce_rewardsystem_user_reward_points_settings', array(
            array(
                'name' => __('User Reward Points', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Following List shows the Total Reward Points for each User', 'rewardsystem'),
                'id' => 'rs_user_reward_points_setting',
            ),
             array(
                 'type'=>'rs_wplist_for_user_reward_points',
             ),
        ));
    }
    
    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSUserRewardPoints::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSUserRewardPoints::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSUserRewardPoints::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}

new RSUserRewardPoints();