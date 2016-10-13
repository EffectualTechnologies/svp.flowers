<?php

class RSUpdate {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_update', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_update', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_update'] = __('Bulk Update','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        $getproductlist = array();
        $categorylist = array();
        $socialproductlists = array();
        $socialproductids = array();
        $socialproducttitles = array();
        $getproductids = array();
        $getproducttitles = array();
        $categoryname = array();
        $categoryid = array();
        $ajaxproductsearch = array();
        $rsproductids = array();
        $rsproduct_name = array();

        $ajaxproductsearchsocial = array();
        $rsproductidssocial = array();
        $rsproduct_namesocial = array();

        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_update') {
                global $woocommerce;
                $product_field_type_ids = get_option('rs_select_particular_products');
                $product_ids = !empty($product_field_type_ids) ? array_map('absint', (array) $product_field_type_ids) : null;
                if ($product_ids) {
                    foreach ($product_ids as $product_id) {
                        $rsproductids[] = $product_id;
                        $productobject = new WC_Product($product_id);
                        $rsproduct_name[] = $productobject->get_formatted_name($rsproductids);
                    }
                }
                @$ajaxproductsearch = array_combine((array) $rsproductids, (array) $rsproduct_name);


                $product_field_type_ids_social = get_option('rs_select_particular_social_products');
                $product_ids_social = !empty($product_field_type_ids_social) ? array_map('absint', (array) $product_field_type_ids_social) : null;
                if ($product_ids_social) {
                    foreach ($product_ids_social as $each_product_id) {
                        $rsproductidssocial[] = $each_product_id;
                        $rsproductobject = new WC_Product($each_product_id);
                        $rsproduct_namesocial[] = $rsproductobject->get_formatted_name($rsproductidssocial);
                    }
                }
                @$ajaxproductsearchsocial = array_combine((array) $rsproductidssocial, (array) $rsproduct_namesocial);



                $listcategories = get_terms('product_cat');             
                if (is_array($listcategories)) {
                    foreach ($listcategories as $category) {
                        $categoryname[] = $category->name;
                        $categoryid[] = $category->term_id;
                    }
                }
               
                @$categorylist = array_combine((array) $categoryid, (array) $categoryname);               
            }
        }
         return apply_filters('woocommerce_rewardsystem_update_settings', array(
            array(
                'name' => __('Bulk Update Settings for Existing Products/Existing Categories', 'rewardsystem'),
                'type' => 'title',                
                'id' => 'rs_update_setting',
            ),   
            array(
                'name' => __('Select Products/Categories', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_which_product_selection',
                'css' => 'min-width:150px;',
                'std' => '1',
                'class' => 'rs_which_product_selection',
                'default' => '1',
                'newids' => 'rs_which_product_selection',
                'type' => 'select',
                'options' => array(
                    '1' => __('All Products', 'rewardsystem'),
                    '2' => __('Selected Products', 'rewardsystem'),
                    '3' => __('All Categories', 'rewardsystem'),
                    '4' => __('Selected Categories', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Selected Particular Products', 'rewardsystem'),
                'type' => 'selected_products',
                'desc' => __('Select Particular Categories in Reward System', 'rewardsystem'),
                'id' => 'rs_select_particular_products',
                'css' => '',
                'class' => 'rs_select_particular_products',
                'newids' => 'rs_select_particular_products',
            ),
            array(
                'name' => __('Select Particular Categories', 'rewardsystem'),
                'desc' => __('Select Particular Categories in Reward System', 'rewardsystem'),
                'id' => 'rs_select_particular_categories',
                'css' => 'min-width:350px;',
                'std' => '1',
                'class' => 'rs_select_particular_categories',
                'default' => '1',
                'newids' => 'rs_select_particular_categories',
                'type' => 'multiselect',
                'options' => $categorylist,
            ),
            array(
                'name' => __('Enable SUMO Reward Points', 'rewardsystem'),                
                'id' => 'rs_local_enable_disable_reward',
                'css' => 'min-width:150px;',
                'std' => '2',
                'default' => '2',
                'placeholder' => '',
                'desc_tip' => true,
                'desc' => __('Enable will Turn On Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. '
                        . 'Disable will Turn Off Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. ', 'rewardsystem'),
                'newids' => 'rs_local_enable_disable_reward',
                'type' => 'select',
                'options' => array(
                    '1' => __('Enable', 'rewardsystem'),
                    '2' => __('Disable', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Reward Type', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_local_reward_type',
                'class' => 'show_if_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_local_reward_type',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_points',
                'class' => 'show_if_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_reward_points',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Reward Points in Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_percent',
                'class' => 'show_if_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_reward_percent',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Referral Reward Type', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_local_referral_reward_type',
                'class' => 'show_if_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_local_referral_reward_type',
                'type' => 'select',
                'desc_tip' => true,
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Referral Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Referral Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_referral_reward_point',
                'class' => 'show_if_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_referral_reward_point',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Referral Reward Points in Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_referral_reward_percent',
                'class' => 'show_if_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_referral_reward_percent',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Test Button', 'rewardsystem'),
                'desc' => __('This is for testing button', 'rewardsystem'),
                'id' => 'rs_sumo_reward_button',
                'css' => '',
                'std' => '',
                'type' => 'button',
                'desc_tip' => true,
                'newids' => 'rs_sumo_reward_button',
            ),
             
            array('type'=>'sectionend', 'id'=>'rs_update_setting'),  
              array(
                'name' => __('Update Point Price Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => 'Here you can update the Point Pricing setting ',
                'id' => '_rs_update_point_priceing'
            ),
              array(
                'name' => __('Select Products/Categories', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_which_point_precing_product_selection',
                'css' => 'min-width:150px;',
                'std' => '1',
                'class' => 'rs_which_point_precing_product_selection',
                'default' => '1',
                'newids' => 'rs_which_point_precing_product_selection',
                'type' => 'select',
                'options' => array(
                    '1' => __('All Products', 'rewardsystem'),
                    '2' => __('Selected Products', 'rewardsystem'),
                    '3' => __('All Categories', 'rewardsystem'),
                    '4' => __('Selected Categories', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
             
               array(
                'name' => __('Selected Particular Products', 'rewardsystem'),
                'type' => 'selected_products_point',
                'desc' => __('Select Particular Categories in Reward System', 'rewardsystem'),
                'id' => 'rs_select_particular_products_for_point_price',
                'css' => '',
                'class' => 'rs_select_particular_products_for_point_price',
                'newids' => 'rs_select_particular_products_for_point_price',
            ),
              array(
                'name' => __('Select Particular Categories', 'rewardsystem'),
                'desc' => __('Select Particular Categories in Reward System', 'rewardsystem'),
                'id' => 'rs_select_particular_categories_for_point_price',
                'css' => 'min-width:350px;',
                'std' => '1',
                'class' => 'rs_select_particular_categories_for_point_price',
                'default' => '1',
                'newids' => 'rs_select_particular_categories_for_point_price',
                'type' => 'multiselect',
                'options' => $categorylist,
            ),
             
             array(
                'name' => __('Enable  Points Prices', 'rewardsystem'),                
                'id' => 'rs_local_enable_disable_point_price',
                'css' => 'min-width:150px;',
                'std' => '2',
                'default' => '2',
                'placeholder' => '',
                'desc_tip' => true,
                'desc' => __('Enable will Turn On  Points Price for Product Purchase and Product Settings will be considered if it is available. '
                        . 'Disable will Turn Off  Points Price for Product Purchase and Product Settings will be considered if it is available. ', 'rewardsystem'),
                'newids' => 'rs_local_enable_disable_point_price',
                'type' => 'select',
                'options' => array(
                    '1' => __('Enable', 'rewardsystem'),
                    '2' => __('Disable', 'rewardsystem'),
                ),
            ),
             
             
             
               array(
                'name' => __('Pricing Type ', 'rewardsystem'),                
                'id' => 'rs_local_point_pricing_type',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'placeholder' => '',
                'desc_tip' => true,
                'desc' => __('Enable will Turn On  Points Price for Product Purchase and Product Settings will be considered if it is available. '
                        . 'Disable will Turn Off  Points Price for Product Purchase and Product Settings will be considered if it is available. ', 'rewardsystem'),
                'newids' => 'rs_local_point_pricing_type',
                'type' => 'select',
                'options' => array(
                    '1' => __('Currency & Point Price', 'rewardsystem'),
                    '2' => __('Only Point Price', 'rewardsystem'),
                ),
            ),
             
             
             
             
             array(
                'name' => __('Points Prices Type ', 'rewardsystem'),                
                'id' => 'rs_local_point_price_type',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'placeholder' => '',
                'desc_tip' => true,
                'desc' => __('Enable will Turn On  Points Price for Product Purchase and Product Settings will be considered if it is available. '
                        . 'Disable will Turn Off  Points Price for Product Purchase and Product Settings will be considered if it is available. ', 'rewardsystem'),
                'newids' => 'rs_local_point_price_type',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed', 'rewardsystem'),
                    '2' => __('Based On Conversion', 'rewardsystem'),
                ),
            ),
             
             array(
                'name' => __('By Fixed Points', 'rewardsystem'),
                'desc' => __('Please Enter Price Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_price_points',
                'class' => 'show_if_price_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_price_points',
                'placeholder' => '',                
                'desc' => __('When left empty, Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
                array(
                'name' => __('Test Button', 'rewardsystem'),
                'desc' => __('This is for testing button', 'rewardsystem'),
                'id' => 'rs_sumo_point_price_button',
                'css' => '',
                'std' => '',
                'type' => 'button_point_price',
                'desc_tip' => true,
                'newids' => 'rs_sumo_point_price_button',
            ),
             
             
             
            array('type'=>'sectionend', 'id'=>'rs_update_setting'),     
           
           
             
            array(
                'name' => __('Bulk Update Social Settings for Existing Products/Existing Categories', 'rewardsystem'),
                'type' => 'title',
                'desc' => 'Here you can update the following Social options for the Existing Products/Existing Categories',
                'id' => '_rs_update_social_settings'
            ),
            array(
                'name' => __('Select Products/Categories', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_which_social_product_selection',
                'css' => 'min-width:150px;',
                'std' => '1',
                'class' => 'rs_which_social_product_selection',
                'default' => '1',
                'newids' => 'rs_which_social_product_selection',
                'type' => 'select',
                'options' => array(
                    '1' => __('All Products', 'rewardsystem'),
                    '2' => __('Selected Products', 'rewardsystem'),
                    '3' => __('All Categories', 'rewardsystem'),
                    '4' => __('Selected Categories', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Selected Particular Products', 'rewardsystem'),
                'type' => 'selected_social_products',
                'desc' => __('Select Particular Categories in Reward System', 'rewardsystem'),
                'id' => 'rs_select_particular_social_products',
                'css' => '',
                'class' => 'rs_select_particular_social_categories',
                'newids' => 'rs_select_particular_social_products',
            ),
            array(
                'name' => __('Select Particular Categories', 'rewardsystem'),
                'desc' => __('Select Particular Categories in Reward System', 'rewardsystem'),
                'id' => 'rs_select_particular_social_categories',
                'css' => 'min-width:350px;',
                'std' => '1',
                'class' => 'rs_select_particular_social_categories',
                'default' => '1',
                'newids' => 'rs_select_particular_social_categories',
                'type' => 'multiselect',
                'options' => $categorylist,
            ),
            array(
                'name' => __('Enable SUMO Reward Points for Social Promotion', 'rewardsystem'),                
                'id' => 'rs_local_enable_disable_social_reward',
                'css' => 'min-width:150px;',
                'std' => '2',
                'default' => '2',
                'placeholder' => '',
                'desc_tip' => true,
                'desc' => __('Enable will Turn On Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. '
                        . 'Disable will Turn Off Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. ', 'rewardsystem'),
                'newids' => 'rs_local_enable_disable_social_reward',
                'type' => 'select',
                'options' => array(
                    '1' => __('Enable', 'rewardsystem'),
                    '2' => __('Disable', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Facebook Like Reward Type', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_local_reward_type_for_facebook',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_local_reward_type_for_facebook',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Facebook Like Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points for Facebook', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_points_facebook',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_reward_points_facebook',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Facebook Like Reward Points in Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_percent_facebook',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_reward_percent_facebook',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
              array(
                'name' => __('Facebook Share Reward Type', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_local_reward_type_for_facebook_share',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_local_reward_type_for_facebook_share',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Facebook Share Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points for Facebook Share', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_points_facebook_share',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_reward_points_facebook_share',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Facebook Share Reward Points in Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_percent_facebook_share',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_reward_percent_facebook_share',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Twitter Tweet Reward Type', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_local_reward_type_for_twitter',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_local_reward_type_for_twitter',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Twitter Tweet Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points for Twitter', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_points_twitter',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_local_reward_points_twitter',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Twitter Tweet Reward Points in Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points for Twitter', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_percent_twitter',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_reward_percent_twitter',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Google+1 Reward Type', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_local_reward_type_for_google',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_local_reward_type_for_google',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Google+1 Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points for Google+', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_points_google',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_local_reward_points_google',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Google+1 Reward Points in Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points for Google+', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_percent_google',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_reward_percent_google',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('VK.com Like Reward Type', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_local_reward_type_for_vk',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_local_reward_type_for_vk',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('VK.com Like Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points for VK', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_points_vk',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_local_reward_points_vk',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('VK.com Like Reward Points in Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points for VK', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_local_reward_percent_vk',
                'class' => 'show_if_social_enable_in_update',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_local_reward_percent_vk',
                'placeholder' => '',                
                'desc' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Test Button', 'rewardsystem'),
                'desc' => __('This is for testing button', 'rewardsystem'),
                'id' => 'rs_sumo_reward_button',
                'css' => '',
                'std' => '',
                'type' => 'button_social',
                'desc_tip' => true,
                'newids' => 'rs_sumo_reward_button',
            ),
            array('type' => 'sectionend', 'id' => '_rs_update_redeem_settings'),
            array(
                'name' => __('Apply Reward Points for Previous Orders', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Here you can Apply Reward Points for Previous Orders', 'rewardsystem'),
                'id' => '_rs_apply_reward_points',
            ),
            array(
                'name' => __('Apply Points for Previous Orders', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_sumo_select_order_range',
                'tip' => '',
                'css' => 'min-width:150px;',
                'std' => '1',
                'type' => 'select',
                'options' => array('1' => 'All Time', '2' => 'Specific Date'),
                'newids' => 'rs_sumo_select_order_range',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Test Button', 'rewardsystem'),
                'desc' => __("This is for Previous Order Reward Points", 'rewardsystem'),
                'id' => 'rs_sumo_reward_previous_order_points',
                'css' => '',
                'std' => '',
                'type' => 'previous_order_button_range',
                'desc_tip' => true,
                'newids' => 'rs_sumo_reward_previous_order_points',
            ),
            array(
                'name' => __('Test Button', 'rewardsystem'),
                'desc' => __("This is for Previous Order Reward Points", 'rewardsystem'),
                'id' => 'rs_sumo_reward_previous_order_points',
                'css' => '',
                'std' => '',
                'type' => 'previous_order_button',
                'desc_tip' => true,
                'newids' => 'rs_sumo_reward_previous_order_points',
            ),
            array('type' => 'sectionend', 'id' => '_rs_update_social_settings'),
            array('type'=>'sectionend', 'id'=>'rs_update_setting'),            
        ));
    }
    
    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSUpdate::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSUpdate::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSUpdate::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
}

new RSUpdate();
