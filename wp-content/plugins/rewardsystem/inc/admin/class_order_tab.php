<?php

class RSOrder {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_order', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_order', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_order'] = __('Order','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_order_settings', array(
            array(
                'name' => __('Order Setting', 'rewardsystem'),
                'type' => 'title',                
                'id' => '_rs_order_setting'
            ),   
            array(
                'name' => __('Show/Hide Total Earned Points in Order Details Section', 'rewardsystem'),
                'desc' =>'',
                'tip' => '',
                'id' => 'rs_show_hide_total_points_order_field',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_show_hide_total_points_order_field',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Points Earned in Order  Caption', 'rewardsystem'),
                'desc' => __('', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_total_earned_point_caption_checkout',
                'css' => 'min-width:150px;',
                'std' => 'Points that can be earned:',
                'type' => 'text',
                'newids' => 'rs_total_earned_point_caption_checkout',
                'desc_tip' => true,
            ), 
            array(
                'name'=>__('Enable Message for Earned Points','rewardsystem'),
                'desc'=>__('Enable Message for Earned Points','rewardsystem'),
                'id'=>'rs_enable_msg_for_earned_points',
                'newids'=>'rs_enable_msg_for_earned_points',
                'class'=>'rs_enable_msg_for_earned_points',
                'type'=>'checkbox',
                'desc_tip'=>true,
            ),
            
            array(
                'name'=>__('Message For Earned Points','rewardsystem'),
                'desc'=>__('Message For Earned Points','rewardsystem'),
                'id'=>'rs_msg_for_earned_points',
                'newids'=>'rs_msg_for_earned_points',
                'class'=>'rs_msg_for_earned_points',
                'css' => 'min-width:550px;',
                'std'=>'Points Earned For this Order [earnedpoints]',
                'type'=>'textarea',
                'desc_tip'=>true,
            ),
            array(
                'name'=>__('Enable Message for Redeem Points','rewardsystem'),
                'desc'=>__('Enable Message for Redeem Points','rewardsystem'),
                'id'=>'rs_enable_msg_for_redeem_points',
                'newids'=>'rs_enable_msg_for_redeem_points',
                'class'=>'rs_enable_msg_for_redeem_points',
                'type'=>'checkbox',
                'desc_tip'=>true,
            ),
            array(
                'name'=>__('Message For Redeem Points','rewardsystem'),
                'desc'=>__('Message For Redeem Points','rewardsystem'),
                'id'=>'rs_msg_for_redeem_points',
                'newids'=>'rs_msg_for_redeem_points',
                'class'=>'rs_msg_for_redeem_points',
                'css' => 'min-width:550px;',
                'std'=>'Points Redeem For this Order [redeempoints]',
                'type'=>'textarea',
                'desc_tip'=>true,
            ),         
           array('type'=>'sectionend', 'id'=>'_rs_order_setting'),
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSOrder::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSOrder::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSOrder::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}
new RSOrder();