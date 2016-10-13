<?php
class RSRequestForSendPoint {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_request_for_send_points', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_request_for_send_points', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_request_for_send_points'] = __('Request For Send Point','rewardsystem');
        return $setting_tabs;
    }
     public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_request_for_send_point_settings', array(
            array(
                'name' => __('Send Point Request', 'rewardsystem'),
                'type' => 'title',                
                'id' => '_rs_request_for_send_point_setting'
            ),                                  
            array(
                'type' => 'rs_send_point_applications_list',
            ),
            array(
                'type' => 'rs_send_point_applications_edit_lists',
            ),
           array('type'=>'sectionend', 'id'=>'_rs_request_for_send_point_setting'),
           
        ));     
    }
    
       public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSRequestForSendPoint::reward_system_admin_fields());
    }
    
    
     public static function reward_system_update_settings() {
        woocommerce_update_options(RSRequestForSendPoint::reward_system_admin_fields());
    }

public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSRequestForSendPoint::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                if (get_option($settings['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
    }
    
    
}

new RSRequestForSendPoint();

