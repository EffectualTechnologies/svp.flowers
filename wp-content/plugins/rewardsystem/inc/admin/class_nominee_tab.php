<?php

class RSNominee {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_nominee', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_nominee', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_nominee'] = __('Nominee','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        global $wp_roles;
        foreach ($wp_roles->roles as $values => $key) {
            $userroleslug[] = $values;
            $userrolename[] = $key['name'];
        }

        $newcombineduserrole = array_combine((array) $userroleslug, (array) $userrolename);
        return apply_filters('woocommerce_rewardsystem_nominee_settings', array(
            array(
                'name' => __('Nominee Settings for Product Purchase in Checkout Page', 'rewardsystem'),
                'type' => 'title',                          
                'id' => '_rs_nominee_setting_in_checkout'
            ),   
            array(
                'name' => __('Show/Hide Nominee Field', 'rewardsystem'),                
                'id' => 'rs_show_hide_nominee_field_in_checkout',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_nominee_field_in_checkout',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),                
            ),
            array(
                'name' => __('My Nominee Label', 'rewardsystem'),
                'desc' => __('Enter the My Nominee Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_nominee_title_in_checkout',
                'css' => 'min-width:350px;',
                'std' => 'My Nominee',
                'type' => 'text',
                'newids' => 'rs_my_nominee_title_in_checkout',
                'desc_tip' => true,
            ),            
            array(
                'name' => __('Select the Nominee for Product Purchase By', 'rewardsystem'),               
                'tip' => '',
                'id' => 'rs_select_type_of_user_for_nominee_checkout',
                'css' => 'min-width:100px;',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1'=>__('By User','rewardsystem'),
                    '2'=>__('By User Role(s)','rewardsystem'),
                ),
                'newids' => 'rs_select_type_of_user_for_nominee_checkout',                
            ),
             array(
                'name' => __('Select the User as Nominee for Product Purchase', 'rewardsystem'),
                'desc' => __('Here you select the users whom you wish to select the user as nominee', 'rewardsystem'),                
                'id' => 'rs_select_users_list_for_nominee_in_checkout',
                'css' => 'min-width:400px;',
                'std' => '',
                'type' => 'rs_select_nominee_for_user_in_checkout',
                'newids' => 'rs_select_users_list_for_nominee_in_checkout',
                'desc_tip' => true,
            ),   
            array(
                'name' => __('Select the User Role to Nominee for Product Purchase', 'rewardsystem'),
                'desc' => '',
                'tip' => '',
                'id' => 'rs_select_users_role_for_nominee_checkout',
                'css' => 'min-width:343px;',
                'std' => '',
                'placeholder' => 'Search for a User Role',
                'type' => 'multiselect',
                'options' => $newcombineduserrole,
                'newids' => 'rs_select_users_role_for_nominee_checkout',
                'desc_tip' => false,
            ),
             array(
                'name' => __('Display Nominee In Checkout Page By Username or E-mail', 'rewardsystem'),               
                'tip' => '',
                'id' => 'rs_select_type_of_user_for_nominee_name_checkout',
                'css' => 'min-width:100px;',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1'=>__('By User E-mail ','rewardsystem'),
                    '2'=>__('By Username','rewardsystem'),
                ),
                ),
            
                        
            array('type'=>'sectionend', 'id'=>'_rs_nominee_setting_in_checkout'),
            
            array(
                'name' => __('Nominee Settings for Product Purchase in My Account Page', 'rewardsystem'),
                'type' => 'title',                          
                'id' => '_rs_nominee_setting'
            ),   
            array(
                'name' => __('Show/Hide Nominee Field', 'rewardsystem'),                
                'id' => 'rs_show_hide_nominee_field',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_nominee_field',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),                
            ),
            array(
                'name' => __('My Nominee Label', 'rewardsystem'),
                'desc' => __('Enter the My Nominee Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_nominee_title',
                'css' => 'min-width:350px;',
                'std' => 'My Nominee',
                'type' => 'text',
                'newids' => 'rs_my_nominee_title',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select the Nominee for Product Purchase By', 'rewardsystem'),               
                'tip' => '',
                'id' => 'rs_select_type_of_user_for_nominee',
                'css' => 'min-width:100px;',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1'=>__('By User','rewardsystem'),
                    '2'=>__('By User Role(s)','rewardsystem'),
                ),
                'newids' => 'rs_select_type_of_user_for_nominee',                
            ),
            array(
                'name' => __('Select the User as Nominee for Product Purchase', 'rewardsystem'),
                'desc' => __('Here you select the users whom you wish to select the user as nominee', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_select_users_list_for_nominee',
                'css' => 'min-width:400px;',
                'std' => '',
                'type' => 'rs_select_nominee_for_user',
                'newids' => 'rs_select_users_list_for_nominee',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select the User Role to Nominee for Product Purchase', 'rewardsystem'),
                'desc' => '',
                'tip' => '',
                'id' => 'rs_select_users_role_for_nominee',
                'css' => 'min-width:343px;',
                'std' => '',
                'placeholder' => 'Search for a User Role',
                'type' => 'multiselect',
                'options' => $newcombineduserrole,
                'newids' => 'rs_select_users_role_for_nominee',
                'desc_tip' => false,
            ),
            
            array(
                'name' => __('Display Nominee In My Account Page By Username or E-Mail', 'rewardsystem'),               
                'tip' => '',
                'id' => 'rs_select_type_of_user_for_nominee_name',
                'css' => 'min-width:100px;',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1'=>__('By User E-mail ','rewardsystem'),
                    '2'=>__('By Username','rewardsystem'),
                ),
                ),
           array('type'=>'sectionend', 'id'=>'_rs_nominee_setting'),
           
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSNominee::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSNominee::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSNominee::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}

new RSNominee();