<?php

class RSSupportTab {
    
    public function __construct() {   
        
        add_action('init', array($this, 'reward_system_default_settings'),999);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_support', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_support', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
        
        add_action('woocommerce_admin_field_fp_support_content',array($this,'fp_support_content'));
        
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_support'] = __('Support','rewardsystem');
        return $setting_tabs;
    }
    
    /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
         global $woocommerce;
        return apply_filters('woocommerce_rewardsystem_support_tab', array(
            array(
                'name' => __('Help & Support', 'fphandycart'),
                'type' => 'title',
                'id' => '_fp_reward_system_support'
            ),
            array(
                'type'=>'fp_support_content',
            ),
            array('type' => 'sectionend', 'id' => '_fp_reward_system_support'),
        ));
    }
     
    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSSupportTab::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSSupportTab::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSSupportTab::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
    public static function fp_support_content(){
        ?>
        <style type="text/css">
                    p.submit{
                        display: none;
                    }
                    #mainforms{
                        display: none;
                    }
                </style>
            <p id="fp_support_content">
                For support, feature request or any help, please <a href="http://support.fantasticplugins.com/">register and open a support ticket on our site</a>.<br><br>
                <h3>Documentation</h3>
                Please check the documentation as we have lots of information there. The documentation file can be found inside the documentation folder which you will find when you unzip the downloaded zip file.
            </p>
        <?php
        
    }
}

new RSSupportTab();