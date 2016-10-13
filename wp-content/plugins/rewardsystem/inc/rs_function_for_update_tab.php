<?php

class RSFunctionForUpdate {

    public function __construct() {

        add_action('woocommerce_admin_field_selected_products', array($this, 'rs_select_products_to_update'));

        add_action('woocommerce_update_option_selected_products', array($this, 'rs_save_select_products_to_update', 999));

        add_action('woocommerce_admin_field_selected_social_products', array($this, 'rs_select_products_to_update_social'));

        add_action('woocommerce_update_option_rewardsystem_update', array($this, 'rs_save_select_products_to_update_social', 999));

        add_action('admin_head', array($this, 'rs_add_update_chosen_reward_system'));

        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_update') {
                add_action('admin_head', array($this, 'rs_show_or_hide_points_and_precentage'));
            }
        }

        add_action('woocommerce_admin_field_previous_order_button', array($this, 'rs_apply_points_for_previous_order_button'));
        
        add_action('admin_head', array($this, 'rs_send_ajax_points_to_previous_orders'));                
        
        add_action('wp_ajax_nopriv_previousorderpoints', array($this, 'rs_process_ajax_points_to_previous_order'));

        add_action('wp_ajax_previousorderpoints', array($this, 'rs_process_ajax_points_to_previous_order'));                

        add_action('wp_ajax_rssplitajaxoptimizationforpreviousorder', array($this, 'process_chunk_ajax_request_for_previous_orders'));

        add_action('woocommerce_admin_field_button_social', array($this, 'rs_save_button_for_update_social'));

        add_action('woocommerce_admin_field_button', array($this, 'rs_save_button_for_update'));

        add_action('woocommerce_admin_field_previous_order_button_range', array($this, 'rs_add_date_picker'));

        add_action('admin_enqueue_scripts', array($this, 'date_enqueqe_script'));

        add_action('admin_head', array($this, 'check_trigger_button_rewardsystem'));

        add_action('wp_ajax_nopriv_previousproductvalue', array($this, 'get_ajax_request_for_previous_product'));

        add_action('wp_ajax_previousproductvalue', array($this, 'get_ajax_request_for_previous_product'));
        
          add_action('woocommerce_admin_field_button_point_price', array($this, 'rs_save_button_for_update_point_price'));
          
           add_action('woocommerce_admin_field_selected_products_point', array($this, 'rs_select_products_to_update_point_price'));

        add_action('wp_ajax_nopriv_previoussocialproductvalue', array($this, 'get_ajax_request_for_previous_social_product'));

        add_action('wp_ajax_previoussocialproductvalue', array($this, 'get_ajax_request_for_previous_social_product'));

        add_action('wp_ajax_rssplitajaxoptimization', array($this, 'process_chunk_ajax_request_in_rewardsystem'));

        add_action('wp_ajax_rssplitajaxoptimizationsocial', array($this, 'process_chunk_ajax_request_in_social_rewardsystem'));
        
        add_action('admin_head',array($this,'rs_validation_of_input_field_in_update'));
        
        add_action('wp_ajax_previousproductpointpricevalue', array($this, 'get_ajax_request_for_previous_product_point_price'));
        
        add_action('wp_ajax_nopriv_previousproductpointpricevalue', array($this, 'get_ajax_request_for_previous_product_point_price'));

        add_action('wp_ajax_rssplitajaxoptimizationforpointprice', array($this, 'process_chunk_ajax_request_in_rewardsystem_point_price'));
    
        
    }

    public static function rs_select_products_to_update() {

        global $woocommerce;
        if ((float) $woocommerce->version > (float) ('2.2.0')) {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_particular_products"><?php _e('Select Particular Products', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">

                    <input type="hidden" class="wc-product-search" style="width: 350px;" id="rs_select_particular_products" name="rs_select_particular_products" data-placeholder="<?php _e('Search for a product&hellip;', 'rewardsystem'); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
            $json_ids = array();
            if (get_option('rs_select_particular_products') != "") {
                $list_of_produts = get_option('rs_select_particular_products');
                if (!is_array($list_of_produts)) {
                    $list_of_produts = explode(',', $list_of_produts);
                    $product_ids = array_filter(array_map('absint', (array) explode(',', get_option('rs_select_particular_products'))));
                } else {
                    $product_ids = $list_of_produts;
                }
                if ($product_ids != NULL) {
                    foreach ($product_ids as $product_id) {
                        if (isset($product_id)) {
                            $product = wc_get_product($product_id);
                            if (is_object($product)) {
                                $json_ids[$product_id] = wp_kses_post($product->get_formatted_name());
                            }
                        }
                    } echo esc_attr(json_encode($json_ids));
                }
            }
            ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" />                
                </td>            
            </tr>
        <?php } else { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_particular_products"><?php _e('Select Particular Products', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <select multiple name="rs_select_particular_products" style='width:350px;' id='rs_select_particular_products' class="rs_select_particular_products">
                        <?php
                        $selected_products_include_to_update = array_filter((array) get_option('rs_select_particular_products'));
                        if ($selected_products_include_to_update != "") {
                            if (!empty($selected_products_include_to_update)) {
                                $list_of_produts = (array) get_option('rs_select_particular_products');
                                foreach ($list_of_produts as $rs_free_id) {
                                    echo '<option value="' . $rs_free_id . '" ';
                                    selected(1, 1);
                                    echo '>' . ' #' . $rs_free_id . ' &ndash; ' . get_the_title($rs_free_id);
                                }
                            }
                        } else {
                            ?>
                            <option value=""></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
        }
    }
 public static function rs_select_products_to_update_point_price() {

        global $woocommerce;
        if ((float) $woocommerce->version > (float) ('2.2.0')) {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_particular_products_for_point_price"><?php _e('Select Particular Products', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">

                    <input type="hidden" class="wc-product-search" style="width: 350px;" id="rs_select_particular_products_for_point_price" name="rs_select_particular_products_for_point_price" data-placeholder="<?php _e('Search for a product&hellip;', 'rewardsystem'); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
                    $json_ids = array();
                    if (get_option('rs_select_particular_products_for_point_price') != "") {
                        $list_of_produts = get_option('rs_select_particular_products_for_point_price');
                        if (!is_array($list_of_produts)) {
                            $list_of_produts = explode(',', $list_of_produts);
                            $product_ids = array_filter(array_map('absint', (array) explode(',', get_option('rs_select_particular_products_for_point_price'))));
                        } else {
                            $product_ids = $list_of_produts;
                        }
                        if ($product_ids != NULL) {
                            foreach ($product_ids as $product_id) {
                                if (isset($product_id)) {
                                    $product = wc_get_product($product_id);
                                    if (is_object($product)) {
                                        $json_ids[$product_id] = wp_kses_post($product->get_formatted_name());
                                    }
                                }
                            } echo esc_attr(json_encode($json_ids));
                        }
                    }
                    ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" />                
                </td>            
            </tr>
        <?php } else { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_particular_products_for_point_price"><?php _e('Select Particular Products', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <select multiple name="rs_select_particular_products_for_point_price" style='width:350px;' id='rs_select_particular_products_for_point_price' class="rs_select_particular_products_for_point_price">
                        <?php
                        $selected_products_include_to_update = array_filter((array) get_option('rs_select_particular_products_for_point_price'));
                        if ($selected_products_include_to_update != "") {
                            if (!empty($selected_products_include_to_update)) {
                                $list_of_produts = (array) get_option('rs_select_particular_products_for_point_price');
                                foreach ($list_of_produts as $rs_free_id) {
                                    echo '<option value="' . $rs_free_id . '" ';
                                    selected(1, 1);
                                    echo '>' . ' #' . $rs_free_id . ' &ndash; ' . get_the_title($rs_free_id);
                                }
                            }
                        } else {
                            ?>
                            <option value=""></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
        }
    }
    public static function rs_select_products_to_update_social($value) {

        global $woocommerce;

        if ((float) $woocommerce->version > (float) ('2.2.0')) {
            ?>

            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_particular_social_products"><?php _e('Select Particular Products', 'rewardsystem'); ?></label>
                </th>            
                <td class="forminp forminp-select">
                    <input type="hidden" class="wc-product-search" style="width: 350px;" id="rs_select_particular_social_products" name="rs_select_particular_social_products" data-placeholder="<?php _e('Search for a product&hellip;', 'rewardsystem'); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
            $json_idss = array();
            if (get_option('rs_select_particular_social_products') != "") {

                $list_of_produtss = get_option('rs_select_particular_social_products');
                

                $list_of_productss = explode(',', get_option('rs_select_particular_social_products'));

                $product_idss = array_filter(array_map('absint', (array) explode(',', get_option('rs_select_particular_social_products'))));

                foreach ($product_idss as $product_ide) {
                    $product = wc_get_product($product_ide);               
                    if(is_object($product)){                   
                        $json_idss[$product_ide] = wp_kses_post($product->get_formatted_name());
                    }
                } echo esc_attr(json_encode($json_idss));
            }
            ?>" value="<?php echo implode(',', array_keys($json_idss)); ?>" />
                </td>
            </tr>
        <?php } else { ?>

            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_particular_social_products"><?php _e('Select Particular Products', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <select multiple name="rs_select_particular_social_products" style='width:350px;' id='rs_select_particular_social_products' class="rs_select_particular_social_products">
                        <?php
                        $selected_socialproducts_exclude = array_filter((array) get_option('rs_select_particular_social_products'));
                        if ($selected_socialproducts_exclude != "") {
                            if (!empty($selected_socialproducts_exclude)) {
                                $list_of_produts = (array) get_option('rs_select_particular_social_products');
                                foreach ($list_of_produts as $rs_free_id) {
                                    echo '<option value="' . $rs_free_id . '" ';
                                    selected(1, 1);
                                    echo '>' . ' #' . $rs_free_id . ' &ndash; ' . get_the_title($rs_free_id);
                                }
                            }
                        } else {
                            ?>
                            <option value=""></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
        }
    }

    public static function rs_save_select_products_to_update_social($value) {

        update_option('rs_select_particular_social_products', $_POST['rs_select_particular_social_products']);
    }

    public static function rs_save_select_products_to_update($value) {

        update_option('rs_select_particular_products', $_POST['rs_select_particular_products']);
    }
    
      public static function rs_save_select_products_to_update_for_point_price($value) {

        update_option('rs_select_particular_products_for_point_price', $_POST['rs_select_particular_products_for_point_price']);
    }

    public static function rs_add_update_chosen_reward_system() {
        global $woocommerce;
        if (isset($_GET['page'])) {
            if ($_GET['page'] == 'rewardsystem_callback') {
                if (isset($_GET['tab'])) {
                    if ($_GET['tab'] == 'rewardsystem_update') {
                        echo RSJQueryFunction::rs_common_ajax_function_to_select_products('rs_select_particular_products');
                        echo RSJQueryFunction::rs_common_ajax_function_to_select_products('rs_select_particular_social_products');
 echo RSJQueryFunction::rs_common_ajax_function_to_select_products('rs_select_particular_products_for_point_price');
                       
                        
                        if ((float) $woocommerce->version <= (float) ('2.2.0')) {

                              echo RSJQueryFunction::rs_common_chosen_function('#rs_select_particular_categories_for_point_price');
                            echo RSJQueryFunction::rs_common_chosen_function('#rs_select_particular_products');
                            echo RSJQueryFunction::rs_common_chosen_function('#rs_select_particular_social_products');
                            echo RSJQueryFunction::rs_common_chosen_function('#rs_select_particular_categories');
                            echo RSJQueryFunction::rs_common_chosen_function('#rs_select_particular_social_categories');
                        } else {

                             echo RSJQueryFunction::rs_common_select_function('#rs_select_particular_categories_for_point_price');
                            echo RSJQueryFunction::rs_common_select_function('#rs_select_particular_categories');
                            echo RSJQueryFunction::rs_common_select_function('#rs_select_particular_social_categories');
                        }
                    }
                }                
                ?>
                <script type="text/javascript">
                    jQuery(function () {
                        jQuery('.gif_rs_sumo_reward_button').css('display', 'none');
                        jQuery('.gif_rs_sumo_reward_button_social').css('display', 'none');
                          jQuery('.gif_rs_sumo_point_price_button').css('display', 'none');

                    });
                    jQuery(document).ready(function () {                        
                        if ((jQuery('.rs_which_point_precing_product_selection').val() === '1')) {
                            jQuery('#rs_select_particular_products_for_point_price').parent().parent().hide();
                            jQuery('#rs_select_particular_categories_for_point_price').parent().parent().hide();
                        } else if (jQuery('.rs_which_point_precing_product_selection').val() === '2') {
                            jQuery('#rs_select_particular_products_for_point_price').parent().parent().show();
                            jQuery('#rs_select_particular_categories_for_point_price').parent().parent().hide();
                        } else if (jQuery('.rs_which_point_precing_product_selection').val() === '3') {
                            jQuery('#rs_select_particular_products_for_point_price').parent().parent().hide();
                            jQuery('#rs_select_particular_categories_for_point_price').parent().parent().hide();
                        } else {
                            jQuery('#rs_select_particular_categories_for_point_price').parent().parent().show();
                            jQuery('#rs_select_particular_products_for_point_price').parent().parent().hide();
                        }


                        jQuery('.rs_which_point_precing_product_selection').change(function () {
                            if ((jQuery(this).val() === '1')) {
                                jQuery('#rs_select_particular_categories_for_point_price').parent().parent().hide();
                                jQuery('#rs_select_particular_products_for_point_price').parent().parent().hide();
                            } else if (jQuery(this).val() === '2') {
                                jQuery('#rs_select_particular_products_for_point_price').parent().parent().show();
                                jQuery('#rs_select_particular_categories_for_point_price').parent().parent().hide();
                            } else  if(jQuery(this).val() === '3'){
                                jQuery('#rs_select_particular_categories_for_point_price').parent().parent().hide();
                                jQuery('#rs_select_particular_products_for_point_price').parent().parent().hide();
                            }else {
                                jQuery('#rs_select_particular_categories_for_point_price').parent().parent().show();
                                jQuery('#rs_select_particular_products_for_point_price').parent().parent().hide();
                            }
                        });
                        
                        
                        

                        if ((jQuery('.rs_which_product_selection').val() === '1')) {
                            jQuery('#rs_select_particular_products').parent().parent().hide();
                            jQuery('#rs_select_particular_categories').parent().parent().hide();
                        } else if (jQuery('.rs_which_product_selection').val() === '2') {
                            jQuery('#rs_select_particular_products').parent().parent().show();
                            jQuery('#rs_select_particular_categories').parent().parent().hide();
                        } else if (jQuery('.rs_which_product_selection').val() === '3') {
                            jQuery('#rs_select_particular_products').parent().parent().hide();
                            jQuery('#rs_select_particular_categories').parent().parent().hide();
                        } else {
                            jQuery('#rs_select_particular_categories').parent().parent().show();
                            jQuery('#rs_select_particular_products').parent().parent().hide();
                        }

                        if ((jQuery('.rs_which_social_product_selection').val() === '1')) {
                            jQuery('#rs_select_particular_social_products').parent().parent().hide();
                            jQuery('#rs_select_particular_social_categories').parent().parent().hide();
                        } else if (jQuery('.rs_which_social_product_selection').val() === '2') {
                            jQuery('#rs_select_particular_social_products').parent().parent().show();
                            jQuery('#rs_select_particular_social_categories').parent().parent().hide();
                        } else if (jQuery('.rs_which_social_product_selection').val() === '3') {
                            jQuery('#rs_select_particular_social_products').parent().parent().hide();
                            jQuery('#rs_select_particular_social_categories').parent().parent().hide();
                        } else {
                            jQuery('#rs_select_particular_social_categories').parent().parent().show();
                            jQuery('#rs_select_particular_social_products').parent().parent().hide();
                        }

                        jQuery('.rs_which_product_selection').change(function () {
                            if ((jQuery(this).val() === '1') || (jQuery(this).val() === '3')) {
                                jQuery('#rs_select_particular_products').parent().parent().hide();
                                jQuery('#rs_select_particular_categories').parent().parent().hide();
                            } else if (jQuery(this).val() === '2') {
                                jQuery('#rs_select_particular_products').parent().parent().show();
                                jQuery('#rs_select_particular_categories').parent().parent().hide();
                            } else {
                                jQuery('#rs_select_particular_categories').parent().parent().show();
                                jQuery('#rs_select_particular_products').parent().parent().hide();
                            }
                        });

                        jQuery('.rs_which_social_product_selection').change(function () {
                            if ((jQuery(this).val() === '1') || (jQuery(this).val() === '3')) {
                                jQuery('#rs_select_particular_social_products').parent().parent().hide();
                                jQuery('#rs_select_particular_social_categories').parent().parent().hide();
                            } else if (jQuery(this).val() === '2') {
                                jQuery('#rs_select_particular_social_products').parent().parent().show();
                                jQuery('#rs_select_particular_social_categories').parent().parent().hide();
                            } else {
                                jQuery('#rs_select_particular_social_categories').parent().parent().show();
                                jQuery('#rs_select_particular_social_products').parent().parent().hide();
                            }
                        });

                        var selectrangevalue = jQuery('#rs_sumo_select_order_range').val();
                        if (selectrangevalue === '1') {
                            jQuery('#rs_from_date').parent().parent().hide();
                        } else {
                            jQuery('#rs_from_date').parent().parent().show();
                        }
                        jQuery('#rs_sumo_select_order_range').change(function () {
                            if (jQuery(this).val() === '1') {
                                jQuery('#rs_from_date').parent().parent().hide();
                            } else {
                                jQuery('#rs_from_date').parent().parent().show();
                            }
                        });
                    });

                </script>
                <?php
            }
        }
    }

    public static function rs_show_or_hide_points_and_precentage() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {

                /*
                 * Show or Hide For Reward Points In Update
                 */
                
                if (jQuery('#rs_local_enable_disable_point_price').val() == '2') {
                    jQuery('.show_if_price_enable_in_update').parent().parent().hide();
                    jQuery('#rs_local_point_price_type').parent().parent().hide();
                     jQuery('#rs_local_point_pricing_type').parent().parent().hide();
                    
                } else {
                     jQuery('#rs_local_point_price_type').parent().parent().show();
                        jQuery('#rs_local_point_pricing_type').parent().parent().show();
                      if (jQuery('#rs_local_point_pricing_type').val() == '2') {                   
                        jQuery('#rs_local_point_price_type').parent().parent().hide();
                        
                    } else {                                             
                         jQuery('#rs_local_point_price_type').parent().parent().show();
                         if (jQuery('#rs_local_point_price_type').val() == '1') {
                    jQuery('.show_if_price_enable_in_update').parent().parent().show();                     
                } 
                if (jQuery('#rs_local_point_price_type').val() == '1') {
                    jQuery('.show_if_price_enable_in_update').parent().parent().show();                                 
                } 
                   
                }
                   
                }

                
                jQuery('#rs_local_enable_disable_point_price').change(function () {
                    if (jQuery(this).val() == '2') {
                        jQuery('.show_if_price_enable_in_update').parent().parent().hide();
                        jQuery('#rs_local_point_price_type').parent().parent().hide();
                         jQuery('#rs_local_point_pricing_type').parent().parent().hide();
                    } else {
                       
                   
                          jQuery('#rs_local_point_pricing_type').parent().parent().show();
                         if (jQuery('#rs_local_point_pricing_type').val() == '2') {                   
                        jQuery('#rs_local_point_price_type').parent().parent().hide();
                          jQuery('#rs_local_price_points').parent().parent().show();
                        
                        
                    } else {                                             
                         jQuery('#rs_local_point_price_type').parent().parent().show();
                         if (jQuery('#rs_local_point_price_type').val() == '1') {
                    jQuery('.show_if_price_enable_in_update').parent().parent().show();                     
                } 
                if (jQuery('#rs_local_point_price_type').val() == '1') {
                    jQuery('.show_if_price_enable_in_update').parent().parent().show();                                 
                } 
                   
                }
                    }
                });
                
                jQuery('#rs_local_point_pricing_type').change(function () {
                    if (jQuery(this).val() == '2') {                   
                        jQuery('#rs_local_point_price_type').parent().parent().hide();
                        
                    } else {                                             
                         jQuery('#rs_local_point_price_type').parent().parent().show();
                         if (jQuery('#rs_local_point_price_type').val() == '1') {
                    jQuery('.show_if_price_enable_in_update').parent().parent().show();
                   
                    
                } 
                    }
                });
                 if (jQuery('#rs_local_point_price_type').val() == '2') {
                    jQuery('.show_if_price_enable_in_update').parent().parent().hide();
                   
                    
                } 
                
                jQuery('#rs_local_point_price_type').change(function () {
                    if (jQuery(this).val() == '2') {
                        jQuery('.show_if_price_enable_in_update').parent().parent().hide();
                       
                    } else {
                       
                         jQuery('.show_if_price_enable_in_update').parent().parent().show();
                    }
                });
                
        if (jQuery('#rs_local_enable_disable_reward').val() == '2') {
                    jQuery('.show_if_enable_in_update').parent().parent().hide();
                } else {
                    jQuery('.show_if_enable_in_update').parent().parent().show();

                    if (jQuery('#rs_local_reward_type').val() === '1') {
                        jQuery('#rs_local_reward_points').parent().parent().show();
                        jQuery('#rs_local_reward_percent').parent().parent().hide();
                    } else {
                        jQuery('#rs_local_reward_points').parent().parent().hide();
                        jQuery('#rs_local_reward_percent').parent().parent().show();
                    }


                    if (jQuery('#rs_local_referral_reward_type').val() === '1') {
                        jQuery('#rs_local_referral_reward_point').parent().parent().show();
                        jQuery('#rs_local_referral_reward_percent').parent().parent().hide();
                    } else {
                        jQuery('#rs_local_referral_reward_point').parent().parent().hide();
                        jQuery('#rs_local_referral_reward_percent').parent().parent().show();
                    }

                    jQuery('#rs_local_reward_type').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_local_reward_points').parent().parent().show();
                            jQuery('#rs_local_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points').parent().parent().hide();
                            jQuery('#rs_local_reward_percent').parent().parent().show();
                        }
                    });
                    jQuery('#rs_local_referral_reward_type').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_local_referral_reward_point').parent().parent().show();
                            jQuery('#rs_local_referral_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_referral_reward_point').parent().parent().hide();
                            jQuery('#rs_local_referral_reward_percent').parent().parent().show();
                        }
                    });

                }

                jQuery('#rs_local_enable_disable_reward').change(function () {
                    if (jQuery(this).val() == '2') {
                        jQuery('.show_if_enable_in_update').parent().parent().hide();
                    } else {
                        jQuery('.show_if_enable_in_update').parent().parent().show();

                        if (jQuery('#rs_local_reward_type').val() === '1') {
                            jQuery('#rs_local_reward_points').parent().parent().show();
                            jQuery('#rs_local_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points').parent().parent().hide();
                            jQuery('#rs_local_reward_percent').parent().parent().show();
                        }


                        if (jQuery('#rs_local_referral_reward_type').val() === '1') {
                            jQuery('#rs_local_referral_reward_point').parent().parent().show();
                            jQuery('#rs_local_referral_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_referral_reward_point').parent().parent().hide();
                            jQuery('#rs_local_referral_reward_percent').parent().parent().show();
                        }

                        jQuery('#rs_local_reward_type').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_local_reward_points').parent().parent().show();
                                jQuery('#rs_local_reward_percent').parent().parent().hide();
                            } else {
                                jQuery('#rs_local_reward_points').parent().parent().hide();
                                jQuery('#rs_local_reward_percent').parent().parent().show();
                            }
                        });
                        jQuery('#rs_local_referral_reward_type').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_local_referral_reward_point').parent().parent().show();
                                jQuery('#rs_local_referral_reward_percent').parent().parent().hide();
                            } else {
                                jQuery('#rs_local_referral_reward_point').parent().parent().hide();
                                jQuery('#rs_local_referral_reward_percent').parent().parent().show();
                            }
                        });
                    }
                });

                /*
                 * End of Show or Hide For Reward Points In Update
                 */


                /*
                 * Show or Hide For Social Reward Points In Update
                 */
                if (jQuery('#rs_local_enable_disable_social_reward').val() == '2') {
                    jQuery('.show_if_social_enable_in_update').parent().parent().hide();
                } else {
                    jQuery('.show_if_social_enable_in_update').parent().parent().show();

                    if (jQuery('#rs_local_reward_type_for_facebook').val() === '1') {
                        jQuery('#rs_local_reward_points_facebook').parent().parent().show();
                        jQuery('#rs_local_reward_percent_facebook').parent().parent().hide();
                    } else {
                        jQuery('#rs_local_reward_points_facebook').parent().parent().hide();
                        jQuery('#rs_local_reward_percent_facebook').parent().parent().show();
                    }

                    jQuery('#rs_local_reward_type_for_facebook').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_local_reward_points_facebook').parent().parent().show();
                            jQuery('#rs_local_reward_percent_facebook').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points_facebook').parent().parent().hide();
                            jQuery('#rs_local_reward_percent_facebook').parent().parent().show();
                        }
                    });
                    
                    if (jQuery('#rs_local_reward_type_for_facebook_share').val() === '1') {
                        jQuery('#rs_local_reward_points_facebook_share').parent().parent().show();
                        jQuery('#rs_local_reward_percent_facebook_share').parent().parent().hide();
                    } else {
                        jQuery('#rs_local_reward_points_facebook_share').parent().parent().hide();
                        jQuery('#rs_local_reward_percent_facebook_share').parent().parent().show();
                    }

                    jQuery('#rs_local_reward_type_for_facebook_share').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_local_reward_points_facebook_share').parent().parent().show();
                            jQuery('#rs_local_reward_percent_facebook_share').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points_facebook_share').parent().parent().hide();
                            jQuery('#rs_local_reward_percent_facebook_share').parent().parent().show();
                        }
                    });
                    if (jQuery('#rs_local_reward_type_for_twitter').val() === '1') {
                        jQuery('#rs_local_reward_points_twitter').parent().parent().show();
                        jQuery('#rs_local_reward_percent_twitter').parent().parent().hide();
                    } else {
                        jQuery('#rs_local_reward_points_twitter').parent().parent().hide();
                        jQuery('#rs_local_reward_percent_twitter').parent().parent().show();
                    }

                    jQuery('#rs_local_reward_type_for_twitter').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_local_reward_points_twitter').parent().parent().show();
                            jQuery('#rs_local_reward_percent_twitter').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points_twitter').parent().parent().hide();
                            jQuery('#rs_local_reward_percent_twitter').parent().parent().show();
                        }
                    });
                    if (jQuery('#rs_local_reward_type_for_vk').val() === '1') {
                        jQuery('#rs_local_reward_points_vk').parent().parent().show();
                        jQuery('#rs_local_reward_percent_vk').parent().parent().hide();
                    } else {
                        jQuery('#rs_local_reward_points_vk').parent().parent().hide();
                        jQuery('#rs_local_reward_percent_vk').parent().parent().show();
                    }

                    jQuery('#rs_local_reward_type_for_vk').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_local_reward_points_vk').parent().parent().show();
                            jQuery('#rs_local_reward_percent_vk').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points_vk').parent().parent().hide();
                            jQuery('#rs_local_reward_percent_vk').parent().parent().show();
                        }
                    });
                    if (jQuery('#rs_local_reward_type_for_google').val() === '1') {
                        jQuery('#rs_local_reward_points_google').parent().parent().show();
                        jQuery('#rs_local_reward_percent_google').parent().parent().hide();
                    } else {
                        jQuery('#rs_local_reward_points_google').parent().parent().hide();
                        jQuery('#rs_local_reward_percent_google').parent().parent().show();
                    }

                    jQuery('#rs_local_reward_type_for_google').change(function () {
                        if ((jQuery(this).val()) === '1') {
                            jQuery('#rs_local_reward_points_google').parent().parent().show();
                            jQuery('#rs_local_reward_percent_google').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points_google').parent().parent().hide();
                            jQuery('#rs_local_reward_percent_google').parent().parent().show();
                        }
                    });
                }

                jQuery('#rs_local_enable_disable_social_reward').change(function () {
                    if (jQuery(this).val() == '2') {
                        jQuery('.show_if_social_enable_in_update').parent().parent().hide();
                    } else {
                        jQuery('.show_if_social_enable_in_update').parent().parent().show();

                        if (jQuery('#rs_local_reward_type_for_facebook').val() === '1') {
                            jQuery('#rs_local_reward_points_facebook').parent().parent().show();
                            jQuery('#rs_local_reward_percent_facebook').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points_facebook').parent().parent().hide();
                            jQuery('#rs_local_reward_percent_facebook').parent().parent().show();
                        }

                        jQuery('#rs_local_reward_type_for_facebook').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_local_reward_points_facebook').parent().parent().show();
                                jQuery('#rs_local_reward_percent_facebook').parent().parent().hide();
                            } else {
                                jQuery('#rs_local_reward_points_facebook').parent().parent().hide();
                                jQuery('#rs_local_reward_percent_facebook').parent().parent().show();
                            }
                        });
                        
                        if (jQuery('#rs_local_reward_type_for_facebook_share').val() === '1') {
                            jQuery('#rs_local_reward_points_facebook_share').parent().parent().show();
                            jQuery('#rs_local_reward_percent_facebook_share').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points_facebook_share').parent().parent().hide();
                            jQuery('#rs_local_reward_percent_facebook_share').parent().parent().show();
                        }

                        jQuery('#rs_local_reward_type_for_facebook_share').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_local_reward_points_facebook_share').parent().parent().show();
                                jQuery('#rs_local_reward_percent_facebook_share').parent().parent().hide();
                            } else {
                                jQuery('#rs_local_reward_points_facebook_share').parent().parent().hide();
                                jQuery('#rs_local_reward_percent_facebook_share').parent().parent().show();
                            }
                        });
                        
                        if (jQuery('#rs_local_reward_type_for_twitter').val() === '1') {
                            jQuery('#rs_local_reward_points_twitter').parent().parent().show();
                            jQuery('#rs_local_reward_percent_twitter').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points_twitter').parent().parent().hide();
                            jQuery('#rs_local_reward_percent_twitter').parent().parent().show();
                        }

                        jQuery('#rs_local_reward_type_for_twitter').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_local_reward_points_twitter').parent().parent().show();
                                jQuery('#rs_local_reward_percent_twitter').parent().parent().hide();
                            } else {
                                jQuery('#rs_local_reward_points_twitter').parent().parent().hide();
                                jQuery('#rs_local_reward_percent_twitter').parent().parent().show();
                            }
                        });
                        if (jQuery('#rs_local_reward_type_for_vk').val() === '1') {
                            jQuery('#rs_local_reward_points_vk').parent().parent().show();
                            jQuery('#rs_local_reward_percent_vk').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points_vk').parent().parent().hide();
                            jQuery('#rs_local_reward_percent_vk').parent().parent().show();
                        }

                        jQuery('#rs_local_reward_type_for_vk').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_local_reward_points_vk').parent().parent().show();
                                jQuery('#rs_local_reward_percent_vk').parent().parent().hide();
                            } else {
                                jQuery('#rs_local_reward_points_vk').parent().parent().hide();
                                jQuery('#rs_local_reward_percent_vk').parent().parent().show();
                            }
                        });
                        if (jQuery('#rs_local_reward_type_for_google').val() === '1') {
                            jQuery('#rs_local_reward_points_google').parent().parent().show();
                            jQuery('#rs_local_reward_percent_google').parent().parent().hide();
                        } else {
                            jQuery('#rs_local_reward_points_google').parent().parent().hide();
                            jQuery('#rs_local_reward_percent_google').parent().parent().show();
                        }

                        jQuery('#rs_local_reward_type_for_google').change(function () {
                            if ((jQuery(this).val()) === '1') {
                                jQuery('#rs_local_reward_points_google').parent().parent().show();
                                jQuery('#rs_local_reward_percent_google').parent().parent().hide();
                            } else {
                                jQuery('#rs_local_reward_points_google').parent().parent().hide();
                                jQuery('#rs_local_reward_percent_google').parent().parent().show();
                            }
                        });
                    }
                });
            });</script>
        <?php
    }

    public static function check_trigger_button_rewardsystem() {
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function () {
                
                 jQuery('.rs_sumo_point_price_button').click(function () {
                    jQuery('.gif_rs_sumo_point_price_button').css('display', 'inline-block');
                    var whichproduct = jQuery('#rs_which_point_precing_product_selection').val();
                    var enabledisablepoints = jQuery('#rs_local_enable_disable_point_price').val();
                   var pointpricetype=jQuery('#rs_local_point_price_type').val();
                    var selectparticularproducts = jQuery('#rs_select_particular_products_for_point_price').val();
                    var pricepoints = jQuery('#rs_local_price_points').val();
                    var selectedcategories = jQuery('#rs_select_particular_categories_for_point_price').val();
                     var pointpricingtype=jQuery('#rs_local_point_pricing_type').val();
                    jQuery(this).attr('data-clicked', '1');
                    var dataclicked = jQuery(this).attr('data-clicked');
                    var dataparam = ({
                        action: 'previousproductpointpricevalue',
                        proceedanyway: dataclicked,
                        whichproduct: whichproduct,
                        enabledisablepoints: enabledisablepoints,
                         pointpricetype:pointpricetype,
                        selectedproducts: selectparticularproducts,
                        pricepoints: pricepoints,
                        selectedcategories:selectedcategories,
                         pointpricingtype:pointpricingtype,
                    });

                    function getDatapointprice(id) {
                        return jQuery.ajax({
                            type: 'POST',
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            data: ({action: 'rssplitajaxoptimizationforpointprice',
                                ids: id,
                                enabledisablepoints: enabledisablepoints,
                                selectedproducts: selectparticularproducts,
                                 pointpricetype:pointpricetype,
                                pricepoints: pricepoints,
                                pointpricetype:pointpricetype,
                                selectedcategories:selectedcategories,
                                 pointpricingtype:pointpricingtype,
                            }),
                            success: function (response) {
                                console.log(response);
                            },
                            dataType: 'json',
                            async: false
                        });
                    }


                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                            function (response) {
                                console.log(response);
                                if (response !== 'success') {
                                    var j = 1;
                                    var i, j, temparray, chunk = 10;
                                    for (i = 0, j = response.length; i < j; i += chunk) {
                                        temparray = response.slice(i, i + chunk);
                                        getDatapointprice(temparray);
                                    }
                                    jQuery.when(getDatapointprice()).done(function (a1) {
                                        console.log('Ajax Done Successfully');
                                        jQuery('.submit .button-primary').trigger('click');
                                    });
                                } else {
                                    var newresponse = response.replace(/\s/g, '');
                                    if (newresponse === 'success') {
                                        jQuery('.submit .button-primary').trigger('click');
                                    }
                                }
                            }, 'json');
                    return false;

                });

                
                
                jQuery('.rs_sumo_reward_button').click(function () {
                    jQuery('.gif_rs_sumo_reward_button').css('display', 'inline-block');
                    var whichproduct = jQuery('#rs_which_product_selection').val();
                    var enabledisablereward = jQuery('#rs_local_enable_disable_reward').val();
                    var selectparticularproducts = jQuery('#rs_select_particular_products').val();
                    var selectedcategories = jQuery('#rs_select_particular_categories').val();
                    var rewardtype = jQuery('#rs_local_reward_type').val();
                    var rewardpoints = jQuery('#rs_local_reward_points').val();
                    var rewardpercent = jQuery('#rs_local_reward_percent').val();
                    var referralrewardtype = jQuery('#rs_local_referral_reward_type').val();
                    var referralrewardpoint = jQuery('#rs_local_referral_reward_point').val();
                    var referralrewardpercent = jQuery('#rs_local_referral_reward_percent').val();

                    jQuery(this).attr('data-clicked', '1');
                    var dataclicked = jQuery(this).attr('data-clicked');
                    var dataparam = ({
                        action: 'previousproductvalue',
                        proceedanyway: dataclicked,
                        whichproduct: whichproduct,
                        enabledisablereward: enabledisablereward,
                        selectedproducts: selectparticularproducts,
                        selectedcategories: selectedcategories,
                        rewardtype: rewardtype,
                        rewardpoints: rewardpoints,
                        rewardpercent: rewardpercent,
                        referralrewardtype: referralrewardtype,
                        referralrewardpoint: referralrewardpoint,
                        referralrewardpercent: referralrewardpercent,
                    });
                    function getData(id) {
                        return jQuery.ajax({
                            type: 'POST',
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            data: ({action: 'rssplitajaxoptimization',
                                ids: id,
                                enabledisablereward: enabledisablereward,
                                selectedproducts: selectparticularproducts,
                                selectedcategories: selectedcategories,
                                rewardtype: rewardtype,
                                rewardpoints: rewardpoints,
                                rewardpercent: rewardpercent,
                                referralrewardtype: referralrewardtype,
                                referralrewardpoint: referralrewardpoint,
                                referralrewardpercent: referralrewardpercent,
                            }),
                            success: function (response) {
                                console.log(response);                                
                            },
                            dataType: 'json',
                            async: false
                        });
                    }
                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                            function (response) {
                                console.log(response);                                
                                if (response !== 'success') {
                                    var j = 1;
                                    var i, j, temparray, chunk = 10;
                                    for (i = 0, j = response.length; i < j; i += chunk) {
                                        temparray = response.slice(i, i + chunk);
                                        getData(temparray);
                                    }
                                    jQuery.when(getData()).done(function (a1) {
                                        console.log('Ajax Done Successfully');
                                        jQuery('.submit .button-primary').trigger('click');
                                    });
                                } else {
                                    var newresponse = response.replace(/\s/g, '');
                                    if (newresponse === 'success') {
                                        jQuery('.submit .button-primary').trigger('click');
                                    }
                                }
                            }, 'json');
                    return false;
                });
                jQuery('.rs_sumo_reward_button_social').click(function () {
                    jQuery('.gif_rs_sumo_reward_button_social').css('display', 'inline-block');
                    var whichproduct = jQuery('#rs_which_social_product_selection').val();
                    var enabledisablereward = jQuery('#rs_local_enable_disable_social_reward').val();
                    var selectparticularproducts = jQuery('#rs_select_particular_social_products').val();
                    var selectedcategories = jQuery('#rs_select_particular_social_categories').val();
                    var rewardtypefacebook = jQuery('#rs_local_reward_type_for_facebook').val();
                    var facebookrewardpoints = jQuery('#rs_local_reward_points_facebook').val();
                    var facebookrewardpercent = jQuery('#rs_local_reward_percent_facebook').val();
                     var rewardtypefacebook_share = jQuery('#rs_local_reward_type_for_facebook_share').val();
                    var facebookrewardpoints_share= jQuery('#rs_local_reward_points_facebook_share').val();
                    var facebookrewardpercent_share = jQuery('#rs_local_reward_percent_facebook_share').val();
                    var rewardtypetwitter = jQuery('#rs_local_reward_type_for_twitter').val();
                    var twitterrewardpoints = jQuery('#rs_local_reward_points_twitter').val();
                    var twitterrewardpercent = jQuery('#rs_local_reward_percent_twitter').val();
                    var rewardtypegoogle = jQuery('#rs_local_reward_type_for_google').val();
                    var googlerewardpoints = jQuery('#rs_local_reward_points_google').val();
                    var googlerewardpercent = jQuery('#rs_local_reward_percent_google').val();
                    var rewardtypevk = jQuery('#rs_local_reward_type_for_vk').val();
                    var vkrewardpoints = jQuery('#rs_local_reward_points_vk').val();
                    var vkrewardpercent = jQuery('#rs_local_reward_percent_vk').val();
                    jQuery(this).attr('data-clicked', '1');
                    var dataclicked = jQuery(this).attr('data-clicked');
                    var dataparam = ({
                        action: 'previoussocialproductvalue',
                        proceedanyway: dataclicked,
                        whichproduct: whichproduct,
                        enabledisablereward: enabledisablereward,
                        selectedproducts: selectparticularproducts,
                        selectedcategories: selectedcategories,
                        rewardtypefacebook: rewardtypefacebook,
                        facebookrewardpoints: facebookrewardpoints,
                        facebookrewardpercent: facebookrewardpercent,
                          rewardtypefacebook_share: rewardtypefacebook_share,
                        facebookrewardpoints_share: facebookrewardpoints_share,
                        facebookrewardpercent_share: facebookrewardpercent_share,
                        rewardtypetwitter: rewardtypetwitter,
                        twitterrewardpoints: twitterrewardpoints,
                        twitterrewardpercent: twitterrewardpercent,
                        rewardtypegoogle: rewardtypegoogle,
                        googlerewardpoints: googlerewardpoints,
                        googlerewardpercent: googlerewardpercent,
                        rewardtypevk: rewardtypevk,
                        vkrewardpoints: vkrewardpoints,
                        vkrewardpercent: vkrewardpercent,
                    });
                    function getDataSocial(id) {
                        return jQuery.ajax({
                            type: 'POST',
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            data: ({action: 'rssplitajaxoptimizationsocial', ids: id, enabledisablereward: enabledisablereward,
                                selectedproducts: selectparticularproducts,
                                selectedcategories: selectedcategories,
                                rewardtypefacebook: rewardtypefacebook,
                                facebookrewardpoints: facebookrewardpoints,
                                facebookrewardpercent: facebookrewardpercent,
                                 rewardtypefacebook_share: rewardtypefacebook_share,
                        facebookrewardpoints_share: facebookrewardpoints_share,
                        facebookrewardpercent_share: facebookrewardpercent_share,
                                rewardtypetwitter: rewardtypetwitter,
                                twitterrewardpoints: twitterrewardpoints,
                                twitterrewardpercent: twitterrewardpercent,
                                rewardtypegoogle: rewardtypegoogle,
                                googlerewardpoints: googlerewardpoints,
                                googlerewardpercent: googlerewardpercent,
                                rewardtypevk: rewardtypevk,
                                vkrewardpoints: vkrewardpoints,
                                vkrewardpercent: vkrewardpercent,
                            }),
                            success: function (response) {
                                console.log(response);
                            },
                            dataType: 'json',
                            async: false
                        });
                    }
                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                            function (response) {
                                console.log(response);
                                if (response !== 'success') {
                                    var j = 1;
                                    var i, j, temparray, chunk = 10;
                                    for (i = 0, j = response.length; i < j; i += chunk) {
                                        temparray = response.slice(i, i + chunk);
                                        getDataSocial(temparray);
                                    }
                                    jQuery.when(getDataSocial()).done(function (a1) {
                                        console.log('Ajax Done Successfully');
                                        jQuery('.submit .button-primary').trigger('click');
                                    });
                                } else {
                                    var newresponse = response.replace(/\s/g, '');
                                    if (newresponse === 'success') {
                                        jQuery('.submit .button-primary').trigger('click');
                                    }
                                }
                            }, 'json');
                    return false;
                });
                jQuery('.rs_sumo_undo_reward').click(function () {
                    jQuery(this).attr('data-clicked', '0');
                    var dataclicked = jQuery(this).attr('data-clicked');
                    var dataparam = ({
                        action: 'previousproductvalue',
                        proceedanyway: dataclicked,
                    });
                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                            function (response) {
                                var newresponse = response.replace(/\s/g, '');
                                if (newresponse === 'success') {
                                    jQuery('.rs_sumo_rewards').fadeIn();
                                    jQuery('.rs_sumo_rewards').html('Successfully Disabled from Existing Products');
                                    jQuery('.rs_sumo_rewards').fadeOut(5000);
                                }
                            });
                    return false;
                });
            });
        </script>
        <?php
    }
    
    
     public static function rs_save_button_for_update_point_price() {
        ?>
        <tr valign="top">
            <th class="titledesc" scope="row">
                <label for="rs_sumo_point_price_button"><?php _e('', 'rewardsystem'); ?></label>
            </th>
            <td class="forminp forminp-select">
                <input type="submit" class="rs_sumo_point_price_button button-primary" value="Save and Update"/>
                <img class="gif_rs_sumo_point_price_button" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/img/update.gif" style="width:32px;height:32px;position:absolute"/>          
                <div class='rs_sumo_point_price_button' style='margin-bottom:10px; margin-top:10px; color:green;'></div>
            </td>
        </tr>
        <?php
    }

    public static function rs_save_button_for_update() {
        ?>
        <tr valign="top">
            <th class="titledesc" scope="row">
                <label for="rs_sumo_reward_button"><?php _e('', 'rewardsystem'); ?></label>
            </th>
            <td class="forminp forminp-select">
                <input type="submit" class="rs_sumo_reward_button button-primary" value="Save and Update"/>
                <img class="gif_rs_sumo_reward_button" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/img/update.gif" style="width:32px;height:32px;position:absolute"/>         
                <div class='rs_sumo_rewards' style='margin-bottom:10px; margin-top:10px; color:green;'></div>
            </td>
        </tr>
        <?php
    }

    public static function rs_save_button_for_update_social() {
        ?>
        <tr valign="top">
            <th class="titledesc" scope="row">
                <label for="rs_sumo_reward_button_social"><?php _e('', 'rewardsystem'); ?></label>
            </th>
            <td class="forminp forminp-select">
                <input type="submit" class="rs_sumo_reward_button_social button-primary" value="Save and Update"/>
                <img class="gif_rs_sumo_reward_button_social" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/img/update.gif" style="width:32px;height:32px;position:absolute"/>          
                <div class='rs_sumo_rewards_social' style='margin-bottom:10px; margin-top:10px; color:green;'></div>
            </td>
        </tr>
        <?php
    }

    public static function rs_apply_points_for_previous_order_button() {
        ?>
        <tr valign="top">
            <th class="titledesc" scope="row">
                <label for="rs_sumo_rewards_for_previous_order"><?php _e('Apply Reward Points to Previous Orders', 'rewardsystem'); ?></label>
            </th>
            <td class="forminp forminp-select">
                <input type="submit" class="rs_sumo_rewards_for_previous_order button-primary" value="Apply Points for Previous Orders"/>
                <img class="gif_rs_sumo_reward_button_for_previous_order" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/img/update.gif" style="width:32px;height:32px;position:absolute"/>
                <div class="rs_sumo_rewards_previous_order" style="margin-bottom:10px;margin-top:10px; color:green;"></div>
            </td>
        </tr>
        <?php
    }

    public static function rs_add_date_picker() {
        ?>
        <script type="text/javascript">
            jQuery(function () {
                jQuery("#rs_from_date").datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    onClose: function (selectedDate) {
                        jQuery("#to").datepicker("option", "minDate", selectedDate);
                    }
                });
                jQuery('#rs_from_date').datepicker('setDate', '-1');
                jQuery("#rs_to_date").datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    onClose: function (selectedDate) {
                        jQuery("#from").datepicker("option", "maxDate", selectedDate);
                    }

                });
                jQuery("#rs_to_date").datepicker('setDate', new Date());
            });
        </script>
        <tr valign="top">
            <th class="titledesc" scope="row">
                <label for="rs_sumo_rewards_for_selecting_particular_date"><?php _e('Select From Specific Date', 'rewardsystem'); ?></label>
            </th>
            <td class="forminp forminp-select">
                From <input type="text" id="rs_from_date" value=""/> To <input type="text" id="rs_to_date" value=""/>
            </td>
        </tr>
        <?php
    }

    public static function date_enqueqe_script() {

        wp_enqueue_script('jquery-ui-datepicker');
        wp_register_script('wp_reward_jquery_ui', plugins_url('rewardsystem/js/jquery-ui.js'));
    }
    
    
    
    public function get_ajax_request_for_previous_product_point_price() {


        global $woocommerce;
        global $post;
        if (isset($_POST['proceedanyway'])) {
            if ($_POST['proceedanyway'] == '1') {
                if ($_POST['whichproduct'] == '1') {
                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    echo json_encode($products);
                } elseif ($_POST['whichproduct'] == '2') {
                    if (!is_array($_POST['selectedproducts'])) {
                        $_POST['selectedproducts'] = explode(',', $_POST['selectedproducts']);
                    }
                    if (is_array($_POST['selectedproducts'])) {

                        foreach ($_POST['selectedproducts']as $particularpost) {
                            $checkprod = get_product($particularpost);
                            if (is_object($checkprod)&&($checkprod->is_type('simple') || ($checkprod->is_type('subscription')) || $checkprod->is_type('booking'))) {
                                if ($_POST['enabledisablepoints'] == '1') {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem_enable_point_price', 'yes');
                                } else {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem_enable_point_price', 'no');
                                }
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem_point_price_type', $_POST['pointpricetype']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem__points', $_POST['pricepoints']);
                                   RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem_enable_point_price_type', $_POST['pointpricingtype']);
                            } else {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_enable_reward_points_price', $_POST['enabledisablepoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_enable_reward_points_price_type', $_POST['pointpricetype']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_enable_reward_points_pricing_type', $_POST['pointpricingtype']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, 'price_points', $_POST['pricepoints']);
                            }
                        }
                    }
                    echo json_encode("success");
                } elseif ($_POST['whichproduct'] == '3') {
                    $allcategories = get_terms('product_cat');


                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    foreach ($products as $product) {
                        $checkproducts = get_product($product);
                        if (is_object($checkproducts)&&($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking'))) {
                            $term = get_the_terms($product, 'product_cat');
                            if (is_array($term)) {
                                foreach ($allcategories as $mycategory) {
                                    if ($_POST['enabledisablepoints'] == '1') {
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_point_price_category', 'yes');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'yes');
                                    } else {
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_point_price_category', 'no');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'no');
                                    }
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_point_price_type', $_POST['pointpricetype']);
                                      RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price_type', $_POST['pointpricingtype']);
                              
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem__points', $_POST['pricepoints']);
                          
                                    
        
                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'point_price_category_type', $_POST['pointpricetype']);

                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_points_price', $_POST['pricepoints']);
                                }
                            }
                        } elseif (is_object($checkproducts)&&($checkproducts->is_type('variable') || ($checkproducts->is_type('variable-subscription'))) ){
                            if (is_array($checkproducts->get_available_variations())) {
                                foreach ($checkproducts->get_available_variations() as $getvariation) {
                                    $term = get_the_terms($checkproducts->id, 'product_cat');

                                    if (is_array($term)) {
                                        foreach ($allcategories as $mycategory) {
                                            if ($_POST['enabledisablepoints'] == '1') {
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_point_price_category', 'yes');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', '1');
                                            } else {
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_point_price_category', 'no');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', '2');
                                            }
                                            
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price_type', $_POST['pointpricetype']);
                                              

                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], 'price_points', $_POST['pricepoints']);
                           
                                           
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'point_price_category_type', $_POST['pointpricetype']);

                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_points_price', $_POST['pricepoints']);
                                        }
                                    }
                                }
                            }
                        }
                    }


                    echo json_encode("success");
                } else {
                    $mycategorylist = $_POST['selectedcategories'];
                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    foreach ($products as $product) {
                        $checkproducts = get_product($product);
                        if (is_object($checkproducts)&&($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking'))) {
                            if (is_array($mycategorylist)) {
                                foreach ($mycategorylist as $eachlist) {
                                    $term = get_the_terms($product, 'product_cat');
                                    if (is_array($term)) {
                                        foreach ($term as $termidlist) {
                                            if ($eachlist == $termidlist->term_id) {
                                                if ($_POST['enabledisablepoints'] == '1') {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_point_price_category', 'yes');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'yes');
                                                } else {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_point_price_category', 'no');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'no');
                                                }
                                                
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_point_price_type', $_POST['pointpricetype']);
                                                  RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price_type', $_POST['pointpricingtype']);
                              
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem__points',$_POST['pricepoints']);
                          
                                                  RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'point_price_category_type', $_POST['pointpricingtype']);
                                          
                                               
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_points_price', $_POST['pricepoints']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'point_price_category_type', $_POST['pointpricetype']);
                                            } 
                                        }
                                    }
                                }
                            }
                        } elseif (is_object($checkproducts)&&($checkproducts->is_type('variable') || ($checkproducts->is_type('variable-subscription')))) {
                            $mycategorylist = $_POST['selectedcategories'];
                            if (is_array($checkproducts->get_available_variations())) {
                                foreach ($checkproducts->get_available_variations() as $getvariation) {


                                    if (is_array($mycategorylist)) {
                                        foreach ($mycategorylist as $eachlist) {
                                            $term = get_the_terms($checkproducts->id, 'product_cat');
                                            if(is_array($term)){
                                            foreach ($term as $termidlist) {
                                                if ($eachlist == $termidlist->term_id) {
                                                    if ($_POST['enabledisablepoints'] == '1') {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_point_price_category', 'yes');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', '1');
                                                    } else {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_point_price_category', 'no');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', '2');
                                                    }
                                                    
                                                     RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price_type', $_POST['pointpricetype']);

                                                     RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], 'price_points', $_POST['pricepoints']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'point_price_category_type', $_POST['pointpricetype']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_pricing_type', $_POST['pointpricingtype']);
                               
                                            
                                                     RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'point_price_category_type', $_POST['pointpricingtype']);
                                          
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_points_price', $_POST['pricepoints']);
                                                } 
                                            }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    echo json_encode("success");
                }
            }

            if ($_POST['proceedanyway'] == '0') {
                $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                $products = get_posts($args);
                foreach ($products as $product) {
                    $checkproducts = get_product($product);
                    if (is_object($checkproducts)&&($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking'))) {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'no');
                    } elseif (is_object($checkproducts)&&($checkproducts->is_type('variable') || ($checkproducts->is_type('variable-subscription')))) {
                        if (is_array($checkproducts->get_available_variations())) {
                            foreach ($checkproducts->get_available_variations() as $getvariation) {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', '2');
                            }
                        }
                    }
                }
                echo json_encode("success");
            }
            exit();
        }
    }

    public function get_ajax_request_for_previous_product() {
        global $woocommerce;
        global $post;
        if (isset($_POST['proceedanyway'])) {
            if ($_POST['proceedanyway'] == '1') {
                if ($_POST['whichproduct'] == '1') {
                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    echo json_encode($products);
                } elseif ($_POST['whichproduct'] == '2') {
                    if (!is_array($_POST['selectedproducts'])) {
                        $_POST['selectedproducts'] = explode(',', $_POST['selectedproducts']);
                    }
                    if (is_array($_POST['selectedproducts'])) {

                        foreach ($_POST['selectedproducts']as $particularpost) {
                            $checkprod = get_product($particularpost);
                            if (is_object($checkprod)&&($checkprod->is_type('simple') || ($checkprod->is_type('subscription')) || $checkprod->is_type('booking')) ){
                                if ($_POST['enabledisablereward'] == '1') {
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystemcheckboxvalue', 'yes');
                                } else {
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystemcheckboxvalue', 'no');
                                }
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystem_options', $_POST['rewardtype']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystempoints', $_POST['rewardpoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_rewardsystempercent', $_POST['rewardpercent']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referral_rewardsystem_options', $_POST['referralrewardtype']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referralrewardsystempoints', $_POST['referralrewardpoint']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referralrewardsystempercent', $_POST['referralrewardpercent']);
                            } else {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_enable_reward_points', $_POST['enabledisablereward']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_select_reward_rule', $_POST['rewardtype']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_reward_points', $_POST['rewardpoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_reward_percent', $_POST['rewardpercent']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_select_referral_reward_rule', $_POST['referralrewardtype']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referral_reward_points', $_POST['referralrewardpoint']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_referral_reward_percent', $_POST['referralrewardpercent']);
                            }
                        }
                    }
                    echo json_encode("success");
                } elseif ($_POST['whichproduct'] == '3') {
                    $allcategories = get_terms('product_cat');

                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    foreach ($products as $product) {

                        $checkproducts = get_product($product);
                        if (is_object($checkproducts)&&($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking')) ){
                            $term = get_the_terms($product, 'product_cat');
                            if (is_array($term)) {

                                foreach ($allcategories as $mycategory) {
                                    if ($_POST['enabledisablereward'] == '1') {

                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_reward_system_category', 'yes');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'yes');
                                    } else {
                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_reward_system_category', 'no');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'no');
                                    }
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_options', '');
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempoints', '');
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempercent', '');
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referral_rewardsystem_options', '');
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempoints', '');
                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempercent', '');

                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_rs_rule', $_POST['rewardtype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_points', $_POST['rewardpoints']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_percent', $_POST['rewardpercent']);


                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_enable_rs_rule', $_POST['referralrewardtype']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_points', $_POST['referralrewardpoint']);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_percent', $_POST['referralrewardpercent']);
                                }
                            }
                        } elseif (is_object($checkproducts)&&($checkproducts->is_type('variable') || ($checkproducts->is_type('variable-subscription')))) {
                            if (is_array($checkproducts->get_available_variations())) {
                                foreach ($checkproducts->get_available_variations() as $getvariation) {
                                    $term = get_the_terms($checkproducts->id, 'product_cat');

                                    if (is_array($term)) {
                                        foreach ($allcategories as $mycategory) {
                                            if ($_POST['enabledisablereward'] == '1') {
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_reward_system_category', 'yes');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', $_POST['enabledisablereward']);
                                            } else {
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_reward_system_category', 'no');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', $_POST['enabledisablereward']);
                                            }

                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_reward_rule', '');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_points', '');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_percent', '');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_referral_reward_rule', '');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_points', '');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_percent', '');

                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_rs_rule', $_POST['rewardtype']);
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_points', $_POST['rewardpoints']);
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'rs_category_percent', $_POST['rewardpercent']);


                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_enable_rs_rule', $_POST['referralrewardtype']);
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_points', $_POST['referralrewardpoint']);
                                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'referral_rs_category_percent', $_POST['referralrewardpercent']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    echo json_encode("success");
                } else {
                    $mycategorylist = $_POST['selectedcategories'];
                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    foreach ($products as $product) {
                        $checkproducts = get_product($product);
                        if (is_object($checkproducts)&&($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking'))) {
                            if (is_array($mycategorylist)) {
                                foreach ($mycategorylist as $eachlist) {
                                    $term = get_the_terms($product, 'product_cat');
                                    if (is_array($term)) {
                                        foreach ($term as $termidlist) {
                                            if ($eachlist == $termidlist->term_id) {
                                                if ($_POST['enabledisablereward'] == '1') {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_reward_system_category', 'yes');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'yes');
                                                } else {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_reward_system_category', 'no');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'no');
                                                }

                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_options', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempoints', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempercent', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referral_rewardsystem_options', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempoints', '');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempercent', '');

                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_rs_rule', $_POST['rewardtype']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_points', $_POST['rewardpoints']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_percent', $_POST['rewardpercent']);


                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_enable_rs_rule', $_POST['referralrewardtype']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_rs_category_points', $_POST['referralrewardpoint']);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_rs_category_percent', $_POST['referralrewardpercent']);
                                            }
                                        }
                                    }
                                }
                            }
                        } elseif (is_object($checkproducts)&&($checkproducts->is_type('variable') || ($checkproducts->is_type('variable-subscription')))) {
                            $mycategorylist = $_POST['selectedcategories'];
                            if (is_array($checkproducts->get_available_variations())) {
                                foreach ($checkproducts->get_available_variations() as $getvariation) {


                                    if (is_array($mycategorylist)) {
                                        foreach ($mycategorylist as $eachlist) {
                                            $term = get_the_terms($checkproducts->id, 'product_cat');
                                            if(is_array($term)){
                                            foreach ($term as $termidlist) {
                                                if ($eachlist == $termidlist->term_id) {
                                                    if ($_POST['enabledisablereward'] == '1') {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_reward_system_category', 'yes');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', $_POST['enabledisablereward']);
                                                    } else {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_reward_system_category', 'no');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', $_POST['enabledisablereward']);
                                                    }

                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_reward_rule', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_points', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_percent', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_referral_reward_rule', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_points', '');
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_percent', '');


                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_rs_rule', $_POST['rewardtype']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_points', $_POST['rewardpoints']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'rs_category_percent', $_POST['rewardpercent']);


                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_enable_rs_rule', $_POST['referralrewardtype']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_rs_category_points', $_POST['referralrewardpoint']);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'referral_rs_category_percent', $_POST['referralrewardpercent']);
                                                }
                                            }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    echo json_encode("success");
                }
            }
            if ($_POST['proceedanyway'] == '0') {
                $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                $products = get_posts($args);
                foreach ($products as $product) {
                    $checkproducts = get_product($product);
                if (is_object($checkproducts)&&($checkproducts->is_type('simple') || ($checkproducts->is_type('subscription')) || $checkproducts->is_type('booking'))) {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'no');
                    } elseif (is_object($checkproducts)&&($checkproducts->is_type('variable') || ($checkproducts->is_type('variable-subscription')))) {
                        if (is_array($checkproducts->get_available_variations())) {
                            foreach ($checkproducts->get_available_variations() as $getvariation) {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', '2');
                            }
                        }
                    }
                }
                echo json_encode("success");
            }
            exit();
        }
    }
    public function get_ajax_request_for_previous_social_product($post_id) {
        global $woocommerce;
        global $post;
        if (isset($_POST['proceedanyway'])) {
            if ($_POST['proceedanyway'] == '1') {
                if ($_POST['whichproduct'] == '1') {
                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    echo json_encode($products);
                } elseif ($_POST['whichproduct'] == '2') {
                    if (!is_array($_POST['selectedproducts'])) {
                        $_POST['selectedproducts'] = explode(',', $_POST['selectedproducts']);
                    }
                    if (is_array($_POST['selectedproducts'])) {
                        foreach ($_POST['selectedproducts']as $particularpost) {
                            $checkprod = get_product($particularpost);
                            if ($_POST['enabledisablereward'] == '1') {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystemcheckboxvalue', 'yes');
                            } else {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystemcheckboxvalue', 'no');
                            }
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_facebook', $_POST['rewardtypefacebook']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_facebook', $_POST['facebookrewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_facebook', $_POST['facebookrewardpercent']);

                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_facebook_share', $_POST['rewardtypefacebook_share']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_facebook_share', $_POST['facebookrewardpoints_share']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_facebook_share', $_POST['facebookrewardpercent_share']);

                            
                            
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_twitter', $_POST['rewardtypetwitter']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_twitter', $_POST['twitterrewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_twitter', $_POST['twitterrewardpercent']);


                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_google', $_POST['rewardtypegoogle']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_google', $_POST['googlerewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_google', $_POST['googlerewardpercent']);


                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_social_rewardsystem_options_vk', $_POST['rewardtypevk']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempoints_vk', $_POST['vkrewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($particularpost, '_socialrewardsystempercent_vk', $_POST['vkrewardpercent']);
                        }
                    }
                    echo json_encode("success");
                } elseif ($_POST['whichproduct'] == '3') {
                    $allcategories = get_terms('product_cat');
                    
                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    foreach ($products as $product) {

                       
                        
                            $term = get_the_terms($product, 'product_cat');
                            if (is_array($term)) {
                    
                            if (is_array($allcategories)) {
                            foreach ($allcategories as $mycategory) {
                            if ($_POST['enabledisablereward'] == '1') {
                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_social_reward_system_category', 'yes');
                                  RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'yes');
                            } else {
                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'enable_social_reward_system_category', 'no');
                                  RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'no');
                            }
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_facebook', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_facebook', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_facebook', '');

                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_twitter', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_twitter', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_twitter', '');


                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_google', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_google', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_google','');


                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_vk', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_vk', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_vk', '');
                        
                            
                            
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_enable_rs_rule', $_POST['rewardtypefacebook']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_rs_category_points', $_POST['facebookrewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_rs_category_percent', $_POST['facebookrewardpercent']);

                            
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_share_enable_rs_rule', $_POST['rewardtypefacebook_share']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_share_rs_category_points', $_POST['facebookrewardpoints_share']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_facebook_share_rs_category_percent', $_POST['facebookrewardpercent_share']);

                            
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_twitter_enable_rs_rule', $_POST['rewardtypetwitter']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_twitter_rs_category_points', $_POST['twitterrewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_twitter_rs_category_percent', $_POST['twitterrewardpercent']);

                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_google_enable_rs_rule', $_POST['rewardtypegoogle']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_google_rs_category_points', $_POST['googlerewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_google_rs_category_percent', $_POST['googlerewardpercent']);


                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_vk_enable_rs_rule', $_POST['rewardtypevk']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_vk_rs_category_points', $_POST['vkrewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($mycategory->term_id, 'social_vk_rs_category_percent', $_POST['vkrewardpercent']);
                        }
                    }
                            }
                        
                    }
                    echo json_encode("success");
                } else {
                    $mycategorylist = $_POST['selectedcategories'];
                    
                    $args = array('post_type' => 'product', 'posts_per_page' => '-1', 'post_status' => 'publish', 'fields' => 'ids', 'cache_results' => false);
                    $products = get_posts($args);
                    foreach ($products as $product) {
                        $checkproducts = get_product($product);
                        
                            if (is_array($mycategorylist)) {
                                foreach ($mycategorylist as $eachlist) {
                                    $term = get_the_terms($product, 'product_cat');
                                    if (is_array($term)) {
                                        foreach ($term as $termidlist) {
                                     if ($eachlist == $termidlist->term_id) {
                            if ($_POST['enabledisablereward'] == '1') {
                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_social_reward_system_category', 'yes');
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'yes');
                            } else {
                                RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'enable_social_reward_system_category', 'no');
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'no');
                            }
                            
                             RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_facebook', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_facebook', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_facebook', '');

                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_twitter', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_twitter', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_twitter', '');


                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_google', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_google', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_google','');


                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_vk', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_vk', '');
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_vk', '');
                        
                            
                            
                            
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_enable_rs_rule', $_POST['rewardtypefacebook']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_rs_category_points', $_POST['facebookrewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_rs_category_percent', $_POST['facebookrewardpercent']);

                               
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_share_enable_rs_rule', $_POST['rewardtypefacebook_share']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_share_rs_category_points', $_POST['facebookrewardpoints_share']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_facebook_share_rs_category_percent', $_POST['facebookrewardpercent_share']);

                            
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_twitter_enable_rs_rule', $_POST['rewardtypetwitter']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_twitter_rs_category_points', $_POST['twitterrewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_twitter_rs_category_percent', $_POST['twitterrewardpercent']);

                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_google_enable_rs_rule', $_POST['rewardtypegoogle']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_google_rs_category_points', $_POST['googlerewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_google_rs_category_percent', $_POST['googlerewardpercent']);


                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_vk_enable_rs_rule', $_POST['rewardtypevk']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_vk_rs_category_points', $_POST['vkrewardpoints']);
                            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($eachlist, 'social_vk_rs_category_percent', $_POST['vkrewardpercent']);
                        }
                    
                                        }
                                    }
                                }
                            }
                        }
                    
                    echo json_encode("success");
                }
            }
            exit();
        }
    }

    public static function process_chunk_ajax_request_in_rewardsystem() {
        if (isset($_POST['ids'])) {
            $products = $_POST['ids'];
            foreach ($products as $product) {
                $checkproduct = get_product($product);
                if (is_object($checkproduct)&&($checkproduct->is_type('simple') || ($checkproduct->is_type('subscription')) || $checkproduct->is_type('booking'))) {
                    if ($_POST['enabledisablereward'] == '1') {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'yes');
                    } else {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystemcheckboxvalue', 'no');
                    }
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_options', $_POST['rewardtype']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempoints', $_POST['rewardpoints']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystempercent', $_POST['rewardpercent']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referral_rewardsystem_options', $_POST['referralrewardtype']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempoints', $_POST['referralrewardpoint']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_referralrewardsystempercent', $_POST['referralrewardpercent']);
                } else {
                    if (is_object($checkproduct)&&($checkproduct->is_type('variable') || ($checkproduct->is_type('variable-subscription')))) {
                        if (is_array($checkproduct->get_available_variations())) {
                            foreach ($checkproduct->get_available_variations() as $getvariation) {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points', $_POST['enabledisablereward']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_reward_rule', $_POST['rewardtype']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_points', $_POST['rewardpoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_reward_percent', $_POST['rewardpercent']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_select_referral_reward_rule', $_POST['referralrewardtype']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_points', $_POST['referralrewardpoint']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_referral_reward_percent', $_POST['referralrewardpercent']);
                            }
                        }
                    }
                }
            }
        }

        exit();
    }
    
    
     public static function process_chunk_ajax_request_in_rewardsystem_point_price() {


        if (isset($_POST['ids'])) {
            $products = $_POST['ids'];
            foreach ($products as $product) {
                $checkproduct = get_product($product);
                if (is_object($checkproduct)&&($checkproduct->is_type('simple') || ($checkproduct->is_type('subscription')) || $checkproduct->is_type('booking'))) {
                    if ($_POST['enabledisablepoints'] == '1') {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'yes');
                        
                    } else {
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price', 'no');
                       
                    }
                      RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_enable_point_price_type', $_POST['pointpricingtype']);
                  
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem_point_price_type', $_POST['pointpricetype']);
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_rewardsystem__points', $_POST['pricepoints']);
                } else {
                    if (is_object($checkproduct)&&($checkproduct->is_type('variable') || ($checkproduct->is_type('variable-subscription')))) {
                        if (is_array($checkproduct->get_available_variations())) {
                            foreach ($checkproduct->get_available_variations() as $getvariation) {
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price', $_POST['enabledisablepoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_pricing_type', $_POST['pointpricingtype']);
                               
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], 'price_points', $_POST['pricepoints']);
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($getvariation['variation_id'], '_enable_reward_points_price_type', $_POST['pointpricetype']);
                            }
                        }
                    }
                }
            }
        }

        exit();
    }

    public static function process_chunk_ajax_request_in_social_rewardsystem() {
        if (isset($_POST['ids'])) {
            $products = $_POST['ids'];
            foreach ($products as $product) {


                if ($_POST['enabledisablereward'] == '1') {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'yes');
                } else {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystemcheckboxvalue', 'no');
                }
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_facebook', $_POST['rewardtypefacebook']);
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_facebook', $_POST['facebookrewardpoints']);
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_facebook', $_POST['facebookrewardpercent']);
 RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_facebook_share', $_POST['rewardtypefacebook_share']);
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_facebook_share', $_POST['facebookrewardpoints_share']);
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_facebook_share', $_POST['facebookrewardpercent_share']);

                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_twitter', $_POST['rewardtypetwitter']);
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_twitter', $_POST['twitterrewardpoints']);
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_twitter', $_POST['twitterrewardpercent']);


                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_google', $_POST['rewardtypegoogle']);
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_google', $_POST['googlerewardpoints']);
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_google', $_POST['googlerewardpercent']);


                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_social_rewardsystem_options_vk', $_POST['rewardtypevk']);
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempoints_vk', $_POST['vkrewardpoints']);
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product, '_socialrewardsystempercent_vk', $_POST['vkrewardpercent']);
            }
        }
        exit();
    }
    
    public static function rs_validation_of_input_field_in_update(){        
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_local_reward_points[type=text],\n\
                                           #rs_local_reward_percent[type=text],\n\
                                           #rs_local_referral_reward_point[type=text],\n\
                                           #rs_local_referral_reward_percent[type=text],\n\
                                           #rs_local_reward_points_facebook[type=text],\n\
                                           #rs_local_reward_percent_facebook[type=text],\n\
                                           #rs_local_reward_points_twitter[type=text],\n\
                                           #rs_local_reward_percent_twitter[type=text],\n\
                                           #rs_local_reward_points_google[type=text],\n\
                                           #rs_local_reward_percent_google[type=text],\n\
                                           #rs_local_reward_points_vk[type=text],\n\
                                           #rs_local_reward_percent_vk[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#rs_local_reward_points[type=text],\n\
                                           #rs_local_reward_percent[type=text],\n\
                                           #rs_local_referral_reward_point[type=text],\n\
                                           #rs_local_referral_reward_percent[type=text],\n\
                                           #rs_local_reward_points_facebook[type=text],\n\
                                           #rs_local_reward_percent_facebook[type=text],\n\
                                           #rs_local_reward_points_twitter[type=text],\n\
                                           #rs_local_reward_percent_twitter[type=text],\n\
                                           #rs_local_reward_points_google[type=text],\n\
                                           #rs_local_reward_percent_google[type=text],\n\
                                           #rs_local_reward_points_vk[type=text],\n\
                                           #rs_local_reward_percent_vk[type=text]', function () {
                    var value = jQuery(this).val();                  
                    var regex = new RegExp("[^\+0-9\%.\\" + woocommerce_admin.mon_decimal_point + "]+", "gi");
                    var newvalue = value.replace(regex, '');

                    if (value !== newvalue) {
                        jQuery(this).val(newvalue);
                        if (jQuery(this).parent().find('.wc_error_tip').size() == 0) {
                            var offset = jQuery(this).position();
                            jQuery(this).after('<div class="wc_error_tip">' + woocommerce_admin.i18n_mon_decimal_error + " Negative Values are not allowed" + '</div>');
                            jQuery('.wc_error_tip')
                                    .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                    .css('top', offset.top + jQuery(this).height())
                                    .fadeIn('100');
                        }
                    }



                    return this;
                });



                jQuery("body").click(function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                });
            });
        </script>
        <?php   
    }
    
    public static function rs_process_ajax_points_to_previous_order() {
    if (isset($_POST['proceedanyway'])) {
        if ($_POST['proceedanyway'] == '1') {
            $orderstatuslist = get_option('rs_order_status_control');
            $new_order = array('wc-completed');
            foreach($orderstatuslist as $each_order){
                $new_order[] = 'wc-'.$each_order;
            }
            $args = array('post_type' => 'shop_order', 'numberposts' => '-1','meta_query'=>array(array('key'=>'reward_points_awarded','compare'=>'NOT EXISTS')),'post_status' => $new_order, 'fields' => 'ids', 'cache_results' => false);            
            $order_id = get_posts($args);                            
            echo json_encode($order_id);             
        }
    }
    exit();
    }

    public static function rs_send_ajax_points_to_previous_orders() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.gif_rs_sumo_reward_button_for_previous_order').css('display','none');
            jQuery('.rs_sumo_rewards_for_previous_order').click(function () {                
                jQuery('.gif_rs_sumo_reward_button_for_previous_order').css('display','inline-block');
                jQuery(this).attr('data-clicked', '1');
                var dataclicked = jQuery(this).attr('data-clicked');
                var fromdate = jQuery('#rs_from_date').val();
                var todate = jQuery('#rs_to_date').val();
                if (jQuery('#rs_sumo_select_order_range').val() === '1') {                     
                    var dataparam = ({
                        action: 'previousorderpoints',
                        proceedanyway: dataclicked,
                    });    
                    function getData(id) {
                        return jQuery.ajax({
                            type: 'POST',
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            data: ({
                                action: 'rssplitajaxoptimizationforpreviousorder',
                                ids: id,                                
                                proceedanyway: dataclicked,
                                //fromdate: fromdate,
                                //todate: todate,
                            }),
                            success: function (response) {
                                console.log(response);
                            },
                            dataType: 'json',
                            async: false
                        });
                    }
                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                        function (response) {
                           console.log(response);
                          
                            if (response != 'success') {                                  
                                    var j = 1;
                                    var i, j, temparray, chunk = 10;
                                    for (i = 0, j = response.length; i < j; i += chunk) {
                                        temparray = response.slice(i, i + chunk);                                        
                                        getData(temparray);                                        
                                    }                                    
                                    jQuery.when(getData()).done(function (a1) {
                                        console.log('Ajax Done Successfully');
                                        location.reload();
                                        jQuery('.rs_sumo_rewards_previous_order').fadeIn();
                                        if(response != ''){
                                            jQuery('.rs_sumo_rewards_previous_order').html('Points Successfully Added to Previous Order');                                        
                                        }else{
                                            jQuery('.rs_sumo_rewards_previous_order').html('There is no order to give points');                                        
                                        }
                                        jQuery('.rs_sumo_rewards_previous_order').fadeOut(5000); 
                                    });
                               }                              
                        },'json');
                } else {                    
                    var dataparam = ({
                        action: 'previousorderpoints',
                        proceedanyway: dataclicked,
                        fromdate: fromdate,
                        todate: todate,
                    });
                    function getDataforDate(id) {
                        return jQuery.ajax({
                            type: 'POST',
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            data: ({
                                action: 'rssplitajaxoptimizationforpreviousorder',
                                ids: id,                                
                                proceedanyway: dataclicked,
                                //fromdate: fromdate,
                                //todate: todate,
                            }),
                            success: function (response) {
                                console.log(response);
                            },
                            dataType: 'json',
                            async: false
                        });
                    }
                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                        function (response) {
                           console.log(response);
                          // alert(response);
                            if (response != 'success') {                                  
                                    var j = 1;
                                    var i, j, temparray, chunk = 10;
                                    for (i = 0, j = response.length; i < j; i += chunk) {
                                        temparray = response.slice(i, i + chunk);                                                                                
                                        getDataforDate(temparray);
                                    }                                    
                                    jQuery.when(getDataforDate()).done(function (a1) {
                                        console.log('Ajax Done Successfully');
                                        location.reload();
                                        jQuery('.rs_sumo_rewards_previous_order').fadeIn();
                                        if(response != ''){
                                            jQuery('.rs_sumo_rewards_previous_order').html('Points Successfully Added to Previous Order');                                        
                                        }else{
                                            jQuery('.rs_sumo_rewards_previous_order').html('There is no order to give points');                                        
                                        }                                        
                                        jQuery('.rs_sumo_rewards_previous_order').fadeOut(5000); 
                                    });
                                }                              
                        },'json');
                }                                 
                return false;
            });
        });</script>
    <?php
    }
    
    public static function process_chunk_ajax_request_for_previous_orders() {          
    if (isset($_POST['ids'])) {
            $products = $_POST['ids'];             
            foreach ($products as $product) {                
                $modified_date = get_the_time('Y-m-d', $product);                
                if (isset($_POST['fromdate']) && ($_POST['todate'])) {
                    if (($_POST['fromdate'] <= $modified_date) && $modified_date <= $_POST['todate']) {
                        $points_awarded_for_this_order = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product, 'reward_points_awarded');                        
                        if($points_awarded_for_this_order != 'yes'){
                            RSAjaxMainFunction::update_earning_points_for_user($product);
                            add_post_meta($product, 'reward_points_awarded', 'yes');
                        }                        
                    }
                } else {                      
                        $points_awarded_for_this_order = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product, 'reward_points_awarded');                        
                        if($points_awarded_for_this_order != 'yes'){
                            RSAjaxMainFunction::update_earning_points_for_user($product);
                            add_post_meta($product, 'reward_points_awarded', 'yes');
                        }                        
                }                
            }
        }
        exit();
    }
}

new RSFunctionForUpdate();

