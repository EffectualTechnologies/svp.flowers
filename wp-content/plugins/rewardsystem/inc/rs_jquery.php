<?php

class RSJQueryFunction {
    /*
     * Common Function For Choosen.
     */

    public static function rs_common_chosen_function($id) {
        wp_enqueue_script('chosen');
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(function () {
                jQuery('select'+'<?php echo $id; ?>').chosen();
            });
        </script>
        <?php
        $getcontent = ob_get_clean();
        return $getcontent;
    }

    /*
     * Common Function For select.
     */

    public static function rs_common_select_function($id) {
        wp_enqueue_script('select2');
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(function () {
                jQuery('select'+'<?php echo $id; ?>').select2();
            });
        </script>
        <?php
        $getcontent = ob_get_clean();
        return $getcontent;
    }

    /*
     * Common ajax function to select user.
     */

    public static function rs_common_ajax_function_to_select_user($ajaxid) {
        global $woocommerce;
        ob_start();
        ?>
        <script type="text/javascript">
        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                jQuery(function () {
                    jQuery('select.<?php echo $ajaxid; ?>').ajaxChosen({
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
        <?php } ?>
        </script>
        <?php
        $getcontent = ob_get_clean();
        return $getcontent;
    }

    /*
     * Common Ajax Function to select products
     */

    public static function rs_common_ajax_function_to_select_products($ajaxid) {
        global $woocommerce;
        ob_start();
        ?>
        <script type="text/javascript">
        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                jQuery(function () {
                    jQuery("select.<?php echo $ajaxid; ?>").ajaxChosen({
                        method: 'GET',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        dataType: 'json',
                        afterTypeDelay: 100,
                        data: {
                            action: 'woocommerce_json_search_products_and_variations',
                            security: '<?php echo wp_create_nonce("search-products"); ?>'
                        }
                    }, function (data) {
                        var terms = {};

                        jQuery.each(data, function (i, val) {
                            terms[i] = val;
                        });
                        return terms;
                    });
                });
        <?php } ?>
        </script>
        <?php
        $getcontent = ob_get_clean();
        return $getcontent;
    }

    /*
     * Ajax Function for Upload Your Own Gift Voucher.
     */

    public static function rs_ajax_for_upload_your_gift_voucher() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var rs_custom_uploader;
                jQuery('#rs_image_upload_button').click(function (e) {
                    e.preventDefault();
                    if (rs_custom_uploader) {
                        rs_custom_uploader.open();
                        return;
                    }
                    rs_custom_uploader = wp.media.frames.file_frame = wp.media({
                        title: 'Choose Image',
                        button: {text: 'Choose Image'
                        },
                        multiple: false
                    });
                    //When a file is selected, grab the URL and set it as the text field's value
                    rs_custom_uploader.on('select', function () {
                        attachment = rs_custom_uploader.state().get('selection').first().toJSON();
                        jQuery('#rs_image_url_upload').val(attachment.url);
                    });
                    //Open the uploader dialog
                    rs_custom_uploader.open();
                });
            });
        </script>
        <?php
    }

}

new RSJQueryFunction();
