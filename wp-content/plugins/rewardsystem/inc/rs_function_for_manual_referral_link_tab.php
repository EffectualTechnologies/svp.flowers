<?php

class RSFunctionForManualReferralLink {
    
    public function __construct() {
        
        add_action('woocommerce_admin_field_rs_user_role_dynamics_manual', array($this, 'reward_system_add_manual_table_to_action'));
        
        add_action('woocommerce_update_options_rewardsystem_manual', array($this, 'save_data_for_dynamic_rule_manual'));
        
        add_action('admin_head', array($this, 'rs_perform_manual_link_referer'));
        
        add_action('wp_head', array($this, 'life_time_referral_link'));
    }
    
    public static function reward_system_add_manual_table_to_action() {
        global $woocommerce;
        wp_nonce_field(plugin_basename(__FILE__), 'rsdynamicrulecreation_manual');
        global $woocommerce;
        ?>
        <style type="text/css">
            .rs_manual_linking_referral{
                width:60%;
            }
            .rs_manual_linking_referer{
                width:60%;
            }
            .chosen-container-single {
                position:absolute;
            }
            .column-columnname-link{
                width:10%;               
            }            

        </style>
        <?php
        echo RSJQueryFunction::rs_common_ajax_function_to_select_user('rs_manual_linking_referer');
        echo RSJQueryFunction::rs_common_ajax_function_to_select_user('rs_manual_linking_referral');
        ?>
        <table class="widefat fixed rsdynamicrulecreation_manual" cellspacing="0">
            <thead>
                <tr>

                    <th class="manage-column column-columnname" scope="col"><?php _e('Referer Username', 'rewardsystem'); ?></th>
                    <th class="manage-column column-columnname" scope="col"><?php _e('Buyer Username', 'rewardsystem'); ?></th>
                    <th class="manage-column column-columnname-link" scope="col"><?php _e('Linking Type', 'rewardsystem'); ?></th>
                    <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Linking', 'rewardsystem'); ?></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="manage-column column-columnname num" scope="col"> <span class="add button-primary"><?php _e('Add Linking', 'rewardsystem'); ?></span></td>
                </tr>
                <tr>

                    <th class="manage-column column-columnname" scope="col"><?php _e('Referer Username', 'rewardsystem'); ?></th>
                    <th class="manage-column column-columnname" scope="col"><?php _e('Buyer Username', 'rewardsystem'); ?></th>
                    <th class="manage-column column-columnname-link" scope="col"><?php _e('Linking Type', 'rewardsystem'); ?></th>
                    <th class="manage-column column-columnname num" scope="col"><?php _e('Add Linking', 'rewardsystem'); ?></th>

                </tr>
            </tfoot>

            <tbody id="here">
                <?php
                $rewards_dynamic_rulerule_manual = get_option('rewards_dynamic_rule_manual');
                echo "<pre>";                
                echo "</pre>";
                $i = 0;
                if (is_array($rewards_dynamic_rulerule_manual)) {
                    foreach ($rewards_dynamic_rulerule_manual as $rewards_dynamic_rule) {
                        ?>
                        <tr>
                            <td class="column-columnname">
                                <p class="form-field">
                                    <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                        <select name="rewards_dynamic_rule_manual[<?php echo $i; ?>][referer]" class="short rs_manual_linking_referer">
                                            <?php
                                            if ($rewards_dynamic_rule['referer'] != '') {
                                                $user = get_user_by('id', absint($rewards_dynamic_rule['referer']));
                                                echo '<option value="' . absint($user->ID) . '" ';
                                                selected(1, 1);
                                                echo '>' . esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')</option>';
                                            } else {
                                                ?>
                                                <option value=""></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                    <?php } else { ?>
                                        <?php
                                        if ($rewards_dynamic_rule['referer'] != '') {
                                            $user_id = absint($rewards_dynamic_rule['referer']);
                                            $user = get_user_by('id', $user_id);
                                            $user_string = esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email).')';
                                        }
                                        ?>
                                        <input type="hidden" class="wc-customer-search" name="rewards_dynamic_rule_manual[<?php echo $i; ?>][referer]" data-placeholder="<?php _e('Search for a customer&hellip;', 'rewardsystem'); ?>" data-selected="<?php echo esc_attr($user_string); ?>" value="<?php echo $user_id; ?>" data-allow_clear="true" />

                                    <?php } ?>
                                                                                                                                                                                                                                          <!--                                    <input type="text" name="rewards_dynamic_rule_manual[<?php echo $i; ?>][referer]" class="short" value="<?php echo $rewards_dynamic_rule['referer']; ?>"/>-->
                                </p>
                            </td>
                            <td class="column-columnname">
                                <p class="form-field">
                                    <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                        <select name="rewards_dynamic_rule_manual[<?php echo $i; ?>][refferal]" class="short rs_manual_linking_referral">
                                            <?php
                                            if ($rewards_dynamic_rule['refferal'] != '') {
                                                $user = get_user_by('id', absint($rewards_dynamic_rule['refferal']));
                                                echo '<option value="' . absint($user->ID) . '" ';
                                                selected(1, 1);
                                                echo '>' . esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')</option>';
                                            } else {
                                                ?>
                                                <option value=""></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    <?php } else { ?>
                                        <?php
                                        if ($rewards_dynamic_rule['refferal'] != '') {
                                            $user_id = absint($rewards_dynamic_rule['refferal']);
                                            $user = get_user_by('id', $user_id);
                                            $user_string = esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email).')';
                                        }
                                        ?>
                                        <input type="hidden" class="wc-customer-search" name="rewards_dynamic_rule_manual[<?php echo $i; ?>][refferal]" data-placeholder="<?php _e('Search for a customer&hellip;', 'rewardsystem'); ?>" data-selected="<?php echo esc_attr($user_string); ?>" value="<?php echo $user_id; ?>" data-allow_clear="true" />
                                    <?php } ?>
                <!--                                    <input type="text" name="rewards_dynamic_rule_manual[<?php echo $i; ?>][refferal]" id="rewards_dynamic_ruleamount_manual<?php echo $i; ?>" class="short" value="<?php echo $rewards_dynamic_rule['refferal']; ?>"/>-->
                                </p>
                            </td>
                    <td class="column-columnname-link">    <?php                    
                                        if (@$rewards_dynamic_rule['type'] != '') {
                                            ?>
                                              <span> <b>Automatic</b></span>
                        <?php                
                        }else{
                            ?>
                              <span> <b>Manual</b></span>
                              <?php
                        }
                                        ?>
                              <input type="hidden" value="<?php echo @$rewards_dynamic_rule['type'];?>" name="rewards_dynamic_rule_manual[<?php echo $i; ?>][type]"/>
                                         </td>
                            <td class="column-columnname num">
                                <span class="remove button-secondary"><?php _e('Remove Linking', 'rewardsystem'); ?></span>
                            </td>
                        </tr>
                        <?php
                        $i = $i + 1;
                    }
                }
                ?>
            </tbody>
        </table>
        <script>
            jQuery(document).ready(function () {
                jQuery('#afterclick').hide();
                var countrewards_dynamic_rule = <?php echo $i; ?>;
                jQuery(".add").click(function () {
                    jQuery('#afterclick').show();
                    countrewards_dynamic_rule = countrewards_dynamic_rule + 1;
        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>

                        jQuery('#here').append('<tr><td><p class="form-field"><select name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][referer]" class="short rs_manual_linking_referer"><option value=""></option></select></p></td>\n\
                                                        \n\<td><p class="form-field"><select name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][refferal]" class="short rs_manual_linking_referral"><option value=""></option></select></p></td>\n\
                                                        \n\<td class="column-columnname-link" ><span><input type="hidden" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][type]"  value="" class="short "/><b>Manual</b></span></td>\n\
                                                    \n\
                                                    <td class="num"><span class="remove button-secondary">Remove Linking</span></td></tr><hr>');
                        jQuery(function () {
                            // Ajax Chosen Product Selectors
                            jQuery("select.rs_manual_linking_referer").ajaxChosen({
                                method: 'GET',
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                dataType: 'json',
                                afterTypeDelay: 100,
                                data: {
                                    action: 'woocommerce_json_search_customers',
                                    security: '<?php echo wp_create_nonce("search-customers"); ?>'
                                }
                            }, function (data) {
                                var terms = {};

                                jQuery.each(data, function (i, val) {
                                    terms[i] = val;
                                });
                                return terms;
                            });
                        });
                        jQuery(function () {
                            // Ajax Chosen Product Selectors
                            jQuery("select.rs_manual_linking_referral").ajaxChosen({
                                method: 'GET',
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                dataType: 'json',
                                afterTypeDelay: 100,
                                data: {
                                    action: 'woocommerce_json_search_customers',
                                    security: '<?php echo wp_create_nonce("search-customers"); ?>'
                                }
                            }, function (data) {
                                var terms = {};

                                jQuery.each(data, function (i, val) {
                                    terms[i] = val;
                                });
                                return terms;
                            });
                        });
        <?php } else { ?>

                        jQuery('#here').append('<tr><td><p class="form-field"><input type="hidden" class="wc-customer-search" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][referer]" data-placeholder="<?php _e("Search for a customer&hellip;", "rewardsystem"); ?>" data-selected="" value="" data-allow_clear="true"/> </p></td>\n\
                                                        \n\<td><p class="form-field"><input type="hidden" class="wc-customer-search" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][refferal]" data-placeholder="<?php _e("Search for a customer&hellip;", "rewardsystem"); ?>" data-selected="" value="" data-allow_clear="true"/></p></td>\n\
                                                      \n\<td class="column-columnname-link" ><span><input type="hidden" name="rewards_dynamic_rule_manual[' + countrewards_dynamic_rule + '][type]"  value="" class="short "/><b>Manual</b></span></td>\n\
                                                    \n\
                                                    <td class="num"><span class="remove button-secondary">Remove Linking</span></td></tr><hr>');
                        jQuery('body').trigger('wc-enhanced-select-init');
        <?php } ?>
                    return false;
                });
                jQuery(document).on('click', '.remove', function () {
                    jQuery(this).parent().parent().remove();
                });
            });</script>

        <?php
    }

    public static function save_data_for_dynamic_rule_manual() {

        $rewards_dynamic_rulerule_manual = array_values($_POST['rewards_dynamic_rule_manual']);

        update_option('rewards_dynamic_rule_manual', $rewards_dynamic_rulerule_manual);
        return false;
    }
    
    public static function rs_get_referer_id_linking_rule($linkarray, $field, $value) {
        if (is_array($linkarray)) {
            foreach ($linkarray as $key => $eachreferer) {                
                if ($eachreferer[$field] == $value)
                    return $eachreferer['referer'];
            }
        }
        return FALSE;
    }

    public static function rs_perform_manual_link_referer($buyer_id) {
        $data = get_option('rewards_dynamic_rule_manual');
        return self::rs_get_referer_id_linking_rule($data, "refferal", $buyer_id);
    }
    
    public static function life_time_referral_link() {

   $userid = get_current_user_id();
        $once_time=get_post_meta($userid,'reward_manuall_referaal_link',true);
        if($once_time != '1'){
    if (isset($_COOKIE['rsreferredusername'])) {
        if (is_user_logged_in()) {
            if (get_option('rs_enable_referral_link_for_life_time') == 'yes') {
                if (get_option('rs_generate_referral_link_based_on_user') == '1') {
                        $referredusername = get_user_by('login', $_COOKIE['rsreferredusername']);
                        $refereduserid = $referredusername->ID;
                    } else {
                        $referredusername = get_userdata($_COOKIE['rsreferredusername']);
                        $refereduserid = $referredusername->ID;
                        
                    }
                $userid = get_current_user_id();
                $getoveralllog = get_option('rewards_dynamic_rule_manual');
                if($userid != $refereduserid ){
                if (!empty($getoveralllog)) {
                    $boolvalue =  self::life_time_bool_value($refereduserid, $userid);
                    if ($boolvalue != 'false') {
                        $merge[] = array('referer' => esc_html($referredusername->ID), 'refferal' => esc_html($userid), 'type' => 'Automatic');
                        $logmerge = array_merge((array) $getoveralllog, $merge);
                        update_option('rewards_dynamic_rule_manual', $logmerge);
                    }
                } else {
                    $merge[] = array('referer' => esc_html($referredusername->ID), 'refferal' => esc_html($userid), 'type' => 'Automatic');
                    update_option('rewards_dynamic_rule_manual', $merge);
                }
                }
            }
        }
        update_post_meta($userid,'reward_manuall_referaal_link',1);
    }
}
}

public static function life_time_bool_value($refuserid, $userid) {
 $getoveralllog = get_option('rewards_dynamic_rule_manual');
    foreach ($getoveralllog as $value) {
        if (($value['referer'] == $refuserid) && ($value['refferal'] == $userid)) {
            $userid = get_current_user_id();
            if($value['referer'] != $userid){
            return true;
            }
        } else {
            return false;
        }
    }
}
}
new RSFunctionForManualReferralLink();