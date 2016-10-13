<?php

// Integrate WP List Table for Master Log

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WP_List_Table_for_Master_Log extends WP_List_Table {

    // Prepare Items
    public function prepare_items() {
        global $wpdb;
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        
        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $startpoint = ($currentPage - 1) * $perPage;
        $userprefix = $wpdb->prefix . "rsrecordpoints";
        $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $userprefix WHERE showmasterlog = false and  userid NOT IN(0)");
        $data = $this->table_data($startpoint, $perPage);

        if (isset($_REQUEST['s'])) {
            $searchvalue = $_REQUEST['s'];
            $keyword = "$searchvalue";            
	    $userobject = get_user_by('login',$keyword);
            if(!empty($userobject)) {
            $newdata = array();            
	    $where_user_id = $userobject->ID;
            $mydata = $wpdb->get_results("SELECT * FROM $userprefix WHERE userid = $where_user_id",ARRAY_A);
            
   
            $newdata = $this->get_data_of_users_for_master_log($mydata);
               
            usort($newdata, array(&$this, 'sort_data'));

            $perPage = 10;
            $currentPage = $this->get_pagenum();
            $totalItems = count($newdata);          
            

            $this->_column_headers = array($columns, $hidden, $sortable);

            $this->items = $newdata;
          }
        } else {
            usort($data, array(&$this, 'sort_data'));                       

            $this->set_pagination_args(array(
                'total_items' => $num_rows,
                'per_page' => $perPage
            ));
           

            $this->_column_headers = array($columns, $hidden, $sortable);

            $this->items = $data;
        }
      
    }
    
    public static function get_data_of_users_for_master_log($array){
        global $wpdb;
        $table_name = $wpdb->prefix . 'rsrecordpoints';                
        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
        $subdatas = $array;  
        $eventname='';
       $i=1;
        if (is_array($subdatas)) {
            foreach ($subdatas as $values) {
                $getuserbyid = get_user_by('id', @$values['userid']);
                if (isset($values['earnedpoints'])) {
                    if (!empty($values['earnedpoints'])) {
                        if (is_float($values['earnedpoints'])) {

                            $total = round(number_format($values['earnedpoints'], 2), $roundofftype);
                        } else {
                            $total = number_format($values['earnedpoints']);
                        }
                    } else {
                        $total = @$values['earnedpoints'];
                    }
                } else {
                    $getuserbyid = get_user_by('id', @$values['userid']);

                    if (!empty($values['totalvalue'])) {
                        if (get_option('rs_round_off_type') == '1') {

                            $total = $values['totalvalue'];
                        } else {
                            $total = number_format($values['totalvalue']);
                        }
                    } else {
                        $total = @$values['totalvalue'];
                    }
                }

                if ($values != '') {
                    if (isset($values['earnedpoints'])) {
                        $orderid = $values['orderid'];
                        $order = new WC_Order($orderid);
                        $checkpoints = $values['checkpoints'];
                        $productid = $values['productid'];
                        $variationid = $values['variationid'];
                        $userid = $values['userid'];
                        $refuserid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($values['refuserid'], 'nickname');
                        $reasonindetail = $values['reasonindetail'];
                        $redeempoints = $values['redeempoints'];
                        $masterlog = true;
                        $earnpoints = $values['earnedpoints'];
                        $user_deleted = true;
                        $order_status_changed = true;
                        $csvmasterlog = false;
                        $nomineeid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($values['nomineeid'], 'nickname');
                        $usernickname = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($values['userid'], 'nickname');
                        $nominatedpoints = $values['nomineepoints'];
                        $eventname = RSPointExpiry::rs_function_to_display_log($csvmasterlog, $user_deleted, $order_status_changed, $earnpoints, $order, $checkpoints, $productid, $orderid, $variationid, $userid, $refuserid, $reasonindetail, $redeempoints, $masterlog, $nomineeid, $usernickname, $nominatedpoints);
                        $total = $total != '0' ? $total : $redeempoints;
                    } else {
                        $eventname = $values['eventname'];
                        $values['earneddate'] = $values['date'];
                    }
                    
                    
                     if(get_option('rs_dispaly_time_format')== '1'){
                                  $update_start_date=$values['earneddate'];
                             }else{                                
                                 $timeformat = get_option('time_format');
                                    $dateformat = get_option('date_format').' '.$timeformat;
                                    $stringto_time = strtotime($values['earneddate']);
                                    $update_start_date = date_i18n($dateformat,$stringto_time);
                             }
                    
                    $data[] = array(
                        'sno' => $i,
                        'user_name' => $getuserbyid->user_login,
                        'points' => $total,
                        'event' => $eventname==''? '-':$eventname,
                        'date' => $update_start_date,
                    );
                    $i++;
                }
            }
        }
        return $data;
    }

    public function get_columns() {
        $columns = array(
            'sno' => __('S.No', 'rewardsystem'),
            'user_name' => __('User Name', 'rewardsystem'),
            'points' => __('Points', 'rewardsystem'),
            'event' => __('Event', 'rewardsystem'),
            'date' => __('Date', 'rewardsystem'),
        );

        return $columns;
    }

    public function get_hidden_columns() {
        return array();
    }

    public function get_sortable_columns() {
        return array(
            'points' => array('points', false),
            'sno' => array('sno', false),
            'date' => array('date', false),
        );
    }

    private function table_data($startpoint, $perPage) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rsrecordpoints';
        $data = array();
        $i = $startpoint + 1;
        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
        $subdatas = $wpdb->get_results("SELECT * FROM $table_name WHERE showmasterlog = false and  userid NOT IN(0) LIMIT $startpoint, $perPage", ARRAY_A);
        $subdatas = $subdatas + (array) get_option('rsoveralllog');
        if (is_array($subdatas)) {
            foreach ($subdatas as $values) {
                $getuserbyid = get_user_by('id', @$values['userid']);
                if (isset($values['earnedpoints'])) {
                    if (!empty($values['earnedpoints'])) {
                        if (is_float($values['earnedpoints'])) {

                            $total = round(number_format($values['earnedpoints'], 2), $roundofftype);
                        } else {
                            $total = number_format($values['earnedpoints']);
                        }
                    } else {
                        $total = @$values['earnedpoints'];
                    }
                } else {
                    $getuserbyid = get_user_by('id', @$values['userid']);

                    if (!empty($values['totalvalue'])) {
                        if (get_option('rs_round_off_type') == '1') {

                            $total = $values['totalvalue'];
                        } else {
                            $total = number_format($values['totalvalue']);
                        }
                    } else {
                        $total = @$values['totalvalue'];
                    }
                }

                if ($values != '') {
                    if (isset($values['earnedpoints'])) {
                        $orderid = $values['orderid'];
                        $order = new WC_Order($orderid);
                        $checkpoints = $values['checkpoints'];
                        $productid = $values['productid'];
                        $variationid = $values['variationid'];
                        $userid = $values['userid'];
                        $refuserid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($values['refuserid'], 'nickname');
                        $reasonindetail = $values['reasonindetail'];
                        $redeempoints = $values['redeempoints'];
                        $masterlog = true;
                        $earnpoints = $values['earnedpoints'];
                        $user_deleted = true;
                        $order_status_changed = true;
                        $csvmasterlog = false;
                        $nomineeid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($values['nomineeid'], 'nickname');
                        $usernickname = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($values['userid'], 'nickname');
                        $nominatedpoints = $values['nomineepoints'];
                        $eventname = RSPointExpiry::rs_function_to_display_log($csvmasterlog, $user_deleted, $order_status_changed, $earnpoints, $order, $checkpoints, $productid, $orderid, $variationid, $userid, $refuserid, $reasonindetail, $redeempoints, $masterlog, $nomineeid, $usernickname, $nominatedpoints);
                        $total = $total != '0' ? $total : $redeempoints;
                    } else {
                         if(get_option('rsoveralllog') != ''){
                        $eventname = $values['eventname'];
                        $values['earneddate'] = $values['date'];
                         }
                    }
                    
                    if(get_option('rs_dispaly_time_format')== '1'){
                                  $update_start_date=$values['earneddate'];
                             }else{                                
                                  $timeformat = get_option('time_format');
                                    $dateformat = get_option('date_format').' '.$timeformat;
                                    $stringto_time = strtotime($values['earneddate']);
                                    $update_start_date = date_i18n($dateformat,$stringto_time);
                             }
                     $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                    $total = round($total, $roundofftype);
                             
                       if($getuserbyid != ''){
                    $data[] = array(
                        'sno' => $i,
                        'user_name' => $getuserbyid->user_login,
                        'points' => $total,
                        'event' => $eventname==''? '-':$eventname,
                        'date' => $update_start_date,
                    );
                       }
                    $i++;
                }
            }
        }
        return $data;
    }

    public function column_id($item) {
        return $item['sno'];
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'sno':
            case 'user_name':
            case 'points':
            case 'event':
            case 'date':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    private function sort_data($a, $b) {

        $orderby = 'sno';
        $order = 'asc';

        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }

        $result = strnatcmp($a[$orderby], $b[$orderby]);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }

}
