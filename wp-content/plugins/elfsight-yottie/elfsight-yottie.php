<?php
/*
Plugin Name: Elfsight Yottie
Description: YouTube Channel Plugin for WordPress. Select desired videos and YouTube channels to display them on your website. Manage 100+ parameters to customize the plugin as you wish.
Plugin URI: https://elfsight.com/youtube-channel-plugin-yottie/wordpress/
Version: 2.2.0
Author: Elfsight
Author URI: https://elfsight.com/
*/

if (!defined('ABSPATH')) exit;


define('ELFSIGHT_YOTTIE_SLUG', 'elfsight-yottie');
define('ELFSIGHT_YOTTIE_VERSION', '2.2.0');
define('ELFSIGHT_YOTTIE_FILE', __FILE__);
define('ELFSIGHT_YOTTIE_PATH', plugin_dir_path(__FILE__));
define('ELFSIGHT_YOTTIE_URL', plugin_dir_url( __FILE__ ));
define('ELFSIGHT_YOTTIE_PLUGIN_SLUG', plugin_basename( __FILE__ ));
define('ELFSIGHT_YOTTIE_TEXTDOMAIN', 'yottie');
define('ELFSIGHT_YOTTIE_UPDATE_URL', 'https://a.elfsight.com/updates/');
define('ELFSIGHT_YOTTIE_SUPPORT_URL', 'https://elfsight.ticksy.com/submit/#product100003623');
define('ELFSIGHT_YOTTIE_PRODUCT_URL', 'http://codecanyon.net/item/youtube-channel-wordpress-plugin-yottie/14115701?ref=Elfsight');


require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'yottie-defaults.php')));
require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'yottie-update.php')));
require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'yottie-widgets-api.php')));
require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'yottie-admin.php')));
require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'yottie-shortcode.php')));
require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'yottie-vc.php')));
require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'yottie-lib.php')));
require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'yottie-analytics.php')));

?>
