<?php
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class FPRewardSystemEncashTabList extends WP_List_Table {

    function __construct() {
        global $status, $page;
        parent::__construct(array(
            'singular' => 'encashing_application',
            'plural' => 'encashing_applications',
            'ajax' => true
        ));
    }

    function column_default($item, $column_name) {
        return $item[$column_name];
    }

    function column_userloginname($item) {


        if ($item['status'] == 'Paid') {
            //Build row actions
            $actions = array(
                'cancel' => sprintf('<a href="?page=%s&tab=%s&action=%s&id=%s">Cancel</a>', $_REQUEST['page'], $_REQUEST['tab'], 'cancel', $item['id']),
                'delete' => sprintf('<a href="?page=%s&tab=%s&action=%s&id=%s">Delete</a>', $_REQUEST['page'], $_REQUEST['tab'], 'encash_application_delete', $item['id']),
            );

            //Return the title contents
            return sprintf('%1$s %3$s',
                    /* $1%s */ $item['userloginname'],
                    /* $2%s */ $item['id'],
                    /* $3%s */ $this->row_actions($actions)
            );
        } elseif ($item['status'] == 'Cancelled') {
            //Build row actions
            $actions = array(
                'delete' => sprintf('<a href="?page=%s&tab=%s&action=%s&id=%s">Delete</a>', $_REQUEST['page'], $_REQUEST['tab'], 'encash_application_delete', $item['id']),
            );

            //Return the title contents
            return sprintf('%1$s %3$s',
                    /* $1%s */ $item['userloginname'],
                    /* $2%s */ $item['id'],
                    /* $3%s */ $this->row_actions($actions)
            );
        } else {
            //Build row actions
            $actions = array(
                'accept' => sprintf('<a href="?page=%s&tab=%s&action=%s&id=%s">Accept</a>', $_REQUEST['page'], $_REQUEST['tab'], 'accept', $item['id']),
                'cancel' => sprintf('<a href="?page=%s&tab=%s&action=%s&id=%s">Cancel</a>', $_REQUEST['page'], $_REQUEST['tab'], 'cancel', $item['id']),
                //'edit' => sprintf('<a href="?page=rewardsystem_callback&tab=rewardsystem_request_for_cash_back&encash_application_id=%s">Edit</a>', $item['id']),
                'delete' => sprintf('<a href="?page=%s&tab=%s&action=%s&id=%s">Delete</a>', $_REQUEST['page'], $_REQUEST['tab'], 'delete', $item['id']),
            );

            //Return the title contents
            return sprintf('%1$s %3$s',
                    /* $1%s */ $item['userloginname'],
                    /* $2%s */ $item['id'],
                    /* $3%s */ $this->row_actions($actions)
            );
        }
    }

    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="id[]" value="%s" />', $item['id']
        );
    }

    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text            
            'userloginname' => __('User Name', 'rewardsystem'),
            'pointstoencash' => __('Points for Cash Back', 'rewardsystem'),
            'pointsconvertedvalue' => __('Amount for Cash Back ' . get_woocommerce_currency_symbol(), 'rewardsystem'),
            'reasonforencash' => __('Reason for Cash Back', 'rewardsystem'),
            'paypalemailid' => __('Paypal Address ', 'rewardsystem'),
            'otherpaymentdetails' => __('Other Payment Details', 'rewardsystem'),
            'status' => __('Application Status', 'rewardsystem'),
            'date' => __('Cash Back Requested Date', 'rewardsystem')
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'userloginname' => array('userloginname', false), //true means it's already sorted            
            'pointstoencash' => array('pointstoencash', false),
            'pointsconvertedvalue' => array('pointsconvertedvalue', false),
            'reasonforencash' => array('reasonforencash', false),
            'paypalemailid' => array('paypalemailid', false),
            'otherpaymentdetails' => array('otherpaymentdetails', false),
            'status' => array('status', false),
            'date' => array('date', false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'encash_application_delete' => __('Delete', 'rewardsystem'),
            'rspaid' => __('Mark as Paid', 'rewardsystem'),
            'rsdue' => __('Mark as Due', 'rewardsystem'),
        );
        return $actions;
    }

    function process_bulk_action() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sumo_reward_encashing_submitted_data'; // do not forget about tables prefix
        $table_name1 = $wpdb->prefix . "rspointexpiry";
        $table_name2 = $wpdb->prefix . "rsrecordpoints";

        if ('encash_application_delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids))
                $ids = implode(',', $ids);

            $mainids = explode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        } elseif ('rspaid' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids))
                $ids = implode(',', $ids);

            if (!empty($ids)) {
                $ids = explode(',', $ids);
                $countids = count($ids);
                foreach ($ids as $eachid) {
                    $wpdb->update($table_name, array('status' => 'Paid'), array('id' => $eachid));
                    $message = __($countids . ' Status Changed to Paid', 'rewardsystem');
                }
                if (!empty($message)):
                    ?>
                    <div id="message" class="updated"><p><?php echo $message ?></p></div>
                    <?php
                endif;
            }
        }elseif ('accept' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids))
                $ids = implode(',', $ids);

            if (!empty($ids)) {
                $ids = explode(',', $ids);
                $countids = count($ids);
                foreach ($ids as $eachid) {
                    $wpdb->update($table_name, array('status' => 'Paid'), array('id' => $eachid));
                    $message = __($countids . ' Status Changed to Paid', 'rewardsystem');
                }
                if (!empty($message)):
                    ?>
                    <div id="message" class="updated"><p><?php echo $message ?></p></div>
                    <?php
                endif;
            }
        }elseif ('cancel' === $this->current_action()) {
            $user_idss = '';
            $returnedpointsss = '';
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids))
                $ids = implode(',', $ids);

            if (!empty($ids)) {
                $ids = explode(',', $ids);
                $countids = count($ids);
                foreach ($ids as $eachid) {
                    $wpdb->update($table_name, array('status' => 'Cancelled'), array('id' => $eachid));
                    $message = __($countids . ' Status Changed to Cancelled', 'rewardsystem');
                    $user_id = $wpdb->get_results("SELECT userid FROM $table_name WHERE id = $eachid", ARRAY_A);
                    foreach ($user_id as $value) {
                        $user_idss = $value['userid'];
                    }
                    $returnedpoints = $wpdb->get_results("SELECT pointstoencash FROM $table_name WHERE id = $eachid", ARRAY_A);
                    foreach ($returnedpoints as $value) {
                        $returnedpointsss = $value['pointstoencash'];
                    }
                }
                $noofdays = get_option('rs_point_to_be_expire');

                if (($noofdays != '0') && ($noofdays != '')) {
                    $date = time() + ($noofdays * 24 * 60 * 60);
                } else {
                    $date = '999999999999';
                }
                $equearnamt = RSPointExpiry::earning_conversion_settings($returnedpointsss);
                RSPointExpiry::insert_earning_points($user_idss, $returnedpointsss, '0', $date, 'RCBRP', '0', $returnedpointsss, '', '');
                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_idss);
                RSPointExpiry::record_the_points($user_idss, $returnedpointsss, '0', $date, 'RCBRP', '0', '0', $equearnamt, '0', '0', '0', '', $totalpoints, '', '0');
                if (!empty($message)):
                    ?>
                    <div id="message" class="updated"><p><?php echo $message ?></p></div>
                    <?php
                endif;
            }
        }elseif ('delete' === $this->current_action()) {
            $user_idss = '';
            $returnedpointsss = '';
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids))
                $ids = implode(',', $ids);

            if (!empty($ids)) {
                $ids = explode(',', $ids);
                $countids = count($ids);
                foreach ($ids as $eachid) {
                    $user_id = $wpdb->get_results("SELECT userid FROM $table_name WHERE id = $eachid", ARRAY_A);
                    foreach ($user_id as $value) {
                        $user_idss = $value['userid'];
                    }
                    $returnedpoints = $wpdb->get_results("SELECT pointstoencash FROM $table_name WHERE id = $eachid", ARRAY_A);
                    foreach ($returnedpoints as $value) {
                        $returnedpointsss = $value['pointstoencash'];
                    }
                }
                $noofdays = get_option('rs_point_to_be_expire');

                if (($noofdays != '0') && ($noofdays != '')) {
                    $date = time() + ($noofdays * 24 * 60 * 60);
                } else {
                    $date = '999999999999';
                }
                $equearnamt = RSPointExpiry::earning_conversion_settings($returnedpointsss);
                RSPointExpiry::insert_earning_points($user_idss, $returnedpointsss, '0', $date, 'RCBRP', '0', $returnedpointsss, '0', '');
                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_idss);
                RSPointExpiry::record_the_points($user_idss, $returnedpointsss, '0', $date, 'RCBRP', '0', '0', $equearnamt, '0', '0', '0', '', $totalpoints, '', '0');

                if (!empty($ids)) {
                    $ids = implode(',', $ids);
                    $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
                }
                if (!empty($message)):
                    ?>
                    <div id="message" class="updated"><p><?php echo $message ?></p></div>
                    <?php
                endif;
            }
        }else {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids))
                $ids = implode(',', $ids);
            if (!empty($ids)) {
                $ids = explode(',', $ids);
                $countids = count($ids);
                foreach ($ids as $eachid) {
                    $wpdb->update($table_name, array('status' => 'Due'), array('id' => $eachid));
                    $message = __($countids . ' Status Changed to Due', 'rewardsystem');
                }
                if (!empty($message)):
                    ?>
                    <div id="message" class="updated"><p><?php echo $message ?></p></div>
                    <?php
                endif;
            }
        }
    }

    function extra_tablenav($which) {
        global $wpdb;
        $mainlistarray = array();
        $mainlistarray_alldata = array();
        $mainlistarray_paypal = array();
        $mainlistarray_alldata_heading = '';
        $tablename = $wpdb->prefix . 'sumo_reward_encashing_submitted_data';
        if ($which == 'top') {
            ?>
            <input type="submit" class="button-primary" name="fprs_encash_export_csv_paypal" id="fprs_encash_export_csv_paypal" value="<?php _e('Export Due Points as CSV for Paypal Mass Payment', 'rewardsystem'); ?>"/>
            <input type="submit" class="button-primary" name="fprs_encash_export_csv_alldata" id="fprs_encash_export_csv_alldata" value="<?php _e('Export All Cash Back Requests', 'rewardsystem'); ?>"/>
            <?php
            $getallresults = $wpdb->get_results("SELECT * FROM $tablename WHERE status='Due'", ARRAY_A);

            if (isset($getallresults)) {
                foreach ($getallresults as $value) {
                    if ($value['pointstoencash'] != '' && $value['paypalemailid'] != '') {
                        $mainlistarray_paypal[] = array($value['paypalemailid'], $value['pointsconvertedvalue'], get_woocommerce_currency(), $value['userid'], get_option('rs_encashing_paypal_custom_notes'));
                    }
                }
                if (isset($_POST['fprs_encash_export_csv_paypal'])) {
                    if (is_array($mainlistarray_paypal) && (!empty($mainlistarray_paypal))) {
                        $dateformat = get_option('date_format');
                        $name = date_i18n('Y-m-d');
                        ob_end_clean();
                        header("Content-type: text/csv");
                        header("Content-Disposition: attachment; filename=sumoreward_cashback_paypal" . $name . ".csv");
                        header("Pragma: no-cache");
                        header("Expires: 0");
                        $output = fopen("php://output", "w");
                        foreach ($mainlistarray_paypal as $row) {
                            if ($row != false) {
                                fputcsv($output, $row); // here you can change delimiter/enclosure
                            }
                        }

                        fclose($output);
                        exit();
                    }
                }
            }

            if (isset($getallresults)) {
                foreach ($getallresults as $allvalue) {
                    if ($allvalue['pointstoencash'] != '') {
                        $mainlistarray_alldata_heading = "User Name,User Current Points,Points for Cash Back, Currency Code, Amount for Cash Back,Reason for Encashing,Paypal Address, Other Payment Details,Application Status,Cash Back Requested Date" . "\n";
                        $mainlistarray_alldata[] = array($allvalue['userloginname'], $allvalue['encashercurrentpoints'], $allvalue['pointstoencash'], get_woocommerce_currency(), $allvalue['pointsconvertedvalue'], $allvalue['reasonforencash'], $allvalue['paypalemailid'], $allvalue['otherpaymentdetails'], $allvalue['status'], $allvalue['date']);
                    }
                }
                if (isset($_POST['fprs_encash_export_csv_alldata'])) {
                    $dateformat = get_option('date_format');
                    $name = date_i18n('Y-m-d');
                    ob_end_clean();
                    echo $mainlistarray_alldata_heading;
                    header("Content-type: text/csv");
                    header("Content-Disposition: attachment; filename=sumoreward_cashback_alldata" . $name . ".csv");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    $output = fopen("php://output", "w");
                    if (is_array($mainlistarray_alldata) && (!empty($mainlistarray_alldata))) {
                        foreach ($mainlistarray_alldata as $row) {
                            if ($row != false) {
                                fputcsv($output, $row); // here you can change delimiter/enclosure
                            }
                        }
                    }
                    fclose($output);
                    exit();
                }
            }
        }
    }

    function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sumo_reward_encashing_submitted_data'; // do not forget about tables prefix

        $per_page = 10; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }

}
