<?php


class FPRewardSystemSingleProductPageTab {
    
    /* Construct the Object */
    
    public function __construct(){
        
        // Add Filter for WooCommerce Update Options Reward System
        add_filter('woocommerce_rs_settings_tabs_array', array($this, 'reward_system_tab_settings'), 10);
        
        // Call to register the admin settings in the Reward System Submenu with general Settings tab
        add_action('woocommerce_rs_settings_tabs_rewardsystem_single_producttab', array($this, 'reward_system_register_admin_settings'));

        add_action('woocommerce_update_options_rewardsystem_single_producttab', array($this, 'reward_system_update_settings'));
        
        // call the init function to update the default settings on page load
        add_action('init', array($this, 'reward_system_default_settings'));
        
        add_action('wp_head',  array($this, 'provide_custom_css_option_single_page'));
    }
    
    
    public static function reward_system_tab_settings($settings_tabs) {
        $settings_tabs['rewardsystem_single_producttab'] = __('Single Product Page', 'rewardsystem');
        return $settings_tabs;
    }
    
    
    // Add Admin Fields in the Array Format
    
    public static function rewardsystem_admin_fields() {
        global $woocommerce;
        return apply_filters('woocommerce_rewardsystem_single_productpage_settings', array(
            array(
                'name' => __('Single Product Page Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __(''),
                'id' => '_rs_reward_point_single_product_page_settings'
            ),            
            array('type' => 'sectionend', 'id' => '_rs_reward_point_single_product_page_settings'),
            array(
                'name' => __('Custom CSS Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => 'Try !important if styles doesn\'t apply ',
                'id' => '_rs_single_product_page_custom_css_settings',
            ),
            array(
                'name' => __('Custom CSS', 'rewardsystem'),
                'desc' => __('Enter the Custom CSS for the Cart Page ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_single_product_page_custom_css',
                'css' => 'min-width:350px; min-height:350px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'rs_single_product_page_custom_css',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_single_product_page_custom_css_settings'),
        ));
    }
    
    
    /*
     * Function to Use Custom CSS in Single Product Page
     *  
     */
    
    public static function provide_custom_css_option_single_page(){
        global $woocommerce;
        if(is_product()){
            ?>
            <style type="text/css">
                <?php
                    echo get_option('rs_single_product_page_custom_css');
                ?> 
            </style>
        
        <?php
        }
    }
    
    


    /**
     * Registering Custom Field Admin Settings of Crowdfunding in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        woocommerce_admin_fields(FPRewardSystemSingleProductPageTab::rewardsystem_admin_fields());
    }
    
    /**
     * Update the Settings on Save Changes may happen in crowdfunding
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(FPRewardSystemSingleProductPageTab::rewardsystem_admin_fields());
    }
    
    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (FPRewardSystemSingleProductPageTab::rewardsystem_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}

$obj = new FPRewardSystemSingleProductPageTab();
?>