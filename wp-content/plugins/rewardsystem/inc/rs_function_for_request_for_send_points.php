<?php
class RSFunctionForRequestSendPoints {
    
    public function __construct() {
        
        add_action('woocommerce_admin_field_rs_send_point_applications_edit_lists', array($this, 'send_point_applications_list_table'));
        
        add_action('woocommerce_admin_field_rs_send_point_applications_list', array($this, 'send_list_overall_applications'));           
        
    }
       public static function send_point_validation($item) {
        $messages = array();

        if (empty($messages))
            return true;
        return implode('<br />', $messages);
    }
    
      public static function send_point_applications_list_table($item) {
        global $wpdb;        
        $table_name = $wpdb->prefix . 'sumo_reward_send_point_submitted_data';
        $message = '';
        $notice = '';
        $default = array(
            'id' => 0,
  'userid' => '',
  'pointstosend'=> '',	
  'sendercurrentpoints' => '',
 'selecteduser'=> '',
  'status' => '',
 
        );

        if (isset($_REQUEST['nonce'])) {
            if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
                $item = shortcode_atts($default, $_REQUEST);                
                $item_valid = self::send_point_validation($item);
                if ($item_valid === true) {
                    if ($item['id'] == 0) {
                        $result = $wpdb->insert($table_name, $item);
                        $item['id'] = $wpdb->insert_id;
                        if ($result) {
                            $message = __('Item was successfully saved');
                        } else {
                            $notice = __('There was an error while saving item');
                        }
                    } else {
                        $result = $wpdb->update($table_name, $item, array('id' => $item['id']));



                        if ($result) {
                            $message = __('Item was successfully updated');
                        } else {
                            $notice = __('There was an error while updating item');
                        }
                    }
                } else {
                    // if $item_valid not true it contains error message(s)
                    $notice = $item_valid;
                }
            }
        } else {
            // if this is not post back we load item to edit or give new one to create
            $item = $default;
            
            if (isset($_REQUEST['send_application_id'])) {
                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['send_application_id']), ARRAY_A);

                if (!$item) {
                    $item = $default;
                    $notice = __('Item not found');
                }
            }
        }
        ?>
        <?php        
        if (isset($_REQUEST['send_application_id'])) {            
            ?>
            <style type="text/css">
                p.submit {
                    display:none;
                }
                #mainforms {
                    display:none;
                }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                     
                 
               
               
                });
            </script>
            <?php
            $timeformat = get_option('time_format');
            $dateformat = get_option('date_format') . ' ' . $timeformat;
            $expired_date = date_i18n($dateformat);
            ?>
            <div class="wrap">
                <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                <h3><?php _e('Edit Cash Back Status', 'rewardsystem'); ?><a class="add-new-h2"
                                             href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=rewardsystem_callback&tab=send_applications'); ?>"><?php _e('Back to list') ?></a>
                </h3>
                <?php if (!empty($notice)): ?>
                    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
                <?php endif; ?>
                <?php if (!empty($message)): ?>
                    <div id="message" class="updated"><p><?php echo $message ?></p></div>
                <?php endif; ?>
                <form id="form" method="POST">
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__)) ?>"/>
                    <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>
                    <input type="hidden" name="userid" value="<?php echo $item['userid']; ?>"/>
                    <input type="hidden" value="<?php echo $item['setvendoradmins']; ?>" name="setvendoradmins"/>
                    <input type="hidden" value="<?php echo $item['setusernickname']; ?>" name="setusernickname"/>
                    <input type="hidden" value="<?php echo $expired_date; ?>" name="date"/>
                    <div class="metabox-holder" id="poststuff">
                        <div id="post-body">
                            <div id="post-body-content">
                                <table class="form-table">
                                    <tbody>                                        
                                        <tr>
                                            <th scope="row"><?php _e('Points for Send', 'rewardsystem'); ?></th>
                                            <td>
                                                <input type="text" name="pointstosend" id="setvendorname" value="<?php echo $item['pointstosend']; ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php _e('Current User Point', 'rewardsystem'); ?></th>
                                            <td>
                                                <textarea name="sendercurrentpoints" rows="3" cols="30"><?php echo $item['sendercurrentpoints']; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php _e('Application Status', 'rewardsystem'); ?></th>
                                            <td>
                                                <?php                                                
                                                $selected_approved = $item['status'] == 'Paid' ? "selected=selected" : '';
                                                $selected_rejected = $item['status'] == 'Due' ? "selected=selected" : '';                                                
                                                ?>
                                                <select name = "status">                                                    
                                                    <option value = "Paid" <?php echo $selected_approved; ?>><?php _e('Paid', 'rewardsystem'); ?></option>
                                                    <option value = "Due" <?php echo $selected_rejected; ?>><?php _e('Due', 'rewardsystem'); ?></option>
                                                </select>
                                            </td>
                                        </tr>                                                                                
                                     
                                    </tbody>
                                </table>
                                <input type="submit" value="<?php _e('Save Changes', 'rewardsystem') ?>" id="submit" class="button-primary" name="submit">
                            </div>
                        </div>
                    </div>                    
                </form>

            </div>
        <?php } ?>

        <?php
    }
    
    public static function send_list_overall_applications() {
        global $wpdb;
        global $current_section;
        global $current_tab;
       
        $testListTable = new FPRewardSystemSendpointTabList();
        $testListTable->prepare_items();



        if (!isset($_REQUEST['send_application_id'])) {
            $array_list = array();
            $message = '';
            if ('send_application_delete' === $testListTable->current_action()) {
                $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d'), count($_REQUEST['id'])) . '</p></div>';
            }
            echo $message;
            $testListTable->display();
            ?>

            <style type="text/css">
                p.submit {
                    display:none;
                }
                #mainforms {
                    display:none;
                }
            </style>

            <?php
        }
    }
    
  
}

new RSFunctionForRequestSendPoints();
