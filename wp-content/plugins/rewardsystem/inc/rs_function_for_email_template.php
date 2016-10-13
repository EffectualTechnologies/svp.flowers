<?php

class RSFunctionForEmailTemplate {

    public function __construct() {

        add_action('wp_enqueue_scripts', array($this, 'wp_enqueqe_script_for_email_footable'));

        add_action('admin_enqueue_scripts', array($this, 'wp_enqueqe_script_for_email_footable'));

        add_action('woocommerce_admin_field_list_table', array($this, 'add_sumo_rewards_table_list_email_templates'));

        add_action('wp_ajax_rs_new_template', array($this, 'create_template'));

        add_action('wp_ajax_nopriv_rs_new_template', array($this, 'create_template'));

        add_action('wp_ajax_rs_edit_template', array($this, 'edit_template'));

        add_action('wp_ajax_nopriv_rs_edit_template', array($this, 'edit_template'));

        add_action('wp_ajax_rs_delete_email_template', array($this, 'delete_template'));

        add_action('wp_ajax_nopriv_rs_delete_email_template', array($this, 'delete_template'));

        add_action('admin_head', array($this, 'rs_validation_of_input_field_in_email_template'));

        add_action('wp_ajax_subscribevalue', array($this, 'get_sub_value'));

        add_action('wp_head', array($this, 'get_the_checkboxvalue_from_myaccount_page'));

        add_action('woocommerce_before_my_account', array($this, 'sub_option_in_my_account_page'));

        add_action('wp_head', array($this, 'getting_value_to_unsubscribe'));
        add_action('wp_ajax_rs_email_template_status', array($this, 'email_template_status'));

        add_action('wp_ajax_nopriv_rs_email_template_status', array($this, 'email_template_status'));
    }

    public static function email_template_status() {
        if (isset($_POST['row_id'])) {
            global $wpdb;
            $table_name_email = $wpdb->prefix . 'rs_templates_email';
            $requesting_state = $_POST['status'] == 'ACTIVE' ? 'NOTACTIVE' : 'ACTIVE';
            $wpdb->update($table_name_email, array('rs_status' => $requesting_state), array('id' => $_POST['row_id']));
            echo $requesting_state;
        }
        exit();
    }

    public static function create_template() {
        global $wpdb;
        if (isset($_POST['rs_template_name'])) {
            if (!is_array($_POST['rs_sendmail_selected'])) {
                $_POST['rs_sendmail_selected'] = explode(',', $_POST['rs_sendmail_selected']);
            }
            $table_name_email = $wpdb->prefix . 'rs_templates_email';
            $wpdb->insert($table_name_email, array('template_name' => stripslashes($_POST['rs_template_name']),
                'sender_opt' => stripslashes($_POST['rs_sender_option']),
                'earningpoints' => stripslashes($_POST['earningpoints']),
                'redeemingpoints' => stripslashes($_POST['redeemingpoints']),
                'mailsendingoptions' => stripslashes($_POST['mailsendingoptions']),
                'rsmailsendingoptions' => stripslashes($_POST['rsmailsendingoptions']),
                'minimum_userpoints' => stripslashes($_POST['rs_minimum_userpoints']),
                'from_name' => stripslashes($_POST['rs_from_name']),
                'from_email' => stripslashes($_POST['rs_from_email']),
                'subject' => stripslashes($_POST['rs_subject']),
                'sendmail_options' => isset($_POST['rs_sendmail_options']) ? stripslashes($_POST['rs_sendmail_options']) : '',
                'sendmail_to' => serialize($_POST['rs_sendmail_selected']),
                'message' => stripslashes($_POST['rs_message']),
                'rs_status' => stripslashes($_POST['rs_template_status']),
            ));
        }
        echo $wpdb->insert_id;
        exit();
    }

    public static function edit_template() {
        if (isset($_POST['rs_template_id'])) {
            $template_id = $_POST['rs_template_id'];
            global $wpdb;
            $table_name_email = $wpdb->prefix . 'rs_templates_email';

            if ($_POST['mailsendingoptions'] == '1') {
                if ($_POST['rsmailsendingoptions'] == '1') {
                    //For earning
                    if ($_POST['earningpoints'] != $_POST['hiddenearningpoints']) {
                        delete_option('rsearningtemplates' . $template_id);
                    }
                }
                if ($_POST['rsmailsendingoptions'] == '2') {
                    if ($_POST['redeemingpoints'] != $_POST['hiddenredeemingpoints']) {
                        delete_option('rsredeemingtemplates' . $template_id);
                    }
                }
            }

            if (!is_array($_POST['rs_sendmail_selected'])) {
                $_POST['rs_sendmail_selected'] = explode(',', $_POST['rs_sendmail_selected']);
            }

            $wpdb->update($table_name_email, array('template_name' => stripslashes($_POST['rs_template_name']),
                'sender_opt' => stripslashes($_POST['rs_sender_option']),
                'from_name' => stripslashes($_POST['rs_from_name']),
                'from_email' => stripslashes($_POST['rs_from_email']),
                'earningpoints' => stripslashes($_POST['earningpoints']),
                'redeemingpoints' => stripslashes($_POST['redeemingpoints']),
                'mailsendingoptions' => stripslashes($_POST['mailsendingoptions']),
                'rsmailsendingoptions' => stripslashes($_POST['rsmailsendingoptions']),
                'minimum_userpoints' => stripslashes($_POST['rs_minimum_userpoints']),
                'sendmail_options' => isset($_POST['rs_sendmail_options']) ? stripslashes($_POST['rs_sendmail_options']) : '',
                'sendmail_to' => serialize($_POST['rs_sendmail_selected']),
                'subject' => stripslashes($_POST['rs_subject']),
                'message' => stripslashes($_POST['rs_message']),
                'rs_status' => stripslashes($_POST['rs_template_status']),
                    ), array('id' => $template_id));
        }
        echo "1";
        exit();
    }

    public static function delete_template() {
        if (isset($_POST['row_id'])) {
            global $wpdb;
            $row_id = $_POST['row_id'];
            $table_name_email = $wpdb->prefix . 'rs_templates_email';
            $wpdb->delete($table_name_email, array('id' => $row_id));
        }
        exit();
    }

    public static function add_sumo_rewards_table_list_email_templates() {
        global $woocommerce;
        ?>
        <p>Email Template Settings</p>

        <style type="text/css">
            p.submit {
                display:none;
            }
            #mainforms {
                display:none;
            }
            .chosen-container .chosen-results {
                clear: both;
            }
            .chosen-container {
                position:absolute !important;
            }
        </style>
        <?php
        echo RSJQueryFunction::rs_common_ajax_function_to_select_products('rs_multiselect_mail_send');
        ?>
        <?php
        if (isset($_GET['page']) == 'rewardsystem_callback') {

            if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                echo RSJQueryFunction::rs_common_chosen_function('rs_multiselect_mail_send');
            }
        }
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function () {
                jQuery('#rs_pagination').change(function (e) {
                    e.preventDefault();
                    var pageSize = jQuery(this).val();
                    jQuery('.rs_email_template_table').data('page-size', pageSize);
                    jQuery('.rs_email_template_table').trigger('footable_initialized');
                });
                jQuery('#rs_email_templates_table').footable().on('click', '.rs_delete', function (e) {
                    e.preventDefault();
                    var row_id = jQuery(this).data('id');
                    console.log(row_id);
                    var footable = jQuery('#rs_email_templates_table').data('footable');
                    var row = jQuery(this).parents('tr:first');
                    footable.removeRow(row);
                    var data = {
                        row_id: row_id,
                        action: "rs_delete_email_template"
                    }
                    jQuery.ajax({type: "POST",
                        url: ajaxurl,
                        data: data}).done(function (res) {

                    });
                });
                jQuery('#rs_email_templates_table').on('click', '.rs_mail_active', function (e) {
                    e.preventDefault();
                    var row_id = jQuery(this).data('rsmailid');
                    var obj = jQuery(this);
                    jQuery(obj).attr('disabled', true);
                    var status = jQuery(this).data('currentstate');
                    var data = {
                        row_id: row_id,
                        status: status,
                        action: "rs_email_template_status"
                    }
                    jQuery.ajax({type: "POST",
                        url: ajaxurl,
                        data: data}).done(function (res) {
                        obj.data('currentstate', res);
                        if (res == "ACTIVE") {
                            obj.text("Deactivate");
                        } else {
                            obj.text("Activate");
                        }
                        jQuery(obj).attr('disabled', false);

                    });
                });

            });
        </script>

        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'rs_templates_email';
        $templates = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);

        if (isset($_GET['rs_new_email'])) {
            $editor_id = "rs_email_template_new";
            $settings = array('textarea_name' => 'rs_email_template_new');
            $admin_url = admin_url('admin.php');
            $template_list_url = add_query_arg(array('page' => 'rewardsystem_callback', 'tab' => 'rewardsystem_emailtemplate'), $admin_url);
            $content = "Hi {rsfirstname} {rslastname}, <br><br> You have Earned Reward Points: {rspoints} on {rssitelink}  <br><br> You can use this Reward Points to make discounted purchases on {rssitelink} <br><br> Thanks";
            ?>

            <table class="widefat"><tr><td>

                <tr><td><span><strong>Use {rssitelink} to insert the Cart Link in the mail</strong></span></td></tr>
                <tr><td><span><strong>Use {rsfirstname} to insert Reciever First Name in the mail</strong></span></td></tr>
                <tr><td><span><strong>Use {rslastname} to insert Reciever Last Name in the mail</strong></span></td></tr>
                <tr><td><span><strong>{rspoints} to insert User Points in the Mail</strong></span></td></tr>
                <tr><td><?php _e('Template Name', 'rewardsystem') ?>: </td><td><input type="text" name="rs_template_name" id="rs_template_name"></td></tr>
                <tr><td><?php _e('Template Status', 'rewardsystem') ?>: </td><td><select name="rs_template_status" id="rs_template_status"> 
                            <option value="NOTACTIVE">Deactivated</option>
                            <option value="ACTIVE">Activated</option>
                        </select></td></tr>

                <tr><td><?php _e('Send Mail', 'rewardsystem'); ?>:</td><td><input type="radio" name="mailsendingoptions" id="mailsendingoptions1" value="1"/>Only Once <br>
                        <input type="radio" name="mailsendingoptions" id="mailsendingoptions2" class="mailsendingoptions" value="2"/>Always<br>
                    </td>
                </tr>
                <tr><td><?php _e('Send Mail Type', 'rewardsystem'); ?>:</td><td><input type="radio" name="rsmailsendingoptions" id="rsmailsendingoptions1" class="rsmailsendingoptions" value="1">Based on Earning Point<br>
                        <input type="radio" name="rsmailsendingoptions" id="rsmailsendingoptions2" class="rsmailsendingoptions" value="2">Based on Redeeming Point<br>
                        <input type="radio" name="rsmailsendingoptions" id="rsmailsendingoptions3" class="rsmailsendingoptions" value="3">Based on Cron Job<br>
                    </td></tr>
                <tr><td><?php _e('Send Mail on Earning Points of', 'rewardsystem'); ?></td>
                    <td><input type="text" name="earningpoints" class="earningpoints" id="earningpoints" value=""/></td>
                </tr>
                <tr><td><?php _e('Send Mail on Redeeming Points of', 'rewardsystem'); ?></td><td>
                        <input type="text" name="redeemingpoints" class="redeemingpoints" id="redeemingpoints" value=""/>
                    </td></tr>
                <tr><td><?php _e('Email Sender Option', 'rewardsystem') ?>: </td><td><input type="radio" name="rs_sender_opt" id="rs_sender_woo" value="woo" class="rs_sender_opt">woocommerce <input type="radio" name="rs_sender_opt" id="rs_sender_local" value="local" class="rs_sender_opt">local</td></tr>
                <tr class="rs_local_senders"><td><?php _e('From Name', 'rewardsystem') ?>: </td><td><input type="text" name="rs_from_name"  id="rs_from_name"></td></tr>
                <tr class="rs_local_senders"><td><?php _e('From Email', 'rewardsystem') ?>: </td><td><input type="text" name="rs_from_email"  id="rs_from_email"></td></tr>
                <tr class="rs_minimum_userpoints_field"><td><?php _e('Minimum User Points to send this Mail', 'rewardsystem'); ?>: </td><td><input type="text" class="rs_minimum_userpoints" name="rs_minimum_userpoints" id="rs_minimum_userpoints" value=""/></td></tr>
                <tr class="rs_sendmail_options"><td><?php _e('Send Mail To', 'rewardsystem'); ?>:</td><td><input type="radio" name="rs_sendmail_options" value="1" class="rs_sendmail_options">All Users <input type="radio" name="rs_sendmail_options" id="rs_sendmail_options" value="2" class="rs_sendmail_options"/>Selected Users</td></tr>
                <!-- Change the Logic to Optimize Get Users -->

            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                    <tr valign="top">
                        <td class="titledesc" scope="row">
                            <label for="rs_multiselect_mail_send"><?php _e('Send Mail to Selected Users', 'rewardsystem'); ?></label>
                        </td>
                        <td>
                            <select name="rs_multiselect_mail_send" id="rs_multiselect_mail_send" style="width:100%;" multiple="multiple" class="short rs_multiselect_mail_send">
                                <option></option>
                            </select>
                        </td>
                    </tr>
            <?php } else { ?>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="rs_multiselect_mail_send"><?php _e('Send Mail to Selected Users', 'rewardsystem'); ?></label>
                        </th>
                        <td>
                            <input type="hidden" class="wc-customer-search" name="rs_multiselect_mail_send" id="rs_multiselect_mail_send" data-multiple="true" data-placeholder="<?php _e('Search for a customer&hellip;', 'rewardsystem'); ?>" data-selected="" value="" data-allow_clear="true" />
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr><td><?php _e('Subject', 'rewardsystem') ?>:</td><td> <input type="text" name="rs_subject" id="rs_subject"></td></tr>
                <tr><td> <?php _e('Message', 'rewardsystem') ?>:</td>
                    <td>
                        <?php
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </td></tr>
                <tr><td>
                        <input type="button" name="rs_save_new_template" class="button button-primary button-large" id="rs_save_new_template" value="Save">&nbsp;

                        <a href="<?php echo $template_list_url ?>"><input type="button" class="button" name="returntolist" value="Return to Mail Templates"></a>&nbsp;
                    </td></tr>
            </table>
            <script>

                function get_tinymce_content() {
                    if (jQuery("#wp-rs_email_template_new-wrap").hasClass("tmce-active")) {
                        return tinyMCE.activeEditor.getContent();
                    } else {
                        return jQuery("#rs_email_template_new").val();
                    }
                }
                jQuery(document).ready(function () {


                    jQuery("#rs_template_name").val("Default");
                    jQuery("#rs_from_name").val("Admin");
                    jQuery('#rs_minimum_userpoints').val("0");
                    jQuery("#rs_sender_woo").attr("checked", "checked");
                    jQuery(".rs_sender_opt").change(function () {
                        if (jQuery("#rs_sender_woo").is(":checked")) {
                            jQuery(".rs_local_senders").css("display", "none");
                        } else {
                            jQuery(".rs_local_senders").css("display", "table-row");
                        }
                    });

                    jQuery('#mailsendingoptions2').attr('checked', 'checked');
                    jQuery('#rsmailsendingoptions3').attr('checked', 'checked');
                    var mailsendoptions = jQuery('.rsmailsendingoptions').filter(':checked').val();
                    if (mailsendoptions === '3') {
                        jQuery('#earningpoints').parent().parent().hide();
                        jQuery('#redeemingpoints').parent().parent().hide();
                    } else if (mailsendoptions === '2') {
                        jQuery('#earningpoints').parent().parent().hide();
                        jQuery('#redeemingpoints').parent().parent().show();
                    } else {
                        jQuery('#earningpoints').parent().parent().show();
                        jQuery('#redeemingpoints').parent().parent().hide();
                    }

                    jQuery('.rsmailsendingoptions').change(function () {
                        if (jQuery(this).val() === '3') {
                            jQuery('#earningpoints').parent().parent().hide();
                            jQuery('#redeemingpoints').parent().parent().hide();
                        } else if (jQuery(this).val() === '2') {
                            jQuery('#earningpoints').parent().parent().hide();
                            jQuery('#redeemingpoints').parent().parent().show();
                        } else {
                            jQuery('#earningpoints').parent().parent().show();
                            jQuery('#redeemingpoints').parent().parent().hide();
                        }
                    });

                    jQuery("#rs_subject").val("SUMO Reward Points");
                    jQuery("#rs_from_email").val("<?php echo get_option('admin_email') ?>");
                    jQuery("#rs_duration_type").val("days");
                    jQuery("#rs_duration").val("1");

                    jQuery("#rs_email_template_new").val("Hi {rsfirstname} {rslastname}, <br><br> You have Earned Reward Points: {rspoints} on {rssitelink}  <br><br> You can use this Reward Points to make discounted purchases on {rssitelink} <br><br> Thanks");
                    jQuery("#rs_duration_type").change(function () {
                        jQuery("span#rs_duration").html(jQuery("#rs_duration_type").val());
                    });
                    jQuery("#rs_save_new_template").click(function () {

                        var multivalue = jQuery('#rs_multiselect_mail_send').val();

                        jQuery(this).prop("disabled", true);
                        var rs_template_name = jQuery("#rs_template_name").val();
                        var sendmail = jQuery('input:radio[name=mailsendingoptions]:checked').val();
                        var sendmailtypes = jQuery('input:radio[name=rsmailsendingoptions]:checked').val();
                        var earningpoints = jQuery('#earningpoints').val();
                        var redeemingpoints = jQuery('#redeemingpoints').val();
                        var rs_sender_option = jQuery("input:radio[name=rs_sender_opt]:checked").val();
                        var rs_sendmail_options = jQuery("input:radio[name=rs_sendmail_options]:checked").val();
                        var rs_sendmail_selected = multivalue;
                        var rs_minimum_userpoints = jQuery('#rs_minimum_userpoints').val();
                        var rs_from_name = jQuery("#rs_from_name").val();
                        var rs_from_email = jQuery("#rs_from_email").val();
                        var rs_subject = jQuery("#rs_subject").val();
                        var rs_message = get_tinymce_content();
                        var rs_template_status = jQuery("#rs_template_status").val();
                        var rs_duration_type = jQuery("#rs_duration_type").val();
                        var rs_mail_duration = jQuery("span #rs_duration").val();
                        console.log(jQuery("#rs_email_template_new").val());

                        var data = {
                            action: "rs_new_template",
                            rs_sender_option: rs_sender_option,
                            rs_template_name: rs_template_name,
                            mailsendingoptions: sendmail,
                            rsmailsendingoptions: sendmailtypes,
                            earningpoints: earningpoints,
                            redeemingpoints: redeemingpoints,
                            rs_minimum_userpoints: rs_minimum_userpoints,
                            rs_from_name: rs_from_name,
                            rs_from_email: rs_from_email,
                            rs_sendmail_options: rs_sendmail_options,
                            rs_sendmail_selected: rs_sendmail_selected,
                            rs_subject: rs_subject,
                            rs_message: rs_message,
                            rs_template_status: rs_template_status,
                            rs_duration_type: rs_duration_type,
                            rs_mail_duration: rs_mail_duration
                        };

                        jQuery.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: data
                        }).done(function (response) {

                            alert("Settings Saved");
                            jQuery("#rs_save_new_template").prop("disabled", false);
                        });
                        console.log(data);
                    });
                });</script>
            <style>
                .rs_local_senders{
                    display:none;
                }
            </style>
            <?php
        } else if (isset($_GET['rs_edit_email'])) {
            $rs_mailsend_implode = '';
            $template_id = $_GET['rs_edit_email'];
            $edit_templates = $wpdb->get_results("SELECT * FROM $table_name WHERE id=$template_id", OBJECT);
            $edit_templates = $edit_templates[0];

            $admin_url = admin_url('admin.php');
            $template_list_url = add_query_arg(array('page' => 'rewardsystem_callback', 'tab' => 'rewardsystem_emailtemplate'), $admin_url);
            $editor_id = "rs_email_template_edit";
            $content = $edit_templates->message;
            $rs_mailsend_options = $edit_templates->sendmail_options;
            $rs_mailsend_selected_users = unserialize($edit_templates->sendmail_to);

            if (!empty($rs_mailsend_selected_users)) {
                $rs_mailsend_implode = implode(',', $rs_mailsend_selected_users);
            }
            $settings = array('textarea_name' => 'rs_email_template_edit');
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var data = "<?php echo $rs_mailsend_implode; ?>";
                    var values = "<?php echo $rs_mailsend_implode; ?>";
                    var splitted_data = values.split(',');
                    jQuery('#rs_multiselect_mail_send ').val(splitted_data);
                    jQuery.each(splitted_data, function (i, e) {
                        jQuery("select#rs_multiselect_mail_send option[value='" + e + "']").attr("selected", true);
            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery('#rs_multiselect_mail_send').trigger("chosen:updated");
            <?php } else { ?>
                            jQuery('body').trigger('wc-enhanced-select-init');
            <?php }
            ?>
                    });
                    jQuery("#rs_multiselect_mail_send option").each(function () {
                    });
                });
            </script>
            <table class="widefat"><tr><td>
                <tr><td><span><strong>Use {rssitelink} to insert the Cart Link in the mail</strong></span></td></tr>
                <tr><td><span><strong>Use {rsfirstname} to insert Reciever First Name in the mail</strong></span></td></tr>
                <tr><td><span><strong>Use {rslastname} to insert Reciever Last Name in the mail</strong></span></td></tr>
                <tr><td><span><strong>{rspoints} to insert User Points in the Mail</strong></span></td></tr>
                <tr>
                    <td> <?php _e('Template Name', 'rewardsystem') ?>:</td>
                    <td><input type="text" name="rs_template_name" id="rs_template_name" value="<?php echo $edit_templates->template_name ?>"></td></tr>
                <?php
                $getuser = array(0);
                $woo_selected = checked($edit_templates->sender_opt, 'woo', false);
                $local_selected = checked($edit_templates->sender_opt, 'local', false);

                $sendmail_option_selected = checked($edit_templates->sendmail_options, '1', false);
                $sendmail_particular_selected = checked($edit_templates->sendmail_options, '2', false);

                $sendmailonce = checked($edit_templates->mailsendingoptions, '1', false);
                $sendmailalways = checked($edit_templates->mailsendingoptions, '2', false);

                $sendmailtypebyearning = checked($edit_templates->rsmailsendingoptions, '1', false);
                $sendmailtypebyredeeming = checked($edit_templates->rsmailsendingoptions, '2', false);
                $sendmailtypebycronjob = checked($edit_templates->rsmailsendingoptions, '3', false);
                $template_active = selected($edit_templates->rs_status, 'ACTIVE', false);
                $template_not_active = selected($edit_templates->rs_status, 'NOTACTIVE', false);
                ?>
                <tr><td><?php _e('Template Status', 'rewardsystem') ?>: </td><td><select name="rs_template_status" id="rs_template_status">                       
                            <option value="NOTACTIVE" <?php echo $template_not_active ?> >Deactivated</option>
                                <option value="ACTIVE" <?php echo $template_active ?> >Activated</option>
                        </select>
                <tr><td><?php _e('Send Mail', 'rewardsystem'); ?>:</td><td><input type="radio" name="mailsendingoptions" id="mailsendingoptions" value="1" <?php echo $sendmailonce; ?>/>Only Once <br>
                        <input type="radio" name="mailsendingoptions" id="mailsendingoptions" class="mailsendingoptions" value="2" <?php echo $sendmailalways; ?>/>Always<br>
                    </td>
                </tr>
                <tr><td><?php _e('Send Mail Type', 'rewardsystem'); ?>:</td><td><input type="radio" name="rsmailsendingoptions" id="rsmailsendingoptions" class="rsmailsendingoptions" value="1" <?php echo $sendmailtypebyearning; ?>>Based on Earning Point<br>
                        <input type="radio" name="rsmailsendingoptions" id="rsmailsendingoptions" class="rsmailsendingoptions" value="2" <?php echo $sendmailtypebyredeeming; ?>>Based on Redeeming Point<br>
                        <input type="radio" name="rsmailsendingoptions" id="rsmailsendingoptions" class="rsmailsendingoptions" value="3" <?php echo $sendmailtypebycronjob; ?>>Based on Cron Job<br>
                    </td></tr>
                <tr><td><?php _e('Send Mail on Earning Points of', 'rewardsystem'); ?></td>
                    <td><input type="text" name="earningpoints" class="earningpoints" id="earningpoints" value="<?php echo $edit_templates->earningpoints; ?>"/></td>
                </tr>
                <tr><td><?php _e('Send Mail on Redeeming Points of', 'rewardsystem'); ?></td><td>
                        <input type="text" name="redeemingpoints" class="redeemingpoints" id="redeemingpoints" value="<?php echo $edit_templates->redeemingpoints; ?>"/>
                    </td></tr>


                <tr><td><?php _e('Email Sender Option', 'rewardsystem') ?>: </td><td><input type="radio" name="rs_sender_opt" id="rs_sender_woo" value="woo" <?php echo $woo_selected ?> class="rs_sender_opt">woocommerce
                        <input type="radio" name="rs_sender_opt" id="rs_sender_local" value="local" <?php echo $local_selected ?> class="rs_sender_opt">local</td></tr>
                <tr class="rs_local_senders"><td><?php _e('From Name', 'rewardsystem') ?>:</td>
                    <td><input type="text" name="rs_from_name" id="rs_from_name" value="<?php echo $edit_templates->from_name ?>"></td></tr>
                <tr class="rs_local_senders"><td><?php _e('From Email', 'rewardsystem') ?>:</td>
                    <td><input type="text" name="rs_from_email" id="rs_from_email" value="<?php echo $edit_templates->from_email ?>"></td></tr>
                <tr class="rs_minimum_userpoints_field">
                    <td><?php _e('Minimum User Points to send this Mail', 'rewardsystem'); ?></td> <td><input type="text" class="rs_minimum_userpoints" name="rs_minimum_userpoints" id="rs_minimum_edit_userpoints" value="<?php echo $edit_templates->minimum_userpoints; ?>"/></td>
                </tr>
                <tr class="rs_sendmail_options"><td><?php _e('Send Mail To', 'rewardsystem'); ?>:</td><td><input type="radio" id = "rs_sendmail_options_all" name="rs_sendmail_options" value="1" <?php echo $sendmail_option_selected ?> class="rs_sendmail_options">All Users <input type="radio" name="rs_sendmail_options" id="rs_sendmail_options_selected" value="2" <?php echo $sendmail_particular_selected; ?> class="rs_sendmail_options"/>Selected Users</td></tr>


                <!-- Send Mail Options -->

                <!-- Change the Logic to Optimize Get Users -->

            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                    <tr valign="top" class = "rs_select_mail_user">
                        <td class="titledesc" scope="row">
                            <label for="rs_multiselect_mail_send"><?php _e('Send Mail to Selected Users', 'rewardsystem'); ?></label>
                        </td>
                        <td>
                            <select name="rs_multiselect_mail_send[]" id="rs_multiselect_mail_send" style="width:100%;" multiple="multiple" class="short rs_multiselect_mail_send">
                                <?php
                                $json_ids = array();
                                if (!empty($edit_templates->sendmail_to)) {
                                    $getuser = array_filter(unserialize($edit_templates->sendmail_to));

                                    if ($getuser != "") {
                                        $listofuser = $getuser;
                                        if (!is_array($listofuser)) {
                                            $userids = array_filter(array_map('absint', (array) explode(',', $listofuser)));
                                        } else {
                                            $userids = $listofuser;
                                        }

                                        foreach ($userids as $userid) {
                                            $user = get_user_by('id', $userid);
                                            ?>

                                            <option value="<?php echo $userid; ?>" selected="selected"><?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email); ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                                <option></option>
                            </select>
                        </td>
                    </tr>
            <?php } else { ?>
                    <tr valign="top" class = "rs_select_mail_user">
                        <th class="titledesc" scope="row">
                            <label for="rs_multiselect_mail_send"><?php _e('Send Mail to Selected Users', 'rewardsystem'); ?></label>
                        </th>
                        <td>
                            <input type="hidden" class="wc-customer-search" name="rs_multiselect_mail_send" id="rs_multiselect_mail_send" data-multiple="true" data-placeholder="<?php _e('Search for a customer&hellip;', 'rewardsystem'); ?>" data-selected="<?php
                            $json_ids = array();
                            if (!empty($edit_templates->sendmail_to)) {
                                if (is_array($getuser)) {
                                    $getuser = array_filter(unserialize($edit_templates->sendmail_to));
                                    if ($getuser != "") {
                                        $listofuser = $getuser;
                                        if (!is_array($listofuser)) {
                                            $userids = array_filter(array_map('absint', (array) explode(',', $listofuser)));
                                        } else {
                                            $userids = $listofuser;
                                        }

                                        if (is_array($userids)) {
                                            if (!empty($userids)) {
                                                foreach ($userids as $userid) {
                                                    $user = get_user_by('id', $userid);
                                                    $json_ids[$user->ID] = esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email);
                                                }
                                            }
                                        }
                                        echo esc_attr(json_encode($json_ids));
                                    }
                                }
                            }
                            ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" data-allow_clear="true" />
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr><td><?php _e('Subject', 'rewardsystem') ?>:</td>
                    <td><input type="text" name="rs_subject" id="rs_subject" value="<?php echo $edit_templates->subject ?>"></td></tr>
                <tr><td> <?php _e('Message', 'rewardsystem') ?>:</td>
                    <td>
                        <?php
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </td></tr>
                <tr><td>
                        <input type="hidden" name="rsearningpointschanges" value="<?php echo $edit_templates->earningpoints ?>" id="rschangesearningpoint"/><br>
                        <input type="hidden" name="rsredeemingpointchanges" value="<?php echo $edit_templates->redeemingpoints; ?>" id="rschangesredeemingpoint"/><br>
                        <input type="button" class="button button-primary button-large" name="rs_save_new_template" id="rs_save_new_template" value="<?php _e('Save Changes', 'rewardsystem') ?>">&nbsp;
                        <a href="<?php echo $template_list_url ?>"><input type="button" class="button" name="returntolist" value="<?php _e('Return to Mail Templates', 'rewardsystem') ?>"></a>&nbsp;
                    </td></tr>
            </table>
            <script>
                function get_tinymce_content_edit() {
                    if (jQuery("#wp-rs_email_template_edit-wrap").hasClass("tmce-active")) {
                        return tinyMCE.activeEditor.getContent();
                    } else {
                        return jQuery("#rs_email_template_edit").val();
                    }
                }


                jQuery(document).ready(function () {


                    jQuery("#rs_duration_type").change(function () {
                        jQuery("span#rs_duration").html(jQuery("#rs_duration_type").val());
                    });


                    //normal ready event
                    if (jQuery("#rs_sender_woo").is(":checked")) {
                        jQuery(".rs_local_senders").css("display", "none");
                    } else {
                        jQuery(".rs_local_senders").css("display", "table-row");
                    }

                    jQuery(".rs_sender_opt").change(function () {
                        if (jQuery("#rs_sender_woo").is(":checked")) {
                            jQuery(".rs_local_senders").css("display", "none");
                        } else {
                            jQuery(".rs_local_senders").css("display", "table-row");
                        }
                    });


                    //normal ready event
                    if (jQuery("#rs_sendmail_options_all").is(":checked")) {
                        jQuery(".rs_select_mail_user").css("display", "none");
                    } else {
                        jQuery(".rs_select_mail_user").css("display", "table-row");
                    }

                    jQuery(".rs_sendmail_options").change(function () {
                        if (jQuery("#rs_sendmail_options_all").is(":checked")) {
                            jQuery(".rs_select_mail_user").css("display", "none");
                        } else {
                            jQuery(".rs_select_mail_user").css("display", "table-row");
                        }
                    });


                    var mailsendoptions = jQuery('.rsmailsendingoptions').filter(':checked').val();
                    if (mailsendoptions === '3') {
                        jQuery('#earningpoints').parent().parent().hide();
                        jQuery('#redeemingpoints').parent().parent().hide();
                    } else if (mailsendoptions === '2') {
                        jQuery('#earningpoints').parent().parent().hide();
                        jQuery('#redeemingpoints').parent().parent().show();
                    } else {
                        jQuery('#earningpoints').parent().parent().show();
                        jQuery('#redeemingpoints').parent().parent().hide();
                    }

                    jQuery('.rsmailsendingoptions').change(function () {
                        if (jQuery(this).val() === '3') {
                            jQuery('#earningpoints').parent().parent().hide();
                            jQuery('#redeemingpoints').parent().parent().hide();
                        } else if (jQuery(this).val() === '2') {
                            jQuery('#earningpoints').parent().parent().hide();
                            jQuery('#redeemingpoints').parent().parent().show();
                        } else {
                            jQuery('#earningpoints').parent().parent().show();
                            jQuery('#redeemingpoints').parent().parent().hide();
                        }
                    });

                    jQuery("#rs_save_new_template").click(function () {

                        var multivalue = jQuery('#rs_multiselect_mail_send').val();
                        jQuery(this).prop("disabled", true);
                        var rs_template_name = jQuery("#rs_template_name").val();

                        var sendmail = jQuery('input:radio[name=mailsendingoptions]:checked').val();
                        var sendmailtypes = jQuery('input:radio[name=rsmailsendingoptions]:checked').val();
                        var earningpoints = jQuery('#earningpoints').val();

                        var hiddenearningpoints = jQuery('#rschangesearningpoint').val();
                        var hiddenredeemingpoints = jQuery('#rschangesredeemingpoint').val();
                        var redeemingpoints = jQuery('#redeemingpoints').val();
                        var rs_sender_option = jQuery("input:radio[name=rs_sender_opt]:checked").val();
                        var rs_from_name = jQuery("#rs_from_name").val();
                        var rs_from_email = jQuery("#rs_from_email").val();
                        var rs_minimum_userpoints = jQuery('#rs_minimum_edit_userpoints').val();
                        var rs_sendmail_options = jQuery("input:radio[name=rs_sendmail_options]:checked").val();
                        var rs_sendmail_selected = multivalue;
                        var rs_subject = jQuery("#rs_subject").val();
                        var rs_template_status = jQuery("#rs_template_status").val();
                        var rs_message = get_tinymce_content_edit();
                        var rs_duration_type = jQuery("#rs_duration_type").val();
                        var rs_mail_duration = jQuery("span #rs_duration").val();
                        var rs_template_id = '<?php echo $template_id ?>';
                        console.log(jQuery("#rs_email_template_edit").val());


                        var data = {
                            action: "rs_edit_template",
                            rs_sender_option: rs_sender_option,
                            rs_template_name: rs_template_name,
                            mailsendingoptions: sendmail,
                            rsmailsendingoptions: sendmailtypes,
                            earningpoints: earningpoints,
                            hiddenearningpoints: hiddenearningpoints,
                            hiddenredeemingpoints: hiddenredeemingpoints,
                            redeemingpoints: redeemingpoints,
                            rs_minimum_userpoints: rs_minimum_userpoints,
                            rs_from_name: rs_from_name,
                            rs_from_email: rs_from_email,
                            rs_sendmail_options: rs_sendmail_options,
                            rs_sendmail_selected: rs_sendmail_selected,
                            rs_subject: rs_subject,
                            rs_message: rs_message,
                            rs_template_status: rs_template_status,
                            rs_duration_type: rs_duration_type,
                            rs_mail_duration: rs_mail_duration,
                            rs_template_id: rs_template_id
                        };

                        jQuery.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: data
                        }).done(function (response) {
                            var newresponse = response.replace(/\s/g, '');
                            if (newresponse === '1') {
                                alert("Settings Updated");
                            }
                            jQuery("#rs_save_new_template").prop("disabled", false);
                        });
                        console.log(data);
                    });
                });</script>
            <?php
        } else {
            $admin_url = admin_url('admin.php');
            $new_template_url = add_query_arg(array('page' => 'rewardsystem_callback', 'tab' => 'rewardsystem_emailtemplate', 'rs_new_email' => 'template'), $admin_url);

            $edit_template_url = add_query_arg(array('page' => 'rewardsystem_callback', 'tab' => 'rewardsystem_emailtemplate', 'rs_edit_email' => 'template'), $admin_url);
            ?>


            <a href='<?php echo $new_template_url ?>'>
                <input type="button" name="rs_new_email_template" id="rs_new_email_template" class="button" value="New Template">
            </a>
            <?php
            echo '<p> ' . __('Search:', 'rewardsystem') . '<input id="rs_email_templates" type="text"/>  ' . __('Page Size:', 'rewardsystem') . '
                <select id="changepagesizertemplates">
                <option value="1">1</option>
		<option value="5">5</option>
		<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                </p>';
            ?>
            <table class="wp-list-table widefat fixed posts" data-filter = "#rs_email_templates" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next" id="rs_email_templates_table" cellspacing="0">
                <thead>
                    <tr>
                        <th scope='col' data-toggle="true" class='manage-column column-serial_number'  style="">
                            <a href="#"><span><?php _e('S.No', 'rewardsystem'); ?></span>
                        </th>
                        <th scope='col' id='rs_user_names' class='manage-column column-rs_user_name'  style=""><?php _e('Template Name', 'rewardsystem'); ?></th>
                        <th scope='col' id='rs_from_name' class='manage-column column-rs_from_name'  style=""><?php _e('From Name', 'rewardsystem'); ?></th>
                        <th scope='col' id='rs_from_email' class='manage-column column-rs_from_email'  style=""><?php _e('From Email', 'rewardsystem'); ?></th>
                        <th scope="col" id="rs_subject" class="manage-column column-rs_subject" style=""><?php _e('Subject', 'rewardsystem'); ?></th>
                        <th scope='col' id='rs_message' class='manage-column column-rs_message' style=''><?php _e('Message', 'rewardsystem'); ?></th>
                        <th scope="col" id="rs_minimum_userpoints" class="manage-column column-rs_minimum_userpoints" style=""><?php _e('Minimum User Points', 'rewardsystem'); ?></th>
                        <th scope="col" id="rs_email_status" class="manage-column column-rs_email_status" style=""><?php _e('Status', 'rewardsystem'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($templates as $each_template) {
                        echo '<tr><td>';
                        echo $each_template->id;
                        $edit_template_url = add_query_arg(array('page' => 'rewardsystem_callback', 'tab' => 'rewardsystem_emailtemplate', 'rs_edit_email' => $each_template->id), $admin_url);
                        echo '&nbsp;<span><a href="' . $edit_template_url . '">' . __('Edit', 'rewardsystem') . ' </a></span>&nbsp; <span><a href="" class="rs_delete" data-id="' . $each_template->id . '">' . __('Delete', 'rewardsystem') . '</a></span>';
                        echo '</td><td>';
                        echo $each_template->template_name;
                        echo '</td><td>';
                        if ("local" == $each_template->sender_opt) {
                            echo $each_template->from_name;
                            echo '</td><td>';
                            echo $each_template->from_email;
                        } else {
                            echo get_option('woocommerce_email_from_name');
                            echo '</td><td>';
                            echo get_option('woocommerce_email_from_address');
                        }
                        echo '</td><td>';
                        echo $each_template->subject;
                        echo '</td><td>';
                        $message = strip_tags($each_template->message);
                        if (strlen($message) > 80) {
                            echo substr($message, 0, 80);
                            echo '...';
                        } else {
                            echo $message;
                        }
                        echo '</td><td>';
                        echo $each_template->minimum_userpoints;
                        echo '</td><td>';
                        $status = $each_template->rs_status;
                        $mail_id = $each_template->id;
                        if ($status == 'ACTIVE') {
                            echo ' <a href="#" class="button rs_mail_active" data-rsmailid="' . $mail_id . '" data-currentstate="ACTIVE">Deactivate</a>';
                            echo '</td></tr>';
                        } else {
                            echo ' <a href="#" class="button rs_mail_active" data-rsmailid="' . $mail_id . '" data-currentstate="NOTACTIVE">Activate</a>';
                            echo '</td></tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div style="clear:both;">
                <div class="pagination pagination-centered"></div>
            </div>
            <?php
        }
    }

    public static function wp_enqueqe_script_for_email_footable() {
        wp_register_script('wp_reward_footable', plugins_url('rewardsystem/js/footable.js'));
        wp_register_script('wp_reward_footable_filter', plugins_url('rewardsystem/js/footable.filter.js'));
        wp_enqueue_script('wp_reward_footable');
        wp_enqueue_script('wp_reward_footable_filter');
    }

    public static function rs_validation_of_input_field_in_email_template() {
        ?>

        <script type="text/javascript">

            jQuery(function () {
                jQuery('body').on('blur', '#earningpoints[type=text],\n\
                                           #rs_minimum_edit_userpoints[type=text],\n\
                                           #rs_minimum_userpoints[type=text],\n\
                                           #redeemingpoints[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#earningpoints[type=text],\n\
                                           #rs_minimum_edit_userpoints[type=text],\n\
                                           #rs_minimum_userpoints[type=text],\n\
                                           #redeemingpoints[type=text]', function () {
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


                if (jQuery('#rs_show_hide_your_subscribe_link').val() === '1') {
                    jQuery('#rs_unsub_field_caption').parent().parent().show();
                } else {
                    jQuery('#rs_unsub_field_caption').parent().parent().hide();
                }

                jQuery('#rs_show_hide_your_subscribe_link').change(function () {
                    if (jQuery('#rs_show_hide_your_subscribe_link').val() === '1') {
                        jQuery('#rs_unsub_field_caption').parent().parent().show();
                    } else {
                        jQuery('#rs_unsub_field_caption').parent().parent().hide();
                    }
                });

            });
        </script>
        <?php
    }

    public static function rsmail_sending_on_custom_rule($user_id, $order_id) {
        global $wpdb;
        global $woocommerce;
        $emailtemplate_table_name = $wpdb->prefix . 'rs_templates_email';
        $email_templates = $wpdb->get_results("SELECT * FROM $emailtemplate_table_name"); //all email templates        
        if (is_array($email_templates)) {
            foreach ($email_templates as $emails) {
                if ($emails->rs_status == "ACTIVE") {
                    if ($emails->mailsendingoptions == '1') {
                        if ($emails->rsmailsendingoptions == '1') {
                            if (get_option('rsearningtemplates' . $emails->id) != '1') {
                                if ($emails->sendmail_options == '1') {
                                    include'rsmailsendingearning.php';
                                } else {
                                    include'rsmailsendingearning2.php';
                                }
                                update_option('rsearningtemplates' . $emails->id, '1');
                            }
                        }

                        if ($emails->rsmailsendingoptions == '2') {
                            if (get_option('rsredeemingtemplates' . $emails->id) != '1') {
                                if ($emails->sendmail_options == '1') {
                                    include'rsmailsendingredeeming.php';
                                } else {
                                    include'rsmailsendingredeeming2.php';
                                }
                                update_option('rsredeemingtemplates' . $emails->id, '1');
                            }
                        }
                    } else {
                        if ($emails->rsmailsendingoptions == '1') {
                            if ($emails->sendmail_options == '1') {
                                include'rsmailsendingearning.php';
                            } else {
                                include'rsmailsendingearning2.php';
                            }
                        }
                        if ($emails->rsmailsendingoptions == '2') {
                            if ($emails->sendmail_options == '1') {
                                include 'rsmailsendingredeeming.php';
                            } else {
                                include 'rsmailsendingredeeming2.php';
                            }
                        }
                    }
                }
            }
        }
    }

    public static function get_sub_value() {
        if ($_POST['getcurrentuser'] && $_POST['subscribe'] == 'no') {
            update_user_meta($_POST['getcurrentuser'], 'unsub_value', 'no');
            echo "1";
        } else {
            update_user_meta($_POST['getcurrentuser'], 'unsub_value', 'yes');
            echo "2";
        }

        exit();
    }

    /* For Unsubscribe option in My account Page */

    public static function sub_option_in_my_account_page() {
        if ((get_option('rs_show_hide_your_subscribe_link')) == '1') {
            ?>
            <br><h3><input type="checkbox" name="subscribeoption" id="subscribeoption" value="yes" <?php checked("yes", get_user_meta(get_current_user_id(), 'unsub_value', true)); ?>/>    <?php echo get_option('rs_unsub_field_caption'); ?></h3>
                <?php
            }
        }

        public static function get_the_checkboxvalue_from_myaccount_page() {
            ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {

                jQuery('#subscribeoption').click(function () {
                    var subscribe = jQuery('#subscribeoption').is(':checked') ? 'yes' : 'no';
                    var getcurrentuser =<?php echo get_current_user_id(); ?>
                    var data = {
                        action: 'subscribevalue',
                        subscribe: subscribe,
                        getcurrentuser: getcurrentuser,
                    };
                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data,
                            function (response) {
                                if (response === '2') {
                                    alert("Successfully Unsubscribed...");
                                } else {
                                    alert("Successfully Subscribed...");
                                }
                            });
                });
            });
        </script>

        <?php
    }

    public static function footer_link() {
        global $unsublink2;

        return $unsublink2;
    }

    public static function getting_value_to_unsubscribe() {

        if (isset($_GET['userid']) && isset($_REQUEST['nonce'])) {
            $user_id = $_GET['userid'];


            if (($_GET['userid']) && ($_GET['unsub'] == 'yes')) {
                update_user_meta($_GET['userid'], 'unsub_value', 'yes');
                wp_safe_redirect(site_url());
                exit();
            }
        }
    }

    public static function rs_common_function_to_get_earned_points_for_order($orderid) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rsrecordpoints';
        $order_obj = new WC_Order($orderid);
        $order_status = str_replace('wc-', '', $order_obj->post_status);
        $getoverallearnpoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE orderid=$orderid and checkpoints != 'RVPFRP'", ARRAY_A);
        foreach ($getoverallearnpoints as $getoverallearnpointss) {
            $overall_earned_totals[] = $getoverallearnpointss['earnedpoints'];
        }
        $getoverallredeempoints = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE orderid=$orderid and checkpoints != 'RVPFPPRP'", ARRAY_A);
        foreach ($getoverallredeempoints as $getoverallredeempointss) {
            $overall_redeem_totals[] = $getoverallredeempointss['redeempoints'];
        }
        $gettotalearnpoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE checkpoints = 'PPRP' and orderid=$orderid", ARRAY_A);
        foreach ($gettotalearnpoints as $gettotalearnpointss) {
            $earned_totals[] = $gettotalearnpointss['earnedpoints'];
        }
        $getrevisedearnedpoint = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE checkpoints = 'RVPFPPRP' and orderid=$orderid", ARRAY_A);
        foreach ($getrevisedearnedpoint as $getrevisedearnedpoints) {
            $revised_earned_totals[] = $getrevisedearnedpoints['redeempoints'];
        }
        $gettotalredeempoints = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE checkpoints = 'RP' and orderid=$orderid", ARRAY_A);
        foreach ($gettotalredeempoints as $gettotalredeempointss) {
            $redeem_totals[] = $gettotalredeempointss['redeempoints'];
        }
        $getrevisedredeempoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE checkpoints = 'RVPFRP' and orderid=$orderid", ARRAY_A);
        foreach ($getrevisedredeempoints as $getrevisedredeempointss) {
            $revised_redeem_totals[] = $getrevisedredeempointss['earnedpoints'];
        }
        $totalearnedvalue = array_sum($overall_earned_totals) - array_sum($revised_earned_totals);
        return $totalearnedvalue;
    }

    public static function rs_common_function_to_get_redeem_points_for_order($orderid) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rsrecordpoints';
        $order_obj = new WC_Order($orderid);
        $order_status = str_replace('wc-', '', $order_obj->post_status);
        $overall_redeem_totals = array(0);
        $revised_redeem_totals = array(0);
        $getoverallearnpoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE orderid=$orderid and checkpoints != 'RVPFRP'", ARRAY_A);
        foreach ($getoverallearnpoints as $getoverallearnpointss) {
            $overall_earned_totals[] = $getoverallearnpointss['earnedpoints'];
        }
        $getoverallredeempoints = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE orderid=$orderid and checkpoints != 'RVPFPPRP'", ARRAY_A);
        foreach ($getoverallredeempoints as $getoverallredeempointss) {
            $overall_redeem_totals[] = $getoverallredeempointss['redeempoints'];
        }
        $gettotalearnpoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE checkpoints = 'PPRP' and orderid=$orderid", ARRAY_A);
        foreach ($gettotalearnpoints as $gettotalearnpointss) {
            $earned_totals[] = $gettotalearnpointss['earnedpoints'];
        }
        $getrevisedearnedpoint = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE checkpoints = 'RVPFPPRP' and orderid=$orderid", ARRAY_A);
        foreach ($getrevisedearnedpoint as $getrevisedearnedpoints) {
            $revised_earned_totals[] = $getrevisedearnedpoints['redeempoints'];
        }
        $gettotalredeempoints = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE checkpoints = 'RP' and orderid=$orderid", ARRAY_A);
        foreach ($gettotalredeempoints as $gettotalredeempointss) {
            $redeem_totals[] = $gettotalredeempointss['redeempoints'];
        }
        $getrevisedredeempoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE checkpoints = 'RVPFRP' and orderid=$orderid", ARRAY_A);
        foreach ($getrevisedredeempoints as $getrevisedredeempointss) {
            $revised_redeem_totals[] = $getrevisedredeempointss['earnedpoints'];
        }
        $totalredeemvalue = array_sum($overall_redeem_totals) - array_sum($revised_redeem_totals);
        return $totalredeemvalue;
    }

}

new RSFunctionForEmailTemplate();
