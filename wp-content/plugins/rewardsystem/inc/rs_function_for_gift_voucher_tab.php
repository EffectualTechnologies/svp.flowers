<?php

class RSFunctionForGiftVoucher {

    public function __construct() {

        add_action('wp_enqueue_scripts', array($this, 'wp_enqueqe_script_for_footable'));

        add_action('admin_enqueue_scripts', array($this, 'wp_enqueqe_script_for_footable'));

        add_action('admin_enqueue_scripts', array($this, 'date_enqueqe_script_for_gift'));

        add_action('woocommerce_admin_field_point_vouchers', array($this, 'rs_point_voucher_field'));

        add_action('admin_head', array($this, 'rs_validation_of_input_field_in_gift_voucher'));

        add_action('wp_ajax_nopriv_rewardsystem_point_vouchers', array($this, 'process_ajax_request_rs_point_vouchers'));

        add_action('wp_ajax_rewardsystem_point_vouchers', array($this, 'process_ajax_request_rs_point_vouchers'));

        add_action('wp_ajax_nopriv_rewardsystem_point_bulk_vouchers', array($this, 'process_ajax_request_for_rs_bulk_point_vouchers'));

        add_action('wp_ajax_rewardsystem_point_bulk_vouchers', array($this, 'process_ajax_request_for_rs_bulk_point_vouchers'));

        add_action('wp_ajax_nopriv_rewardsystem_redeem_voucher_codes', array($this, 'process_ajax_request_to_redeem_voucher_reward_system'));

        add_action('wp_ajax_rewardsystem_redeem_voucher_codes', array($this, 'process_ajax_request_to_redeem_voucher_reward_system'));

        add_action('wp_ajax_nopriv_rewardsystem_delete_array', array($this, 'delete_array_keys_rs_point_vouchers'));

        add_action('wp_ajax_rewardsystem_delete_array', array($this, 'delete_array_keys_rs_point_vouchers'));

        if (get_option('rs_show_hide_redeem_voucher') == '1') {
            if (get_option('rs_redeem_voucher_position') == '1') {
                add_action('woocommerce_before_my_account', array($this, 'reward_system_my_account_voucher_redeem'));
            } else {
                add_action('woocommerce_after_my_account', array($this, 'reward_system_my_account_voucher_redeem'));
            }
        }

        add_shortcode('rs_redeem_vouchercode', array($this, 'rewardsystem_myaccount_voucher_redeem_shortcode'));
    }

    public static function rs_point_voucher_field() {
        ?>
        <tr valign="top">
            <th class="titledesc" scope="row">
                <label for="rs_point_voucher_create_option"><?php _e('Voucher Creation', 'rewardsystem'); ?></label>
            </th>
            <td class="forminp forminp-select">
                <input type="radio" name="rs_point_voucher_create_option" class="rs_point_voucher_create_option" id="rs_point_voucher_create_option" value="1" checked="checked"/> Individual Voucher Code<br>
                <input type="radio" name="rs_point_voucher_create_option" class="rs_point_voucher_create_option" id="rs_point_voucher_create_option" value="2"/> Bulk Voucher Code<br/>
            </td>
        </tr>
        <tbody class="rs_bulk_vouchers">
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_point_bulk_voucher_count">
                        <?php _e('Enter Number of Vouchers to Generate', 'rewardsystem'); ?>
                    </label>
                </th>
                <td class="forminp forminp-select">
                    <input type="text" id="rs_point_bulk_voucher_count" name="rs_point_bulk_voucher_count" value=""/><em>For Example: 10</em>
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_prefix_for_gift_voucher"><?php _e('Prefix for Gift Voucher Code', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="text" name="rs_prefix_for_gift_voucher" id="rs_prefix_for_gift_voucher" value="SRP"/>
                </td>
            </tr>   
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_point_bulk_voucher_points">
                        <?php _e('Enter Bulk Gift Voucher Points', 'rewardsystem'); ?>
                    </label>
                </th>
                <td class="forminp forminp-select">
                    <input type="text" id="rs_point_bulk_voucher_points" name="rs_point_bulk_voucher_points" value=""/>
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_point_bulk_voucher_expiry"><?php _e('Bulk Gift Voucher Expiry', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="text" class="rs_point_bulk_voucher_expiry" value="" name="rs_point_bulk_voucher_expiry" id="rs_point_bulk_voucher_expiry" />
                </td>
            </tr>
        </tbody>

        <tbody class="rs_individual_vouchers">
            <tr valign ="top">
                <th class="titledesc" scope="row">
                    <label for="rs_point_voucher_field"><?php _e('Enter Gift Voucher Code', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="text" id="rs_point_voucher_field" name="rs_point_voucher_field" value=""/><em>For Example: giftvoucher</em>
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_point_voucher_reward_points"><?php _e('Enter Gift Voucher Points'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="text" id="rs_point_voucher_reward_points" name="rs_point_voucher_reward_points" value=""/>
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_point_voucher_expiry"><?php _e('Gift Voucher Expiry', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="text" class="rs_point_voucher_expiry" value="" name="rs_point_voucher_expiry" id="rs_point_voucher_expiry" />
                </td>
            </tr>
        </tbody>
        <tr valign="top">
            <td>


            </td>
            <td>
                <input type='submit' name='rs_submit_point_vouchers' id='rs_submit_point_vouchers' class='button-primary' value='Create Voucher'/>
                <div class="vouchererror"></div>
            </td>
        </tr>
        <?php if (isset($_GET['tab'])) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#rs_point_voucher_expiry').datepicker({dateFormat: 'yy-mm-dd', minDate: 0});
                    jQuery('#rs_point_bulk_voucher_expiry').datepicker({dateFormat: 'yy-mm-dd', minDate: 0});
                });
            </script>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#rs_voucher_lists').footable().bind('footable_filtering', function (e) {
                        var selected = jQuery('.filter-status').find(':selected').text();
                        if (selected && selected.length > 0) {
                            e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                            e.clear = !e.filter;
                        }
                    });
                    jQuery('#changepagesizers').change(function (e) {
                        e.preventDefault();
                        var pageSize = jQuery(this).val();
                        jQuery('.footable').data('page-size', pageSize);
                        jQuery('.footable').trigger('footable_initialized');
                    });
                });
            </script>
        <?php }
        ?>

        <script type="text/javascript">
            jQuery(function () {
                var checkvouchervalue = jQuery('.rs_point_voucher_create_option').filter(':checked').val();
                if (checkvouchervalue === '1') {
                    jQuery('.rs_bulk_vouchers').css('display', 'none');
                    jQuery('.rs_individual_vouchers').css('display', 'table-row-group');
                } else {
                    jQuery('.rs_individual_vouchers').css('display', 'none');
                    jQuery('.rs_bulk_vouchers').css('display', 'table-row-group');
                }
                jQuery('.rs_point_voucher_create_option').change(function () {
                    if (jQuery(this).val() === '1') {
                        jQuery('.rs_bulk_vouchers').css('display', 'none');
                        jQuery('.rs_individual_vouchers').css('display', 'table-row-group');
                    } else {
                        jQuery('.rs_individual_vouchers').css('display', 'none');
                        jQuery('.rs_bulk_vouchers').css('display', 'table-row-group');
                    }
                });

                jQuery('#rs_submit_point_vouchers').click(function () {
                    var vouchercreateoption = jQuery('.rs_point_voucher_create_option').filter(':checked').val();
                    jQuery(this).prop("disabled", true);
                    if (vouchercreateoption === '1') {
                        var vouchercode = jQuery('#rs_point_voucher_field').val();
                        var voucherpoints = jQuery('#rs_point_voucher_reward_points').val();
                        var voucherexpiry = jQuery('#rs_point_voucher_expiry').val();
                        var dataparam = ({
                            action: 'rewardsystem_point_vouchers',
                            vouchercode: vouchercode,
                            voucherpoints: voucherpoints,
                            vouchercreated: '<?php echo date('Y-m-d'); ?>',
                            voucherexpiry: voucherexpiry,
                        });
                    }
                    if (vouchercreateoption === '2') {
                        var bulkvouchercount = jQuery('#rs_point_bulk_voucher_count').val();
                        var bulkvoucherprefix = jQuery('#rs_prefix_for_gift_voucher').val();
                        var bulkvoucherpoints = jQuery('#rs_point_bulk_voucher_points').val();
                        var bulkvoucherexpiry = jQuery('#rs_point_bulk_voucher_expiry').val();
                        var dataparam = ({
                            action: 'rewardsystem_point_bulk_vouchers',
                            vouchercount: bulkvouchercount,
                            voucherprefix: bulkvoucherprefix,
                            bulkvoucherpoints: bulkvoucherpoints,
                            bulkvouchercreated: '<?php echo date('Y-m-d'); ?>',
                            bulkvoucherexpiry: bulkvoucherexpiry,
                        });
                    }
                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                            function (response) {
                                if (response === '1') {
                                    jQuery('.vouchererror').html('Unique Code Already Exists').fadeIn().delay(4000).fadeOut();
                                }
                                jQuery(".voucher_rs_list_table").load(window.location + " .voucher_rs_list_table");
                                jQuery('#rs_submit_point_vouchers').prop("disabled", false);
                                return false;
                            });
                    return false;
                });
            });
        </script>
        <style type="text/css">
            .rs_vouchers_click {

                border: 2px solid #a1a1a1;
                padding: 3px 9px;
                background: #dddddd;
                width: 5px;
                border-radius: 25px;
            }
            .rs_vouchers_click:hover {
                cursor: pointer;
                background:red;
                color:#fff;
                border: 2px solid #fff;
            }
        </style>
        <table>
            <tr valign="top">
                <td class="forminp forminp-select">
                    <input type="submit" id="rs_export_gift_voucher_csv" name="rs_export_gift_voucher_csv" value="Export Gift Voucher as CSV"/>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <?php
                    echo '<p> ' . __('Search:', 'rewardsystem') . '<input id="filterings_vouchers" type="text"/>  ' . __('Page Size:', 'rewardsystem') . '
                <select id="changepagesizers">
									<option value="5">5</option>
									<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select></p>';
                    ?>

            </tr>
        </table>
        <tr>

        <table class="wp-list-table widefat fixed posts voucher_rs_list_table " data-filter = "#filterings_vouchers" data-page-size="10" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next" id="rs_voucher_lists" >
            <script type="text/javascript">
                jQuery(function () {
                    jQuery(document).on('click', '.rs_vouchers_click', function () {
                        var uniquecode = jQuery(this).attr('data-code');
                        var dataparameter = ({
                            action: 'rewardsystem_delete_array',
                            deletecode: uniquecode,
                        });
                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparameter,
                                function (response) {
                                    jQuery(".voucher_rs_list_table").load(window.location + " .voucher_rs_list_table");

                                });
                        return false;
                    });
                });
            </script>

            <thead>
                <tr>
                    <th scope='col' data-toggle="true"  class='manage-column column-serial_number'  style="">
                        <a href="#"><span><?php _e('S.No', 'rewardsystem'); ?></span>
                    </th>
                    <th scope='col' id='rs_voucher_codes' class='manage-column column-rs_voucher_codes'  style=""><?php _e('Voucher Code', 'rewardsystem'); ?></th>
                    <th scope='col' id='rs_points_assigned' class='manage-column column-rs_points_assigned'  style=""><?php _e('Points Assigned', 'rewardsystem'); ?></th>
                    <th scope="col" id="rs_voucher_created" class="manage-column column-rs_voucher_created" style=""><?php _e('Voucher Created', 'rewardsystem'); ?></th>
                    <th scope="col" id="rs_voucher_expiry" class="manage-column column-rs_voucher_expiry" style=""><?php _e('Voucher Expiry', 'rewardsystem'); ?></th>
                    <th scope="col" id="rs_voucher_used" class="manage-column column-rs_voucher_used" style=""><?php _e('Voucher used by', 'rewardsystem'); ?></th>
                    <th scope="col" id="rs_delete_vouchers" class="manage-column column-rs_delete_vouchers" style=""><?php _e('Delete', 'rewardsystem'); ?></th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php
                $i = 1;
                $checkvalues = get_option('rsvoucherlists');
                if (!empty($checkvalues)) {
                    foreach ($checkvalues as $voucher) {
                        foreach ($voucher as $value) {
                            if ($i % 2 != 0) {
                                $name = 'alternate';
                            } else {
                                $name = '';
                            }
                            ?>
                            <tr id="post-141"  class="type-shop_order status-publish post-password-required hentry <?php echo $name; ?> iedit author-self level-0" valign="top">
                                <td data-value="<?php echo $i; ?>">
                                    <?php echo $i; ?>
                                </td>
                                <td class="rs_user_name">
                                    <?php echo $value['vouchercode']; ?>
                                </td>
                                <td>
                                    <?php echo $value['points']; ?>
                                </td>
                                <td>
                                    <?php echo $value['vouchercreated']; ?>
                                </td>
                                <td>
                                    <?php
                                    if ($value['voucherexpiry'] != '') {
                                        echo $value['voucherexpiry'];
                                    } else {
                                        echo "Never";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($value['memberused'] != '') {
                                        $userinfo = get_userdata($value['memberused']);
                                        echo $userinfo->user_login;
                                    } else {
                                        echo "Not Yet";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div data-code="<?php echo $value['vouchercode']; ?>" class="rs_vouchers_click">x</div>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                    }
                }
                ?>
            </tbody>

        </table>
        <div style="clear:both;">
            <div class="pagination pagination-centered"></div>
        </div>
        </tr>
        <?php
        if (isset($_POST['rs_export_gift_voucher_csv'])) {
            $get_list_of_coupons = get_option('rsvoucherlists');
            foreach ($get_list_of_coupons as $each_coupon) {
                foreach ($each_coupon as $coupon_info) {
                    $voucher_code = $coupon_info['vouchercode'];
                    $voucher_amount = $coupon_info['points'];
                    $voucher_created_date = $coupon_info['vouchercreated'];
                    $voucher_expiry_date = $coupon_info['voucherexpiry'];
                    $voucher_used_count = $coupon_info['memberused'] != "" ? get_user_by("id", $coupon_info['memberused'])->user_login : "Not yet";
                    $voucher_info_array[] = array($voucher_code, $voucher_amount, $voucher_created_date, $voucher_expiry_date, $voucher_used_count);
                }
            }
            ob_end_clean();
            header("Content-type: text/csv");
            $dateformat = get_option('date_format') ;
            $currentdate = date_i18n($dateformat);
            header("Content-Disposition: attachment; filename=reward_points_gift_voucher" . $currentdate . ".csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            RSFunctionForMasterLog::outputCSV($voucher_info_array);
            exit();
        }
    }

    public static function date_enqueqe_script_for_gift() {

        wp_enqueue_script('jquery-ui-datepicker');
        wp_register_script('wp_reward_jquery_ui', plugins_url('rewardsystem/js/jquery-ui.js'));
    }

    public static function wp_enqueqe_script_for_footable() {
        wp_register_script('wp_reward_footable', plugins_url('rewardsystem/js/footable.js'));
        wp_register_script('wp_reward_footable_filter', plugins_url('rewardsystem/js/footable.filter.js'));
        wp_enqueue_script('wp_reward_footable');
        wp_enqueue_script('wp_reward_footable_filter');
    }

    public static function rs_validation_of_input_field_in_gift_voucher() {
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_point_voucher_reward_points[type=text],\n\
                                           #rs_point_bulk_voucher_points[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#rs_point_voucher_reward_points[type=text],\n\
                                           #rs_point_bulk_voucher_points[type=text]', function () {
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

    public static function process_ajax_request_rs_point_vouchers() {
        if (isset($_POST['vouchercode']) && ($_POST['voucherpoints'])) {

            $checkifexists = get_option('rsvoucherlists');
            if (!empty($checkifexists)) {
                foreach (get_option('rsvoucherlists') as $updates) {
                    if (!array_key_exists($_POST['vouchercode'], $updates)) {
                        $newupdatess[] = $updates;
                    } else {
                        echo "1";
                        exit();
                    }
                }

                $newupdates = array(
                    array(
                        $_POST['vouchercode'] => array('points' => $_POST['voucherpoints'], 'vouchercode' => $_POST['vouchercode'], 'vouchercreated' => $_POST['vouchercreated'], 'voucherexpiry' => $_POST['voucherexpiry'], 'memberused' => '', 'voucherused' => '')
                    ),
                );
                $array1 = (array) $newupdatess;
                $array2 = $newupdates;
                $array3 = array_merge($array1, $array2);
                update_option('rsvoucherlists', array_filter($array3));
            } else {

                $newupdates = array(
                    array(
                        $_POST['vouchercode'] => array('points' => $_POST['voucherpoints'], 'vouchercode' => $_POST['vouchercode'], 'vouchercreated' => $_POST['vouchercreated'], 'voucherexpiry' => $_POST['voucherexpiry'], 'memberused' => '', 'voucherused', '')
                    ),
                );
                update_option('rsvoucherlists', $newupdates);
            }
        }
        exit();
    }

    public static function process_ajax_request_for_rs_bulk_point_vouchers() {
        if (isset($_POST['vouchercount']) && ($_POST['bulkvoucherpoints'])) {

            $checkifexists = get_option('rsvoucherlists');
            if (!empty($checkifexists)) {

                for ($i = 1; $i <= $_POST['vouchercount']; $i++) {
                    $num = mt_rand(1000001, 9999998);
                    $output = sprintf('%07x', $num);
                    $newvouchercode[] = $_POST['voucherprefix'] . $output;
                }
                foreach (get_option('rsvoucherlists') as $updates) {
                    foreach ($newvouchercode as $codess) {
                        if (!array_key_exists($codess, $updates)) {
                            $newupdatess[] = array_filter($updates);
                        } else {
                            echo "1";
                        }
                    }
                }
                foreach ($newvouchercode as $newcodess) {
                    $newupdates = array(
                        array(
                            $newcodess => array('points' => $_POST['bulkvoucherpoints'], 'vouchercode' => $newcodess, 'vouchercreated' => $_POST['bulkvouchercreated'], 'voucherexpiry' => $_POST['bulkvoucherexpiry'], 'memberused' => '', 'voucherused' => '')
                        ),
                    );
                    $array1 = (array) $newupdatess;
                    $array2 = $newupdates;
                    $array3 = array_merge((array) get_option('rsvoucherlists'), $array2);

                    $array3 = array_map("unserialize", array_unique(array_map("serialize", $array3)));
                    update_option('rsvoucherlists', array_filter($array3));
                }
            } else {
                for ($i = 1; $i <= $_POST['vouchercount']; $i++) {
                    $num = mt_rand(1000001, 9999998);
                    $output = sprintf('%07x', $num);
                    $newvouchercode[] = $_POST['voucherprefix'] . $output;
                }
                foreach ($newvouchercode as $newcde) {
                    $newupdates = array(
                        array(
                            $newcde => array('points' => $_POST['bulkvoucherpoints'], 'vouchercode' => $newcde, 'vouchercreated' => $_POST['bulkvouchercreated'], 'voucherexpiry' => $_POST['bulkvoucherexpiry'], 'memberused' => '', 'voucherused', '')
                        ),
                    );
                    $array2 = $newupdates;
                    $array3 = array_merge((array) get_option('rsvoucherlists'), $array2);

                    $array3 = array_map("unserialize", array_unique(array_map("serialize", $array3)));
                    update_option('rsvoucherlists', array_filter($array3));
                }
            }
        }
        exit();
    }

    public static function reward_system_my_account_voucher_redeem() {
        ?>
        <h3><?php echo get_option('rs_redeem_your_gift_voucher_label'); ?></h3>
        <input type="text" size="50" name="rs_redeem_voucher" id="rs_redeem_voucher_code" value=""><input type="submit" style="margin-left:10px;" class="button <?php echo get_option('rs_extra_class_name_redeem_gift_voucher_button'); ?>" name="rs_submit_redeem_voucher" id="rs_submit_redeem_voucher" value="<?php echo get_option('rs_redeem_gift_voucher_button_label'); ?>"/>
        <div class="rs_redeem_voucher_error" style="color:red;"></div>
        <div class="rs_redeem_voucher_success" style="color:green"></div>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#rs_submit_redeem_voucher').click(function () {

                    var redeemvouchercode = jQuery('#rs_redeem_voucher_code').val();
                    var new_redeemvouchercode = redeemvouchercode.replace(/\s/g, '');
                    if (new_redeemvouchercode === '') {
                        jQuery('.rs_redeem_voucher_error').html('<?php echo addslashes(get_option('rs_voucher_redeem_empty_error')); ?>').fadeIn().delay(5000).fadeOut();
                        return false;
                    } else {
                        jQuery('.rs_redeem_voucher_error').html('');
                        var dataparam = ({
                            action: 'rewardsystem_redeem_voucher_codes',
                            redeemvouchercode: new_redeemvouchercode,
                        });
                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                function (response) {
                                    console.log(jQuery.parseHTML(response));
                                    jQuery('.rs_redeem_voucher_success').html(jQuery.parseHTML(response));
                                });
                        return false;
                    }
                });
            });
        </script>
        <?php
    }

    public static function rewardsystem_myaccount_voucher_redeem_shortcode() {
        ob_start();
        if (is_user_logged_in()) {
            ?>
            <h3><?php _e('Redeem your Gift Voucher', 'rewardsystem'); ?></h3>
            <input type="text" size="50" name="rs_redeem_voucher" id="rs_redeem_voucher_code" value=""><input type="submit" style="margin-left:10px;" class="button <?php echo get_option('rs_extra_class_name_redeem_gift_voucher_button'); ?>" name="rs_submit_redeem_voucher" id="rs_submit_redeem_voucher" value="<?php _e('Redeem Gift Voucher', 'rewardsystem'); ?>"/>
            <div class="rs_redeem_voucher_error" style="color:red;"></div>
            <div class="rs_redeem_voucher_success" style="color:green"></div>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#rs_submit_redeem_voucher').click(function () {

                        var redeemvouchercode = jQuery('#rs_redeem_voucher_code').val();
                        var new_redeemvouchercode = redeemvouchercode.replace(/\s/g, '');
                        if (new_redeemvouchercode === '') {
                            jQuery('.rs_redeem_voucher_error').html('<?php echo addslashes(get_option('rs_voucher_redeem_empty_error')); ?>').fadeIn().delay(5000).fadeOut();
                            return false;
                        } else {
                            jQuery('.rs_redeem_voucher_error').html('');
                            var dataparam = ({
                                action: 'rewardsystem_redeem_voucher_codes',
                                redeemvouchercode: new_redeemvouchercode,
                            });
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                    function (response) {
                                        console.log(jQuery.parseHTML(response));
                                        jQuery('.rs_redeem_voucher_success').html(jQuery.parseHTML(response));
                                    });
                            return false;
                        }
                    });
                });
            </script>
            <?php
        } else {
            $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
            $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
            ?>
            <?php ob_start(); ?><a href="<?php echo $myaccountlink; ?>" title="Login"><?php echo addslashes(get_option('rs_redeem_voucher_login_link_label')); ?></a>                
            <?php
            $message_for_guest = get_option("rs_voucher_redeem_guest_error_message");
            $redeem_voucher_guest_to_find = "[rs_login_link]";
            $redeem_voucher_guest_to_replace = ob_get_clean();
            $redeem_voucher_guest_replaced_content = str_replace($redeem_voucher_guest_to_find, $redeem_voucher_guest_to_replace, $message_for_guest);
            echo addslashes($redeem_voucher_guest_replaced_content);
            ?>

            <?php
        }
        $maincontent = ob_get_clean();
        return $maincontent;
    }

    public static function search($array, $key, $value) {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, self::search($subarray, $key, $value));
            }
        }

        return $results;
    }

    public static function process_ajax_request_to_redeem_voucher_reward_system() {
       
        $newone[] = '';
        $userid = get_current_user_id();
        $banning_type = FPRewardSystem::check_banning_type($userid);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {
            if (isset($_POST['redeemvouchercode'])) {

                if (is_array(get_option('rsvoucherlists'))) {
                    foreach (get_option('rsvoucherlists')as $newones) {
                        if (!array_key_exists($_POST['redeemvouchercode'], $newones)) {
                            $newone[] = $newones;
                        }
                    }
                }
                $findedarray = self::search(get_option('rsvoucherlists'), 'vouchercode', $_POST['redeemvouchercode']);
                if (($findedarray == NULL) || ($findedarray == '')) {
                    echo addslashes(get_option('rs_invalid_voucher_code_error_message'));
                    exit();
                } else {
                    $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                    $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                    $dateformat = get_option('date_format');
                    $todays_date = date_i18n($dateformat);
                    $today = strtotime($todays_date);
                    $exp_date = $findedarray[0]['voucherexpiry'];
                    $vouchercreated = $findedarray[0]['vouchercreated'];
                    $voucherused = isset($findedarray[0]['voucherused']) != '' ? $findedarray[0]['voucherused'] : '';
                    $voucherpoints = $findedarray[0]['points'];
                    $noofdays = get_option('rs_point_to_be_expire');
                   
                    if (($noofdays != '0') && ($noofdays != '')) {
                        $date = time() + ($noofdays * 24 * 60 * 60);
                    } else {
                        $date = '999999999999';
                    }
                    if ($voucherused == '') {
                        if ($exp_date != '') {
                            $expiration_date = strtotime($exp_date);
                            if ($expiration_date > $today) {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $voucherpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $voucherpoints = $findedarray[0]['points'];
                                                $translatedstring = $_POST['redeemvouchercode'];
                                                $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user('0', get_current_user_id());
                                                $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($redeempoints, get_current_user_id());
                                                RSPointExpiry::insert_earning_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', '0', $voucherpoints, $redeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($voucherpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                                RSPointExpiry::record_the_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', $equearnamt, $equredeemamt, '0', '0', '0', '0', $translatedstring, $totalpoints, '', '0');
                                                $rs_voucher_redeem_success_to_find = "[giftvoucherpoints]";
                                                $rs_voucher_redeem_success_to_replace = $voucherpoints;
                                                $rs_voucher_redeem_success_message = get_option('rs_voucher_redeem_success_message');
                                                $rs_voucher_redeem_success_message_replaced = str_replace($rs_voucher_redeem_success_to_find, $rs_voucher_redeem_success_to_replace, $rs_voucher_redeem_success_message);
                                                echo addslashes($rs_voucher_redeem_success_message_replaced);
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points(get_current_user_id(), $insertpoints, '0', $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                                RSPointExpiry::record_the_points(get_current_user_id(), $insertpoints, '0', $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points(get_current_user_id(), '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                            RSPointExpiry::record_the_points(get_current_user_id(), '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $voucherpoints = $findedarray[0]['points'];
                                        $translatedstring = $_POST['redeemvouchercode'];
                                        $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user('0', get_current_user_id());
                                        $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($redeempoints, get_current_user_id());
                                        RSPointExpiry::insert_earning_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', '0', $voucherpoints, $redeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($voucherpoints);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                        RSPointExpiry::record_the_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', $equearnamt, '0', '0', '0', '0', '0', $translatedstring, $totalpoints, '', '0');
                                        $rs_voucher_redeem_success_to_find = "[giftvoucherpoints]";
                                        $rs_voucher_redeem_success_to_replace = $voucherpoints;
                                        $rs_voucher_redeem_success_message = get_option('rs_voucher_redeem_success_message');
                                        $rs_voucher_redeem_success_message_replaced = str_replace($rs_voucher_redeem_success_to_find, $rs_voucher_redeem_success_to_replace, $rs_voucher_redeem_success_message);
                                        echo addslashes($rs_voucher_redeem_success_message_replaced);
                                    }
                                } else {
                                    $voucherpoints = $findedarray[0]['points'];
                                    $translatedstring = $_POST['redeemvouchercode'];
                                    $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user('0', get_current_user_id());
                                    $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($redeempoints, get_current_user_id());
                                    RSPointExpiry::insert_earning_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', '0', $voucherpoints, $redeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($voucherpoints);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                    RSPointExpiry::record_the_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', $equearnamt, '0', '0', '0', '0', '0', $translatedstring, $totalpoints, '', '0');
                                    $rs_voucher_redeem_success_to_find = "[giftvoucherpoints]";
                                    $rs_voucher_redeem_success_to_replace = $voucherpoints;
                                    $rs_voucher_redeem_success_message = get_option('rs_voucher_redeem_success_message');
                                    $rs_voucher_redeem_success_message_replaced = str_replace($rs_voucher_redeem_success_to_find, $rs_voucher_redeem_success_to_replace, $rs_voucher_redeem_success_message);
                                    echo addslashes($rs_voucher_redeem_success_message_replaced);
                                }
                            } else {
                                echo addslashes(get_option('rs_voucher_code_expired_error_message'));
                            }
                        } else {
                            // Coupon Never Expired
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $voucherpoints;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $voucherpoints = $findedarray[0]['points'];
                                            $translatedstring = $_POST['redeemvouchercode'];
                                            $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user('0', get_current_user_id());
                                            $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($redeempoints, get_current_user_id());
                                            RSPointExpiry::insert_earning_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', '0', $voucherpoints, $redeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($voucherpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                            RSPointExpiry::record_the_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', $equearnamt, $equredeemamt, '0', '0', '0', '0', $translatedstring, $totalpoints, '', '0');
                                            $rs_voucher_redeem_success_to_find = "[giftvoucherpoints]";
                                            $rs_voucher_redeem_success_to_replace = $voucherpoints;
                                            $rs_voucher_redeem_success_message = get_option('rs_voucher_redeem_success_message');
                                            $rs_voucher_redeem_success_message_replaced = str_replace($rs_voucher_redeem_success_to_find, $rs_voucher_redeem_success_to_replace, $rs_voucher_redeem_success_message);
                                            echo addslashes($rs_voucher_redeem_success_message_replaced);
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points(get_current_user_id(), $insertpoints, '0', $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                            RSPointExpiry::record_the_points(get_current_user_id(), $insertpoints, '0', $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points(get_current_user_id(), '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                        RSPointExpiry::record_the_points(get_current_user_id(), '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $voucherpoints = $findedarray[0]['points'];
                                    $translatedstring = $_POST['redeemvouchercode'];
                                    $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user('0', get_current_user_id());
                                    $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($redeempoints, get_current_user_id());
                                    RSPointExpiry::insert_earning_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', '0', $voucherpoints, $redeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($voucherpoints);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                    RSPointExpiry::record_the_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', $equearnamt, '0', '0', '0', '0', '0', $translatedstring, $totalpoints, '', '0');
                                    $rs_voucher_redeem_success_to_find = "[giftvoucherpoints]";
                                    $rs_voucher_redeem_success_to_replace = $voucherpoints;
                                    $rs_voucher_redeem_success_message = get_option('rs_voucher_redeem_success_message');
                                    $rs_voucher_redeem_success_message_replaced = str_replace($rs_voucher_redeem_success_to_find, $rs_voucher_redeem_success_to_replace, $rs_voucher_redeem_success_message);
                                    echo addslashes($rs_voucher_redeem_success_message_replaced);
                                }
                            } else {
                                $voucherpoints = $findedarray[0]['points'];
                                $translatedstring = $_POST['redeemvouchercode'];
                                $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user('0', get_current_user_id());
                                $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($redeempoints, get_current_user_id());
                                RSPointExpiry::insert_earning_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', '0', $voucherpoints, $redeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($voucherpoints);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
                                RSPointExpiry::record_the_points(get_current_user_id(), $voucherpoints, '0', $date, 'RPGV', $equearnamt, '0', '0', '0', '0', '0', $translatedstring, $totalpoints, '', '0');
                                $rs_voucher_redeem_success_to_find = "[giftvoucherpoints]";
                                $rs_voucher_redeem_success_to_replace = $voucherpoints;
                                $rs_voucher_redeem_success_message = get_option('rs_voucher_redeem_success_message');
                                $rs_voucher_redeem_success_message_replaced = str_replace($rs_voucher_redeem_success_to_find, $rs_voucher_redeem_success_to_replace, $rs_voucher_redeem_success_message);
                                echo addslashes($rs_voucher_redeem_success_message_replaced);
                            }
                        }
                        $updates = array(
                            array(
                                $_POST['redeemvouchercode'] => array('points' => $voucherpoints, 'vouchercode' => $_POST['redeemvouchercode'], 'vouchercreated' => $vouchercreated, 'voucherexpiry' => $exp_date, 'memberused' => get_current_user_id(), 'voucherused' => '1')
                            ),
                        );

                        $array1 = $newone;
                        $array2 = $updates;
                        $array3 = array_merge((array) $array1, (array) $array2);
                        update_option('rsvoucherlists', array_filter($array3));
                    } else {
                        echo addslashes(get_option('rs_voucher_code_used_error_message'));
                    }
                }
            }
        } else {
            echo addslashes(get_option('rs_banned_user_redeem_voucher_error'));
        }
        do_action('fp_reward_point_for_using_gift_voucher');
        exit();
    }

    public static function delete_array_keys_rs_point_vouchers() {
        if (isset($_POST['deletecode'])) {
            $checkifexists = get_option('rsvoucherlists');
            if (!empty($checkifexists)) {
                foreach (get_option('rsvoucherlists') as $updates) {
                    if (array_key_exists($_POST['deletecode'], $updates)) {
                        unset($updates);
                    }
                    $newupdates[] = $updates;
                }
                $new_array_without_nulls = array_filter($newupdates);
                update_option('rsvoucherlists', $new_array_without_nulls);
            }
        }
        exit();
    }

}

new RSFunctionForGiftVoucher();
