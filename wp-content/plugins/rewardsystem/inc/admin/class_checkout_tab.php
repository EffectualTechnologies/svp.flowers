<?php

class RSCheckout {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_checkout', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_checkout', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
        
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_checkout'] = __('Checkout','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
         global $woocommerce;       
         return apply_filters('woocommerce_rewardsystem_checkout_settings', array(
            array(
                'name' => __('Checkout', 'rewardsystem'),
                'type' => 'title',               
                'id' => 'rs_checkout_setting',
            ), 
            array(
                'name' => __('Show/Hide Redeeming Field in Checkout Page', 'rewardsystem'),
                'desc' => __('Show/Hide Redeeming Field in Checkout Page of WooCommerce', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_show_hide_redeem_field_checkout',
                'css' => '',
                'std' => '2',
                'type' => 'select',
                'newids' => 'rs_show_hide_redeem_field_checkout',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
             array(
                'name' => __('Show/Hide Points that can be earned in Checkout page Order details', 'rewardsystem'),
                'desc' =>'',
                'tip' => '',
                'id' => 'rs_show_hide_total_points_checkout_field',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_show_hide_total_points_checkout_field',
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
                'name' => __('Redeeming Field Type', 'rewardsystem'),
                'desc' => __('Select the type of Redeeming to used in Cart Page of WooCommerce', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeem_field_type_option_checkout',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_redeem_field_type_option_checkout',
                'options' => array(
                    '1' => __('Default', 'rewardsystem'),
                    '2' => __('Button', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
             
             array(
                'name' => __('Show/Hide Coupon Field', 'rewardsystem'),
                'desc' =>'',
                'tip' => '',
                'id' => 'rs_show_hide_coupon_field_checkout',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_show_hide_coupon_field_checkout',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
             
             array(
                'name' => __('Percentage of Cart Total to be Redeemed', 'rewardsystem'),
                'desc' => __('Enter the Percentage of the cart total that has to be Redeemed', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_percentage_cart_total_redeem_checkout',
                'css' => 'min-width:550px;',
                'std' => '100 ',
                'type' => 'text',
                'newids' => 'rs_percentage_cart_total_redeem_checkout',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redeeming Field label', 'rewardsystem'),
                'desc' => __('This Text will be displayed as redeeming field label in checkout page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reedming_field_label_checkout',
                'css' => 'min-width:550px;',
                'std' => 'Have Reward Points ?',
                'type' => 'text',
                'newids' => 'rs_reedming_field_label_checkout',
                'class' => 'rs_reedming_field_label_checkout',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redeeming Field link label', 'rewardsystem'),
                'desc' => __('This Text will be displayed as redeeming field link label in checkout page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reedming_field_link_label_checkout',
                'css' => 'min-width:550px;',
                'std' => 'Redeem it',
                'type' => 'text',
                'newids' => 'rs_reedming_field_link_label_checkout',
                'class' => 'rs_reedming_field_link_label_checkout',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Redeem It Link Call To Action', 'rewardsystem'),
                'desc' => __('Show/Hide Redeem It Link Call To Action in WooCommerce', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_show_hide_redeem_it_field_checkout',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_show_hide_redeem_it_field_checkout',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Enable Selected Products for Purchase using reward points', 'rewardsystem'),
                'desc' => __('Enable Products Purchase for Selceted Products Using Earned Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_enable_selected_product_for_purchase_using_points',
                'class' => 'rs_enable_selected_product_for_purchase_using_points',
                'newids' => 'rs_enable_selected_product_for_purchase_using_points',
                'type' => 'checkbox',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select Products for Purchase Using Points', 'rewardsystem'),
                'desc' => __('Select Products for Purchase Using Earned Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_select_product_for_purchase_using_points',
                'css' => 'min-width:350px;',
                'type' => 'rs_product_for_purchase',
                'newids' => 'rs_select_product_for_purchase_using_points',
                'class' => 'rs_select_product_for_purchase_using_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message when other products added to Cart Page', 'rewardsystem'),
                'desc' => __('Error Message when other products added to Cart Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_errmsg_when_other_products_added_to_cart_page',
                'css' => 'min-width:550px;',
                'std' => '[productname] is removed from the Cart.Because it can be purchased only through Reward points',
                'type' => 'textarea',
                'newids' => 'rs_errmsg_when_other_products_added_to_cart_page',
                'class' => 'rs_errmsg_when_other_products_added_to_cart_page',
                'desc_tip' => true,
            ),            
            array(
                'name' => __('Redeeming Button Message ', 'rewardsystem'),
                'desc' => __('Enter the Message for the Redeeming Button', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeeming_button_option_message_checkout',
                'css' => 'min-width:550px;',
                'std' => '[cartredeempoints] points worth of [currencysymbol] [pointsvalue] will be Redeemed',
                'type' => 'textarea',
                'newids' => 'rs_redeeming_button_option_message_checkout',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => 'rs_checkout_setting'),
            array(
                'name' => __('Custom CSS Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => 'Try !important if styles doesn\'t apply ',
                'id' => '_rs_checkout_custom_css_settings',
            ),
            array(
                'name' => __('Custom CSS', 'rewardsystem'),
                'desc' => __('Enter the Custom CSS for the Cart Page ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_checkout_page_custom_css',
                'css' => 'min-width:350px; min-height:350px;',
                'std' => '#rs_apply_coupon_code_field { } #mainsubmi { } .fp_apply_reward{ }',
                'type' => 'textarea',
                'newids' => 'rs_checkout_page_custom_css',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_checkout_custom_css_settings'),                
        ));
    }
    
    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSCheckout::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSCheckout::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSCheckout::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
}

new RSCheckout();