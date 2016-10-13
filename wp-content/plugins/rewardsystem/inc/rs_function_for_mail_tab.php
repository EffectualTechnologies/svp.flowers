<?php

class RSFunctionForMail {
    
    public function __construct() {
        
        add_action('admin_head', array($this, 'add_header_script_for_js'));
        
        add_action('admin_head',array($this,'rs_validation_of_input_field_in_mail'));
        
        add_action('update_option_rs_mail_cron_type', array($this, 'rs_cron_job_setting'));
        add_action('update_option_rs_mail_cron_time', array($this, 'rs_cron_job_setting'));
        
    }
     public static function rs_cron_job_setting() {
        wp_clear_scheduled_hook('rscronjob');
        delete_option('rscheckcronsafter');
        if (wp_next_scheduled('rscronjob') == false) {
            wp_schedule_event(time(), 'rshourly', 'rscronjob');
        }
    }
    
    public static function add_header_script_for_js() {
        global $woocommerce;
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_mail') {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                <?php if ((float) $woocommerce->version > (float) ('2.2.0')) { ?>
                            var troubleemail = jQuery('#rs_select_mail_function').val();
                            if (troubleemail === '1') {
                                jQuery('.prependedrc').remove();
                                jQuery('#rs_select_mail_function').parent().append('<span class="prependedrc">For WooCommerce 2.3 or higher version mail() function will not load the woocommerce default template. This option will be deprecated </span>');
                            } else {
                                jQuery('.prependedrc').remove();
                            }
                            jQuery('#rs_select_mail_function').change(function () {
                                if (jQuery(this).val() === '1') {
                                    jQuery('.prependedrc').remove();
                                    jQuery('#rs_select_mail_function').parent().append('<span class="prependedrc">For WooCommerce 2.3 or higher version mail() function will not load the woocommerce default template. This option will be deprecated </span>');
                                } else {
                                    jQuery('.prependedrc').remove();
                                }
                            });

                <?php } ?>
                    });
                </script>
                <?php
            }
        }
    }
    
    public static function rs_validation_of_input_field_in_mail(){        
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_mail_cron_time[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#rs_mail_cron_time[type=text]', function () {
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
}

new RSFunctionForMail();
