<?php

global $woocommerce;
global $unsublink2;
$user = get_userdata($user_id);
if (get_user_meta($user_id, 'unsub_value', true) != 'yes') {
    $user_wmpl_lang = get_user_meta($user_id, 'rs_wpml_lang', true);
    if (empty($user_wmpl_lang)) {
        $user_wmpl_lang = 'en';
    }
    $to = $user->user_email;
    $subject = RSWPMLSupport::fp_rs_get_wpml_text('rs_template_' . $emails->id . '_subject', $user_wmpl_lang, $emails->subject);
    $firstname = $user->user_firstname;
    $lastname = $user->user_lastname;
    $url_to_click = "<a href=" . site_url() . ">" . site_url() . "</a>";
    $userpoint = RSPointExpiry::get_sum_of_total_earned_points($user_id);
    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
    $userpoint = round($userpoint, $roundofftype);
    $earnedpointpoint = RSFunctionForEmailTemplate::rs_common_function_to_get_earned_points_for_order($order_id);   
    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
    $earnedpointpoint = round($earnedpointpoint, $roundofftype);
    $minimumuserpoints = $emails->minimum_userpoints;
    $wpnonce = wp_create_nonce('rs_unsubscribe_' . $user_id);
    $unsublink = esc_url_raw(add_query_arg(array('userid' => $user_id, 'unsub' => 'yes', 'nonce' => $wpnonce), site_url()));
    if ($minimumuserpoints == '') {
        $minimumuserpoints = 0;
    } else {
        $minimumuserpoints = $emails->minimum_userpoints;
    }
    if ($minimumuserpoints < $userpoint) {        
        if ($earnedpointpoint >= $emails->earningpoints) {
            $message = RSWPMLSupport::fp_rs_get_wpml_text('rs_template_' . $emails->id . '_message', $user_wmpl_lang, $emails->message);
            $message = str_replace('{rssitelink}', $url_to_click, $message);
            $message = str_replace('{rsfirstname}', $firstname, $message);
            $message = str_replace('{rslastname}', $lastname, $message);
            $message = str_replace('{rspoints}', $userpoint, $message);
            $message = $message;
            $message = do_shortcode($message); //shortcode feature
            $unsublink1 = get_option('rs_unsubscribe_link_for_email');
            $unsublink2 = str_replace('{rssitelinkwithid}', $unsublink, $unsublink1);
            add_filter('woocommerce_email_footer_text',array('RSFunctionForEmailTemplate', 'footer_link'));
            ob_start();
            wc_get_template('emails/email-header.php', array('email_heading' => $subject));
            echo $message;
            wc_get_template('emails/email-footer.php');
            $woo_temp_msg = ob_get_clean();
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            if ($emails->sender_opt == 'local') { 
                $headers .= "From: " . $emails->from_name . " <" . $emails->from_email . ">\r\n";
            } else {
                $headers .= "From: " . get_option('woocommerce_email_from_name') . " <" . get_option('woocommerce_email_from_address') . ">\r\n";                
            }            
            if ('2' == get_option('rs_select_mail_function')) {
                if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                    if (wp_mail($to, $subject, $woo_temp_msg, $headers = '')) {

                    }
                } else {
                    $mailer = WC()->mailer();
                   $mailer->send($to, $subject, $woo_temp_msg, $headers);
                }
            } elseif ('1' == get_option('rs_select_mail_function')) {
                if (mail($to, $subject, $woo_temp_msg, $headers, '-fwebmaster@' . $_SERVER['SERVER_NAME'])) {

                }
            } else {
                if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                    if (wp_mail($to, $subject, $woo_temp_msg, $headers = '')) {

                    }
                } else {
                    $mailer = WC()->mailer();
                   $mailer->send($to, $subject, $woo_temp_msg, $headers);
                }
            }
        }
    }
}
?>
