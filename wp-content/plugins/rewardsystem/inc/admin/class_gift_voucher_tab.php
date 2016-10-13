<?php

class RSGiftVoucher {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_giftvoucher', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_giftvoucher', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_giftvoucher'] = __('Gift Voucher','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_gift_voucher_settings', array(
            array(
                'name' => __('Gift Voucher Creation Setting', 'rewardsystem'),
                'type' => 'title',              
                'id' => '_rs_gift_voucher_setting'
            ),           
            array(
                'name' => 'test',
                'type' => 'point_vouchers',
            ),
            array('type' => 'sectionend', 'id' => '_rs_gift_voucher_setting'),
            array(
                'name' => __('Gift Voucher Message settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_gift_voucher_message_settings',
            ),
            array(
                'name' => __('Error Message when Redeem Voucher Field is empty', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Redeem Voucher Button is clicked without entering the voucher code ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_voucher_redeem_empty_error',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter your Voucher Code',
                'type' => 'text',
                'newids' => 'rs_voucher_redeem_empty_error',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Success Message when Gift Voucher is Redeemed', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when the Gift Voucher has been Successfully Redeemed', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_voucher_redeem_success_message',
                'css' => 'min-width:550px;',
                'std' => '[giftvoucherpoints] Reward points has been added to your Account',
                'type' => 'text',
                'newids' => 'rs_voucher_redeem_success_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Messgae when Voucher has Expired', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when the Gift Voucher has been Successfully Redeemed', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_voucher_code_expired_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Voucher has been Expired',
                'type' => 'text',
                'newids' => 'rs_voucher_code_expired_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Invalid Voucher Code Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when a Invalid Voucher is used for Redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_invalid_voucher_code_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Sorry, Voucher not found in a list',
                'type' => 'text',
                'newids' => 'rs_invalid_voucher_code_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Login link for Guest Label', 'rewardsystem'),
                'desc' => __('Please Enter Login link for Guest Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeem_voucher_login_link_label',
                'css' => 'min-width:200px;',
                'std' => 'Login',
                'type' => 'text',
                'newids' => 'rs_redeem_voucher_login_link_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Displayed for Guest', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed for Guest when Gift Voucher Shortcode is used', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_voucher_redeem_guest_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please [rs_login_link] to View this Page',
                'type' => 'text',
                'newids' => 'rs_voucher_redeem_guest_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Voucher Already Used Error Mesage', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed when User tries to Redeem a Voucher code that has already been Used', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_voucher_code_used_error_message',
                'css' => 'min-width:200px;',
                'std' => 'Voucher has been used',
                'type' => 'text',
                'newids' => 'rs_voucher_code_used_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Displayed for Banned Users', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed when a Banned User tries to Redeem the Gift Voucher', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_banned_user_redeem_voucher_error',
                'css' => 'min-width:400px;',
                'std' => 'You have Earned 0 Points',
                'type' => 'textarea',
                'newids' => 'rs_banned_user_redeem_voucher_error',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_gift_voucher_message_settings'),
           array('type'=>'sectionend', 'id'=>'_rs_gift_voucher_setting'),
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSGiftVoucher::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSGiftVoucher::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSGiftVoucher::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
}
new RSGiftVoucher();