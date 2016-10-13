<?php

if (!defined('ABSPATH')) exit;

function elfsight_yottie_add_action_links($links) {
    $links[] = '<a href="' . esc_url(admin_url('admin.php?page=elfsight-yottie')) . '">Settings</a>';
    $links[] = '<a href="http://codecanyon.net/user/elfsight/portfolio?ref=Elfsight" target="_blank">More plugins by Elfsight</a>';
    return $links;
}
add_filter('plugin_action_links_' . ELFSIGHT_YOTTIE_PLUGIN_SLUG, 'elfsight_yottie_add_action_links');


function elfsight_yottie_admin_init() {
    wp_register_style('yottie-admin', plugins_url('assets/yottie-admin.css', ELFSIGHT_YOTTIE_FILE), array(), ELFSIGHT_YOTTIE_VERSION);
    wp_register_script('yottie', plugins_url('assets/yottie/dist/jquery.yottie.bundled.js', ELFSIGHT_YOTTIE_FILE), array('jquery'), ELFSIGHT_YOTTIE_VERSION);
    wp_register_script('yottie-admin', plugins_url('assets/yottie-admin.js', ELFSIGHT_YOTTIE_FILE), array('jquery', 'yottie'), ELFSIGHT_YOTTIE_VERSION);
}

function elfsight_yottie_admin_scripts() {
    wp_enqueue_style('yottie-admin');
    wp_enqueue_script('yottie');
    wp_enqueue_script('yottie-admin');
}

function elfsight_yottie_create_menu() {
    $page_hook = add_menu_page(__('Yottie', ELFSIGHT_YOTTIE_TEXTDOMAIN), __('Yottie', ELFSIGHT_YOTTIE_TEXTDOMAIN), 'manage_options', ELFSIGHT_YOTTIE_SLUG, 'elfsight_yottie_settings_page', plugins_url('assets/img/yottie-wp-icon.png', ELFSIGHT_YOTTIE_FILE));
    add_action('admin_init', 'elfsight_yottie_admin_init');
    add_action('admin_print_styles-' . $page_hook, 'elfsight_yottie_admin_scripts');
}
add_action('admin_menu', 'elfsight_yottie_create_menu');


function elfsight_yottie_underscore_to_cc($l) {
    return strtoupper(substr($l[0], 1));
}


function elfsight_yottie_update_activation_data() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'elfsight_yottie_update_activation_data_nonce')) {
        exit;
    }

    update_option('elfsight_yottie_purchase_code', !empty($_REQUEST['purchase_code']) ? $_REQUEST['purchase_code'] : '');
    update_option('elfsight_yottie_activated', !empty($_REQUEST['activated']) ? $_REQUEST['activated'] : '');
}
add_action('wp_ajax_elfsight_yottie_update_activation_data', 'elfsight_yottie_update_activation_data');


function elfsight_yottie_get_new_version() {
    $latest_version = get_option('elfsight_yottie_latest_version', '');
    $last_check_datetime = get_option('elfsight_yottie_last_check_datetime', '');

    $result = array();

    if (!empty($last_check_datetime)) {
        $result['message'] = sprintf(__('Last checked on %1$s at %2$s', ELFSIGHT_YOTTIE_TEXTDOMAIN), date_i18n(get_option('date_format'), $last_check_datetime), date_i18n(get_option('time_format'), $last_check_datetime));
    }

    if (!empty($latest_version) && version_compare(ELFSIGHT_YOTTIE_VERSION, $latest_version, '<')) {
        $result['version'] = $latest_version;
    }

    die(json_encode($result));
}
add_action('wp_ajax_elfsight_yottie_get_new_version', 'elfsight_yottie_get_new_version');


function elfsight_yottie_update_preferences() {
    if (!wp_verify_nonce($_REQUEST['nonce'], 'elfsight_yottie_update_preferences_nonce')) {
        exit;
    }

    $result = array();

    if (!empty($_REQUEST['custom'])) {
        $type = $_REQUEST['custom'];
        $path = null;

        $uploads_dir_params = wp_upload_dir();
        $uploads_dir = $uploads_dir_params['basedir'] . '/' . ELFSIGHT_YOTTIE_SLUG;

        switch ($type) {
            case 'css':
                $path = $uploads_dir . '/yottie-custom.css';
                break;

            case 'js':
                $path = $uploads_dir . '/yottie-custom.js';
                break;
        }

        if ($path) {
            if (!is_dir($uploads_dir)) {
              wp_mkdir_p($uploads_dir);
            }

            if (file_exists($path) && !is_writable($path)) {
                $result['success'] = false;
                $result['error'] = __('The file can not be overwritten. Please check the permissions.', ELFSIGHT_YOTTIE_TEXTDOMAIN);

            } else {
                $result['success'] = true;

                file_put_contents($path, stripslashes($_REQUEST['contents']));
            }
        }
    } else if (!empty($_REQUEST['force_script_add'])) {
        $result['success'] = true;

        update_option('elfsight_yottie_force_script_add',  $_REQUEST['force_script_add']);
    }

    exit(json_encode($result));
}
add_action('wp_ajax_elfsight_yottie_update_preferences', 'elfsight_yottie_update_preferences');


function elfsight_yottie_settings_page() {
    global $elfsight_yottie_defaults, $elfsight_yottie_color_schemes;

    wp_elfsight_yottie_widgets_upgrade();

    // widgets
    $widgets_clogged = get_option('elfsight_yottie_widgets_clogged', '');


    // defaults to json
    $yottie_json = array();
    foreach ($elfsight_yottie_defaults as $name => $val) {
        if ($name == 'header_info' || $name == 'video_info' || $name == 'popup_info') {
            $val = explode(', ', $val);
        }

        $yottie_json[preg_replace_callback('/(_.)/', 'elfsight_yottie_underscore_to_cc', $name)] = $val;
    }

    // color schemes to json
    $yottie_color_schemes_json = array();
    foreach ($elfsight_yottie_color_schemes as $scheme_name => $scheme_colors) {
        $yottie_color_schemes_json[$scheme_name] = array();
        foreach ($scheme_colors as $name => $value) {
            $yottie_color_schemes_json[$scheme_name][preg_replace_callback('/(_.)/', 'elfsight_yottie_underscore_to_cc', $name)] = $value;
        }
    }


    // preferences
    $uploads_dir_params = wp_upload_dir();
    $uploads_dir = $uploads_dir_params['basedir'] . '/' . ELFSIGHT_YOTTIE_SLUG;

    $custom_css_path = $uploads_dir . '/yottie-custom.css';
    $custom_js_path = $uploads_dir . '/yottie-custom.js';
    $custom_css = is_readable($custom_css_path) ? file_get_contents($custom_css_path) : '';
    $custom_js = is_readable($custom_js_path) ? file_get_contents($custom_js_path) : '';

    $force_script_add = get_option('elfsight_yottie_force_script_add');


    // activation
    $purchase_code = get_option('elfsight_yottie_purchase_code', '');
    $activated = get_option('elfsight_yottie_activated', '') === 'true';

    $latest_version = get_option('elfsight_yottie_latest_version', '');
    $last_check_datetime = get_option('elfsight_yottie_last_check_datetime', '');
    $has_new_version = !empty($latest_version) && version_compare(ELFSIGHT_YOTTIE_VERSION, $latest_version, '<');

    $activation_css_classes = '';
    if ($activated) {
        $activation_css_classes .= 'yottie-admin-activation-activated ';
    }
    else if (!empty($purchase_code)) {
        $activation_css_classes .= 'yottie-admin-activation-invalid ';
    }
    if ($has_new_version) {
        $activation_css_classes .= 'yottie-admin-activation-has-new-version ';
    }

    ?><div class="<?php echo $activation_css_classes; ?>yottie-admin wrap">
        <h2 class="yottie-admin-wp-notifications-hack"></h2>

        <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'templates', 'header.php'))); ?>

        <main class="yottie-admin-main yottie-admin-loading" data-yt-admin-galleries-clogged="<?php echo $widgets_clogged; ?>">
            <div class="yottie-admin-loader"></div>

            <div class="yottie-admin-menu-container">
                <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'templates', 'menu.php'))); ?>

                <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'templates', 'menu-actions.php'))); ?>
            </div>

            <div class="yottie-admin-pages-container">
                <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'templates', 'page-welcome.php'))); ?>

                <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'templates', 'page-galleries.php'))); ?>

                <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'templates', 'page-edit-gallery.php'))); ?>

                <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'templates', 'page-support.php'))); ?>

                <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'templates', 'page-preferences.php'))); ?>

                <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'templates', 'page-activation.php'))); ?>

                <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'templates', 'page-error.php'))); ?>
            </div>
        </main>
    </div>
<?php } ?>
