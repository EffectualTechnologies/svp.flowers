<?php

class RSMessage {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_message', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_message', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    
        
    }
    
     /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_message'] = __('Message','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        return apply_filters('woocommerce_rewardsystem_message_settings', array(
            array(
                'name' => __('Message', 'rewardsystem'),
                'type' => 'title',
                'id' => 'rs_message_setting',
            ),
            array('type' => 'sectionend', 'id' => 'rs_message_setting'),
            array(
                'name' => __('Reward System Message Customization ', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Customize your Message with Reward System', 'rewardsystem'),
                'id' => '_rs_reward_messages'
            ),
            array(
                'name' => __('List of Shortcodes', 'rewardsystem'),
                'type' => 'rs_list_of_shortcodes',
                'id' => '_rs_list_shortcodes',
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_messages'),
            array(
                'name' => __('Shop Page Messages', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_shop_page_msg',
            ),
            array(
                'name' => __('Show/Hide Earn Point(s) Message for Simple Product in Shop Page ', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_simple_in_shop',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_simple_in_shop',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Earn Point(s) Message for Simple Product in Shop Page', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on Shop Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_in_shop_page_for_simple',
                'css' => 'min-width:550px;',
                'std' => 'Earn [rewardpoints] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_message_in_shop_page_for_simple',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Position in Shop Page for Simple Product', 'rewardsystem'),
                'desc' =>'',
                'tip' => '',
                'id' => 'rs_message_position_for_simple_products_in_shop_page',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_message_position_for_simple_products_in_shop_page',
                'options' => array(
                    '1' => __('Before Product Price', 'rewardsystem'),
                    '2' => __('After Product Price', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),                                    
            array(
                'type' => 'sectionend',
                'id' => '_rs_shop_page_msg'
            ),
            array(
                'name' => __('Single Product Page Messages', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_single__product_page_msg',
            ),
            array(
                'name' => __('Show/Hide Message for Single Product Page as Points', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_single_product',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_single_product',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message for Simple Product in Single Product Page', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on top of Single Product Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_for_single_product_point_rule',
                'css' => 'min-width:550px;',
                'std' => 'Purchase this Product and Earn [rewardpoints] Reward Points ([equalamount])',
                'type' => 'textarea',
                'newids' => 'rs_message_for_single_product_point_rule',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Earn Points Message for Single Product Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_shop_archive_single',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_shop_archive_single',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Earn Point(s) Message for Simple Product in Single Product Page', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on Shop Page and Single Product Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_in_single_product_page',
                'css' => 'min-width:550px;',
                'std' => 'Earn [rewardpoints] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_message_in_single_product_page',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Earn Points Message for Variations in Single Product Page', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_show_hide_message_for_variable_in_single_product_page',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_variable_in_single_product_page',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Earn Point(s) Message for Variations (Variable Product) in Single Product Page', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on Variation Page of Variable Product', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_for_single_product_variation',
                'css' => 'min-width:550px;',
                'std' => 'Earn [variationrewardpoints] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_message_for_single_product_variation',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Position in Single Product Page for Simple Product', 'rewardsystem'),
                'desc' => '',
                'tip' => '',
                'id' => 'rs_message_position_in_single_product_page_for_simple_products',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_message_position_in_single_product_page_for_simple_products',
                'options' => array(
                    '1' => __('Before Product Price', 'rewardsystem'),
                    '2' => __('After Product Price', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Position in Single Product Page for Variable Product', 'rewardsystem'),
                'desc' => '',
                'tip' => '',
                'id' => 'rs_message_position_in_single_product_page_for_variable_products',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_message_position_in_single_product_page_for_variable_products',
                'options' => array(
                    '1' => __('Before Product Price', 'rewardsystem'),
                    '2' => __('After Product Price', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Message for each Variant (Variable Product) in Single Product Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_variable_product',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_variable_product',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message for each Variant (Variable Product) in Single Product Page', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on top of Variable Product', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_for_variation_products',
                'css' => 'min-width:550px;',
                'std' => 'Purchase this Product and Earn [variationrewardpoints] Reward Points ([variationpointsvalue])',
                'type' => 'textarea',
                'newids' => 'rs_message_for_variation_products',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_single__product_page_msg'),
            array(
                'name' => __('Cart Page Messages', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_cart_page_msg',
            ),
            array(
                'name' => __('Success Message for Redeeming Reward Points in Cart Page', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Reward Points are redeemed in cart', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_success_coupon_message',
                'css' => 'min-width:550px;',
                'std' => 'Reward Points Successfully Added',
                'type' => 'text',
                'newids' => 'rs_success_coupon_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Message for Guest in Cart Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_guest',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_guest',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message for Guest in Cart Page', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on top of Cart Page for Guest', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_for_guest_in_cart',
                'css' => 'min-width:550px;',
                'std' => 'Earn Reward Points for Product Purchase, Product Review and Signup [loginlink]',
                'type' => 'textarea',
                'newids' => 'rs_message_for_guest_in_cart',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Reward Points Message for each Products in Cart Page', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_show_hide_message_for_each_products',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_each_products',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message in Cart Page for each Products', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed in each Products added in the Cart', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_product_in_cart',
                'css' => 'min-width:550px;',
                'std' => 'Purchase [titleofproduct] and Earn <strong>[rspoint]</strong> Reward Points ([carteachvalue])',
                'type' => 'textarea',
                'newids' => 'rs_message_product_in_cart',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Total Reward Points Message in Cart Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_total_points',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_total_points',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message in Cart Page for Completing the Total Purchase', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on top of Cart Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_total_price_in_cart',
                'css' => 'min-width:550px;',
                'std' => 'Complete the Purchase and Earn <strong>[totalrewards]</strong> Reward Points ([totalrewardsvalue])',
                'type' => 'textarea',
                'newids' => 'rs_message_total_price_in_cart',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Your Reward Points Message in Cart Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_my_rewards',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_my_rewards',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message in Cart Page that display Your Reward Points', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on top of Cart Page with Your Reward Points ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_user_points_in_cart',
                'css' => 'min-width:550px;',
                'std' => 'My Reward Points [userpoints] ([userpoints_value])',
                'type' => 'textarea',
                'newids' => 'rs_message_user_points_in_cart',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Redeemed Points Message in Cart Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_redeem_points',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_redeem_points',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message in Cart Page that Display Redeeming Your Points', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on top of Cart Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_user_points_redeemed_in_cart',
                'css' => 'min-width:550px;',
                'std' => '[redeempoints] Reward Points Redeemed. Balance [redeemeduserpoints] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_message_user_points_redeemed_in_cart',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_cart_page_msg'),
            array(
                'name' => __('Checkout Page Messages', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_checkout_page_msg',
            ),
            array(
                'name' => __('Show/Hide Message for Guest in Checkout Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_guest_checkout_page',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_guest_checkout_page',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message for Guest in Checkout Page', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on top of Checkout Page for Guest', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_for_guest_in_checkout',
                'css' => 'min-width:550px;',
                'std' => 'Earn Reward Points for Product Purchase, Product Review and Signup [loginlink]',
                'type' => 'textarea',
                'newids' => 'rs_message_for_guest_in_checkout',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Reward Points Message for each Products in Checkout Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_each_products_checkout_page',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_each_products_checkout_page',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message in Checkout Page for each Products', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed in each Products added in the Checkout', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_product_in_checkout',
                'css' => 'min-width:550px;',
                'std' => 'Purchase [titleofproduct] and Earn <strong>[rspoint]</strong> Reward Points ([carteachvalue])',
                'type' => 'textarea',
                'newids' => 'rs_message_product_in_checkout',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Total Reward Points Message in Checkout Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_total_points_checkout_page',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_total_points_checkout_page',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message in Checkout Page for Completing the Total Purchase', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on top of Checkout Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_total_price_in_checkout',
                'css' => 'min-width:550px;',
                'std' => 'Complete the Purchase and Earn <strong>[totalrewards]</strong> Reward Points ([totalrewardsvalue])',
                'type' => 'textarea',
                'newids' => 'rs_message_total_price_in_checkout',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Your Reward Points Message in Checkout Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_for_my_rewards_checkout_page',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_my_rewards_checkout_page',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message in Checkout Page that display Your Reward Points', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on top of Checkout Page with Your Reward Points ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_user_points_in_checkout',
                'css' => 'min-width:550px;',
                'std' => 'My Reward Points [userpoints] ([userpoints_value])',
                'type' => 'textarea',
                'newids' => 'rs_message_user_points_in_checkout',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Redeemed Points Message in Checkout Page', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_show_hide_message_for_redeem_points_checkout_page',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_for_redeem_points_checkout_page',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message in Checkout Page that Display Redeeming Your Points', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed on top of Checkout Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_user_points_redeemed_in_checkout',
                'css' => 'min-width:550px;',
                'std' => '[redeempoints] Reward Points Redeemed. Balance [redeemeduserpoints] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_message_user_points_redeemed_in_checkout',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Payment Gateway Reward Points Message in Checkout Page', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_message_payment_gateway_reward_points',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_show_hide_message_payment_gateway_reward_points',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Message for Payment Gateway Reward Points', 'rewardsystem'),
                'desc' => __('Enter the Message for Payment Gateway Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_payment_gateway_reward_points',
                'css' => 'min-width:550px;',
                'std' => 'Use this [paymentgatewaytitle] and Earn [paymentgatewaypoints] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_message_payment_gateway_reward_points',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_checkout_page_msg'),
            array(
                'name' => __('Cart/Checkout Page Messages', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_cart_checkout_page_msg',
            ),
            array(
                'name' => __('Error Message for Maximum Discount Type', 'rewardsystem'),
                'desc' => __('Error Message for Maximum Discount Type', 'rewardsystem'),
                'id' => 'rs_errmsg_for_max_discount_type',
                'css' => 'min-width:550px;',
                'std' => 'Maximum Discount has been Limited to [percentage] %',
                'type' => 'textarea',
                'newids' => 'rs_errmsg_for_max_discount_type',
                'class' => 'rs_errmsg_for_max_discount_type',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Displayed in Cart When the Order Contain Only Redeeming', 'rewardsystem'),
                'desc' => __('Message Displayed in Cart When the Order Contain Only Redeeming', 'rewardsystem'),
                'id' => 'rs_errmsg_for_redeeming_in_order',
                'css' => 'min-width:550px;',
                'std' => 'Since,You Redeemed Your Reward Points in this Order, You Cannot Earn Reward Points For this Order',
                'type' => 'textarea',
                'newids' => 'rs_errmsg_for_redeeming_in_order',
                'class' => 'rs_errmsg_for_redeeming_in_order',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_cart_checkout_page_msg'),
            array(
                'name' => __('Unsubscribe Link Messages', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_unsub_link',
            ),
            array(
                'name' => __('Unsubscribe Link Message for Email', 'rewardsystem'),
                'desc' => __('This link is to unsubscribe your email', 'rewardsystem'),
                'id' => 'rs_unsubscribe_link_for_email',
                'css' => 'min-width:550px;',
                'std' => 'If you want to unsubscribe your mail,click here...{rssitelinkwithid}',
                'type' => 'textarea',
                'newids' => 'rs_unsubscribe_link_for_email',
                'class' => 'rs_unsubscribe_link_for_email',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_unsub_link'),
             array(
                'name' => __('Cart Error Messages', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_cart_error_msg',
            ),
            array(
                'name' => __('Error Message To Add normal product with point price product', 'rewardsystem'),
                'desc' => __('Message Displayed in Cart When to Add normal product with point price product', 'rewardsystem'),
                'id' => 'rs_errmsg_for_normal_product_with_point_price',
                'css' => 'min-width:550px;',
                'std' => 'Cannot add normal product with point pricing product',
                'type' => 'textarea',
                'newids' => 'rs_errmsg_for_normal_product_with_point_price',
                'class' => 'rs_errmsg_for_normal_product_with_point_price',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message To Add point price product with normal product', 'rewardsystem'),
                'desc' => __('Message Displayed in Cart When to Add point price product with normal product', 'rewardsystem'),
                'id' => 'rs_errmsg_for_point_price_product_with_normal',
                'css' => 'min-width:550px;',
                'std' => 'Cannot Purchase Point Pricing Product with Normal product',
                'type' => 'textarea',
                'newids' => 'rs_errmsg_for_point_price_product_with_normal',
                'class' => 'rs_errmsg_for_point_price_product_with_normal',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Error Message To Add same point price product', 'rewardsystem'),
                'desc' => __('Message Displayed in Cart When to Add same point price product ', 'rewardsystem'),
                'id' => 'rs_errmsg_for_point_price_product_with_same',
                'css' => 'min-width:550px;',
                'std' => 'You cannot add same product to cart',
                'type' => 'textarea',
                'newids' => 'rs_errmsg_for_point_price_product_with_same',
                'class' => 'rs_errmsg_for_point_price_product_with_same',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_cart_error_msg1'),
        ));
    }

    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSMessage::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSMessage::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSMessage::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}

new RSMessage();