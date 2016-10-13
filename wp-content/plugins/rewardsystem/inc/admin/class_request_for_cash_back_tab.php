<?php

class RSRequestForCashBack {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_request_for_cash_back', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_request_for_cash_back', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_request_for_cash_back'] = __('Request For Cash Back','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_request_for_cash_back_settings', array(
            array(
                'name' => __('Cash Back Request', 'rewardsystem'),
                'type' => 'title',                
                'id' => '_rs_request_for_cash_back_setting'
            ),                                  
            array(
                'type' => 'rs_encash_applications_list',
            ),
            array(
                'type' => 'rs_encash_applications_edit_lists',
            ),
           array('type'=>'sectionend', 'id'=>'_rs_request_for_cash_back_setting'),
           
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSRequestForCashBack::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSRequestForCashBack::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSRequestForCashBack::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                if (get_option($settings['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
    }
}
new RSRequestForCashBack();