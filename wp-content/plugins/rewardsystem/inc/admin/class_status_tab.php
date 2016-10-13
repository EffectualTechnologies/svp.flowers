<?php

class RSStatus {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_status', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_status', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_status'] = __('Status','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        global $woocommerce;
        $newcombinedarray = '';
        $newcombinedarray_gateway_status = '';
        if (function_exists('wc_get_order_statuses')) {
            $orderstatus = str_replace('wc-', '', array_keys(wc_get_order_statuses()));
            $orderslugs = array_values(wc_get_order_statuses());
            $newcombinedarray = array_combine((array) $orderstatus, (array) $orderslugs);
            $newcombinedarray_gateway_status = array_combine((array) $orderstatus, (array) $orderslugs);
        } else {
            $taxonomy = 'shop_order_status';
            $orderstatus = '';
            $orderslugs = '';

            $term_args = array(
                'hide_empty' => false,
                'orderby' => 'date',                   
            );
            $tax_terms = get_terms($taxonomy, $term_args);
            foreach ($tax_terms as $getterms) {
                $orderstatus[] = $getterms->name;
                $orderslugs[] = $getterms->slug;
            }
            $newcombinedarray = array_combine((array) $orderslugs, (array) $orderstatus);
            $newcombinedarray_gateway_status = array_combine((array) $orderslugs, (array) $orderstatus);
        }
        return apply_filters('woocommerce_rewardsystem_status_settings', array(
            array(
                'name' => __('Status Setting', 'rewardsystem'),
                'type' => 'title',                
                'id' => 'rs_status_setting',
            ),
             array(
                'name' => __('Status on which Reward Points for Product Review should be applied', 'rewardsystem'),
                'desc' => __('Here you can set on which Status Reward Points for Product Review should be applied', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_review_reward_status',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'Approve', '2' => 'UnApprove'),
                'newids' => 'rs_review_reward_status',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Reward Points awarded when Order Status is', 'rewardsystem'),
                'desc' => __('Here you can set Reward Points should awarded on which Status of Order', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_order_status_control',
                'css' => 'min-width:150px;',
                'std' => array('completed'),
                'type' => 'multiselect',
                'options' => $newcombinedarray,
                'newids' => 'rs_order_status_control',
                'desc_tip' => true,
            ),
            
             array(
                'name' => __('Redeem Apply when Order Status is', 'rewardsystem'),
                'desc' => __('Here you can set Reward Points should awarded on which Status of Order', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_order_status_control_redeem',
                'css' => 'min-width:150px;',
                'std' => array('completed','pending','processing','on-hold'),
                'type' => 'multiselect',
                'options' => $newcombinedarray,
                'newids' => 'rs_order_status_control_redeem',
                'desc_tip' => true,
            ),
            
            array(
                'name' => __('After Successful payment with SUMO Reward Points Gateway the order status becomes ', 'rewardsystem'),
                'desc' => __('Here you can set what should be the order status after successful payment with SUMO Reward Points Gateway', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_order_status_after_gateway_purchase',
                'css' => '',
                'std' => 'completed',
                'type' => 'radio',
                'options' => $newcombinedarray_gateway_status,
                'newids' => 'rs_order_status_after_gateway_purchase',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_status'),
            array('type'=>'sectionend', 'id'=>'rs_status_setting'),
        ));
    }
    
    
    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSStatus::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSStatus::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSStatus::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}

new RSStatus();