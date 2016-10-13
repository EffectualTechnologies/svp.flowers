<?php

class RSMail {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_mail', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_mail', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_mail'] = __('Mail','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        $checkmandrill = 'wpmandrill/wpmandrill.php';
        if (function_exists('is_plugin_active')) {
            if (is_plugin_active($checkmandrill)) {
                $arraymailoption = array(
                    '1' => 'mail()',
                    '2' => 'wp_mail()',
                    '3' => 'wpmandrill',
                );
            } else {
                $arraymailoption = array(
                    '1' => 'mail()',
                    '2' => 'wp_mail()',
                );
            }
        } else {
            $arraymailoption = array(
                '1' => 'mail()',
                '2' => 'wp_mail()',
            );
        }
        return apply_filters('woocommerce_rewardsystem_mail_settings', array(
            array(
                'name' => __('Mail', 'rewardsystem'),
                'type' => 'title',               
                'id' => '_rs_mail_setting'
            ),      
            array(
                'name' => __('Select Mail Function', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_select_mail_function',
                'css' => 'min-width:150px;',
                'std' => '2',
                'default' => '2',
                'newids' => 'rs_select_mail_function',
                'type' => 'select',
                'options' => $arraymailoption,
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_mail_settings'),
            array(
                'name' => __('Mail Cron Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => 'Here you can set the time duration for which mail will be sent repeatedly. For example if you set the time duration as 3 days then for every 3 days mail will be sent',
                'id' => 'rs_cron_settings',
            ),
            array(
                'name' => __('Mail Cron Time Type', 'rewardsystem'),
                'desc' => '',
                'tip' => '',
                'id' => 'rs_mail_cron_type',
                'css' => 'min-width:150px;',
                'type' => 'select',
                'newids' => 'rs_mail_cron_type',
                'desc_tip' => true,
                'options' => array('minutes' => 'Minutes', 'hours' => 'Hours', 'days' => 'Days'),
                'std' => 'days',
                'default' => 'days',
            ),
            array(
                'name' => __('Mail Cron Time', 'rewardsystem'),
                'desc' => __('Please Enter time after which Email cron job should run', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_mail_cron_time',
                'newids' => 'rs_mail_cron_time',
                'css' => 'min-width:150px;',
                'type' => 'text',
                'desc_tip' => true,
                'std' => '3',
                'default' => '3',
            ),
           array('type'=>'sectionend', 'id'=>'_rs_mail_setting'),
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSMail::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSMail::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSMail::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}

new RSMail();
