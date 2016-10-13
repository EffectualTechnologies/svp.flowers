<?php

// Integrate WP List Table for Users for Viewing Log

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WP_List_Table_for_View_Log extends WP_List_Table {

    // Prepare Items
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();

        if (isset($_REQUEST['s'])) {
            $searchvalue = $_REQUEST['s'];
            $keyword = "/$searchvalue/";

            $newdata = array();
            foreach ($data as $eacharray => $value) {
                $searchfunction = preg_grep($keyword, $value);
                if (!empty($searchfunction)) {
                    $newdata[] = $data[$eacharray];
                }
            }
            usort($newdata, array(&$this, 'sort_data'));

            $perPage = 10;
            $currentPage = $this->get_pagenum();
            $totalItems = count($newdata);

            $this->set_pagination_args(array(
                'total_items' => $totalItems,
                'per_page' => $perPage
            ));

            $newdata = array_slice($newdata, (($currentPage - 1) * $perPage), $perPage);

            $this->_column_headers = array($columns, $hidden, $sortable);

            $this->items = $newdata;
        } else {
            usort($data, array(&$this, 'sort_data'));

            $perPage = 10;
            $currentPage = $this->get_pagenum();
            $totalItems = count($data);

            $this->set_pagination_args(array(
                'total_items' => $totalItems,
                'per_page' => $perPage
            ));

            $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

            $this->_column_headers = array($columns, $hidden, $sortable);

            $this->items = $data;
        }
    }

    public function get_columns() {
        $columns = array(
            'sno' => __('S.No', 'rewardsystem'),
            'user_name' => __('User Name', 'rewardsystem'),
            'reward_for' => __('Reward For', 'rewardsystem'),
            'earned_points' => __('Earned Points', 'rewardsystem'),
            'redeemed_points' => __('Redeemed Points', 'rewardsystem'),
            'total_points' => __('Total Points', 'rewardsystem'),
            'log_date' => __('Date', 'rewardsystem'),
        );

        return $columns;
    }

    public function get_hidden_columns() {
        return array();
    }

    public function get_sortable_columns() {
        return array('user_name' => array('user_name', false),
            'sno' => array('sno', false),
            'redeemed_points' => array('redeemed_points', false),
            'earned_points' => array('earned_points', false),
            'log_date' => array('log_date', false),
            'total_points' => array('total_points', false),
        );
    }

    private function table_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rsrecordpoints';
        $data = array();
        $i = 1;
        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
        $redeempoints = '0';
        $totalpoints = '0';
        $earnpoints = '0';
        $user_ID = $_GET['view'];
        $getuserbyid = get_user_by('id', $user_ID);
        $fetcharray = $wpdb->get_results("SELECT * FROM $table_name WHERE  userid=$user_ID AND showuserlog = false ", ARRAY_A);
        $fetcharray = $fetcharray + (array) get_user_meta($user_ID, '_my_points_log', true);

        if (is_array($fetcharray)) {
            foreach ($fetcharray as $values) {
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
                        $totalpoints = $values['totalpoints'];
                        $nomineeid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($values['nomineeid'], 'nickname');
                        $usernickname = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($values['userid'], 'nickname');
                        $nominatedpoints = $values['nomineepoints'];
                        $eventname = RSPointExpiry::rs_function_to_display_log($csvmasterlog, $user_deleted, $order_status_changed, $earnpoints, $order, $checkpoints, $productid, $orderid, $variationid, $userid, $refuserid, $reasonindetail, $redeempoints, $masterlog, $nomineeid, $usernickname, $nominatedpoints);
                    } else {
                        if (!empty($values['points_earned_order'])) {
                            if (get_option('rs_round_off_type') == '1') {
                                $pointsearned = $values['points_earned_order'];
                            } else {
                                $pointsearned = number_format($values['points_earned_order']);
                            }
                        } else {
                            $pointsearned = '0';
                        }

                        if (!empty($values['before_order_points'])) {
                            if (is_float($values['before_order_points'])) {
                                $beforepoints = number_format($values['before_order_points'], 2);
                            } else {
                                $beforepoints = number_format($values['before_order_points']);
                            }
                        } else {
                            $beforepoints = '0';
                        }

                        if (!empty($values['points_redeemed'])) {
                            if (get_option('rs_round_off_type') == '1') {
                                $redeemedpoints = $values['points_redeemed'];
                            } else {
                                $redeemedpoints = number_format((float) $values['points_redeemed']);
                            }
                        } else {
                            $redeemedpoints = '0';
                        }
                        if (!empty($values['totalpoints'])) {
                            if (get_option('rs_round_off_type') == '1') {
                                $totalpoints = $values['totalpoints'];
                            } else {
                                $totalpoints = number_format($values['totalpoints']);
                            }
                        } else {
                            $totalpoints = '0';
                        }

                        $usernickname = get_user_meta($values['userid'], 'nickname', true);
                        if (!empty($values['rewarder_for'])) {
                            $rewarderfor = $values['rewarder_for'];
                        } else {
                            $rewarderfor = '';
                        }

                        $eventname = $rewarderfor;
                        $earnpoints = $pointsearned;
                        $redeemedpoints = $redeempoints;



                        $values['earneddate'] = $update_start_date;
                    }
                    if (get_option('rs_dispaly_time_format') == '1') {
                        $update_start_date = $values['earneddate'];
                    } else {
                        $timeformat = get_option('time_format');
                        $dateformat = get_option('date_format') . ' ' . $timeformat;
                        $stringto_time = strtotime($values['earneddate']);
                        $update_start_date = date_i18n($dateformat, $stringto_time);
                    }
                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                    $earnpoints = round($earnpoints, $roundofftype);
                    $redeempoints = round($redeempoints, $roundofftype);
                    
                    $data[] = array(
                        'sno' => $i,
                        'user_name' => $getuserbyid->user_login,
                        'reward_for' => $eventname,
                        'earned_points' => $earnpoints,
                        'redeemed_points' => $redeempoints,
                        'total_points' => $totalpoints,
                        'log_date' => $update_start_date,
                    );
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
            case 'reward_for':
            case 'earned_points':
            case 'redeemed_points':
            case 'total_points':
            case 'log_date':

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
