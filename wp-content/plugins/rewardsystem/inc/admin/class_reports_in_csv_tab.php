<?php

class RSReportsInCsv {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_reports_in_csv', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_reports_in_csv', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_reports_in_csv'] = __('Reports in CSV','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_reports_in_csv_settings', array(
            array(
                'name' => __('Reports in CSV Settings(CSV Exported from here cannot be Imported)', 'rewardsystem'),
                'type' => 'title',                           
                'id' => '_rs_csvreport_setting'
            ),     
            array(
                'name' => __('Export User Points for', 'rewardsystem'),
                'desc' => __('Here you can set whether to Export Reward Points for All Users or Selected Users', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_export_user_report_option',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'All Users', '2' => 'Selected Users'),
                'newids' => 'rs_export_user_report_option',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select the users that you wish to Export Reward Points', 'rewardsystem'),
                'desc' => __('Here you select the users to whom you wish to Export Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_export_users_report_list',
                'css' => 'min-width:400px;',
                'std' => '',
                'type' => 'rs_select_users_report_in_csv',
                'newids' => 'rs_export_users_report_list',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Export User Points from', 'rewardsystem'),
                'desc' => __('Here you can set whether to Export Reward Points for All Time or Selected Date', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_export_report_date_option',
                'class' => 'rs_export_report_date_option',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'All Time', '2' => 'Selected Date'),
                'newids' => 'rs_export_report_date_option',
                'desc_tip' => true,
            ),
            array(
                'type' => 'export_reports',
            ),
           array('type'=>'sectionend', 'id'=>'_rs_csvreport_setting'),
           
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSReportsInCsv::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSReportsInCsv::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSReportsInCsv::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}
new RSReportsInCsv();