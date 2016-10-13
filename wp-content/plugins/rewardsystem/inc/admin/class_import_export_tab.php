<?php

class RSImportExport {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_import_export', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_import_export', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_import_export'] = __('Import/Export Points in CSV','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_gift_voucher_settings', array(
            array(
                'name' => __('Import/Export User Points in CSV Format', 'rewardsystem'),
                'type' => 'title',                
                'id' => '_rs_import_export_setting'
            ),                       
            array(
                'name' => __('Export User Points for', 'rewardsystem'),
                'desc' => __('Here you can set whether to Export Reward Points for All Users or Selected Users', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_export_import_user_option',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'All Users', '2' => 'Selected Users'),
                'newids' => 'rs_export_import_user_option',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select the users that you wish to Export Reward Points', 'rewardsystem'),
                'desc' => __('Here you select the users to whom you wish to Export Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_import_export_users_list',
                'css' => '',
                'std' => '',
                'type' => 'rs_import_export_selected_user',
                'newids' => 'rs_import_export_users_list',
                'desc_tip' => true,
            ),
            array(
                'name' => __('CSV Format', 'rewardsystem'),
                'desc' => __('Here you can set whether to Export CSV Format with Username or Userid or Emailid', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_csv_format',
                'class' => 'rs_csv_format',
                'newids' => 'rs_csv_format',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'Username/Points', '2' => 'Email-Id/Points'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Export User Points from', 'rewardsystem'),
                'desc' => __('Here you can set whether to Export Reward Points for All Time or Selected Date', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_export_import_date_option',
                'class' => 'rs_export_import_date_option',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'All Time', '2' => 'Selected Date'),
                'newids' => 'rs_export_import_date_option',
                'desc_tip' => true,
            ),
            array(
                'type' => 'import_export',
            ),            
           array('type'=>'sectionend', 'id'=>'_rs_import_export_setting'),
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSImportExport::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSImportExport::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSImportExport::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
}
new RSImportExport();