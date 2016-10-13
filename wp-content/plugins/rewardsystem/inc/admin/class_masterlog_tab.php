<?php

class RSMasterLog {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_masterlog', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_masterlog', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_masterlog'] = __('MasterLog','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
         global $woocommerce;       
         return apply_filters('woocommerce_rewardsystem_myaccount_settings', array(
            array(
                'name' => __('MasterLog', 'rewardsystem'),
                'type' => 'title',                
                'id' => 'rs_masterlog_setting',
            ), 
            array(
                'name' => __('Export Master Log Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_reward_system_export_masterlog_csv'
            ),
            array(
                'name' => __('Export Master Log for', 'rewardsystem'),
                'desc' => __('Here you can set whether to Export Master Log for All Users or Selected Users', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_export_import_masterlog_option',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'All Users', '2' => 'Selected Users'),
                'newids' => 'rs_export_import_masterlog_option',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select the users that you wish to Export Master Log', 'rewardsystem'),
                'desc' => __('Here you select the users to whom you wish to Export the Master Log', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_export_masterlog_users_list',
                'css' => 'min-width:400px;',
                'std' => '',
                'type' => 'rs_select_users_master_log',
                'newids' => 'rs_export_masterlog_users_list',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_system_export_masterlog_csv'),
            array(
                'type' => 'rs_masterlog',
            ),           
            array('type' => 'sectionend'),
            array('type'=>'sectionend', 'id'=>'rs_masterlog_setting'),            
        ));
    }
    
    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSMasterLog::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSMasterLog::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSMasterLog::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
}

new RSMasterLog();