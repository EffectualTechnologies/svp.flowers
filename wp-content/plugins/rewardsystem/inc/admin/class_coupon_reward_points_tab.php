<?php

class RSCouponRewardPoints {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_coupon_reward_points', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_coupon_reward_points', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_coupon_reward_points'] = __('Coupon Reward Points','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_coupon_reward_points_settings', array(
            array(
                'name' => __('Coupon Reward Points Settings', 'rewardsystem'),
                'type' => 'title',                             
                'id' => '_rs_coupon_reward_points_setting'
            ),    
            array(
                'name' => __('Priority Level Selection', 'rewardsystem'),
                'desc' => __('If more than one type(level) is enabled then use the highest/lowest points for the Coupons ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_choose_priority_level_selection_coupon_points',
                'class' => 'rs_choose_priority_level_selection_coupon_points',
                'std' => '1',
                'type' => 'radio',
                'newids' => 'rs_choose_priority_level_selection_coupon_points',
                'options' => array(
                    '1' => __('Use Highest Reward Points for the Coupon Codes', 'rewardsystem'),
                    '2' => __('Use Lowest Reward Points  for the Coupon Codes', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Coupon Reward Points Message Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('', 'rewardsystem'),
                'id' => '_rs_reward_point_coupon_message_settings'
            ),
            array(
                'name' => __('Coupon Applied Success Message', 'rewardsystem'),
                'desc' => __('This messgae will be displayed when The User applies the Selected coupons for Reward Points ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_coupon_applied_reward_success',
                'css' => 'min-width:550px;',
                'std' => 'You have recieved [coupon_rewardpoints] Points for using the coupon [coupon_name]',
                'type' => 'textarea',
                'newids' => 'rs_coupon_applied_reward_success',
                'desc_tip' => true,
            ),
            array(
                'type' => 'rs_coupon_usage_points_dynamics',
            ),
           array('type'=>'sectionend', 'id'=>'_rs_coupon_reward_points_setting'),
           
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSCouponRewardPoints::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSCouponRewardPoints::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSCouponRewardPoints::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
}
new RSCouponRewardPoints();