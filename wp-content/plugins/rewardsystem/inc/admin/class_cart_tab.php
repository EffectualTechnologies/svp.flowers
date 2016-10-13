<?php

class RSCart {

    public function __construct() {

        add_action('init', array($this, 'reward_system_default_settings'), 103); // call the init function to update the default settings on page load

        add_filter('woocommerce_rs_settings_tabs_array', array($this, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings

        add_action('woocommerce_rs_settings_tabs_rewardsystem_cart', array($this, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab

        add_action('woocommerce_update_options_rewardsystem_cart', array($this, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system
    }

    /*
     * Function to Define Name of the Tab
     */

    public static function reward_system_tab_setting($setting_tabs) {
        $setting_tabs['rewardsystem_cart'] = __('Cart', 'rewardsystem');
        return $setting_tabs;
    }

    /*
     * Function label settings to Member Level Tab
     */

    public static function reward_system_admin_fields() {
        global $woocommerce;

        $categorylist = array();
        $categoryname = array();
        $categoryid = array();
        $particularcategory = get_terms('product_cat');
        if (!is_wp_error($particularcategory)) {
            if (!empty($particularcategory)) {
                if (is_array($particularcategory)) {
                    foreach ($particularcategory as $category) {
                        $categoryname[] = $category->name;
                        $categoryid[] = $category->term_id;
                    }
                }
                $categorylist = array_combine((array) $categoryid, (array) $categoryname);
            }
        }
        return apply_filters('woocommerce_rewardsystem_cart_settings', array(
            array(
                'type' => 'title',
                'id' => 'rs_cart_setting',
            ),
            array(
                'name' => __('Apply Redeeming Before Tax', 'rewardsystem'),
                'desc' => 'Works with WooCommerce Versions 2.2 or older',
                'tip' => '',
                'id' => 'rs_apply_redeem_before_tax',
                'css' => 'min-width:150px;',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_apply_redeem_before_tax',
                'options' => array(
                    '1' => __('Enable', 'rewardsystem'),
                    '2' => __('Disable', 'rewardsystem'),
                ),
                'desc_tip' => false,
            ),
            array(
                'name' => __('Enable Free Shipping ', 'rewardsystem'),
                'desc' => '',
                'tip' => '',
                'id' => 'rs_apply_shipping_tax',
                'css' => 'min-width:150px;',
                'std' => '2',
                'type' => 'select',
                'newids' => 'rs_apply_shipping_tax',
                'options' => array(
                    '1' => __('Enable', 'rewardsystem'),
                    '2' => __('Disable', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Redeeming Field', 'rewardsystem'),
                'desc' => '',
                'tip' => '',
                'id' => 'rs_show_hide_redeem_field',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_show_hide_redeem_field',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Coupon Field', 'rewardsystem'),
                'desc' => '',
                'tip' => '',
                'id' => 'rs_show_hide_coupon_field',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_show_hide_coupon_field',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Total Earned Points in Cart Total Section', 'rewardsystem'),
                'desc' => '',
                'tip' => '',
                'id' => 'rs_show_hide_total_points_cart_field',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_show_hide_total_points_cart_field',
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
                'id' => 'rs_total_earned_point_caption',
                'css' => 'min-width:150px;',
                'std' => 'Points that can be earned:',
                'type' => 'text',
                'newids' => 'rs_total_earned_point_caption',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Apply Redeeming Based On', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_apply_redeem_basedon_cart_or_product_total',
                'newids' => 'rs_apply_redeem_basedon_cart_or_product_total',
                'class' => 'rs_apply_redeem_basedon_cart_or_product_total',
                'css' => 'min-width:150px;',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1' => __('Cart Subtotal', 'rewardsystem'),
                    '2' => __('Product Total', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Enable Redeeming for Selected Product', 'rewardsystem'),
                'desc' => __('Enable Redeeming for Selected Product', 'rewardsystem'),
                'id' => 'rs_enable_redeem_for_selected_products',
                'css' => 'min-width:150px;',
                'type' => 'checkbox',
                'newids' => 'rs_enable_redeem_for_selected_products',
            ),
            array(
                'name' => __('Select Products', 'rewardsystem'),
                'desc' => __('Select Products to enable redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_select_products_to_enable_redeeming',
                'class' => 'rs_select_products_to_enable_redeeming',
                'css' => 'min-width:350px',
                'std' => '',
                'type' => 'include_product_selection',
                'newids' => 'rs_select_products_to_enable_redeeming',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Exclude Products for Redeeming', 'rewardsystem'),
                'desc' => __('Exclude Products for Redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_exclude_products_for_redeeming',
                'class' => 'rs_exclude_products_for_redeeming',
                'css' => '',
                'std' => '',
                'type' => 'checkbox',
                'newids' => 'rs_exclude_products_for_redeeming',
            ),
            array(
                'name' => __('Exclude Products', 'rewardsystem'),
                'desc' => __('Exclude Products to enable redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_exclude_products_to_enable_redeeming',
                'class' => 'rs_exclude_products_to_enable_redeeming rs_ajax_chosen_select_products_redeem',
                'css' => 'min-width:350px',
                'std' => '',
                'type' => 'exclude_product_selection',
                'newids' => 'rs_exclude_products_to_enable_redeeming',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Enable Redeeming for Selected Category', 'rewardsystem'),
                'desc' => __('Enable Redeeming for Selected Category', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_enable_redeem_for_selected_category',
                'class' => 'rs_enable_redeem_for_selected_category',
                'css' => '',
                'std' => '',
                'type' => 'checkbox',
                'newids' => 'rs_enable_redeem_for_selected_category',
            ),
            array(
                'name' => __('Select Category', 'rewardsystem'),
                'desc' => __('Select Category to enable redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_select_category_to_enable_redeeming',
                'class' => 'rs_select_category_to_enable_redeeming',
                'css' => 'min-width:350px',
                'std' => '',
                'type' => 'multiselect',
                'newids' => 'rs_select_category_to_enable_redeeming',
                'options' => $categorylist,
                'desc_tip' => true,
            ),
            array(
                'name' => __('Exclude Category for Redeeming', 'rewardsystem'),
                'desc' => __('Exclude Category for Redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_exclude_category_for_redeeming',
                'css' => '',
                'std' => '',
                'type' => 'checkbox',
                'newids' => 'rs_exclude_category_for_redeeming',
            ),
            array(
                'name' => __('Exclude Category', 'rewardsystem'),
                'desc' => __('Exclude Category to enable redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_exclude_category_to_enable_redeeming',
                'class' => 'rs_exclude_category_to_enable_redeeming',
                'css' => 'min-width:350px',
                'std' => '',
                'type' => 'multiselect',
                'newids' => 'rs_exclude_category_to_enable_redeeming',
                'options' => $categorylist,
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redeeming Field Type', 'rewardsystem'),
                'desc' => '',
                'tip' => '',
                'id' => 'rs_redeem_field_type_option',
                'css' => '',
                'std' => '1',
                'type' => 'select',
                'newids' => 'rs_redeem_field_type_option',
                'options' => array(
                    '1' => __('Default', 'rewardsystem'),
                    '2' => __('Button', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Percentage of Cart Total to be Redeemed', 'rewardsystem'),
                'desc' => __('Enter the Percentage of the cart total that has to be Redeemed', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_percentage_cart_total_redeem',
                'css' => 'min-width:150px;',
                'std' => '100 ',
                'type' => 'text',
                'newids' => 'rs_percentage_cart_total_redeem',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redeeming Button Message ', 'rewardsystem'),
                'desc' => __('Enter the Message for the Redeeming Button', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeeming_button_option_message',
                'css' => 'min-width:550px;',
                'std' => '[cartredeempoints] points worth of [currencysymbol] [pointsvalue] will be Redeemed',
                'type' => 'textarea',
                'newids' => 'rs_redeeming_button_option_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Enable Auto Redeem Points ', 'rewardsystem'),
                'desc' => __('Enable Auto Redeem Points', 'rewardsystem'),
                'id' => 'rs_enable_disable_auto_redeem_points',
                'type' => 'checkbox',
                'std' => 'no',
                'newids' => 'rs_enable_disable_auto_redeem_points',
            ),
            array(
                'name' => __('Percentage of Cart Total to be Auto Redeemed', 'rewardsystem'),
                'desc' => __('Enter the Percentage of the cart total that has to be Auto Redeemed', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_percentage_cart_total_auto_redeem',
                'css' => 'min-width:150px;',
                'std' => '100 ',
                'type' => 'text',
                'newids' => 'rs_percentage_cart_total_auto_redeem',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Do not Award Points on Order when Reward Points are Redeemed', 'rewardsystem'),
                'desc' => __('Enable to not award Points on Order when Reward Points are Redeemed', 'rewardsystem'),
                'id' => 'rs_enable_redeem_for_order',
                'css' => 'min-width:150px;',
                'type' => 'checkbox',
                'std' => 'no',
                'newids' => 'rs_enable_redeem_for_order',
            ),
            array(
                'name' => __('Minimum Cart Total for Earned Points', 'rewardsystem'),
                'desc' => __('Enter Minimum Cart Total for Earned Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_minimum_cart_total_for_earning',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_minimum_cart_total_for_earning',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Cart Total for Earned Points Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when the user doesn\'t have enough Cart Total for Earning', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_min_cart_total_for_earning_error_message',
                'css' => 'min-width:550px;',
                'std' => 'You need Minimum of [carttotal] carttotal to Earn Points',
                'type' => 'textarea',
                'newids' => 'rs_min_cart_total_for_earning_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Points to be Earned for Redeeming First Time', 'rewardsystem'),
                'desc' => __('Enter Minimum Points to be Earned for Redeeming First Time in Cart/Checkout', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_first_time_minimum_user_points',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_first_time_minimum_user_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Points for first time Redeeming Error Message Show/Hide', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_first_redeem_error_message',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_first_redeem_error_message',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Minimum Points for first time Redeeming Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when the user doesn\'t have enough points for first time redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_min_points_first_redeem_error_message',
                'css' => 'min-width:550px;',
                'std' => 'You need Minimum of [firstredeempoints] Points when redeeming for the First time',
                'type' => 'textarea',
                'newids' => 'rs_min_points_first_redeem_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Balance Points after First Redeeming', 'rewardsystem'),
                'desc' => __('Enter Minimum Balance Points for Redeeming in Cart/Checkout', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_minimum_user_points_to_redeem',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_minimum_user_points_to_redeem',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Points After first time Redeeming Error Message Show/Hide', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_after_first_redeem_error_message',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_after_first_redeem_error_message',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Minimum Points After first time Redeeming Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when the Current User doesn\'t have minimum points for Redeeming ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_min_points_after_first_error',
                'css' => 'min-width:550px;',
                'std' => 'You need minimum of [points_after_first_redeem] Points for Redeeming',
                'type' => 'textarea',
                'newids' => 'rs_min_points_after_first_error',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Points for Redeeming', 'rewardsystem'),
                'desc' => __('Enter Minimum Points for Redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_minimum_redeeming_points',
                'css' => 'min-width:150px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_minimum_redeeming_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Points for Redeeming', 'rewardsystem'),
                'desc' => __('Enter Maximum Points for Redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_maximum_redeeming_points',
                'css' => 'min-width:150px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_maximum_redeeming_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Redeem Point Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Entered Points is less than Minimum Redeeming Points which is set in this Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_minimum_redeem_point_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter Points more than [rsminimumpoints]',
                'type' => 'text',
                'newids' => 'rs_minimum_redeem_point_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Redeem Point Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Entered Points is more than Maximum Redeeming Points which is set in this Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_maximum_redeem_point_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter Points less than [rsmaximumpoints]',
                'type' => 'text',
                'newids' => 'rs_maximum_redeem_point_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum and Minimum Redeem Point Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Maximum and Minimum Redeeming Points are Equal which is set in this Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_minimum_and_maximum_redeem_point_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter [rsequalpoints] Points ',
                'type' => 'text',
                'newids' => 'rs_minimum_and_maximum_redeem_point_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Redeem Point Error Message for Button type', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Entered Points is less than Minimum Redeeming Points which is set in this Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_minimum_redeem_point_error_message_for_button_type',
                'css' => 'min-width:550px;',
                'std' => 'You can not redeem because the current points to be redemed is less than [rsminimumpoints] Points',
                'type' => 'text',
                'newids' => 'rs_minimum_redeem_point_error_message_for_button_type',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Redeem Point Error Message for Button type', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Entered Points is more than Maximum Redeeming Points which is set in this Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_maximum_redeem_point_error_message_for_button_type',
                'css' => 'min-width:550px;',
                'std' => 'You can not redeem because the current points to be redemed is more than [rsmaximumpoints] points',
                'type' => 'text',
                'newids' => 'rs_maximum_redeem_point_error_message_for_button_type',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum and Minimum Redeem Point Error Message for Button type', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Maximum and Minimum Redeeming Points are Equal which is set in this Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_minimum_and_maximum_redeem_point_error_message_for_buttontype',
                'css' => 'min-width:550px;',
                'std' => 'You can not redeem because the points to be redeemed is not equal to  [rsequalpoints] Points ',
                'type' => 'text',
                'newids' => 'rs_minimum_and_maximum_redeem_point_error_message_for_buttontype',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Cart Total for Redeeming', 'rewardsystem'),
                'desc' => __('Enter Minimum Cart Total for Redeeming', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_minimum_cart_total_points',
                'css' => 'min-width:150px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_minimum_cart_total_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Cart Total for Redeeming Error Message Show/Hide', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_minimum_cart_total_error_message',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_minimum_cart_total_error_message',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Minimum Cart Total for Redeeming Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when current Cart total is less than minimum Cart Total for Redeeming ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_min_cart_total_redeem_error',
                'css' => 'min-width:550px;',
                'std' => 'You need minimum cart Total of [currencysymbol][carttotal] in order to Redeem',
                'type' => 'textarea',
                'newids' => 'rs_min_cart_total_redeem_error',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redeem Field Caption Show/Hide', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_redeem_caption',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_redeem_caption',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Redeem Field Caption', 'rewardsystem'),
                'desc' => __('Enter the Label which will be displayed in Redeem Field', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeem_field_caption',
                'css' => 'min-width:550px;',
                'std' => 'Redeem your Reward Points:',
                'type' => 'text',
                'newids' => 'rs_redeem_field_caption',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redeem Field Placeholder Show/Hide', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_redeem_placeholder',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_redeem_placeholder',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Redeem Field Placeholder', 'rewardsystem'),
                'desc' => __('Enter the Placeholder which will be displayed in Redeem Field', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeem_field_placeholder',
                'css' => 'min-width:550px;',
                'std' => 'Reward Points to Enter',
                'type' => 'text',
                'newids' => 'rs_redeem_field_placeholder',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redeem Field Submit Button Caption', 'rewardsystem'),
                'desc' => __('Enter the Label which will be displayed in Submit Button', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeem_field_submit_button_caption',
                'css' => 'min-width:550px;margin-bottom:40px;',
                'std' => 'Apply Reward Points',
                'type' => 'text',
                'newids' => 'rs_redeem_field_submit_button_caption',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Cart Redeem Error Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_cart_redeem_error_settings'
            ),
            array(
                'name' => __('Empty Redeem Point Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Redeem Field has Empty Value', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeem_empty_error_message',
                'css' => 'min-width:550px;',
                'std' => 'No Reward Points Entered',
                'type' => 'text',
                'newids' => 'rs_redeem_empty_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redeeming Contain Characters', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when redeeming field value contain characters', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeem_character_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter Only Numbers',
                'type' => 'text',
                'newids' => 'rs_redeem_character_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Redeem Point Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when Entered Reward Points is more than Earned Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_redeem_max_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Reward Points you entered is more than Your Earned Reward Points ',
                'type' => 'text',
                'newids' => 'rs_redeem_max_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Current User Points is Empty Error Message Show/Hide', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_points_empty_error_message',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_points_empty_error_message',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Current User Points is Empty Error Message', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed when the Current User Points is Empty', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_current_points_empty_error_message',
                'css' => 'min-width:550px;',
                'std' => 'You don\'t have Points for Redeeming',
                'type' => 'text',
                'newids' => 'rs_current_points_empty_error_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_cart_redeem_error_settings'),
            array(
                'name' => __('Coupon Label Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_cart_redeem_error_settings'
            ),
            array(
                'name' => __('Coupon Label Settings', 'rewardsystem'),
                'desc' => __('Enter the Message which will be displayed in Cart Subtotal', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_coupon_label_message',
                'css' => 'min-width:550px;',
                'std' => 'Redeemed Points Value',
                'type' => 'text',
                'newids' => 'rs_coupon_label_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_cart_redeem_error_settings'),
            array(
                'name' => __('Extra Class Name for Button', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_cart_custom_class_name',
            ),
            array(
                'name' => __('Extra Class Name for Cart Apply Reward Points Button', 'rewardsystem'),
                'desc' => __('Add Extra Class Name to the Cart Apply Reward Points Button, Don\'t Enter dot(.) before Class Name', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_extra_class_name_apply_reward_points',
                'css' => 'min-width:550px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_extra_class_name_apply_reward_points',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_cart_custom_class_name'),
            array(
                'name' => __('Custom CSS Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => 'Try !important if styles doesn\'t apply ',
                'id' => '_rs_cart_custom_css_settings',
            ),
            array(
                'name' => __('Custom CSS', 'rewardsystem'),
                'desc' => __('Enter the Custom CSS for the Cart Page ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_cart_page_custom_css',
                'css' => 'min-width:350px; min-height:350px;',
                'std' => '#rs_apply_coupon_code_field { } #mainsubmi { } .fp_apply_reward{ }',
                'type' => 'textarea',
                'newids' => 'rs_cart_page_custom_css',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_cart_custom_css_settings'),
        ));
    }

    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {

        woocommerce_admin_fields(RSCart::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSCart::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSCart::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }

}

new RSCart();
