<?php

class RSTroubleshoot {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_troubleshoot', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_troubleshoot', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
        
        add_action('woocommerce_admin_field_rs_add_old_version_points',array($this,'add_old_points_for_all_user'));
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_troubleshoot'] = __('Troubleshoot','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_troubleshoot_settings', array(
            array(
                'name' => __('Troubleshoot Setting', 'rewardsystem'),
                'type' => 'title',                              
                'id' => '_rs_troubleshoot_setting'
            ),                 
            array(
                'name' => __('Troubleshoot Option for Cart Page', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Here you can select the Options for Different Hooks for your theme', 'rewardsystem'),
                'id' => '_rs_reward_point_troubleshoot_cart_page'
            ),
            array(
                'name' => __('Troubleshoot Before Cart Hook', 'rewardsystem'),
                'desc' => __('Here you can select the different hooks in Cart Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_point_troubleshoot_before_cart',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'woocommerce_before_cart', '2' => 'woocommerce_before_cart_table'),
                'newids' => 'rs_reward_point_troubleshoot_before_cart',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Troubleshoot After Cart Hook for Position of Redeem Points Field', 'rewardsystem'),
                'desc' => __('Here you can select the Redeem Point Position Options for Cart Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_point_troubleshoot_after_cart',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'woocommerce_after_cart_table', '2' => 'woocommerce_cart_coupon'),
                'newids' => 'rs_reward_point_troubleshoot_after_cart',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Troubleshoot load Tipsy jQuery settings', 'rewardsystem'),
                'desc' => __('Here you can select to change the load load tipsy option if some jQuery conflict occurs', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_reward_point_enable_tipsy_social_rewards',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'Enable ', '2' => 'Disable'),
                'newids' => 'rs_reward_point_enable_tipsy_social_rewards',
                'desc_tip' => true,
            ),
            array(                               
                'type' => 'rs_add_old_version_points',                
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_troubleshoot_cart_page'),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_troubleshoot_settings'),
           array('type'=>'sectionend', 'id'=>'_rs_troubleshoot_setting'),
           
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSTroubleshoot::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSTroubleshoot::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSTroubleshoot::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
    public static function add_old_points_for_all_user(){
        ?>
        <tr valign="top">
            <th>
                <label for="rs_add_old_points" style="font-size:14px;font-weight:600;"><?php _e('Click Here to Add Existing Points for User','rewardsystem'); ?></label>
            </th>
            <td>
                <input type="button" value="<?php _e('Add Existing Points','rewardsystem'); ?>"  id="rs_add_old_points" name="rs_add_old_points" /><b><span style="font-size: 18px;">(Experimental)</span></b>
                <img class="gif_rs_sumo_reward_button" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/img/update.gif" style="width:32px;height:32px;position:absolute"/>                         
            </td>
        </tr>
        <?php
    }
}
new RSTroubleshoot();