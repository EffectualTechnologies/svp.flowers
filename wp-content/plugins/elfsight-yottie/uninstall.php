<?php 

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();

// delete plugin options
delete_option('elfsight_yottie_purchase_code');
delete_option('elfsight_yottie_activated');
delete_option('elfsight_yottie_latest_version');
delete_option('elfsight_yottie_last_check_datetime');
delete_option('elfsight_yottie_force_script_add');
delete_option('elfsight_yottie_widgets_clogged');

?>