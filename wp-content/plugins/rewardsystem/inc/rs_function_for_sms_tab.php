<?php

class RSFunctionForSms {
   
    public function __construct() {
        
        add_action('admin_head',array($this,'display_credentials_for_smsapi'));                
        
    }
    
    public static function display_credentials_for_smsapi(){
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                if ((jQuery('input[name=rs_sms_sending_api_option]:checked').val()) === '1') {
                    jQuery('#rs_nexmo_key').parent().parent().hide();
                    jQuery('#rs_nexmo_secret').parent().parent().hide();                
                    jQuery('#rs_twilio_secret_account_id').parent().parent().show();                
                    jQuery('#rs_twilio_auth_token_id').parent().parent().show();                
                    jQuery('#rs_twilio_from_number').parent().parent().show();                
                }else{
                    jQuery('#rs_nexmo_key').parent().parent().show();
                    jQuery('#rs_nexmo_secret').parent().parent().show();                
                    jQuery('#rs_twilio_secret_account_id').parent().parent().hide();                
                    jQuery('#rs_twilio_auth_token_id').parent().parent().hide();                
                    jQuery('#rs_twilio_from_number').parent().parent().hide();                
                } 
                jQuery('input[name=rs_sms_sending_api_option]:radio').change(function () {
                    if ((jQuery('input[name=rs_sms_sending_api_option]:checked').val()) === '1') {
                    jQuery('#rs_nexmo_key').parent().parent().hide();
                    jQuery('#rs_nexmo_secret').parent().parent().hide(); 
                    jQuery('#rs_twilio_secret_account_id').parent().parent().show();                
                    jQuery('#rs_twilio_auth_token_id').parent().parent().show();                
                    jQuery('#rs_twilio_from_number').parent().parent().show();
                }else{
                    jQuery('#rs_nexmo_key').parent().parent().show();
                    jQuery('#rs_nexmo_secret').parent().parent().show();                
                    jQuery('#rs_twilio_secret_account_id').parent().parent().toggle();                
                    jQuery('#rs_twilio_auth_token_id').parent().parent().toggle();                
                    jQuery('#rs_twilio_from_number').parent().parent().toggle();                
                } 
                });
            });
        </script>
        <?php
    }
    
    public static function send_sms_twilio_api($order_id) {        
        global $woocommerce;
        require_once  "Twilio.php";
        $order_id = new WC_order($order_id);
        $phone_number = get_user_meta($order_id->user_id, 'billing_phone', true);
        if (strpos($phone_number, '+') !== false) {
            $user_id = $order_id->user_id;
            $user_details = get_user_by('id', $user_id);
            $user_login = $user_details->user_login;
            $user_points = RSPointExpiry::get_sum_of_total_earned_points($user_id);
            $url_to_click = site_url();
                    $banning_type = FPRewardSystem::check_banning_type($order_id->user_id);        
                    if($banning_type!='earningonly'&&$banning_type!='both') {                   
            $AccountSid = get_option('rs_twilio_secret_account_id');
            $AuthToken = get_option('rs_twilio_auth_token_id');
            $client = new Services_Twilio($AccountSid, $AuthToken);
            $people = array(
                $phone_number => $user_login,
            );
                    $message_content = get_option('rs_points_sms_content');
                    $message_content_name_to_find = "{username}";
                    $message_content_name_to_replace = $user_login;
                    $message_content_points_to_find = "{rewardpoints}";
                    $message_content_points_to_replace = round($user_points);
                    $message_content_link_to_find = "{sitelink}";
                    $message_content_link_to_replace = $url_to_click;
                    $message_replaced_name = str_replace($message_content_name_to_find,$message_content_name_to_replace,$message_content);
                    $message_replaced_points = str_replace($message_content_points_to_find,$message_content_points_to_replace,$message_replaced_name);
                    $message_replaced_link = str_replace($message_content_link_to_find,$message_content_link_to_replace,$message_replaced_points);            
            foreach ($people as $number => $name) {
                $sms = $client->account->messages->sendMessage(
                        get_option('rs_twilio_from_number'),
    // the number we are sending to - Any phone number
                        $phone_number,
    // the sms body
                        $message_replaced_link
                );             
            }
        }
        
            }
    }
    
    public static function send_sms_nexmo_api($order_id) {        
        global $woocommerce;
        include_once ( "NexmoMessage.php" );
        $order_id = new WC_order($order_id);
        $phone_number = get_user_meta($order_id->user_id, 'billing_phone', true);
        if (strpos($phone_number, '+') !== false) {
            echo "valid Phone number";
            $user_id = $order_id->user_id;
            $user_details = get_user_by('id', $user_id);
            $user_login = $user_details->user_login;
            $user_points = RSPointExpiry::get_sum_of_total_earned_points($user_id);
            $url_to_click = site_url();
                    $banning_type = FPRewardSystem::check_banning_type($order_id->user_id);        
                    if($banning_type!='earningonly'&&$banning_type!='both') {
                    $nexmo_sms = new NexmoMessage(get_option('rs_nexmo_key'), get_option('rs_nexmo_secret'));
                    $message_content = get_option('rs_points_sms_content');
                    $message_content_name_to_find = "{username}";
                    $message_content_name_to_replace = $user_login;
                    $message_content_points_to_find = "{rewardpoints}";
                    $message_content_points_to_replace = round($user_points);
                    $message_content_link_to_find = "{sitelink}";
                    $message_content_link_to_replace = $url_to_click;
                    $message_replaced_name = str_replace($message_content_name_to_find,$message_content_name_to_replace,$message_content);
                    $message_replaced_points = str_replace($message_content_points_to_find,$message_content_points_to_replace,$message_replaced_name);
                    $message_replaced_link = str_replace($message_content_link_to_find,$message_content_link_to_replace,$message_replaced_points);                   
                    $info = $nexmo_sms->sendText($phone_number, 'Sumo Rewards', $message_replaced_link);
        }         
        }
    }
}
new RSFunctionForSms();